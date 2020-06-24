<ol class="dd-list list-group">
@foreach ($indexingsections as $section)
<li class="dd-item dd3-item list-group-item list-group-item-action active" data-id="{{ $section->id }}" id="section-{{ $section->id }}" >
  
  <span class="pull-right m-xs">
    <a href="#" data-toggle="ajaxModal">
      @icon('solid/pencil-alt', 'icon-muted fa-fw m-r-xs')
    </a>
    aSs
    
    @if ($section->user_id === Auth::id())
    <a href="#" class="deleteSection" data-todo-id="{{$section->id}}" title="@langapp('delete')">
      @icon('solid/times', 'icon-muted fa-fw m-r-xs')
    </a>
    @endif
  </span>
  
  <div class="dd3-content">
    <label>
      <span class="label-text">
        <span class="{!! $section->status ? 'text-success' : 'text-danger' !!}" id="section-id-{{ $section->id }}">
          {{ $section->pui }}
		  {{ $section->orderid }}
		  {{ $section->sectionvalue }}
		   <small class="text-muted small m-l-sm" data-rel="tooltip" title="{{ $section->jobid }}">@icon('solid/calendar-alt') {{ dateTimeFormatted($section->created_at) }}</small>
        </span>
      </span>
    </label>
    <p class="m-xs">@parsedown($section->sectionid)</p>
    
  </div>

   
</li>
@endforeach
</ol>



<div class="list-group">
    <a href="#" class="list-group-item list-group-item-action active">
        <i class="fa fa-home"></i> Home
    </a>
    <a href="#" class="list-group-item list-group-item-action">
        <i class="fa fa-camera"></i> Pictures <span class="badge badge-pill badge-primary pull-right">145</span>
    </a>
    <a href="#" class="list-group-item list-group-item-action">
        <i class="fa fa-music"></i> Music <span class="badge badge-pill badge-primary pull-right">50</span>
    </a>
    <a href="#" class="list-group-item list-group-item-action">
        <i class="fa fa-film"></i> Videos <span class="badge badge-pill badge-primary pull-right">8</span>
    </a>
</div>