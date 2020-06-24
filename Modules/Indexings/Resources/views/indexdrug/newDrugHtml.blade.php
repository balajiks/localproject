@foreach ($data['drugtermdata'] as $termsdata )
<li class="dd-item dd3-item active" data-id="{{ $termsdata->id }}" id="drugtermsdata-{{ $termsdata->id }}" onclick="selecteddrugdata('{{$termsdata->id}}','{{$termsdata->drugterm}}','{{$termsdata->type}}')" > <span class="pull-right m-xs">  @if ($termsdata->user_id === Auth::id()) <a href="#" class="deletedrugterm" data-section-id="{{$termsdata->id}}" title="@langapp('delete')"> @icon('solid/times', 'icon-muted fa-fw m-r-xs') </a> @endif </span>
  <div class="dd3-content" id="drugtermshighlight-{{ $termsdata->id }}">
    <label><span class="label-text active"> <span class=" {!! $termsdata->status ? 'text-info' : 'text-danger' !!}" id="section-id-{{ $termsdata->id }}"> {{ $termsdata->drugterm }}  </span></span></label><span class="btn btn-info btn-xs pull-right">{{ $termsdata->termtype }}</span>
  </div>
</li>
@endforeach 