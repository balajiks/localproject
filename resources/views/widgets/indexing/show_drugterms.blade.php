@php
// Field 4 - Saved Data
		$matchThese 			= ['user_id' => $user_id, 'jobid' => $jobid, 'orderid' => $orderid];
		$drugtermdata 			= DB::table('index_drug')->where($matchThese)->get()->toArray();
		$drugtermtypecount 		= DB::table('index_drug')->select('type', DB::raw('count(*) as total'))->where($matchThese)->groupBy('type')->pluck('total','type')->all();
		

		$drugdata = array();
		foreach($drugtermdata as $termgroup){
		   $drugdata[$termgroup->type][] = $termgroup;
		}
		$data['drugtermtypecount']  = $drugtermtypecount;
		$data['drugdata']   		= $drugdata;
@endphp
           <div class="col-lg-12">
  <div class="panel-group">
               <div class="panel panel-primary">
      <div class="panel-heading"> @langapp('drugsublink')</div>
      <div class="panel-body">
                   <div class="slim-scroll">
          <div class="form-group">
                       <div class="col-md-12">
					   <div class="row">
              <span class="btn btn-success btn-xs">Total: <span id="drugtotalajax">{{@$drugtermtypecount['minor'] + @$drugtermtypecount['major']}} </span></span><span class="btn btn-info btn-xs">Minor: <span id="drugminortotalajax">{{@$drugtermtypecount['minor']}}</span> </span><span class="btn btn-warning btn-xs">Major: <span id="drugmajortotalajax">{{@$drugtermtypecount['major']}}</span> </span>
			  </div><br />
			  <div class="row">
			   @if(!empty($drugdata))
              @foreach ($drugdata as $key =>$drugterms )
              <ol class="dd-list" id="{{$key}}-druglistdata">
                           <li class="btn-warning" style="padding-left:20px;">
                  <label>{{$key}}</label>
                </li>
                          
                           @foreach ($drugterms as $termsdata )
                           <li class="dd-item dd3-item active" data-id="{{ $termsdata->id }}" id="drugtermsdata-{{ $termsdata->id }}" onclick="selecteddrugdata('{{$termsdata->id}}','{{$termsdata->drugterm}}','{{$termsdata->type}}')" > <span class="pull-right m-xs">  @if ($termsdata->user_id === Auth::id()) <a href="#" class="deletedrugterm" data-section-id="{{$termsdata->id}}" title="@langapp('delete')"> @icon('solid/times', 'icon-muted fa-fw m-r-xs') </a> @endif </span>
                  <div class="dd3-content" id="drugtermshighlight-{{ $termsdata->id }}">
                               <label><span class="label-text active"> <span class="{!! $termsdata->status ? 'text-info' : 'text-danger' !!}" id="drug-id-{{ $termsdata->id }}"> {{ $termsdata->drugterm }} </span></span></label>
                               <span class="btn btn-info btn-xs pull-right">{{ $termsdata->termtype }}</span> </div>
                </li>
                           @endforeach
                         </ol>
              @endforeach
              @else
              <ol class="dd-list" id="major-druglistdata">
                           <li class="btn-warning" style="padding-left:20px;">
                  <label>Major</label>
                </li>
                           <li id="major-listdata"></li>
                         </ol>
              <ol class="dd-list" id="minor-druglistdata">
                           <li class="btn-warning" style="padding-left:20px;">
                  <label>Minor</label>
                </li>
                           <li id="minor-listdata"></li>
                         </ol>
              @endif </div></div>
                     </div>
        </div>
                 </div>
    </div>
             </div>
</div>
