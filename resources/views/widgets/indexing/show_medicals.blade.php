@php
// Field 3 - Saved Data
		$matchThese 			= ['user_id' => $user_id, 'jobid' => $jobid, 'orderid' => $orderid];
		$checktagdata 			= DB::table('index_medical_checktag')->where($matchThese)->get()->toArray();
		$medicaltermdata 		= DB::table('index_medical_term')->where($matchThese)->get()->toArray();
		$medicaltermtypecount 	= DB::table('index_medical_term')->select('type', DB::raw('count(*) as total'))->where($matchThese)->groupBy('type')->pluck('total','type')->all();
		$diseasescount 			= DB::table('index_medical_term')->select(DB::raw("(CHAR_LENGTH(diseaseslink) - CHAR_LENGTH(REPLACE(diseaseslink, ',', '')) + 1) as TotalValue"))->where($matchThese)->where('diseaseslink', '<>', 'Null')->get()->toArray();
		
		$totaldiseasescnt = 0;
		foreach($diseasescount as $cntval){
		   $totaldiseasescnt = $totaldiseasescnt + $cntval->TotalValue;
		}

		$medicaldata = array();
		foreach($medicaltermdata as $termgroup){
		   $medicaldata[$termgroup->type][] = $termgroup;
		}
		$data['checktagdata']   		= $checktagdata;
		$data['medicaltermtypecount']   = $medicaltermtypecount;
		$data['medicaldata']   			= $medicaldata;
@endphp
<div class="col-lg-12">
  <div class="panel-group">
     <div class="panel panel-primary">
	 <div class="panel-heading">@langapp('medicalinfo')</div>
       <div class="panel-body">
	   <div class="slim-scroll">
         <div class="form-group">
          <div class="col-md-6">
		  
        <header class="btn btn-primary btn-sm" > @icon('solid/exclamation-circle') Medical Term (Major & Minor)</header>
      
		   <span class="btn btn-success btn-xs pull-right">Total: <span id="medtotalajax">{{@$medicaltermtypecount['minor'] + @$medicaltermtypecount['major'] + count(@$checktagdata)}} </span></span><span class="btn btn-dracula btn-xs pull-right">Checktags: <span id="medchecktagtotalajax">{{count(@$checktagdata)}}</span> </span><span class="btn btn-info btn-xs pull-right">Minor: <span id="medminortotalajax">{{@$medicaltermtypecount['minor']}}</span> </span><span class="btn btn-warning btn-xs pull-right">Major: <span id="medmajortotalajax">{{@$medicaltermtypecount['major']}}</span> </span>
             
			 @if(!empty($medicaldata))
              @foreach ($medicaldata as $key =>$medicalterms )
			  <ol class="dd-list" id="{{$key}}-mediallistdata">
              <li class="btn-warning" style="padding-left:20px;"><label>{{$key}}</label></li>
			  <li id="{{$key}}-listdata"></li>
              @foreach ($medicalterms as $termsdata )
              <li class="dd-item dd3-item active" data-id="{{ $termsdata->id }}" id="termsdata-{{ $termsdata->id }}" > <span class="pull-right m-xs"> <a href="#" class=""> @icon('solid/pencil-alt', 'icon-muted fa-fw m-r-xs') </a> @if ($termsdata->user_id === Auth::id()) <a href="#" class="deletemedicalterm" data-section-id="{{$termsdata->id}}" title="@langapp('delete')"> @icon('solid/times', 'icon-muted fa-fw m-r-xs') </a> @endif </span>
                 <div class="dd3-content">
                  <label><span class="label-text"> <span class="{!! $termsdata->status ? 'text-info' : 'text-danger' !!}" id="section-id-{{ $termsdata->id }}"> {{ $termsdata->medicalterm }} </span></span></label><span class="btn btn-info btn-xs pull-right">{{ $termsdata->termtype }}</span>
                </div></li>
              @endforeach  
			  </ol>
              @endforeach
			 @else
			 <ol class="dd-list" id="major-mediallistdata"><li class="btn-warning" style="padding-left:20px;"><label>Major</label></li><li id="major-listdata"></li></ol>
			 <ol class="dd-list" id="minor-mediallistdata"><li class="btn-warning" style="padding-left:20px;"><label>Minor</label></li><li id="minor-listdata"></li></ol>
			 @endif
             
		   
		   
              <ol class="dd-list" id="checktag-mediallistdata">
              <li class="btn-warning" style="padding-left:20px;"><label>Minor Term (_ib) CheckTags</label></li>
			  <li id="checktags-listdata"></li>
              @foreach ($checktagdata as $checktag )
              <li class="dd-item dd3-item active" data-id="{{ $checktag->id }}" id="checktag-{{ $checktag->id }}" > <span class="pull-right m-xs"> <a href="#" class=""> @icon('solid/pencil-alt', 'icon-muted fa-fw m-r-xs') </a> @if ($checktag->user_id === Auth::id()) <a href="#" class="deletechecktag" data-section-id="{{$checktag->id}}" title="@langapp('delete')"> @icon('solid/times', 'icon-muted fa-fw m-r-xs') </a> @endif </span>
                 <div class="dd3-content">
                  <label><span class="label-text"> <span class="{!! $checktag->status ? 'text-info' : 'text-danger' !!}" id="checktags-id-{{ $checktag->id }}"> {{ $checktag->checktag }} </span></span></label>
                </div></li>
              @endforeach  
             
            </ol>
			
			
         </div>
          <div class="col-md-5">
		   <header class="btn btn-primary btn-sm" > @icon('solid/exclamation-circle') Diseases links </header>
      
		    <span class="btn btn-primary btn-xs pull-right">Count: <span id="meddiseasestotalajax">{{$totaldiseasescnt}}</span></span>
             <ol class="dd-list" id="diseases-listdata">
              @foreach ($medicaltermdata as $diseaseslink )
			  @if($diseaseslink->diseaseslink != 'Null')
			  	
			  	@if(strpos($diseaseslink->diseaseslink, ',') !== false)
					 @foreach(explode(',', $diseaseslink->diseaseslink) as $selected)
					 	<li class="dd-item dd3-item active" data-id="{{ $diseaseslink->id }}" id="termsdiseasesdata-{{ $diseaseslink->id }}" > <span class="pull-right m-xs"> <a href="#" class=""> @icon('solid/pencil-alt', 'icon-muted fa-fw m-r-xs') </a> @if ($diseaseslink->user_id === Auth::id()) <a href="#" class="deletemedicalterm" data-section-id="{{$diseaseslink->id}}" title="@langapp('delete')"> @icon('solid/times', 'icon-muted fa-fw m-r-xs') </a> @endif </span>
					  <div class="dd3-content">
					  <label><span class="label-text"> <span class="{!! $diseaseslink->status ? 'text-info' : 'text-danger' !!}" id="section-id-{{ $diseaseslink->id }}"> {{ $selected }} </span></span></label>
					</div>
				  </li>
					 @endforeach
				@else
					<li class="dd-item dd3-item active" data-id="{{ $diseaseslink->id }}" id="termsdiseasesdata-{{ $diseaseslink->id }}" > <span class="pull-right m-xs"> <a href="#" class=""> @icon('solid/pencil-alt', 'icon-muted fa-fw m-r-xs') </a> @if ($diseaseslink->user_id === Auth::id()) <a href="#" class="delete{{$key}}" data-section-id="{{$diseaseslink->id}}" title="@langapp('delete')"> @icon('solid/times', 'icon-muted fa-fw m-r-xs') </a> @endif </span>
					  <div class="dd3-content">
					  <label><span class="label-text"> <span class="{!! $diseaseslink->status ? 'text-info' : 'text-danger' !!}" id="section-id-{{ $diseaseslink->id }}"> {{ $diseaseslink->diseaseslink }} </span></span></label>
					</div>
				  </li>
				@endif
			  
			  @endif
              @endforeach
            </ol>
                     </div>
        </div>
		
		</div>
                 </div>
    </div>
             </div>
</div>
