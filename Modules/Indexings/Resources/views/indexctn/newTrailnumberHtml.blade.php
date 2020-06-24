@foreach ($data['ctntermdata'] as $termsdata )


<li class="dd-item dd3-item active" data-id="{{ $termsdata->id }}" id="ctn-{{ $termsdata->id }}" >
  
  <span class="pull-right m-xs">
    
    @if ($termsdata->user_id === Auth::id())
    <a href="#" class="deleteCtn" data-ctn-id="{{$termsdata->id}}" title="@langapp('delete')">
      @icon('solid/times', 'icon-muted fa-fw m-r-xs')
    </a>
    @endif
  </span>
  
  <div class="dd3-content">
    <label>
      <span class="label-text">
        <span class="{!! $termsdata->status ? 'text-info' : 'text-danger' !!}" id="ctn-id-{{ $termsdata->id }}">
		  Registry Name : {{ $termsdata->registryname }} <br />
		  Clinical Trail Number  : {{ $termsdata->registryname }}
- <span class="label label-info">
		  {{ !empty($termsdata->created_at) ? dateElapsed($termsdata->created_at) : '' }}</span>
		   
        </span>
      </span>
    </label>
    <p class="m-0-sm"><small class="text-muted small m-0-sm" data-rel="tooltip" title="{{ $termsdata->jobid }}">@icon('solid/calendar-alt') {{ dateTimeFormatted($termsdata->created_at) }}</small></p>
    
  </div>

   
</li>
@endforeach 