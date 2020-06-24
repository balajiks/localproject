@foreach ($data['devicetradename'] as $termsdata )
<li class="dd-item dd3-item active" data-id="{{ $termsdata->id }}" id="termsdata-{{ $termsdata->id }}" > <span class="pull-right m-xs"> @if ($termsdata->user_id === Auth::id()) <a href="#" class="deletetradeterm" data-section-id="{{$termsdata->id}}" title="@langapp('delete')"> @icon('solid/times', 'icon-muted fa-fw m-r-xs') </a> @endif </span>
	  <div class="dd3-content" onclick="ajaxtradename({{ $termsdata->id }})" style="cursor:pointer">
    <label style="cursor:pointer"><span class="label-text text-info"><strong>Field Code : </strong>&nbsp;&nbsp; <span class="label label-info {!! $termsdata->status ? 'text-info' : 'text-danger' !!}" id="section-id-{{ $termsdata->id }}">_{{ $termsdata->type }} </span></label>
    <label style="cursor:pointer"><span class="label-text text-info"><strong>Drug Manufacture : </strong>&nbsp;&nbsp; <span class="label label-info {!! $termsdata->status ? 'text-info' : 'text-danger' !!}" >{{ $termsdata->manufacturename }} </span></label>
    <label style="cursor:pointer"><span class="label-text text-info"><strong>Country : </strong>&nbsp;&nbsp; <span class="label label-info {!! $termsdata->status ? 'text-info' : 'text-danger' !!}" >{{ $termsdata->countrycode }} </span></label>
  </div>
</li>
@endforeach 
