<ol class="dd-list" id="trailnumberlist">
  @foreach ($indexingctn as $ctn)
  <li class="dd-item dd3-item active" data-id="{{ $ctn->id }}" id="ctn-{{ $ctn->id }}" > <span class="pull-right m-xs"> @if ($ctn->user_id === Auth::id()) <a href="#" class="deleteCtn" data-ctn-id="{{$ctn->id}}" title="@langapp('delete')"> @icon('solid/times', 'icon-muted fa-fw m-r-xs') </a> @endif </span>
    <div class="dd3-content">
      <label> <span class="label-text"> <span class="{!! $ctn->status ? 'text-info' : 'text-danger' !!}" id="ctn-id-{{ $ctn->id }}"> Registry Name : {{ $ctn->registryname }} <br />
      Clinical Trail Number  : {{ $ctn->trailnumber }}
      - <span class="label label-info"> {{ !empty($ctn->created_at) ? dateElapsed($ctn->created_at) : '' }}</span> </span> </span> </label>
      <p class="m-0-sm"><small class="text-muted small m-0-sm" data-rel="tooltip" title="{{ $ctn->jobid }}">@icon('solid/calendar-alt') {{ dateTimeFormatted($ctn->created_at) }}</small></p>
    </div>
  </li>
  @endforeach
</ol>
