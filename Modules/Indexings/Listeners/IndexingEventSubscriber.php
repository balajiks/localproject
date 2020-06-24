<?php

namespace Modules\Indexings\Listeners;

use App\Services\EventCreatorFactory;
use Modules\Indexings\Emails\RequestConsent;
use Modules\Indexings\Notifications\IndexingAssignedAlert;
use Modules\Indexings\Notifications\IndexingConvertedAlert;

class IndexingEventSubscriber
{
    protected $user;
    protected $eventCreator;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(EventCreatorFactory $eventfactory)
    {
        $this->user         = \Auth::id() ?? 1;
        $this->eventCreator = new \App\Services\EventCreator($eventfactory, 'indexings');
    }

    /**
     * Indexing created listener
     */
    public function onIndexingCreated($event)
    {
        $data = [
            'action' => 'activity_create_indexing', 'icon' => 'fa-user-circle-o', 'user_id' => $this->user,
            'value1' => $event->indexing->name, 'value2'   => $event->indexing->AsSource->name,
            'url'    => $event->indexing->url,
        ];
        $event->indexing->activities()->create($data);
        $this->eventCreator->logEvent($event->indexing);
        if (settingEnabled('indexings_opt_in')) {
            \Mail::to($event->indexing)->send(new RequestConsent($event->indexing));
        }
        if (!\App::runningUnitTests() && session('lock_assigned_alert') == false) {
            $event->indexing->agent->notify(new IndexingAssignedAlert($event->indexing));
        }
    }

    /**
     * Indexing has been converted to customer
     */
    public function onIndexingConverted($event)
    {
        $event->indexing->update(['converted_at' => now()->toDateTimeString(), 'archived_at' => now()->toDateTimeString()]);
        $data = [
            'action' => 'activity_convert_indexing', 'icon' => 'fa-balance-scale', 'user_id' => $event->user,
            'value1' => $event->indexing->name, 'value2'    => '',
            'url'    => $event->indexing->url,
        ];
        $event->indexing->activities()->create($data);
        $event->indexing->agent->notify(new IndexingConvertedAlert($event->indexing));
        if (settingEnabled('indexings_delete_converted')) {
            $event->indexing->delete();
        }
    }

    /**
     * Indexing emailed listener
     */
    public function onIndexingEmailed($event)
    {
        $data = [
            'action' => 'activity_email_indexing', 'icon' => 'fa-envelope-o', 'user_id' => $this->user,
            'value1' => $event->indexing->name, 'value2'  => $event->mail->indexing->email,
            'url'    => $event->indexing->url,
        ];
        $event->indexing->activities()->create($data);
        $event->indexing->unsetEventDispatcher();
        $event->indexing->update(['next_folowup' => now()->addDays(get_option('indexing_followup_days'))]);
    }

    /**
     * Indexing updated listener
     */
    public function onIndexingUpdated($event)
    {
        $data = [
            'action' => 'activity_update_indexing', 'icon' => 'fa-pencil-alt', 'user_id' => $this->user,
            'value1' => $event->indexing->name, 'value2'   => '',
            'url'    => $event->indexing->url,
        ];
        $event->indexing->activities()->create($data);
        $this->eventCreator->logEvent($event->indexing);
    }

    /**
     * Indexing deleted listener
     */
    public function onIndexingDeleted($event)
    {
        $data = [
            'action' => 'activity_delete_indexing', 'icon' => 'fa-trash', 'user_id' => $this->user,
            'value1' => $event->indexing->name, 'value2'   => $event->indexing->AsSource->name,
            'url'    => $event->indexing->url,
        ];
        $event->indexing->activities()->create($data);
        $this->eventCreator->deleteEvent($event->indexing);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Modules\Indexings\Events\IndexingCreated',
            'Modules\Indexings\Listeners\IndexingEventSubscriber@onIndexingCreated'
        );

        $events->listen(
            'Modules\Indexings\Events\IndexingUpdated',
            'Modules\Indexings\Listeners\IndexingEventSubscriber@onIndexingUpdated'
        );
        $events->listen(
            'Modules\Indexings\Events\IndexingConverted',
            'Modules\Indexings\Listeners\IndexingEventSubscriber@onIndexingConverted'
        );
        $events->listen(
            'Modules\Indexings\Events\IndexingEmailed',
            'Modules\Indexings\Listeners\IndexingEventSubscriber@onIndexingEmailed'
        );
        $events->listen(
            'Modules\Indexings\Events\IndexingDeleted',
            'Modules\Indexings\Listeners\IndexingEventSubscriber@onIndexingDeleted'
        );
    }
}
