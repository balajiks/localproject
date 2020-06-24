@php
// Field 3 - Saved Data
		$matchThese 			= ['user_id' => $user_id, 'jobid' => $jobid, 'orderid' => $orderid];
		$drugtradename 			= DB::table('drugtradename')->where($matchThese)->get()->toArray();
		$drugtradenametypecount = DB::table('drugtradename')->select('type', DB::raw('count(*) as total'))->where($matchThese)->groupBy('type')->pluck('total','type')->all();
		$data['drugtradename']   		= $drugtradename;
		$data['drugtradenametypecount'] = $drugtradenametypecount;
@endphp
        
		
<div class="col-lg-12">
  <div class="panel-group">
    <div class="panel panel-primary">
      <div class="panel-heading">@langapp('indexeddrugtradename')</div>
      <div class="panel-body">
        <div class="slim-scroll">
          <div class="form-group">
            <div class="col-md-6">
              <ol class="drugtradename-list"  style="margin:0px; padding:0px;">
                @foreach ($drugtradename as $termsdata )
                <li class="dd-item dd3-item " data-id="{{ $termsdata->id }}" id="termsdata-{{ $termsdata->id }}" > <span class="pull-right m-xs"> @if ($termsdata->user_id === Auth::id()) <a href="#" class="deletetradeterm" data-termsdata-id="{{$termsdata->id}}" title="@langapp('delete')"> @icon('solid/times', 'icon-muted fa-fw m-r-xs') </a> @endif </span>
                  <div class="dd3-content indexedmaterm"  id="ajaxterm-{{ $termsdata->id }}" onclick="ajaxtradename({{ $termsdata->id }})" style="cursor:pointer">
                    <label style="cursor:pointer"><span class="label-text text-info"><strong>Field Code : </strong>&nbsp;&nbsp; <span class="label label-info {!! $termsdata->status ? 'text-info' : 'text-danger' !!}" id="section-id-{{ $termsdata->id }}">_{{ $termsdata->type }} </span></span></label>
					
					 <label style="cursor:pointer"><span class="label-text text-info"><strong>Drug Manufacture : </strong>&nbsp;&nbsp; <span class="label label-info {!! $termsdata->status ? 'text-info' : 'text-danger' !!}" >{{ $termsdata->manufacturename }} </span></span></label>
					 
					 <label style="cursor:pointer"><span class="label-text text-info"><strong>Country : </strong>&nbsp;&nbsp; <span class="label label-info {!! $termsdata->status ? 'text-info' : 'text-danger' !!}" >{{ $termsdata->countrycode }} </span></span></label>
					
					
                     </div>
                </li>
                @endforeach
              </ol>
            </div>
			
			 <div class="col-md-6" id="ajaxdrugtradename">
			
			 </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
