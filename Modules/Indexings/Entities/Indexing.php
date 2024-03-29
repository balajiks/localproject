<?php

namespace Modules\Indexings\Entities;

use App\Entities\Category;
use App\Traits\Actionable;
use App\Traits\Chatable;
use App\Traits\Commentable;
use App\Traits\Customizable;
use App\Traits\Emailable;
use App\Traits\Eventable;
use App\Traits\Noteable;
use App\Traits\Observable;
use App\Traits\Phoneable;
use App\Traits\Remindable;
use App\Traits\Searchable;
use App\Traits\Taggable;
use App\Traits\Todoable;
use App\Traits\Uploadable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Modules\Deals\Entities\Deal;
use Modules\Indexings\Events\IndexingCreated;
use Modules\Indexings\Events\IndexingDeleted;
use Modules\Indexings\Events\IndexingUpdated;
use Modules\Indexings\Notifications\IndexingCommented;
use Modules\Indexings\Observers\IndexingObserver;
use Modules\Indexings\Scopes\IndexingScope;
use Modules\Todos\Entities\Todo;
use Modules\Users\Entities\Profile;
use Modules\Users\Entities\User;

class Indexing extends Model
{
    use Notifiable, Commentable, Todoable, Taggable, Actionable, SoftDeletes, Customizable, 
    Observable, Noteable, Eventable, Uploadable, Remindable, Phoneable, Searchable, Emailable, Chatable;

    protected static $observer = IndexingObserver::class;
    protected static $scope    = IndexingScope::class;

    protected $fillable = [
        'name', 'source', 'stage_id', 'job_title', 'company', 'phone', 'mobile', 'email', 'address1', 'address2',
        'city', 'state', 'zip_code', 'country', 'timezone', 'website', 'skype', 'facebook', 'twitter', 'linkedin',
        'sales_rep', 'indexing_score', 'due_date', 'indexing_value', 'computed_value', 'todo_percent', 'message',
        'has_email', 'has_activity', 'has_chats', 'next_followup', 'unsubscribed_at', 'archived_at', 'converted_at', 'token',
        'deleted_at',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => IndexingCreated::class,
        'updated' => IndexingUpdated::class,
        'deleted' => IndexingDeleted::class,
    ];

    protected $dates = [
        'due_date', 'deleted_at', 'next_followup',
    ];

    protected $appends = ['map', 'map_link'];

    public function status()
    {
        return $this->belongsTo(Category::class, 'stage_id', 'id');
    }

    public function AsSource()
    {
        return $this->belongsTo(Category::class, 'source', 'id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'sales_rep', 'id')->with(['profile:user_id,avatar,use_gravatar']);
    }

    public function unreadMessages()
    {
        return $this->emails()->unread()->count();
    }

    public function hasPendingActivity()
    {
        return $this->todos()->pending()->count();
    }

    public function hasNewChats()
    {
        return $this->chats()->unread()->count();
    }

    public function commentAlert($comment)
    {
        $this->agent->notify(new IndexingCommented($this));
    }

    public function nextActivities()
    {
        return Todo::whereDate('datetime', '>', now()->toDateTimeString())
            ->whereAssigned(\Auth::user()->id)
            ->where('parent', '<=', 0)
            ->whereIndexingId($this->id)
            ->pending()
            ->orderBy('id', 'desc')
            ->get();
    }

    public function toCustomer()
    {
        $contact = $this->createContactFromIndexing();

        if (request()->noPotential === '1') {
            $deal = Deal::create(
                [
                    'title'          => request('deal_title'),
                    'stage_id'       => get_option('default_deal_stage'),
                    'deal_value'     => $this->indexing_value,
                    'contact_person' => $contact->user_id,
                    'organization'   => $contact->business->id,
                    'currency'       => get_option('default_currency'),
                    'user_id'        => get_option('default_deal_owner'),
                    'due_date'       => now()->addDays(get_option('deal_rotting')),
                    'source'         => $this->source,
                    'pipeline'       => get_option('default_deal_pipeline'),
                ]
            );
            foreach ($this->comments as $comment) {
                $deal->comments()->create(
                    [
                        'user_id' => \Auth::id(),
                        'message' => $comment->message,
                    ]
                );
            }
            $this->tags()->sync($deal->tags);
            // Transfer indexing notes to deal
            foreach ($this->notes as $note) {
                $deal->notes()->create([
                    'user_id'     => $note->user_id,
                    'name'        => $note->name,
                    'description' => $note->description,
                ]);
            }
            return [
                'message'  => langapp('saved_successfully'),
                'redirect' => route('deals.view', ['id' => $deal->id]),
            ];
        } else {
            return [
                'message'  => langapp('saved_successfully'),
                'redirect' => route('clients.view', ['id' => $contact->business->id]),
            ];
        }
    }

    public function nextStage()
    {
        $max          = \App\Entities\Category::indexings()->max('order');
        $currentStage = \App\Entities\Category::whereId($this->stage_id)->first()->order;
        if (\App\Entities\Category::whereId($this->stage_id)->first()->order == $max) {
            return false;
        } else {
            return $currentStage + 1;
        }
    }

    public function metaValue($key)
    {
        return optional($this->custom()->whereMetaKey($key)->first())->meta_value;
    }

    public function compute()
    {
        $this->update(
            [
                'computed_value' => formatCurrency(get_option('default_currency'), $this->indexing_value),
                'has_email'      => $this->unreadMessages() ? 1 : 0,
                'has_activity'   => $this->hasPendingActivity() ? 1 : 0,
            ]
        );
    }

    public function createContactFromIndexing()
    {
        $user    = \Modules\Users\Entities\User::firstOrCreate(['email' => $this->email], ['username' => $this->email, 'name' => $this->name]);
        $account = \Modules\Clients\Entities\Client::firstOrCreate(
            ['email' => $this->email],
            [
                'name'     => $this->company, 'primary_contact' => $user->id, 'code'        => generateCode('clients'),
                'website'  => $this->website, 'phone'           => $this->phone,
                'address1' => $this->address1, 'address2'       => $this->address2,
                'city'     => $this->city, 'state'              => $this->state, 'currency' => get_option('default_currency'),
                'country'  => $this->country, 'zip_code'        => $this->zip_code, 'skype' => $this->skype, 'linkedin' => $this->linkedin,
                'facebook' => $this->facebook, 'twitter'        => $this->twitter, 'owner'  => \Auth::id(),
            ]
        );
        $user->profile->update(
            [
                'company' => $account->id, 'job_title' => $this->job_title,
                'city'    => $this->city, 'country'    => $this->country, 'address' => $this->address, 'phone'    => $this->phone,
                'skype'   => $this->skype, 'state'     => $this->state, 'zip_code'  => $this->zip_code, 'website' => $this->website,
                'twitter' => $this->twitter,
            ]
        );
        // Transfer indexing notes to client comments
        foreach ($this->notes as $note) {
            $account->comments()->create([
                'user_id' => $note->user_id,
                'message' => $note->description,
            ]);
        }
        return $user->profile;
    }

    public function scopeConverted($query)
    {
        return $query->whereNotNull('converted_at');
    }

    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    public function getMapAttribute()
    {
        return urlencode($this->address1 . ',' . $this->state . ' ' . $this->zip_code . ',' . $this->city . ',' . $this->country);
    }

    public function getMaplinkAttribute()
    {
        return 'https://maps.google.com/maps?q=' . $this->address1 . '+' . $this->city . '+' . $this->state . '+' . $this->zip_code;
    }

    public function setNextFollowupAttribute($value)
    {
        $this->attributes['next_followup'] = dbDate($value);
    }

    public function getScoreAttribute()
    {
        $score     = 0;
        $allStages = Category::whereModule('indexings')->orderBy('order', 'asc')->get();
        foreach ($allStages as $stage) {
            $f = round(100 / $allStages->count());
            $score += $f;
            if ($stage->id == $this->stage_id) {
                break;
            }
        }
        return $score;
    }

    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = dbDate($value);
    }

    public function canReceiveEmail()
    {
        if (is_null($this->unsubscribed_at)) {
            return true;
        }
        return false;
    }

    /**
     * Route notifications for the Nexmo channel.
     */
    public function routeNotificationForNexmo()
    {
        return $this->mobile;
    }

    public function routeNotificationForWhatsapp()
    {
        return $this->whatsapp_optin ? $this->mobile : null;
    }

    public function getUrlAttribute()
    {
        return '/indexings/view/' . $this->id;
    }

    public function getPhotoAttribute()
    {
        return app('gravatar')->get($this->email);
    }
}
