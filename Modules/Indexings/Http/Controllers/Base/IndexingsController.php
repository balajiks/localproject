<?php

namespace Modules\Indexings\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use Auth;
use App\Http\Requests\CSVRequest;
use App\Http\Requests\EmailRequest;
use App\Notifications\WhatsAppSubscribe;
use DataTables;
use Illuminate\Http\Request;
use Modules\Files\Helpers\Uploader;
use Modules\Indexings\Emails\IndexingsBulkEmail;
use Modules\Indexings\Emails\RequestConsent;
use Modules\Indexings\Entities\Indexing;
use Modules\Indexings\Exports\IndexingsExport;
use Modules\Indexings\Helpers\IndexingCsvProcessor;
use Modules\Indexings\Http\Requests\BulkSendRequest;
use Modules\Indexings\Jobs\BulkDeleteIndexings;
use Modules\Messages\Entities\Emailing;
use Modules\Users\Entities\User;
use DB;

abstract class IndexingsController extends Controller
{
    /**
     * Indexing model
     *
     * @var \Modules\Indexings\Entities\Indexing
     */
    public $indexing;
    /**
     * Request instance
     *
     * @var \Illuminate\Http\Request
     */
    public $request;
    /**
     * Indexing display type kanban|table
     *
     * @var string
     */
    public $displayType;
	
	protected $user;

    public function __construct(Indexing $indexing, Request $request,User $user)
    {
        $this->middleware(['auth', 'verified', '2fa', 'can:menu_indexings']);
        $this->displayType 	= request('view', 'kanban');
        $this->request     	= $request;
        $this->indexing     = $indexing;
		$this->user    		= $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
		$this->userId = \Auth::id();
		$loggeduserrole =  \Auth::user()->profile->userrole;
		
		if($loggeduserrole == '2'){
			$jobdata = DB::table('projects')->where('qc', 'admin')->first();
		} else {
			$jobdata = DB::table('projects')->where('indexer', 'admin')->first();
		}
		
		
		
		if($jobdata->id !=''){
			$matchThese 		= ['user_id' => \Auth::id(), 'jobid' => $jobdata->id, 'orderid' => $jobdata->orderid];
			$repositoryname 	= DB::table('datasections')->where($matchThese)->get()->toArray();
		}
		
		
		$tabmenu				= 'indexermeta';
		$tab 					= 'section';
		$allowedTabs    		= ['section', 'medical', 'drug', 'drugtradename', 'mdt', 'ctn', 'msn', 'mdi'];
        $data['tab']    		= in_array($tab, $allowedTabs) ? $tab : 'section';
		$data['page']        	= $this->getPage();
        $data['displayType'] 	= $this->getDisplayType();
        $data['filter']      	= $this->request->filter;
		$data['jobdata']      	= $jobdata;
		$data['sectioncount']   = count($repositoryname);
		$data['tabmenu']   		= $tabmenu;
		
		
		
		
		
		
        return view('indexings::index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(Indexing $indexing, $tab = 'section', $option = null)
    {
		$allowedTabs    = ['section', 'medical', 'drug', 'drugtradename', 'mdt', 'ctn', 'msn', 'mdi'];
        $data['tab']    		= in_array($tab, $allowedTabs) ? $tab : 'section';
		$data['page']        	= $this->getPage();
		$data['displayType'] 	= $this->getDisplayType();
        $data['filter']      	= $this->request->filter;
		$data['indexing']   	= $indexing;
		$data['option'] 		= $option;
	    return view('indexings::create')->with($data);
    }
	
	
	 /**
     * Display the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function addindexing(Indexing $indexing, $tab = 'section', $option = null)
    {
       $this->userId = \Auth::id();
		$loggeduserrole =  \Auth::user()->profile->userrole;
		
		
		//Get JOB 
		if($loggeduserrole == '2'){
			$jobdata = DB::table('projects')->where('qc', 'admin')->first();
		} else {
			$jobdata = DB::table('projects')->where('indexer', 'admin')->first();
		}
		
		
		if($jobdata->id !=''){
			$matchThese 		= ['user_id' => \Auth::id(), 'jobid' => $jobdata->id, 'orderid' => $jobdata->orderid];
			$datasections 		= DB::table('datasections')->where($matchThese)->get()->toArray();
		}
		
		
		
		
		
		// FIELD 3
		$diseaseslink 	= DB::table('diseases')->where('status', 1)->get()->toArray();
		$minorchecktags = DB::table('minorchecktags')->where('status', 1)->get()->toArray();
		$mmt_ct_list = array();
		foreach($minorchecktags as $checktag){
		   $mmt_ct_list[$checktag->type][] = $checktag;
		}
		
		
		// FIELD 4
		$drugotherfields		= DB::table('drug_otherfield')->where('status', 1)->get()->toArray();
		$drugunits 				= DB::table('drugunits')->where('status', 1)->get()->toArray();
		$routedrugadmins 		= DB::table('routedrugadmins')->where('status', 1)->get()->toArray();
		$dosefrequencys 		= DB::table('dosefrequencys')->where('status', 1)->get()->toArray();
		$drugdosescheduleterms 	= DB::table('drugdosescheduleterms')->where('status', 1)->get()->toArray();		
		$specialsituations 		= DB::table('specialsituations')->where('status', 1)->get()->toArray();
		$unexpectedoutcomes 	= DB::table('unexpectedoutcomes')->where('status', 1)->get()->toArray();
		
		
		// FIELD 5
		$registries 	= DB::table('registries')->where('status', 1)->get()->toArray();
		
		// FIELD 6
		$repositoryname 	= DB::table('repositoryname')->where('status', 1)->get()->toArray();
		
		$allowedTabs    					= 	['section', 'medical', 'drug', 'drugtradename', 'mdt', 'ctn', 'msn', 'mdi'];
        $data['tab']    					= 	in_array($tab, $allowedTabs) ? $tab : 'section';
		$data['page']        				= 	$this->getPage();
        $data['displayType'] 				= 	$this->getDisplayType();
        $data['filter']      				= 	$this->request->filter;
		$data['jobdata']      				= 	$jobdata;
		$data['diseaseslink']   			= 	$diseaseslink;
		$data['mmt_ct_list']   				=  	$mmt_ct_list;
		$data['drugunits']   				=  	$drugunits;
		$data['routedrugadmins']   			=  	$routedrugadmins;
		$data['dosefrequencys']   			=  	$dosefrequencys;
		$data['drugdosescheduleterms']   	=  	$drugdosescheduleterms;
		$data['specialsituations']   		=  	$specialsituations;
		$data['unexpectedoutcomes']   		=  	$unexpectedoutcomes;
		$data['registries']   				=  	$registries;
		$data['repositoryname']   			=  	$repositoryname;
		$data['sectioncount']   			=   count($datasections);
		$data['drugotherfields']   			=   $drugotherfields;
		$tabmenu							=   'indexermeta';
		$data['tabmenu']   					=   $tabmenu;
		
		
        return view('indexings::index')->with($data);
    }
	
	public function showmeta($id){
		$jobmetainfo 			= DB::table('projects')->where('id', $id)->get()->toArray();
		$data['jobmetainfo']    = $jobmetainfo;
		return view('indexings::modal.showmeta')->with($data);
	}
	
	public function showsource($id){
		$jobsourceinfo 			= DB::table('projects')->where('id', $id)->get()->toArray();
		$data['jobsourceinfo']  = $jobsourceinfo;
		$data['page']        	= $this->getPage();
        $data['displayType'] 	= $this->getDisplayType();
        $data['filter']      	= $this->request->filter;
		return view('indexings::showsource')->with($data);
	}
	
	/**
     * Show the form for creating a new section resource.
     *
     * @return \Illuminate\View\View
     */
    public function createsection()
    {
		print '<pre>ASAsAS';
		//print_r($indexing);
		///print_r($request);
		exit;	
    }
	
	public function getsections($id = null)
    {
        
		
		
		$WherematchThese 		= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid];
		$findsectiondata 		= DB::table('datasections')->select('sectionid')->whereIn('sectionid',$request->indexer_section)->where($WherematchThese)->get()->toArray();
			
			
	   
	   // $indexing  = $this->indexing->findOrFail($id);
       // $calls = new CallsResource($indexing->calls()->orderBy('id', 'desc')->paginate(50));
        return response($findsectiondata, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function view(Indexing $indexing, $tab = 'overview', $option = null)
    {
        $allowedTabs    = ['activity', 'calendar', 'comments', 'compose', 'conversations', 'files', 'calls', 'overview', 'whatsapp'];
        $data['tab']    = in_array($tab, $allowedTabs) ? $tab : 'overview';
        $data['page']   = $this->getPage();
        $data['indexing']   = $indexing;
        $data['option'] = $option;

        return view('indexings::view')->with($data);
    }
	
	

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(Indexing $indexing)
    {
        $data['indexing'] = $indexing;
        return view('indexings::modal.update')->with($data);
    }
    /**
     * Show modal to convert indexing
     */
    public function convert(Indexing $indexing)
    {
        $data['indexing'] = $indexing;
        return view('indexings::modal.convert')->with($data);
    }

    /**
     * Export Indexings as CSV
     */
    public function export()
    {
        if (isAdmin()) {
            return (new IndexingsExport)->download('indexings_' . now()->toIso8601String() . '.csv');
        }
        abort(404);
    }
    /**
     * Show import indexings form
     */
    public function import()
    {
        if ($this->request->type == 'google') {
            return $this->importGoogleContacts();
        }

        $data['page'] = $this->getPage();

        return view('indexings::modal.uploadcsv')->with($data);
    }

    public function nextStage(Indexing $indexing)
    {
        $data['indexing'] = $indexing;
        return view('indexings::modal.next_stage')->with($data);
    }

    public function parseImport(CSVRequest $request, \App\Helpers\ExcelImport $importer)
    {
        $dt['page'] = $this->getPage();
        $path       = $request->file('csvfile')->getRealPath();
        if ($request->has('header')) {
            $data = $importer->getData($path);
        } else {
            $data = array_map('str_getcsv', file($path));
        }
        if (count($data) > 0) {
            if ($request->has('header')) {
                $csv_header_fields = [];
                foreach ($data[0] as $key => $value) {
                    $csv_header_fields[] = $key;
                }
            }
            $csv_data      = array_slice($data, 0, 2);
            $csv_data_file = \App\Entities\CsvData::create(
                [
                    'csv_filename' => $request->file('csvfile')->getClientOriginalName(),
                    'csv_header'   => $request->has('header'),
                    'csv_data'     => json_encode($data),
                ]
            );
        } else {
            return redirect()->back();
        }

        return view('indexings::import_fields', compact('csv_header_fields', 'csv_data', 'csv_data_file'))->with($dt);
    }
    /**
     * Send consent request to indexing
     */
    public function sendConsent(Indexing $indexing)
    {
        if (is_null($indexing->token)) {
            $indexing->update(['token' => genToken()]);
        }
        \Mail::to($indexing)->send(new RequestConsent($indexing));
        toastr()->success(langapp('sent_successfully'), langapp('response_status'));

        return redirect()->route('indexings.view', ['id' => $indexing->id]);
    }

    /**
     * Send whatsapp consent request to indexing
     */
    public function sendWhatsappConsent(Indexing $indexing)
    {
        $indexing->notify(new WhatsAppSubscribe($indexing->mobile));
        $indexing->chats()->create([
            'user_id' => \Auth::id(),
            'inbound' => 0,
            'message' => langapp('whatsapp_subscribe_reply', [
                'company' => get_option('company_name'), 'subtext' => get_option('whatsapp_sub_text'),
            ]),
            'from'    => get_option('whatsapp_number'),
            'to'      => $indexing->mobile,
        ]);
        toastr()->success(langapp('sent_successfully'), langapp('response_status'));

        return redirect(url()->previous());
    }

    public function processImport()
    {
        \Validator::make(
            array_flip($this->request->fields),
            [
                'name'    => 'required',
                'company' => 'required',
                'email'   => 'required',
            ]
        )->validate();
        (new IndexingCsvProcessor)->import($this->request);

        $data['message']  = langapp('saved_successfully');
        $data['redirect'] = route('indexings.index');

        return ajaxResponse($data);
    }
    /**
     * Confirm delete
     */
    public function delete(Indexing $indexing)
    {
        $data['indexing'] = $indexing;

        return view('indexings::modal.delete')->with($data);
    }
    /**
     * Send email to indexing
     */
    public function email(EmailRequest $request, Indexing $indexing)
    {
        $when = empty($request->reserved_at) ? now()->addMinutes(1) : dateParser($request->reserved_at);
        $request->request->add(['meta' => ['sender' => \Auth::user()->email, 'to' => $indexing->email]]);
        $request->request->add(['reserved_at' => $when->toDateTimeString()]);
        $request->request->add(['message' => str_replace("{name}", $indexing->name, $request->message)]);
        $mail = $indexing->emails()->create($request->except(['uploads', 'selectCanned']));

        if ($request->hasFile('uploads')) {
            $this->makeUploads($mail, $request);
        }

        \Mail::to($indexing)->later($when, new IndexingsBulkEmail($mail, \Auth::user()->profile->email_signature));

        $data['message']  = langapp('sent_successfully');
        $data['redirect'] = route('indexings.view', ['id' => $indexing->id, 'tab' => 'conversations']);

        return ajaxResponse($data);
    }

    protected function makeUploads($mail, $request)
    {
        $request->request->add(['module' => 'emails']);
        $request->request->add(['module_id' => $mail->id]);
        $request->request->add(['title' => $mail->subject]);
        $request->request->add(['description' => 'Email ' . $mail->subject . ' file']);

        return (new Uploader)->save('uploads/emails', $request);
    }

    public function replyEmail(EmailRequest $request)
    {
        $recipients = explode(',', trim($request->to));
        $email      = Emailing::create($request->except(['to']));

        $email->update([
            'from' => \Auth::id(), 'meta' => ['sender' => \Auth::user()->email, 'to' => $recipients],
        ]);
        $data['message']  = langapp('sent_successfully');
        $data['redirect'] = route(
            'indexings.view',
            [
                'id' => $email->indexing->id, 'tab' => 'emails', 'action' => $request->reply_id,
            ]
        );

        return ajaxResponse($data);
    }
    /**
     * Select indexings to send email
     */
    public function bulkEmail()
    {
        if ($this->request->has('checked')) {
            $data['page']  = $this->getPage();
            $data['indexings'] = $this->indexing->whereIn('id', $this->request->checked)->select('id', 'name', 'email')->get();

            return view('indexings::bulkEmail')->with($data);
        }
        return response()->json(['message' => 'No indexings selected', 'errors' => ['missing' => ["Please select atleast 1 indexing"]]], 500);
    }
    /**
     * Send email to multiple indexings
     */
    public function sendBulk(BulkSendRequest $request)
    {
        $when = empty($request->later_date) ? now()->addMinutes(1) : dateParser($request->later_date);
        if ($request->has('indexings')) {
            foreach ($request->indexings as $l) {
                $indexing = $this->indexing->findOrFail($l);
                $mail = $indexing->emails()->create(
                    [
                        'to'          => $indexing->id,
                        'from'        => \Auth::id(),
                        'subject'     => $request->subject,
                        'message'     => str_replace("{name}", $indexing->name, $request->message),
                        'reserved_at' => $when->toDateTimeString(),
                        'meta'        => [
                            'sender' => get_option('company_email'),
                            'to'     => $indexing->email,
                        ],
                    ]
                );
                \Mail::to($indexing)->bcc(!empty($request->bcc) ? $request->bcc : [])->later($when, new IndexingsBulkEmail($mail, \Auth::user()->profile->email_signature));
            }
        }
        $data['message']  = langapp('sent_successfully');
        $data['redirect'] = route('indexings.index');

        return ajaxResponse($data);
    }
    /**
     * Delete multiple indexings
     */
    public function bulkDelete()
    {
        if ($this->request->has('checked')) {
            BulkDeleteIndexings::dispatch($this->request->checked, \Auth::id())->onQueue('normal');
            $data['message']  = langapp('deleted_successfully');
            $data['redirect'] = url()->previous();
            return ajaxResponse($data);
        }
        return response()->json(['message' => 'No indexings selected', 'errors' => ['missing' => ["Please select atleast 1 indexing"]]], 500);
    }

    public function ajaxDeleteMail()
    {
        if ($this->request->ajax()) {
            Emailing::findOrFail($this->request->id)->delete();
            return response()->json(
                ['status' => 'success', 'message' => langapp('deleted_successfully')],
                200
            );
        }
    }

    /**
     * Get indexings for display in datatable
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tableData()
    {
        $model = $this->applyFilter()->with('status:id,name', 'agent:id,username,name');

        return DataTables::eloquent($model)
            ->editColumn(
                'name',
                function ($indexing) {
                    $str = '<a href="' . route('indexings.view', $indexing->id) . '">';
                    if ($indexing->has_email) {
                        $str .= '<i class="fas fa-envelope-open text-danger"></i> ';
                    }
                    if ($indexing->has_chats) {
                        $str .= '<i class="fab fa-whatsapp text-danger"></i> ';
                    }
                    return $str . str_limit($indexing->name, 15) . '</a>';
                }
            )
            ->editColumn(
                'chk',
                function ($indexing) {
                    return '<label><input type="checkbox" name="checked[]" value="' . $indexing->id . '"><span class="label-text"></span></label>';
                }
            )
            ->editColumn(
                'company',
                function ($indexing) {
                    return str_limit($indexing->company, 15);
                }
            )
            ->editColumn(
                'email',
                function ($indexing) {
                    $str = '<a href="' . route('indexings.view', ['indexing' => $indexing->id, 'tab' => 'conversations']) . '">';
                    return $str . $indexing->email . '</a>';
                }
            )
            ->editColumn(
                'indexing_value',
                function ($indexing) {
                    return formatCurrency(get_option('default_currency'), (float) $indexing->indexing_value);
                }
            )
            ->editColumn(
                'stage',
                function ($indexing) {
                    return '<span class="text-dark">' . str_limit($indexing->status->name, 15) . '</span>';
                }
            )
            ->editColumn(
                'sales_rep',
                function ($indexing) {
                    return str_limit(optional($indexing->agent)->name, 15);
                }
            )
            ->rawColumns(['name', 'stage', 'chk', 'indexing', 'email'])
            ->make(true);
    }

    public function importGoogleContacts()
    {
        $code          = $this->request->code;
        $googleService = \OAuth::consumer('Google', route('indexings.import.callback'));
        if (!is_null($code)) {
            $token  = $googleService->requestAccessToken($code);
            $result = json_decode($googleService->request('https://www.google.com/m8/feeds/contacts/default/full?alt=json&max-results=1500'), true);
            session(['lock_assigned_alert' => true]);
            foreach ($result['feed']['entry'] as $contact) {
                if (isset($contact['gd$email'])) {
                    $data              = [];
                    $data['name']      = isset($contact['title']['$t']) ? $contact['title']['$t'] : $contact['gd$email'][0]['address'];
                    $data['source']    = 'Google Contacts';
                    $data['stage_id']  = get_option('default_indexing_stage');
                    $data['job_title'] = isset($contact['gd$organization'][0]['gd$orgTitle']['$t']) ? $contact['gd$organization'][0]['gd$orgTitle']['$t'] : '';
                    $data['company']   = isset($contact['gd$organization'][0]['gd$orgName']['$t']) ? $contact['gd$organization'][0]['gd$orgName']['$t'] : '';
                    $data['phone']     = isset($contact['gd$phoneNumber'][0]['$t']) ? $contact['gd$phoneNumber'][0]['$t'] : '';
                    $data['email']     = $contact['gd$email'][0]['address'];
                    $data['address1']  = isset($contact['gd$postalAddress'][0]['$t']) ? $contact['gd$postalAddress'][0]['$t'] : '';
                    $data['city']      = isset($contact['gd$structuredPostalAddress'][0]['gd$city']) ? $contact['gd$structuredPostalAddress'][0]['gd$city'] : '';
                    $data['state']     = isset($contact['gd$structuredPostalAddress'][0]['gd$region']) ? $contact['gd$structuredPostalAddress'][0]['gd$region'] : '';
                    $data['country']   = isset($contact['gd$structuredPostalAddress'][0]['gd$country']) ? $contact['gd$structuredPostalAddress'][0]['gd$country'] : '';
                    $data['sales_rep'] = get_option('default_sales_rep');
                    $indexing              = Indexing::updateOrCreate(
                        [
                            'email' => $contact['gd$email'][0]['address'],
                        ],
                        $data
                    );
                    $indexing->tag('google');
                }
            }
            session(['lock_assigned_alert' => false]);

            toastr()->info('Indexings created from Google contacts', langapp('response_status'));

            return redirect()->route('indexings.index');
        } else {
            $url = $googleService->getAuthorizationUri();
            return redirect((string) $url);
        }
    }

    protected function applyFilter()
    {
        if ($this->request->filter === 'converted') {
            return $this->indexing->apply(['converted' => 1])->whereNull('archived_at');
        }
        if ($this->request->filter === 'archived') {
            return $this->indexing->apply(['archived' => 1]);
        }
        return $this->indexing->query()->whereNull('archived_at');
    }

    protected function getDisplayType()
    {
        if (!is_null($this->request->view)) {
            session(['indexingview' => $this->displayType]);
        }

        return session('indexingview', $this->displayType);
    }

    private function getPage()
    {
        return langapp('indexings');
    }
	
    protected function getIndexJobFile()
    {
        

        return session('indexingview', $this->displayType);
    }
	
}
