@foreach ($data['medicaltermdata'] as $termsdata )
<li class="dd-item dd3-item active" data-id="{{ $termsdata->id }}" id="termsdata-{{ $termsdata->id }}" > <span class="pull-right m-xs"> <a href="#" class=""> @icon('solid/pencil-alt', 'icon-muted fa-fw m-r-xs') </a> @if ($termsdata->user_id === Auth::id()) <a href="#" class="deletemedicalterm" data-section-id="{{$termsdata->id}}" title="@langapp('delete')"> @icon('solid/times', 'icon-muted fa-fw m-r-xs') </a> @endif </span>
  <div class="dd3-content">
    <label><span class="label-text"> <span class="{!! $termsdata->status ? 'text-info' : 'text-danger' !!}" id="section-id-{{ $termsdata->id }}"> {{ $termsdata->medicalterm }}  </span></span></label><span class="btn btn-info btn-xs pull-right">{{ $termsdata->termtype }}</span>
  </div>
</li>
@endforeach 