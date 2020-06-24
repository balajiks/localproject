<?php

namespace Modules\Indexings\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Comments\Transformers\CommentsResource;
use Modules\Extras\Transformers\CallsResource;
use Modules\Indexings\Entities\Indexing;
use Modules\Indexings\Events\IndexingConverted;
use Modules\Indexings\Http\Requests\IndexingsRequest;
use Modules\Indexings\Http\Requests\CreateSectionRequest;
use Modules\Indexings\Http\Requests\CreateMedicalRequest;
use Modules\Indexings\Http\Requests\CreateDruglinksRequest;
use Modules\Indexings\Http\Requests\CreateCtnRequest;

use Modules\Indexings\Transformers\IndexingResource;
use Modules\Indexings\Transformers\IndexingsResource;
use Modules\Todos\Transformers\TodosResource;
use DB;
class IndexingsApiController extends Controller
{
    /**
     * Indexing Model
     *
     * @var \Modules\Indexings\Entities\Indexing
     */
    protected $indexing;
    /**
     * Request instance
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->middleware('localize');
        $this->request = $request;
        $this->indexing    = new Indexing;
    }
    /**
     * Return JSON Indexings
     */
    public function index()
    {
        $indexings = new IndexingsResource(
            $this->indexing->whereNull('archived_at')->whereNull('converted_at')
                ->with(['AsSource:id,name', 'status:id,name', 'agent:id,email,name,username'])
                ->orderByDesc('id')
                ->paginate(50)
        );

        return response($indexings, Response::HTTP_OK);
    }
    /**
     * Show Indexing
     */
    public function show($id = null)
    {
        $indexing = $this->indexing->findOrFail($id);
        return response(new IndexingResource($indexing), Response::HTTP_OK);
    }
	
	
	
	
	
	/**
     * Save new indexing
     */
    public function savesection(CreateSectionRequest $request)
    {
	
		/*	print $request->orderid;*/
		if($request->jobdatayestoall == '1'){
			$sectionid =  $request->indexer_section[0];
			$matchThese 		= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid, 'sectionid' => $sectionid, 'pubchoice' => $request->indexer_publication, 'classid' => $request->indexer_classification];
			$repositoryname 	= DB::table('datasections')->where($matchThese)->get()->toArray();
			/*print'<pre>';
			print_r($repositoryname);
			exit;*/
			
			if(empty($repositoryname)){
				$conditionValues	= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid];
				$repositoryname 	= DB::table('datasections')->where($conditionValues)->get()->toArray();
			
				//print count($repositoryname);
				//exit;
				
				if(count($repositoryname) ==3){
					return ajaxResponse(
						[
							'count'      => count($repositoryname)+1,
							'message' => 'Maximum 6 allowed',
						],
					false,
					Response::HTTP_OK
					);
				
				} else {
					$InsertedID = DB::table('datasections')->insertGetId(
						[
							'jobid' 		=> $request->jobid, 
							'orderid' 		=> $request->orderid,
							'pui' 			=> $request->pui, 
							'user_id' 		=> \Auth::id(),
							'sectionid' 	=> $sectionid, 
							'pubchoice' 	=> $request->indexer_publication,
							'classid' 		=> $request->indexer_classification, 
							'status' 		=> '1', 
							'created_at' 	=> date('Y-m-d H:i:s'),
						]
					);
					return ajaxResponse(
						[
							'count'      	=> count($repositoryname)+1,
							'message' 		=> langapp('saved_successfully'),
						],
					true,
					Response::HTTP_OK
					);
			  }
			} else {
				
			}
			
		} else if($request->jobdatayestoall == '0'){

			//print_r($_POST);
			$indexersection 		= implode(',',$request->indexer_section);
			$WherematchThese 		= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid];
			$findsectiondata 		= DB::table('datasections')->select('sectionid')->whereIn('sectionid',$request->indexer_section)->where($WherematchThese)->get()->toArray();
			$totalsectiondata 		= DB::table('datasections')->where($WherematchThese)->get()->toArray();
			
			if(count($totalsectiondata) != langapp('sectioncnt')){
				if(!empty($findsectiondata)){
					return ajaxResponse(
							[
								'count'      => count($findsectiondata),
								'message' 	 => langapp('sectionexists'),
							],
						false,
						Response::HTTP_OK
						);
				} else {
					$data = array();
					$html = '';
				 	foreach($request->indexer_section as $key => $sectionid){
					  $data =[
								'jobid' 		=> $request->jobid, 
								'orderid' 		=> $request->orderid,
								'pui' 			=> $request->pui,
								'user_id' 		=> \Auth::id(),
								'sectionid' 	=> $sectionid, 							
								'status' 		=> '1',
								'created_at' 	=> date('Y-m-d H:i:s'),
							   ];   
						
						$InsertedID = DB::table('datasections')->insert($data);
						$last_id 	= DB::getPDO()->lastInsertId();	 
						//$secdata 	= DB::table('datasections')->where('id', $last_id)->first();
						//DB::enableQueryLog();
						$secdata 	= DB::table('datasections')->join('embaseindex_sections', 'datasections.sectionid', '=', 'embaseindex_sections.id')
						->select('datasections.*', 'embaseindex_sections.sectionvalue')->where('datasections.user_id', \Auth::id())->where('datasections.jobid', $request->jobid)->where('datasections.orderid', $request->orderid)->where('datasections.pui', $request->pui)->where('datasections.id', $last_id)->get()->toArray();
						//dd(DB::getQueryLog());
						
						
						
						
						if ($this->request->has('json')) {
							$html.= view('indexings::newSectionHtml', compact('secdata'))->render();
						}  
				    }
					
					$conditionValues	= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid];
					$sectioncount 		= DB::table('datasections')->where($conditionValues)->count();
					
					
					//$InsertedID = DB::table('datasections')->insert($data);
					//$last_id 	= DB::getPDO()->lastInsertId();
					
					if ($this->request->has('json')) {
						return response()->json(['status' =>'success', 'count' =>$sectioncount, 'html' =>$html, 'message' => langapp('saved_successfully')], Response::HTTP_OK);
					}
					
					return ajaxResponse(
						[
							'count'      	=> $sectioncount,
							'message' 		=> langapp('saved_successfully'),
						],
					true,
					Response::HTTP_OK
					);
				}
			} else {
				return ajaxResponse(
						[
							'count'      => count($totalsectiondata),
							'message'    => langapp('sectionallow'),
						],
					false,
					Response::HTTP_OK
					);
			}
		}
    }
	
	/**
     * Save new Medical Term
     */
    public function savemedical(CreateMedicalRequest $request){
			
			if(!empty($request->indexer_diseaseslink)){
				$diseaseslink = implode(',',@$request->indexer_diseaseslink);
			} else {
				$diseaseslink = 'Null';
			}
			
			if($request->medicaltermindexing == '1' && $request->txtmedicalterm !=''){
				$data =[
						'jobid' 		=> $request->jobid, 
						'orderid' 		=> $request->orderid,
						'pui' 			=> $request->pui,
						'user_id' 		=> \Auth::id(),
						'type' 			=> 'major',
						'termtype' 		=> $request->txtmedicaltermtype,		
						'medicalterm' 	=> $request->txtmedicalterm,
						'diseaseslink' 	=> $diseaseslink,
						'status' 		=> '1', 		
						'created_at' 	=> date('Y-m-d H:i:s'),
					   ];   
				$InsertedID = DB::table('index_medical_term')->insert($data);
				$last_id 	= DB::getPDO()->lastInsertId();	
			} elseif($request->medicaltermindexing == '0' && $request->txtmedicalterm !=''){
				$data =[
						'jobid' 		=> $request->jobid, 
						'orderid' 		=> $request->orderid,
						'pui' 			=> $request->pui,
						'user_id' 		=> \Auth::id(),
						'type' 			=> 'minor',
						'termtype' 		=> $request->txtmedicaltermtype, 							
						'medicalterm' 	=> $request->txtmedicalterm,
						'diseaseslink' 	=> $diseaseslink,
						'status' 		=> '1', 		
						'created_at' 	=> date('Y-m-d H:i:s'),
					   ];   
				$InsertedID = DB::table('index_medical_term')->insert($data);
				$last_id 	= DB::getPDO()->lastInsertId();	
			}
			
			if($request->hide_mmtct == 'TRUE'){
				foreach($request->medicalchecktags as $key => $checktags){
						$data =[
								'jobid' 		=> $request->jobid, 
								'orderid' 		=> $request->orderid,
								'pui' 			=> $request->pui,
								'user_id' 		=> \Auth::id(),
								'checktag' 	=> $checktags, 							
								'status' 		=> '1',
								'created_at' 	=> date('Y-m-d H:i:s'),
							   ];   
				$checktags_last_id 	=	DB::table('index_medical_checktag')->insert($data);
				$last_id 			= 	DB::getPDO()->lastInsertId();	
				$checktagdata[]		= 	DB::table('index_medical_checktag')->where('id', $last_id)->get()->toArray();
				}
			}
			
			
			
			//Last Inserted Data 			
			$medicaltermdata 		= DB::table('index_medical_term')->where('id', $last_id)->get()->toArray();
			
			
			
			
			
			//Total count of data
			
			$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid];
			$medicaltermtypecount 	= DB::table('index_medical_term')->select('type', DB::raw('count(*) as total'))->where($matchThese)->groupBy('type')->pluck('total','type')->all();
			$checktagcount 			= DB::table('index_medical_checktag')->where($matchThese)->get()->toArray();
			
			$diseasescount 			= DB::table('index_medical_term')->select(DB::raw("(CHAR_LENGTH(diseaseslink) - CHAR_LENGTH(REPLACE(diseaseslink, ',', '')) + 1) as TotalValue"))->where($matchThese)->where('diseaseslink', '<>', 'Null')->get()->toArray();
			$totaldiseasescnt = 0;
			foreach($diseasescount as $cntval){
			   $totaldiseasescnt = $totaldiseasescnt + $cntval->TotalValue;
			}
	
			$medicaldata = array();
			foreach($medicaltermdata as $termgroup){
			   $medicaldata[$termgroup->type][] = $termgroup;
			}
			
			
			$data['checktagdata']   		= @$checktagdata;
			$data['medicaltermdata']   		= $medicaltermdata;
			$data['type']   				= $request->medicaltermindexing;	
			
			
				
			$majorcount 					= @$medicaltermtypecount['major'];	
			$minorcount 					= @$medicaltermtypecount['minor'];
			$checktagscount 				= count($checktagcount);
			$totalmedcountterm				= @$medicaltermtypecount['major'] + @$medicaltermtypecount['minor'] + @$checktagscount;
			
			
			if ($this->request->has('json')) {
				$htmlmedicalterm	= view('indexings::indexmedial.newMedicalHtml', compact('data'))->render();
				$htmldiseases		= view('indexings::indexmedial.newMedicaldiseasesHtml', compact('data'))->render();
				$htmlchecktag		= view('indexings::indexmedial.newMedicalchecktagHtml', compact('data'))->render();
				
				
				
				
				
				return response()->json(['status' =>'success', 'type' => $request->medicaltermindexing,  'totalmedcountterm' => $totalmedcountterm, 'diseasescount' => $totaldiseasescnt, 'minorcount' => $minorcount,'majorcount' => $majorcount,'checktagcount' => $checktagscount, 'htmlchecktag' =>$htmlchecktag, 'htmlmedicalterm' =>$htmlmedicalterm, 'htmldiseases' =>$htmldiseases, 'message' => langapp('saved_successfully')], Response::HTTP_OK);
			} 
			
			
			return ajaxResponse(
            [
                'message'  => langapp('saved_successfully')
            ],
            true,
            Response::HTTP_CREATED
        );
			
    }
	
	
	
	/**
     * Save new indexing
    */
	public function savectn(CreateCtnRequest $request)
    {
		$data =[
				'jobid' 		=> $request->jobid, 
				'orderid' 		=> $request->orderid,
				'pui' 			=> $request->pui,
				'user_id' 		=> \Auth::id(),
				'registryname' 	=> $request->registryname,		
				'trailnumber' 	=> $request->clinicaltrailnumber,
				'status' 		=> '1', 		
				'created_at' 	=> date('Y-m-d H:i:s'),
			   ];   
		$InsertedID = DB::table('ctn')->insert($data);
		$last_id 	= DB::getPDO()->lastInsertId();	
		
		
		//Last Inserted Data 			
		$data['ctntermdata'] 		= DB::table('ctn')->where('id', $last_id)->get()->toArray();
			
			
		if ($this->request->has('json')) {
				$htmlctnterm	= view('indexings::indexctn.newTrailnumberHtml', compact('data'))->render();
				return response()->json(['status' =>'success',  'htmlctnterm' =>$htmlctnterm, 'message' => langapp('saved_successfully')], Response::HTTP_OK);
		} 
		
		 return ajaxResponse(
            [
                'id'       => $last_id,
                'message'  => langapp('saved_successfully'),
            ],
            true,
            Response::HTTP_CREATED
        );
			
	
	}
	
	/**
     * Delete a indexing
     */
    public function deletectn($id = null)
    {
		DB::table('ctn')->where('id', $id)->delete();
		
		
        return ajaxResponse(
            [
                'message'  => langapp('deleted_successfully'),
                'redirect' => route('indexings.index'),
            ],
            true,
            Response::HTTP_OK
        );
    }
	
	/**
     * Delete a indexing
     */
    public function drugtrademanufacture($id = null)
    {
		DB::table('drugtradename')->where('id', $id)->delete();
		
		
        return ajaxResponse(
            [
                'message'  => langapp('deleted_successfully'),
                'redirect' => route('indexings.index'),
            ],
            true,
            Response::HTTP_OK
        );
    }
	
	
	/**
     * Delete a indexing
     */
    public function deletedrugtradename($id = null, $value = null)
    {
		
		$tradelist 		= 	DB::table('drugtradename')->where('id', $id)->get()->toArray();
		$explodedata 	=  	explode(',',$tradelist[0]->tradename);
		$to_remove[] = $value;
		$result = array_diff($explodedata, $to_remove);
		$tradenamedata = implode(',',$result);
		DB::table('drugtradename')->where('id', $id)->update(['tradename' => $tradenamedata]);
		
        return ajaxResponse(
            [
                'message'  => langapp('deleted_successfully'),
                'redirect' => route('indexings.index'),
            ],
            true,
            Response::HTTP_OK
        );
    }
	
	
	
    /**
     * Save new indexing
     */
    public function save(IndexingsRequest $request)
    {
        $indexing = $this->indexing->firstOrCreate(['email' => $request->email], $request->except(['custom', 'tags']));

        return ajaxResponse(
            [
                'id'       => $indexing->id,
                'message'  => langapp('saved_successfully'),
                'redirect' => route('indexings.view', $indexing->id),
            ],
            true,
            Response::HTTP_CREATED
        );
    }
    /**
     * Update indexing
     */
    public function update(IndexingsRequest $request, $id = null)
    {
        $request->validate(['email' => 'unique:indexings,email,'.$id]);
        $indexing = $this->indexing->findOrFail($id);
        $indexing->update($request->except(['custom', 'tags']));
        return ajaxResponse(
            [
                'id'       => $indexing->id,
                'message'  => langapp('changes_saved_successful'),
                'redirect' => route('indexings.view', $indexing->id),
            ],
            true,
            Response::HTTP_OK
        );
    }
    /**
     * Convert indexing to opportunity
     */
    public function convert($id)
    {
        $this->request->validate(['deal_title' => 'required', 'id' => 'required']);
        $indexing = $this->indexing->findOrFail($id);
        $data = $indexing->toCustomer();
        event(new IndexingConverted($indexing, \Auth::id()));

        return ajaxResponse($data);
    }
    /**
     * Move indexing to next stage
     */
    public function nextStage($id = null)
    {
        $this->request->validate(['stage' => 'required']);
        $indexing = $this->indexing->findOrFail($id);
        $indexing->update(['stage_id' => $this->request->stage]);
        return ajaxResponse(
            [
                'id'       => $indexing->id,
                'message'  => langapp('saved_successfully'),
                'redirect' => $this->request->url,
            ],
            true,
            Response::HTTP_OK
        );
    }
    /**
     * Move indexing to specified stage
     */
    public function moveStage()
    {
        $target_id = \App\Entities\Category::whereName(humanize($this->request->target))->first()->id;
        $indexing      = $this->indexing->findOrFail($this->request->id);
        $indexing->update(['stage_id' => $target_id]);
        return ajaxResponse(
            [
                'id'      => $indexing->id,
                'message' => langapp('indexing_stage_changed', ['name' => $indexing->name, 'stage' => humanize($this->request->target)]),
            ],
            true,
            Response::HTTP_OK
        );
    }
	
	 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function calls($id = null)
    {
        $indexing  = $this->indexing->findOrFail($id);
        $calls = new CallsResource($indexing->calls()->orderBy('id', 'desc')->paginate(50));
        return response($calls, Response::HTTP_OK);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function todos($id = null)
    {
        $indexing  = $this->indexing->findOrFail($id);
        $todos = new TodosResource($indexing->todos()->with(['agent:id,username,name'])->orderBy('id', 'desc')->paginate(50));
        return response($todos, Response::HTTP_OK);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function comments($id = null)
    {
        $indexing     = $this->indexing->findOrFail($id);
        $comments = new CommentsResource($indexing->comments()->orderBy('id', 'desc')->paginate(50));
        return response($comments, Response::HTTP_OK);
    }
    /**
     * Delete a indexing
     */
    public function delete($id = null)
    {
        $indexing = $this->indexing->findOrFail($id);
        $indexing->delete();
        return ajaxResponse(
            [
                'message'  => langapp('deleted_successfully'),
                'redirect' => route('indexings.index'),
            ],
            true,
            Response::HTTP_OK
        );
    }
	
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function terms()
	{
		$term		=	$this->request->searchterm;
		$termdata 	= 	DB::table('terms')->where('term_name', 'like', ''.$term.'%')->take(10)->get();
		$output 	= 	'<ul id="termList">'; 	
		foreach($termdata as $termval){
			$output .=	'<li onClick="selectedTerms(\''.$termval->term_name.'\',\''.$termval->term_type.'\')">'.$termval->term_name.' <span class="btn-warning btn-xs pull-right">['.$termval->term_type.']</span></li>';
			
			
		}
		$output .= '</ul>';  
		return response($output, Response::HTTP_OK);	
	}
	
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function termdrug()
	{
		$term		=	$this->request->searchterm;
		
		//DB::enableQueryLog();
						
						
		$termdata 	= 	DB::table('terms')->where('term_type','=','DRG')->where('term_name', 'like', ''.$term.'%')->take(10)->get();
		//dd(DB::getQueryLog());
		$output 	= 	'<ul id="termList">'; 	
		foreach($termdata as $termval){
			$output .=	'<li onClick="selectedTerms(\''.$termval->term_name.'\',\''.$termval->term_type.'\')">'.$termval->term_name.' <span class="btn-warning btn-xs pull-right">['.$termval->term_type.']</span></li>';
			
			
		}
		$output .= '</ul>';  
		return response($output, Response::HTTP_OK);	
	}
	
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function termdevice()
	{
		$term		=	$this->request->searchterm;
		$termdata 	= 	DB::table('terms')->where('term_type','=','MDV')->where('term_name', 'like', ''.$term.'%')->take(10)->get();
		$output 	= 	'<ul id="termList">'; 	
		foreach($termdata as $termval){
			$output .=	'<li onClick="selectedTerms(\''.$termval->term_name.'\',\''.$termval->term_type.'\')">'.$termval->term_name.' <span class="btn-warning btn-xs pull-right">['.$termval->term_type.']</span></li>';
		}
		$output .= '</ul>';  
		return response($output, Response::HTTP_OK);	
	}
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	function termemmans()
	{
		$term		=	$this->request->searchterm;
		$termdata 	= 	DB::table('manufacturer')->where('manufacturer', 'like', ''.$term.'%')->take(10)->get();
		$output 	= 	'<ul id="termList">'; 	
		foreach($termdata as $termval){
			if($termval->synonym_IDs!=''){
			   $output .=	'<label class="btn-info" style="width:100%; margin:0px!important;padding:5px;">'.$termval->manufacturer.'</label>';
			   $synonymterms = 	$results = DB::select('select * from tbl_manufacturer_synonym where id IN ('.$termval->synonym_IDs.') ');
				foreach($synonymterms as $synonyms){
					$output .=	'<li onClick="selectedemmansTerms(\''.$synonyms->synonym.'\')">'.$synonyms->type.' : '.$synonyms->synonym.'</li>';
				}
			} else {
				$output .=	'<label class="btn-info" style="width:100%; margin:0px!important; padding:5px;">'.$termval->manufacturer.'</label>';
				$output .=	'<li onClick="selectedemmansTerms(\''.$termval->manufacturer.'\')">'.$termval->manufacturer.'</li>';
			}
			
		}
		$output .= '</ul>'; 
		return response($output, Response::HTTP_OK);
	}
	
		/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	function termcountry()
	{
		$term		=	$this->request->searchterm;
		
		$termdata 	= 	DB::table('countries')->where('name', 'like', ''.$term.'%')->take(20)->get();
		//dd(DB::getQueryLog());
		$output 	= 	'<ul id="termList">'; 	
		foreach($termdata as $termval){
			$name 	 = $termval->name.'|'.$termval->code;
			$output .=	'<li onClick="selectedCountry(\''.$name.'\')">'.$termval->name.'</li>';
			
			
		}
		$output .= '</ul>';  
		return response($output, Response::HTTP_OK);
	}

	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function classification()
    {
		$output = '<option selected="true" disabled="disabled">Select Classification</option>';
		if($this->request->id!=''){
			$classification = DB::table('embaseindex_classifications')->where('section_id', $this->request->id)->get();
			foreach($classification as $classval){
				$output .=	'<option value="'.$classval->id.'">'.$classval->classvalue.'</option>';
			}
		}
		
        /*$indexing     = $this->indexing->findOrFail($id);
        $comments = new CommentsResource($indexing->comments()->orderBy('id', 'desc')->paginate(50));
        return response($comments, Response::HTTP_OK);*/
		return response($output, Response::HTTP_OK);
    }
	
	 /**
     * Delete a indexing
     */
    public function deletesection($id = null)
    {
		DB::table('datasections')->where('id', $id)->delete();
		
		
        return ajaxResponse(
            [
                'message'  => langapp('deleted_successfully'),
                'redirect' => route('indexings.index'),
            ],
            true,
            Response::HTTP_OK
        );
    }
	
	
	 /**
     * Delete a indexing
     */
    public function deletemedical($id = null,$jobid = null,$orderid = null)
    {
		DB::table('index_medical_term')->where('id', $id)->delete();
		
		//Total count of data
			
		$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $jobid, 'orderid' => $orderid];
		$medicaltermtypecount 	= DB::table('index_medical_term')->select('type', DB::raw('count(*) as total'))->where($matchThese)->groupBy('type')->pluck('total','type')->all();
		$checktagcount 			= DB::table('index_medical_checktag')->where($matchThese)->get()->toArray();
		
		$diseasescount 			= DB::table('index_medical_term')->select(DB::raw("(CHAR_LENGTH(diseaseslink) - CHAR_LENGTH(REPLACE(diseaseslink, ',', '')) + 1) as TotalValue"))->where($matchThese)->where('diseaseslink', '<>', 'Null')->get()->toArray();
		$totaldiseasescnt = 0;
		foreach($diseasescount as $cntval){
		   $totaldiseasescnt = $totaldiseasescnt + $cntval->TotalValue;
		}

		$majorcount 					= @$medicaltermtypecount['major'];	
		$minorcount 					= @$medicaltermtypecount['minor'];
		$checktagscount 				= count($checktagcount);
		$totalmedcountterm				= @$medicaltermtypecount['major'] + @$medicaltermtypecount['minor'] + @$checktagscount;
		
        return ajaxResponse(
            [
                'message'  			=> langapp('deleted_successfully'),
				'majorcount' 		=> $majorcount,
				'minorcount'  		=> $minorcount,
				'checktagscount'  	=> $checktagscount,
				'totalmedcountterm' => $totalmedcountterm,
				'totaldiseasecount' => $totaldiseasescnt,
                'redirect' 			=> route('indexings.index'),
            ],
            true,
            Response::HTTP_OK
        );
    }
	
	 /**
     * Delete a indexing
     */
    public function deletemedicalchecktag($id = null,$jobid = null,$orderid = null)
    {
		DB::table('index_medical_checktag')->where('id', $id)->delete();
		
		//Total count of data
			
		$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $jobid, 'orderid' => $orderid];
		$medicaltermtypecount 	= DB::table('index_medical_term')->select('type', DB::raw('count(*) as total'))->where($matchThese)->groupBy('type')->pluck('total','type')->all();
		$checktagcount 			= DB::table('index_medical_checktag')->where($matchThese)->get()->toArray();
		
		$diseasescount 			= DB::table('index_medical_term')->select(DB::raw("(CHAR_LENGTH(diseaseslink) - CHAR_LENGTH(REPLACE(diseaseslink, ',', '')) + 1) as TotalValue"))->where($matchThese)->where('diseaseslink', '<>', 'Null')->get()->toArray();
		$totaldiseasescnt = 0;
		foreach($diseasescount as $cntval){
		   $totaldiseasescnt = $totaldiseasescnt + $cntval->TotalValue;
		}

		$majorcount 					= @$medicaltermtypecount['major'];	
		$minorcount 					= @$medicaltermtypecount['minor'];
		$checktagscount 				= count($checktagcount);
		$totalmedcountterm				= @$medicaltermtypecount['major'] + @$medicaltermtypecount['minor'] + @$checktagscount;
		
        return ajaxResponse(
            [
                'message'  			=> langapp('deleted_successfully'),
				'majorcount' 		=> $majorcount,
				'minorcount'  		=> $minorcount,
				'checktagscount'  	=> $checktagscount,
				'totalmedcountterm' => $totalmedcountterm,
				'totaldiseasecount' => $totaldiseasescnt,
                'redirect' 			=> route('indexings.index'),
            ],
            true,
            Response::HTTP_OK
        );
    }
	
	
	
	/**
     * Save new Medical Term
     */
    public function savedrug(CreateMedicalRequest $request){
			if($request->fcttermindexing == '1' && $request->txtdrugmedicalterm !=''){
				$data =[
						'jobid' 		=> $request->jobid, 
						'orderid' 		=> $request->orderid,
						'pui' 			=> $request->pui,
						'user_id' 		=> \Auth::id(),
						'type' 			=> 'major',
						'termtype' 		=> $request->txtdrugtermtype,		
						'drugterm' 	=> $request->txtdrugmedicalterm,
						'status' 		=> '1', 		
						'created_at' 	=> date('Y-m-d H:i:s'),
					   ];   
				$InsertedID = DB::table('index_drug')->insert($data);
				$last_id 	= DB::getPDO()->lastInsertId();	
			} elseif($request->fcttermindexing == '0' && $request->txtdrugmedicalterm !=''){
				$data =[
						'jobid' 		=> $request->jobid, 
						'orderid' 		=> $request->orderid,
						'pui' 			=> $request->pui,
						'user_id' 		=> \Auth::id(),
						'type' 			=> 'minor',
						'termtype' 		=> $request->txtdrugtermtype, 							
						'drugterm' 		=> $request->txtdrugmedicalterm,
						'status' 		=> '1', 		
						'created_at' 	=> date('Y-m-d H:i:s'),
					   ];   
				$InsertedID = DB::table('index_drug')->insert($data);
				$last_id 	= DB::getPDO()->lastInsertId();	
			}
			//Last Inserted Data 			
			$drugtermdata 		= DB::table('index_drug')->where('id', $last_id)->get()->toArray();
			//Total count of data
			$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid];
			$drugtermtypecount 	= DB::table('index_drug')->select('type', DB::raw('count(*) as total'))->where($matchThese)->groupBy('type')->pluck('total','type')->all();
	
			$drugdata = array();
			foreach($drugtermdata as $termgroup){
			   $drugdata[$termgroup->type][] = $termgroup;
			}
			$data['drugtermdata']   		= $drugtermdata;
			$data['type']   				= $request->fcttermindexing;	
			$majorcount 					= @$drugtermtypecount['major'];	
			$minorcount 					= @$drugtermtypecount['minor'];
			$totaldrugcountterm				= @$drugtermtypecount['major'] + @$drugtermtypecount['minor'];
			if ($this->request->has('json')) {
				$htmldrugterm	= view('indexings::indexdrug.newDrugHtml', compact('data'))->render();
				return response()->json(['status' =>'success', 'type' => $request->fcttermindexing,  'totaldrugcountterm' => $totaldrugcountterm, 'minorcount' => $minorcount,'majorcount' => $majorcount,'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
			} 
			
			return ajaxResponse(
            [
                'message'  => langapp('saved_successfully')
            ],
            true,
            Response::HTTP_CREATED
        );
	}
	
	 public function savedrugtradename(CreateMedicalRequest $request){
	 
			if($request->termDTNindexing == '1' && $request->id =='0'){
					$data =[
						'jobid' 			=> $request->jobid, 
						'orderid' 			=> $request->orderid,
						'pui' 				=> $request->pui,
						'user_id' 			=> \Auth::id(),
						'type' 				=> 'ma',
						'countrycode' 		=> $request->txtcountrycode,		
						'manufacturename' 	=> $request->txtdrugmanufacturename,
						'tradename' 		=> implode(',',@$request->txtdrugtradename),
						'status' 			=> '1', 		
						'created_at' 		=> date('Y-m-d H:i:s'),
					   ];   
					$InsertedID = DB::table('drugtradename')->insert($data);
					$last_id 	= DB::getPDO()->lastInsertId();	
					$action		= 'insert';
				
				} else if($request->id =='0'){
					$data =[
						'jobid' 			=> $request->jobid, 
						'orderid' 			=> $request->orderid,
						'pui' 				=> $request->pui,
						'user_id' 			=> \Auth::id(),
						'type' 				=> 'tr',
						'tradename' 		=> implode(',',@$request->txtdrugtradename),
						'status' 			=> '1', 		
						'created_at' 		=> date('Y-m-d H:i:s'),
					   ];   
					$InsertedID = DB::table('drugtradename')->insert($data);
					$last_id 	= DB::getPDO()->lastInsertId();
					$action		= 'insert';	
				} else if($request->id !='0'){
					$tbl_drugtradename	= DB::table('drugtradename')->where('id', $request->id)->get()->toArray();
					$explodedata		= explode(',',@$tbl_drugtradename[0]->tradename);
					$result 			= array_merge($explodedata,@$request->txtdrugtradename);
					$tradenamedata 		= implode(',',$result);
					DB::table('drugtradename')->where('id', $request->id)->update(['tradename' => $tradenamedata]);
					$last_id 			= $request->id;
					$action				= 'update';
				}
				
				$tbl_drugtradename	= DB::table('drugtradename')->where('id', $last_id)->get()->toArray();
				$explodedata		= explode(',',@$tbl_drugtradename[0]->tradename);
				$indexedtradename	= '"' . implode ( '", "', $explodedata ) . '"';
				
				$data['drugtradename']	= $tbl_drugtradename;
				$data['tblindex_tradename']		= $indexedtradename;	
				
				
				$htmldrugterm	= view('indexings::indexdrug.newdrugtradenameHtml', compact('data'))->render();
				return response()->json(['status' =>'success', 'action' =>$action, 'id' => $last_id, 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);	
	
	}
	
	public function tradenamedata(CreateMedicalRequest $request)
	{
		
		$tbl_drugtradename	= DB::table('drugtradename')->where('id', $request->selectedterm)->get()->toArray();
		$explodedata		= explode(',',@$tbl_drugtradename[0]->tradename);
		
		$data['drugtradename']	= $explodedata;
		$data['selectedid']		= $request->selectedterm;
		$data['type']			= $tbl_drugtradename[0]->type;
		$data['manufacturename']= $tbl_drugtradename[0]->manufacturename;
		$data['countrycode']	= $tbl_drugtradename[0]->countrycode;
		
		$htmldrugterm	= view('indexings::indexdrug.ajaxtradenamelistHtml', compact('data'))->render();
		return response()->json(['status' =>'success', 'type' => $data['type'], 'manufacturename' => $data['manufacturename'], 'countrycode' => $data['countrycode'], 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);	

	}
	
	 public function savedruglinks(CreateDruglinksRequest $request){
	 	
		$druglinks = $request->field;
		
		switch ($druglinks) {
		  case "drugotherfield":
				$drugotherfield = implode(',',$request->drugotherfield);
				DB::table('index_drug')->where('id', $request->selecteddrugid)->update(['drugotherfields' => $drugotherfield]);

				$tbldrugotherfields		= DB::table('drug_otherfield')->where('status', 1)->get()->toArray();
				
				$data['tbldrugotherfields']	= $tbldrugotherfields;	
				$data['drugindextermtype']	= $request->drugindextermtype;
				$data['drugindexterm']		= $request->drugindexterm;
				$data['field']				= $request->field;
				$data['drugotherfield'][]	= $drugotherfield;
				$data['selecteddrugid']		= $request->selecteddrugid;
				$data['tblindex_drug']		= $request->drugotherfield;
				$htmldrugterm	= view('indexings::indexdrug.newDrugOtherfieldHtml', compact('data'))->render();
				return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
			break;
		  case "drugtherapy":
		  		
		  		$drugtherapy =	$request->drugtherapy;
				if(@$request->txtdrugtherapy !=''){
					array_push($drugtherapy,$request->txtdrugtherapy);
				}
				$seldrugtherapy = implode(',',$drugtherapy);
				DB::table('index_drug')->where('id', $request->selecteddrugid)->update(['drugtherapy' => $seldrugtherapy]);
				
				$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid,'pui' => $request->pui, 'termtype' => 'DIS'];
				$termtype 				= ['MED','DIS'];
				$tblindex_medical_term	= DB::table('index_medical_term')->select('medicalterm')->where($matchThese)->get()->toArray();
				
				$tblindex_drug			= DB::table('index_drug')->select('drugtherapy')->where('id', $request->selecteddrugid)->get()->toArray();
				$explodedata			= explode(',',@$tblindex_drug[0]->drugtherapy);
				$indexeddrugterm		= '"' . implode ( '", "', $explodedata ) . '"';
				
				$data['field']					= $request->field;
				$data['selecteddrugid']			= $request->selecteddrugid;
				$data['indexed_medical_term']	= $tblindex_medical_term;
				$data['drugtherapy']			= $seldrugtherapy;
				$data['tblindex_drug']			= $indexeddrugterm;
				
				$htmldrugterm	= view('indexings::indexdrug.newDrugTherapyHtml', compact('data'))->render();
				return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
			
			break;
		  case "drugdoseinfo":
		  		$routeofdrug =	$request->routeofdrug;
				$selrouteofdrug = implode(',',$routeofdrug);
				DB::table('index_drug')->where('id', $request->selecteddrugid)->update(['routeofdrug' => $selrouteofdrug]);
					
				$tblroutedrugadmins		= DB::table('routedrugadmins')->where('status', 'Active')->get()->toArray();
			
				$tblindex_drug			= DB::table('index_drug')->select('routeofdrug')->where('id', $request->selecteddrugid)->get()->toArray();
				$explodedata			= explode(',',@$tblindex_drug[0]->routeofdrug);
				$indexedrouteofdrug		= '"' . implode ( '", "', $explodedata ) . '"';
				
				$data['field']			= $request->field;
				$data['selecteddrugid']	= $request->selecteddrugid;
				$data['drugtermtype']	= $request->drugtermtype;
				$data['drugterm']		= $request->drugterm;
				$data['routedrugadmin']	= $tblroutedrugadmins;
				$data['tblindex_drug']	= $indexedrouteofdrug;
			
				$htmldrugterm	= view('indexings::indexdrug.newRouteDrugHtml', compact('data'))->render();
				return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
			break;
			
		  case "dosefreq":
		  		$dosefreq =	$request->dosefrequency;
				$seldosefreq = implode(',',$dosefreq);
				
				DB::table('index_drug')->where('id', $request->selecteddrugid)->update(['dosefreq' => $seldosefreq]);
					
				$tbldosefrequencys		= DB::table('dosefrequencys')->where('status', 'Active')->get()->toArray();
			
				$tblindex_drug			= DB::table('index_drug')->select('dosefreq')->where('id', $request->selecteddrugid)->get()->toArray();
				$explodedata			= explode(',',@$tblindex_drug[0]->dosefreq);
				$indexeddosefrequency		= '"' . implode ( '", "', $explodedata ) . '"';
				
				$data['field']			= $request->field;
				$data['selecteddrugid']	= $request->selecteddrugid;
				$data['drugtermtype']	= $request->drugtermtype;
				$data['drugterm']		= $request->drugterm;
				$data['dosefrequency']	= $tbldosefrequencys;
				$data['tblindex_drug']	= $indexeddosefrequency;
			
				$htmldrugterm	= view('indexings::indexdrug.newDoseFrequencyHtml', compact('data'))->render();
				return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
			break;

		  case "drugcombination":
		  		$drugcombination 	=	$request->drugcombination;
				$seldrugcombination = implode(',',$drugcombination);
				
				DB::table('index_drug')->where('id', $request->selecteddrugid)->update(['drugcomb' => $seldrugcombination]);
					
				
				
				$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid,'pui' => $request->pui];	
				$tblindex_drug			= DB::table('index_drug')->select('drugterm')->where('drugterm', '!=', $request->drugterm)->where($matchThese)->get()->toArray();
				
				$tbl_drugcombination	= DB::table('index_drug')->select('drugcomb')->where('id', $request->selecteddrugid)->get()->toArray();
				$explodedata			= explode(',',@$tbl_drugcombination[0]->drugcomb);
				$indexeddrugcombination	= '"' . implode ( '", "', $explodedata ) . '"';
				
				$data['field']			= 'drugcombination';
				$data['selecteddrugid']	= $request->selecteddrugid;
				$data['drugtermtype']	= $request->drugtermtype;
				$data['drugterm']		= $request->drugterm;
				$data['drugcombination']= $tblindex_drug;
				$data['tblindex_drug']	= $indexeddrugcombination;
				
				$htmldrugterm	= view('indexings::indexdrug.newDrugCombinationHtml', compact('data'))->render();
				return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
				break;
				
		  case "advdrug":
		  		$adversedrug =	$request->adversedrug;
				if(@$request->txtadversedrug !=''){
					array_push($adversedrug,$request->txtadversedrug);
				}
				$seladversedrug = implode(',',$adversedrug);
				DB::table('index_drug')->where('id', $request->selecteddrugid)->update(['advdrug' => $seladversedrug]);
				
				$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid,'pui' => $request->pui, 'termtype' => 'DIS'];
				$termtype 				= ['MED','DIS'];
				$tblindex_medical_term	= DB::table('index_medical_term')->select('medicalterm')->where($matchThese)->get()->toArray();
				
				$tblindex_drug			= DB::table('index_drug')->select('advdrug')->where('id', $request->selecteddrugid)->get()->toArray();
				$explodedata			= explode(',',@$tblindex_drug[0]->advdrug);
				$indexeddrugterm		= '"' . implode ( '", "', $explodedata ) . '"';
				
				$data['field']					= $request->field;
				$data['selecteddrugid']			= $request->selecteddrugid;
				$data['indexed_medical_term']	= $tblindex_medical_term;
				$data['drugtherapy']			= $seladversedrug;
				$data['tblindex_drug']			= $indexeddrugterm;
				
				$htmldrugterm	= view('indexings::indexdrug.newadvdrugHtml', compact('data'))->render();
				return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
			
			break;
				
		  case "drugcomparison":
		  		$drugcomparison 	=	$request->drugcomparison;
				$seldrugcomparison 	= implode(',',$drugcomparison);
				
				DB::table('index_drug')->where('id', $request->selecteddrugid)->update(['drugcomp' => $seldrugcomparison]);
				$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid,'pui' => $request->pui];	
				$tblindex_drug			= DB::table('index_drug')->select('drugterm')->where('drugterm', '!=', $request->drugterm)->where($matchThese)->get()->toArray();
				
				$tbl_drugcomparison		= DB::table('index_drug')->select('drugcomp')->where('id', $request->selecteddrugid)->get()->toArray();
				$explodedata			= explode(',',@$tbl_drugcomparison[0]->drugcomp);
				$indexeddrugcomparison	= '"' . implode ( '", "', $explodedata ) . '"';
				
				$data['field']			= $request->field;
				$data['selecteddrugid']	= $request->selecteddrugid;
				$data['drugtermtype']	= $request->drugindextermtype;
				$data['drugterm']		= $request->drugterm;
				$data['drugcomparison']	= $tblindex_drug;
				$data['tblindex_drug']	= $indexeddrugcomparison;
				
				$htmldrugterm	= view('indexings::indexdrug.newDrugComparisonHtml', compact('data'))->render();
				return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
				break;
				
		  case "drugdosageschedule":
		  		$drugdosescheduleterm 	=	$request->drugdosescheduleterm;
				$drugdosescheduleterm 	= implode(',',$drugdosescheduleterm);
				
				DB::table('index_drug')->where('id', $request->selecteddrugid)->update(['drugdosage' => $drugdosescheduleterm]);
				$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid,'pui' => $request->pui];	
				
				$tblindex_drug			= DB::table('index_drug')->select('drugterm')->where('drugterm', '!=', $request->drugterm)->where($matchThese)->get()->toArray();
				
				$tbl_drugdosage			= DB::table('index_drug')->select('drugdosage')->where('id', $request->selecteddrugid)->get()->toArray();
				$explodedata			= explode(',',@$tbl_drugdosage[0]->drugdosage);
				$indexeddrugdosage		= '"' . implode ( '", "', $explodedata ) . '"';
				
				$tbldrugdosescheduleterms	= DB::table('drugdosescheduleterms')->where('status', 'Active')->get()->toArray();
			
				$data['field']			= 'drugdosageschedule';
				$data['selecteddrugid']	= $request->selecteddrugid;
				$data['drugtermtype']	= $request->drugindextermtype;
				$data['drugterm']		= $request->drugindexterm;
				$data['dosescheduleterms']	= $tbldrugdosescheduleterms;
				$data['tblindex_drug']	= $indexeddrugdosage;
				$htmldrugterm	= view('indexings::indexdrug.newdrugdosagescheduleHtml', compact('data'))->render();
				return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
				break;
		
		  case "druginteraction":
		  		$druginteraction =	$request->druginteraction;
				if(@$request->txtdruginteraction !=''){
					array_push($druginteraction,$request->txtdruginteraction);
				}
				$seldruginteraction = implode(',',$druginteraction);
				
				DB::table('index_drug')->where('id', $request->selecteddrugid)->update(['druginteraction' => $seldruginteraction]);
				$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid,'pui' => $request->pui];	
				
				$tblindex_drug			= DB::table('index_drug')->select('drugterm')->where('drugterm', '!=', $request->drugterm)->where($matchThese)->get()->toArray();
				
				$tbl_druginteraction	= DB::table('index_drug')->select('druginteraction')->where('id', $request->selecteddrugid)->get()->toArray();
				$explodedata			= explode(',',@$tbl_druginteraction[0]->druginteraction);
				$indexeddruginteraction		= '"' . implode ( '", "', $explodedata ) . '"';
				
			
				$data['field']			= 'druginteraction';
				$data['selecteddrugid']	= $request->selecteddrugid;
				$data['drugtermtype']	= $request->drugindextermtype;
				$data['drugterm']		= $request->drugindexterm;
				$data['druginteraction']	= $tblindex_drug;
				$data['tblindex_drug']	= $indexeddruginteraction;
				$htmldrugterm	= view('indexings::indexdrug.newdruginteractionHtml', compact('data'))->render();
				return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
				break;
		
		  case "drugpharma":
		  		
				$selspecialsitutation = implode(',',$request->specialsitutation);				
				DB::table('index_drug')->where('id', $request->selecteddrugid)->update(['specialpharma' => $selspecialsitutation]);
				
				$selunexpectedoutcome = implode(',',$request->unexpectedoutcome);				
				DB::table('index_drug')->where('id', $request->selecteddrugid)->update(['unexpecteddrugtreatment' => $selunexpectedoutcome]);
				
				$tblspecialsituations	= DB::table('specialsituations')->where('status', 'Active')->get()->toArray();
				$tblunexpectedoutcomes	= DB::table('unexpectedoutcomes')->where('status', 'Active')->get()->toArray();
				
				
				$tbl_specialpharma		= DB::table('index_drug')->select('specialpharma')->where('id', $request->selecteddrugid)->get()->toArray();
				$explodedata			= explode(',',@$tbl_specialpharma[0]->specialpharma);
				$indexedspecialpharma	= '"' . implode ( '", "', $explodedata ) . '"';
				
				
				$tbl_drugtreatment				= DB::table('index_drug')->select('unexpecteddrugtreatment')->where('id', $request->selecteddrugid)->get()->toArray();
				$explodedata_drugtreatment		= explode(',',@$tbl_drugtreatment[0]->unexpecteddrugtreatment);
				$indexedunexpecteddrugtreatment	= '"' . implode ( '", "', $explodedata_drugtreatment ) . '"';
				
				
				$data['field']			= 'drugpharma';
				$data['selecteddrugid']	= $request->selecteddrugid;
				$data['drugtermtype']	= $request->drugtermtype;
				$data['drugterm']		= $request->drugterm;
				
				$data['specialpharma']	= $tblspecialsituations;
				$data['drugtreatment']	= $tblunexpectedoutcomes;
				
				
				$data['tblindex_drugspecialpharma']				= $indexedspecialpharma;		
				$data['tblindex_drugunexpecteddrugtreatment']	= $indexedunexpecteddrugtreatment;
				
				$htmldrugterm	= view('indexings::indexdrug.newdrugpharmaHtml', compact('data'))->render();
				return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
				break;
		
		
		 case "drugtradename":
		 
				if($request->termDTNindexing == '1' && $request->id =='0'){
					$data =[
						'jobid' 			=> $request->jobid, 
						'orderid' 			=> $request->orderid,
						'pui' 				=> $request->pui,
						'user_id' 			=> \Auth::id(),
						'drugtermid' 		=> $request->selecteddrugid,
						'type' 				=> 'ma',
						'countrycode' 		=> $request->txtcountrycode,		
						'manufacturename' 	=> $request->txtdrugmanufacturename,
						'status' 			=> '1', 		
						'created_at' 		=> date('Y-m-d H:i:s'),
					   ];   
					$InsertedID = DB::table('drugtradename')->insert($data);
					$last_id 	= DB::getPDO()->lastInsertId();	
				
				} else if($request->id =='0'){
					$data =[
						'jobid' 			=> $request->jobid, 
						'orderid' 			=> $request->orderid,
						'pui' 				=> $request->pui,
						'user_id' 			=> \Auth::id(),
						'drugtermid' 		=> $request->selecteddrugid,
						'type' 				=> 'tr',
						'tradename' 		=> $request->txtdrugtradename,
						'status' 			=> '1', 		
						'created_at' 		=> date('Y-m-d H:i:s'),
					   ];   
					$InsertedID = DB::table('drugtradename')->insert($data);
					$last_id 	= DB::getPDO()->lastInsertId();	
				}
		  		
				$tbl_drugtradename	= DB::table('drugtradename')->where('drugtermid', $request->selecteddrugid)->get()->toArray();
				$explodedata		= explode(',',@$tbl_drugtradename[0]->tradename);
				$indexedtradename	= '"' . implode ( '", "', $explodedata ) . '"';
				
				$data['field']			= $request->field;
				$data['id']				= $last_id;
				$data['selecteddrugid']	= $request->selecteddrugid;
				$data['drugtermtype']	= $request->drugtermtype;
				$data['drugterm']		= $request->drugterm;
				$data['drugtradename']	= $tbl_drugtradename;
				$data['tblindex_tradename']		= $indexedtradename;	
				
				
				$htmldrugterm	= view('indexings::indexdrug.newdrugtradenameHtml', compact('data'))->render();
				return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
				break;
		}

		return ajaxResponse(
            [
                'message'  => langapp('saved_successfully')
            ],
            true,
            Response::HTTP_CREATED
        );
		
	 }
	
	
	public function frmdrugotherfield(CreateMedicalRequest $request){
		$tbldrugotherfields		= DB::table('drug_otherfield')->where('status', 1)->get()->toArray();
		$tblindex_drug			= DB::table('index_drug')->select('drugotherfields')->where('id', $request->drugid)->get()->toArray();
		
		$data['tbldrugotherfields']	= $tbldrugotherfields;	
		$data['tblindex_drug']		= explode(',',@$tblindex_drug[0]->drugotherfields);
		
		$data['drugtermtype']	= $request->drugtermtype;
		$data['drugterm']		= $request->drugterm;
		$data['field']			= 'drugotherfield';
		$data['selecteddrugid']	= $request->drugid;
		$data['drugotherfield']	= array();
		
		$htmldrugterm	= view('indexings::indexdrug.newDrugOtherfieldHtml', compact('data'))->render();
		return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
	}

	public function frmdrugtherapy(CreateMedicalRequest $request){
		$tblindex_drug			= DB::table('index_drug')->select('drugtherapy')->where('id', $request->drugid)->get()->toArray();
		$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid,'pui' => $request->pui,'termtype' => 'DIS'];
		$termtype 				= ['MED','DIS'];
		$tblindex_medical_term	= DB::table('index_medical_term')->select('medicalterm')->where($matchThese)->get()->toArray();
		$explodedata			= explode(',',@$tblindex_drug[0]->drugtherapy);
		$indexeddrugterm		= '"' . implode ( '", "', $explodedata ) . '"';
		
		$data['field']			= 'drugtherapy';
		$data['selecteddrugid']	= $request->drugid;
		$data['drugtermtype']	= $request->drugtermtype;
		$data['drugterm']		= $request->drugterm;
		$data['indexed_medical_term']		= $tblindex_medical_term;
		$data['tblindex_drug']		= $indexeddrugterm;
		
		$htmldrugterm	= view('indexings::indexdrug.newDrugTherapyHtml', compact('data'))->render();
		return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
	}

	public function frmdrugdoseinfo(CreateMedicalRequest $request){
	
	}

	public function frmrouteofdrug(CreateMedicalRequest $request){
		$tblroutedrugadmins		= DB::table('routedrugadmins')->where('status', 'Active')->get()->toArray();
		
		$tblindex_drug			= DB::table('index_drug')->select('routeofdrug')->where('id', $request->drugid)->get()->toArray();
		$explodedata			= explode(',',@$tblindex_drug[0]->routeofdrug);
		$indexedrouteofdrug		= '"' . implode ( '", "', $explodedata ) . '"';
		
		$data['field']			= 'drugdoseinfo';
		$data['selecteddrugid']	= $request->drugid;
		$data['drugtermtype']	= $request->drugtermtype;
		$data['drugterm']		= $request->drugterm;
		$data['routedrugadmin']	= $tblroutedrugadmins;
		$data['tblindex_drug']	= $indexedrouteofdrug;
		
		$htmldrugterm	= view('indexings::indexdrug.newRouteDrugHtml', compact('data'))->render();
		return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
	
	}

	public function frmdosefrequency(CreateMedicalRequest $request){
	
		$tbldosefrequencys		= DB::table('dosefrequencys')->where('status', 'Active')->get()->toArray();
		
		$tblindex_drug			= DB::table('index_drug')->select('dosefreq')->where('id', $request->drugid)->get()->toArray();
		$explodedata			= explode(',',@$tblindex_drug[0]->dosefreq);
		$indexeddosefrequency	= '"' . implode ( '", "', $explodedata ) . '"';
		
		$data['field']			= 'dosefreq';
		$data['selecteddrugid']	= $request->drugid;
		$data['drugtermtype']	= $request->drugtermtype;
		$data['drugterm']		= $request->drugterm;
		$data['dosefrequency']	= $tbldosefrequencys;
		$data['tblindex_drug']	= $indexeddosefrequency;
		
		$htmldrugterm	= view('indexings::indexdrug.newDoseFrequencyHtml', compact('data'))->render();
		return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
	
	}

	public function frmdrugcombination(CreateMedicalRequest $request){
	
		$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid,'pui' => $request->pui];	
		$tblindex_drug			= DB::table('index_drug')->select('drugterm')->where('drugterm', '!=', $request->drugterm)->where($matchThese)->get()->toArray();
		
		$tbl_drugcombination	= DB::table('index_drug')->select('drugcomb')->where('id', $request->drugid)->get()->toArray();
		$explodedata			= explode(',',@$tbl_drugcombination[0]->drugcomb);
		$indexeddrugcombination	= '"' . implode ( '", "', $explodedata ) . '"';
		
		$data['field']			= 'drugcombination';
		$data['selecteddrugid']	= $request->drugid;
		$data['drugtermtype']	= $request->drugtermtype;
		$data['drugterm']		= $request->drugterm;
		$data['drugcombination']= $tblindex_drug;
		$data['tblindex_drug']	= $indexeddrugcombination;
		
		$htmldrugterm	= view('indexings::indexdrug.newDrugCombinationHtml', compact('data'))->render();
		return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
		
	}
	
	public function frmadversedrug(CreateMedicalRequest $request){
		$tblindex_drug			= DB::table('index_drug')->select('advdrug')->where('id', $request->drugid)->get()->toArray();
		$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid,'pui' => $request->pui,'termtype' => 'DIS'];
		$termtype 				= ['MED','DIS'];
		$tblindex_medical_term	= DB::table('index_medical_term')->select('medicalterm')->where($matchThese)->get()->toArray();
		
		$explodedata			= explode(',',@$tblindex_drug[0]->advdrug);
		$indexeddrugterm		= '"' . implode ( '", "', $explodedata ) . '"';
		
		$data['field']			= 'advdrug';
		$data['selecteddrugid']	= $request->drugid;
		$data['drugtermtype']	= $request->drugtermtype;
		$data['drugterm']		= $request->drugterm;
		$data['indexed_medical_term']		= $tblindex_medical_term;
		$data['tblindex_drug']		= $indexeddrugterm;
		
		$htmldrugterm	= view('indexings::indexdrug.newadvdrugHtml', compact('data'))->render();
		return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
	
	}

	public function frmdrugcomparison(CreateMedicalRequest $request){
	
		$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid,'pui' => $request->pui];	
		$tblindex_drug			= DB::table('index_drug')->select('drugterm')->where('drugterm', '!=', $request->drugterm)->where($matchThese)->get()->toArray();
		
		$tbl_drugcomparison		= DB::table('index_drug')->select('drugcomp')->where('id', $request->drugid)->get()->toArray();
		$explodedata			= explode(',',@$tbl_drugcomparison[0]->drugcomp);
		$indexeddrugcomparison	= '"' . implode ( '", "', $explodedata ) . '"';
		
		$data['field']			= 'drugcomparison';
		$data['selecteddrugid']	= $request->drugid;
		$data['drugtermtype']	= $request->drugtermtype;
		$data['drugterm']		= $request->drugterm;
		$data['drugcomparison']	= $tblindex_drug;
		$data['tblindex_drug']	= $indexeddrugcomparison;
		
		$htmldrugterm	= view('indexings::indexdrug.newDrugComparisonHtml', compact('data'))->render();
		return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
	
	}

	public function frmdrugdosage(CreateMedicalRequest $request){
		$tbldrugdosescheduleterms	= DB::table('drugdosescheduleterms')->where('status', 'Active')->get()->toArray();
		
		$tblindex_drug			= DB::table('index_drug')->select('drugdosage')->where('id', $request->drugid)->get()->toArray();
		$explodedata			= explode(',',@$tblindex_drug[0]->drugdosage);
		$indexeddrugdosage		= '"' . implode ( '", "', $explodedata ) . '"';
		
		$data['field']			= 'drugdosageschedule';
		$data['selecteddrugid']	= $request->drugid;
		$data['drugtermtype']	= $request->drugtermtype;
		$data['drugterm']		= $request->drugterm;
		$data['dosescheduleterms']	= $tbldrugdosescheduleterms;
		$data['tblindex_drug']	= $indexeddrugdosage;
		
		$htmldrugterm	= view('indexings::indexdrug.newdrugdosagescheduleHtml', compact('data'))->render();
		return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
	
	}

	public function frmdruginteraction(CreateMedicalRequest $request){
		$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid,'pui' => $request->pui];	
		$tblindex_drug			= DB::table('index_drug')->select('drugterm')->where('drugterm', '!=', $request->drugterm)->where($matchThese)->get()->toArray();
		
		$tbl_druginteraction	= DB::table('index_drug')->select('druginteraction')->where('id', $request->drugid)->get()->toArray();
		$explodedata			= explode(',',@$tbl_druginteraction[0]->druginteraction);
		$indexeddruginteraction	= '"' . implode ( '", "', $explodedata ) . '"';
		
		$data['field']			= 'druginteraction';
		$data['selecteddrugid']	= $request->drugid;
		$data['drugtermtype']	= $request->drugtermtype;
		$data['drugterm']		= $request->drugterm;
		$data['druginteraction']	= $tblindex_drug;
		$data['tblindex_drug']	= $indexeddruginteraction;
		
		$htmldrugterm	= view('indexings::indexdrug.newdruginteractionHtml', compact('data'))->render();
		return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
	
	}

	public function frmdrugpharma(CreateMedicalRequest $request){
		$tblspecialsituations	= DB::table('specialsituations')->where('status', 'Active')->get()->toArray();
		$tblunexpectedoutcomes	= DB::table('unexpectedoutcomes')->where('status', 'Active')->get()->toArray();
		
		$tbl_specialpharma		= DB::table('index_drug')->select('specialpharma')->where('id', $request->drugid)->get()->toArray();
		$explodedata			= explode(',',@$tbl_specialpharma[0]->specialpharma);
		$indexedspecialpharma	= '"' . implode ( '", "', $explodedata ) . '"';
		
		
		$tbl_drugtreatment				= DB::table('index_drug')->select('unexpecteddrugtreatment')->where('id', $request->drugid)->get()->toArray();
		$explodedata_drugtreatment		= explode(',',@$tbl_drugtreatment[0]->unexpecteddrugtreatment);
		$indexedunexpecteddrugtreatment	= '"' . implode ( '", "', $explodedata_drugtreatment ) . '"';
		
		
		$data['field']			= 'drugpharma';
		$data['selecteddrugid']	= $request->drugid;
		$data['drugtermtype']	= $request->drugtermtype;
		$data['drugterm']		= $request->drugterm;
		
		$data['specialpharma']	= $tblspecialsituations;
		$data['drugtreatment']	= $tblunexpectedoutcomes;
		
		
		$data['tblindex_drugspecialpharma']				= $indexedspecialpharma;		
		$data['tblindex_drugunexpecteddrugtreatment']	= $indexedunexpecteddrugtreatment;
		
		$htmldrugterm	= view('indexings::indexdrug.newdrugpharmaHtml', compact('data'))->render();
		return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
		
	
	}

	public function frmdrugtradename(CreateMedicalRequest $request){
		
		$tbl_drugtradename	= DB::table('drugtradename')->where('drugtermid', $request->drugid)->get()->toArray();
		$explodedata		= explode(',',@$tbl_drugtradename[0]->tradename);
		$indexedtradename	= '"' . implode ( '", "', $explodedata ) . '"';
		
		$data['field']			= 'drugtradename';
		$data['selecteddrugid']	= $request->drugid;
		$data['drugtermtype']	= $request->drugtermtype;
		$data['drugterm']		= $request->drugterm;
		$data['drugtradename']	= $tbl_drugtradename;
		$data['tblindex_tradename']		= $indexedtradename;
		
		$data['jobid']		= $request->jobid;
		$data['orderid']	= $request->orderid;
		$data['pui']		= $request->pui;
		
		$htmldrugterm	= view('indexings::indexdrug.newdrugtradenameHtml', compact('data'))->render();
		return response()->json(['status' =>'success', 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
	
	}

	 public function savedevicetradename(CreateMedicalRequest $request){
			if($request->termDTNindexing == '1' && $request->id =='0'){
					$data =[
						'jobid' 			=> $request->jobid, 
						'orderid' 			=> $request->orderid,
						'pui' 				=> $request->pui,
						'user_id' 			=> \Auth::id(),
						'type' 				=> 'mv',
						'countrycode' 		=> $request->txtcountrycode,		
						'manufacturename' 	=> $request->txtdevicemanufacturename,
						'tradename' 		=> implode(',',@$request->txtdevicetradename),
						'status' 			=> '1', 		
						'created_at' 		=> date('Y-m-d H:i:s'),
					   ];   
					$InsertedID = DB::table('devicetradename')->insert($data);
					$last_id 	= DB::getPDO()->lastInsertId();	
					$action		= 'insert';
				
				} else if($request->id =='0'){
					$data =[
						'jobid' 			=> $request->jobid, 
						'orderid' 			=> $request->orderid,
						'pui' 				=> $request->pui,
						'user_id' 			=> \Auth::id(),
						'type' 				=> 'tv',
						'tradename' 		=> implode(',',@$request->txtdevicetradename),
						'status' 			=> '1', 		
						'created_at' 		=> date('Y-m-d H:i:s'),
					   ];   
					$InsertedID = DB::table('devicetradename')->insert($data);
					$last_id 	= DB::getPDO()->lastInsertId();
					$action		= 'insert';	
				} else if($request->id !='0'){
					$tbl_devicetradename= DB::table('devicetradename')->where('id', $request->id)->get()->toArray();
					$explodedata		= explode(',',@$tbl_devicetradename[0]->tradename);
					$result 			= array_merge($explodedata,@$request->txtdevicetradename);
					$tradenamedata 		= implode(',',$result);
					DB::table('devicetradename')->where('id', $request->id)->update(['tradename' => $tradenamedata]);
					$last_id 			= $request->id;
					$action				= 'update';
				}
		  		
				$tbl_devicetradename	= DB::table('devicetradename')->where('id', $last_id)->get()->toArray();
				$explodedata			= explode(',',@$tbl_devicetradename[0]->tradename);
				$indexedtradename		= '"' . implode ( '", "', $explodedata ) . '"';
				
				$data['devicetradename']	= $tbl_devicetradename;
				$data['tblindex_tradename']	= $indexedtradename;	
				
				
				$htmldrugterm	= view('indexings::indexdrug.newdevicetradenameHtml', compact('data'))->render();
				return response()->json(['status' =>'success', 'action' =>$action, 'id' => $last_id, 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);	
	
	}

	public function devicetradenamedata(CreateMedicalRequest $request)
	{
		$tbl_devicetradename	= DB::table('devicetradename')->where('id', $request->selectedterm)->get()->toArray();
		$explodedata			= explode(',',@$tbl_devicetradename[0]->tradename);
		
		$data['devicetradename']= $explodedata;
		$data['selectedid']		= $request->selectedterm;
		$data['type']			= $tbl_devicetradename[0]->type;
		$data['manufacturename']= $tbl_devicetradename[0]->manufacturename;
		$data['countrycode']	= $tbl_devicetradename[0]->countrycode;
		
		$htmldrugterm	= view('indexings::indexdrug.ajaxdevicetradenamelistHtml', compact('data'))->render();
		return response()->json(['status' =>'success', 'type' => $data['type'], 'manufacturename' => $data['manufacturename'], 'countrycode' => $data['countrycode'], 'htmldrugterm' =>$htmldrugterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);	

	}

	/**
     * Delete a indexing
     */
    public function deletedevicetradename($id = null, $value = null)
    {
		$tradelist 		= 	DB::table('devicetradename')->where('id', $id)->get()->toArray();
		$explodedata 	=  	explode(',',$tradelist[0]->tradename);
		$to_remove[] = $value;
		$result = array_diff($explodedata, $to_remove);
		$tradenamedata = implode(',',$result);
		DB::table('devicetradename')->where('id', $id)->update(['tradename' => $tradenamedata]);
		
        return ajaxResponse(
            [
                'message'  => langapp('deleted_successfully'),
                'redirect' => route('indexings.index'),
            ],
            true,
            Response::HTTP_OK
        );
    }
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function sublink()
	{
		$term		=	$this->request->searchterm;
		$WherematchThese 		= ['user_id' => \Auth::id(), 'jobid' => $this->request->jobid, 'pui' => $this->request->pui, 'orderid' => $this->request->orderid];
		$output = '';
		switch ($term) {
		  case 'Adverse device effect':
		  		$sublinks	= 	DB::table('index_medical_term')->where($WherematchThese)->where('termtype','DIS')->get()->toArray();
				foreach($sublinks as $sublink){
					$output .=	'<option value="'.$sublink->medicalterm.'">'.$sublink->medicalterm.'</option>';
				}
			break;
		  case 'Clinical trial':
			break;
		  case 'Device Comparison':
			break;
		  case 'Device economics':
			break;
		}
		return response($output, Response::HTTP_OK);	
	}
	
	
	/**
     * Save new Medical Term
     */
    public function savemedicalindexing(CreateMedicalRequest $request){
			if(!empty($request->sublink)){
				$sublink = implode(',',@$request->sublink);
			} else {
				$sublink = 'Null';
			}
			
			if($request->medicaltermindexing == '1' && $request->txtdeviceterm !=''){
				$data =[
						'jobid' 		=> $request->jobid, 
						'orderid' 		=> $request->orderid,
						'pui' 			=> $request->pui,
						'user_id' 		=> \Auth::id(),
						'type' 			=> 'major',
						'termtype' 		=> $request->txtdevicetermtype,		
						'deviceterm' 	=> $request->txtdeviceterm,
						'devicelink'	=> $request->indexer_devicelink,
						'sublink'		=> $sublink,
						'status' 		=> '1', 		
						'created_at' 	=> date('Y-m-d H:i:s'),
					   ];   
				$InsertedID = DB::table('medicaldevice')->insert($data);
				$last_id 	= DB::getPDO()->lastInsertId();	
			} elseif($request->medicaltermindexing == '0' && $request->txtdeviceterm !=''){
				$data =[
						'jobid' 		=> $request->jobid, 
						'orderid' 		=> $request->orderid,
						'pui' 			=> $request->pui,
						'user_id' 		=> \Auth::id(),
						'type' 			=> 'minor',
						'termtype' 		=> $request->txtdevicetermtype,		
						'deviceterm' 	=> $request->txtdeviceterm,
						'devicelink'	=> $request->indexer_devicelink,
						'sublink'		=> $sublink,
						'status' 		=> '1', 		
						'created_at' 	=> date('Y-m-d H:i:s'),
					   ];   
				$InsertedID = DB::table('medicaldevice')->insert($data);
				$last_id 	= DB::getPDO()->lastInsertId();	
			}
			
			
			
			//Last Inserted Data 			
			$medicaltermdata 		= DB::table('medicaldevice')->where('id', $last_id)->get()->toArray();
			
			
			
			
			
			//Total count of data
			
			$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid,'pui' => $request->pui];
			
			
			$medicaltermtypecount 	= DB::table('medicaldevice')->select('type', DB::raw('count(*) as total'))->where($matchThese)->groupBy('type')->pluck('total','type')->all();
			
			$sublinkcount 			= DB::table('medicaldevice')->select(DB::raw("(CHAR_LENGTH(sublink) - CHAR_LENGTH(REPLACE(sublink, ',', '')) + 1) as TotalValue"))->where($matchThese)->where('sublink', '<>', 'Null')->get()->toArray();
			$totalsublinkcnt = 0;
			foreach($sublinkcount as $cntval){
			   $totalsublinkcnt = $totalsublinkcnt + $cntval->TotalValue;
			}
	
			$medicaldata = array();
			foreach($medicaltermdata as $termgroup){
			   $medicaldata[$termgroup->type][] = $termgroup;
			}
			
			
			$data['medicaltermdata']   		= $medicaltermdata;
			$data['type']   				= $request->medicaltermindexing;	
			
			
				
			$majorcount 					= @$medicaltermtypecount['major'];	
			$minorcount 					= @$medicaltermtypecount['minor'];
			$totalmedcountterm				= @$medicaltermtypecount['major'] + @$medicaltermtypecount['minor'];
			
			
			if ($this->request->has('json')) {
				$htmlmedicalterm	= view('indexings::indexmedial.newMedicaldeviceHtml', compact('data'))->render();
				return response()->json(['status' =>'success', 'type' => $request->medicaltermindexing,  'totalmedcountterm' => $totalmedcountterm, 'sublinkcount' => $totalsublinkcnt, 'minorcount' => $minorcount,'majorcount' => $majorcount, 'htmlmedicalterm' =>$htmlmedicalterm,  'message' => langapp('saved_successfully')], Response::HTTP_OK);
			} 
			
			
			return ajaxResponse(
            [
                'message'  => langapp('saved_successfully')
            ],
            true,
            Response::HTTP_CREATED
        );
	
	}
	
	
	 /**
     * Delete a indexing
     */
    public function deletemedicaldevice($id = null,$jobid = null,$orderid = null)
    {
		DB::table('medicaldevice')->where('id', $id)->delete();
		
		//Total count of data
			
		$matchThese 			= ['user_id' => \Auth::id(), 'jobid' => $jobid, 'orderid' => $orderid];
		$medicaltermtypecount 	= DB::table('medicaldevice')->select('type', DB::raw('count(*) as total'))->where($matchThese)->groupBy('type')->pluck('total','type')->all();
		
		
		$sublinkcount 			= DB::table('medicaldevice')->select(DB::raw("(CHAR_LENGTH(sublink) - CHAR_LENGTH(REPLACE(sublink, ',', '')) + 1) as TotalValue"))->where($matchThese)->where('sublink', '<>', 'Null')->get()->toArray();
			$totalsublinkcnt = 0;
			foreach($sublinkcount as $cntval){
			   $totalsublinkcnt = $totalsublinkcnt + $cntval->TotalValue;
			}
	
			$medicaldata = array();
			foreach($medicaltermdata as $termgroup){
			   $medicaldata[$termgroup->type][] = $termgroup;
			}
			
			
			$data['medicaltermdata']   		= $medicaltermdata;
			$data['type']   				= $request->medicaltermindexing;	
			
			
				
			$majorcount 					= @$medicaltermtypecount['major'];	
			$minorcount 					= @$medicaltermtypecount['minor'];
			$totalmedcountterm				= @$medicaltermtypecount['major'] + @$medicaltermtypecount['minor'];
			
			
			
	
        return ajaxResponse(
            [
                'message'  			=> langapp('deleted_successfully'),
				'majorcount' 		=> $majorcount,
				'minorcount'  		=> $minorcount,
				'totalmedcountterm' => $totalmedcountterm,
				'totaldiseasecount' => $totaldiseasescnt,
                'redirect' 			=> route('indexings.index'),
            ],
            true,
            Response::HTTP_OK
        );
    }
	
	public function esvsentences(CreateMedicalRequest $request){
			
			if($request->selectterm !='null'){
				$selectedterms =  base64_decode($request->selectterm);
				$selectedterms =  str_replace(array('[', ']'), array('', ''), $selectedterms);
				$termArys 	   =  explode('",',$selectedterms);
				
				$output = '<div id="preloader"><i class="fas fa-spin fa-spinner"></i> Loading...</div><div class="list-group">';
				
				$output .= '<a href="#" class="list-group-item list-group-item-action flex-column align-items-start active"><div class="d-flex w-100 justify-content-between"><h5 class="mb-1"><strong>Term :</strong> '.$request->term.'</span></h5></div><p class="mb-1"><span><strong>TermType :</strong> '.$request->termType.'</span></p><p class="mb-1"><span><strong>Score :</strong> '.$request->score.'</span></p><small><span><strong>Sentence(s) Count :</strong> '.count($termArys).'</span></small></a>';
				
					
				foreach($termArys as $termAry){
					$output .= '<a href="#" class="list-group-item">'.str_replace('"','',$termAry).'</a>';
				
				}
				$output .= '</div>';
			} else {
			$output = '<div id="preloader"><i class="fas fa-spin fa-spinner"></i> Loading...</div>';
			$output .= '<a href="#" class="list-group-item list-group-item-action flex-column align-items-start active"><div class="d-flex w-100 justify-content-between"><h5 class="mb-1"><strong>Term :</strong> '.$request->term.'</span></h5></div><p class="mb-1"><span><strong>TermType :</strong> '.$request->termType.'</span></p><p class="mb-1"><span><strong>Score :</strong> '.$request->score.'</span></p></a>';
				
			}
			
		return ajaxResponse(
            [
                'message'  			=> $output,
            ],
            true,
            Response::HTTP_OK
        );
	
	}
	
	public function saveesvdata(CreateMedicalRequest $request){
			 if($request->termtype =='DRG'){
				$WherematchThese 	= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid, 'pui' => $request->pui];
				$findesvdata 		= DB::table('index_drug')->select('drugterm')->where('drugterm',$request->term)->where($WherematchThese)->get()->toArray();
					if(!empty($findesvdata)){
						return ajaxResponse(
								[
									'message' 	 => 'Already exists ( '.$request->type.' )'.$request->termtype.''  ,
								],
							false,
							Response::HTTP_OK
							);
					} else {
						$data =[
							'jobid' 		=> $request->jobid, 
							'orderid' 		=> $request->orderid,
							'pui' 			=> $request->pui,
							'user_id' 		=> \Auth::id(),
							'type' 			=> $request->type,
							'termtype' 		=> $request->termtype,		
							'drugterm' 		=> $request->term,
							'term_added' 	=> $request->term_added,
							'status' 		=> '1', 		
							'created_at' 	=> date('Y-m-d H:i:s'),
						   ];   
						$InsertedID = DB::table('index_drug')->insert($data);
						$last_id 	= DB::getPDO()->lastInsertId();	
						
						return ajaxResponse(
							[
								'message'  => langapp('saved_successfully')
							],
							true,
							Response::HTTP_CREATED
						);	
					}
			} else if($request->termtype =='Checktag'){ 
					$WherematchThese 	= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid, 'pui' => $request->pui];
					$findesvdata 		= DB::table('index_medical_checktag')->select('checktag')->where('checktag',$request->term)->where($WherematchThese)->get()->toArray();
						if(!empty($findesvdata)){
							return ajaxResponse(
									[
										'message' 	 => 'Already exists ( '.$request->type.' )'.$request->termtype.''  ,
									],
								false,
								Response::HTTP_OK
								);
						} else {
							$data =[
								'jobid' 		=> $request->jobid, 
								'orderid' 		=> $request->orderid,
								'pui' 			=> $request->pui,
								'user_id' 		=> \Auth::id(),
								'checktag' 		=> $request->term, 
								'term_added' 	=> $request->term_added,							
								'status' 		=> '1',
								'created_at' 	=> date('Y-m-d H:i:s'),
							   ];   
							$checktags_last_id 	=	DB::table('index_medical_checktag')->insert($data);
							$last_id 			= 	DB::getPDO()->lastInsertId();	

							
							return ajaxResponse(
								[
									'message'  => langapp('saved_successfully')
								],
								true,
								Response::HTTP_CREATED
							);	
						}
			} else if($request->termtype =='MED' || $request->termtype =='DIS'){
				$WherematchThese 	= ['user_id' => \Auth::id(), 'jobid' => $request->jobid, 'orderid' => $request->orderid, 'pui' => $request->pui];
				$findesvdata 		= DB::table('index_medical_term')->select('medicalterm')->where('medicalterm',$request->term)->where($WherematchThese)->get()->toArray();
					if(!empty($findesvdata)){
						return ajaxResponse(
								[
									'message' 	 => 'Already exists ( '.$request->type.' )'.$request->termtype.''  ,
								],
							false,
							Response::HTTP_OK
							);
					} else {
						$data =[
							'jobid' 		=> $request->jobid, 
							'orderid' 		=> $request->orderid,
							'pui' 			=> $request->pui,
							'user_id' 		=> \Auth::id(),
							'type' 			=> $request->type,
							'termtype' 		=> $request->termtype,		
							'medicalterm' 	=> $request->term,
							'term_added' 	=> $request->term_added,
							'status' 		=> '1', 		
							'created_at' 	=> date('Y-m-d H:i:s'),
						   ];   
						$InsertedID = DB::table('index_medical_term')->insert($data);
						$last_id 	= DB::getPDO()->lastInsertId();	
						
					return ajaxResponse(
						[
							'message'  => langapp('saved_successfully')
						],
						true,
						Response::HTTP_CREATED
					);	
				}
			}
		}
}
