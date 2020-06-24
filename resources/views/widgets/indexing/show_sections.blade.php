      
    

<ol class="dd-list slim-scroll">
@foreach ($indexingsections as $section)
<li class="dd-item dd3-item active" data-id="{{ $section->id }}" id="section-{{ $section->id }}" >
  
  <span class="pull-right m-xs">
   
    @if ($section->user_id === Auth::id())
    <a href="#" class="deleteSection" data-section-id="{{$section->id}}" title="@langapp('delete')">
      @icon('solid/times', 'icon-muted fa-fw m-r-xs')
    </a>
    @endif
  </span>
  
  <div class="dd3-content">
    <label>
      <span class="label-text">
        <span class="{!! $section->status ? 'text-info' : 'text-danger' !!}" id="section-id-{{ $section->id }}">
		  Section : {{ $section->sectionvalue }} - <span class="label label-info">
		  {{ !empty($section->created_at) ? dateElapsed($section->created_at) : '' }}</span>
		   
        </span>
      </span>
    </label>
    <p class="m-0-sm"><small class="text-muted small m-0-sm" data-rel="tooltip" title="{{ $section->jobid }}">@icon('solid/calendar-alt') {{ dateTimeFormatted($section->created_at) }}</small></p>
    
  </div>

   
</li>
@endforeach
</ol>


</div>
