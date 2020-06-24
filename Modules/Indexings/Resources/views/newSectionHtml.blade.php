
<li class="dd-item dd3-item active" data-id="{{ $secdata[0]->id }}" id="section-{{ $secdata[0]->id }}" >
  
  <span class="pull-right m-xs">
    <a href="#" class="">
      @icon('solid/pencil-alt', 'icon-muted fa-fw m-r-xs')
    </a>
    
    
    @if ($secdata[0]->user_id === Auth::id())
    <a href="#" class="deleteSection" data-section-id="{{$secdata[0]->id}}" title="@langapp('delete')">
      @icon('solid/times', 'icon-muted fa-fw m-r-xs')
    </a>
    @endif
  </span>
  
  <div class="dd3-content">
    <label>
      <span class="label-text">
        <span class="{!! $secdata[0]->status ? 'text-info' : 'text-danger' !!}" id="section-id-{{ $secdata[0]->id }}">
		  Section : {{ $secdata[0]->sectionvalue }} - <span class="label label-info">
		  {{ !empty($secdata[0]->created_at) ? dateElapsed($secdata[0]->created_at) : '' }}</span>
		   
        </span>
      </span>
    </label>
    <p class="m-0-sm"><small class="text-muted small m-0-sm" data-rel="tooltip" title="{{ $secdata[0]->jobid }}">@icon('solid/calendar-alt') {{ dateTimeFormatted($secdata[0]->created_at) }}</small></p>
    
  </div>

   
</li>
