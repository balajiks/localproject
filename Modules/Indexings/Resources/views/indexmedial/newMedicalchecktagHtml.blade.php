@if(!empty($data['checktagdata']))
@foreach ($data['checktagdata'] as $checktags )
@foreach ($checktags as $checktag )
              <li class="dd-item dd3-item active" data-id="{{ $checktag->id }}" id="checktag-{{ $checktag->id }}" > <span class="pull-right m-xs"> <a href="#" class=""> @icon('solid/pencil-alt', 'icon-muted fa-fw m-r-xs') </a> @if ($checktag->user_id === Auth::id()) <a href="#" class="deletechecktag" data-section-id="{{$checktag->id}}" title="@langapp('delete')"> @icon('solid/times', 'icon-muted fa-fw m-r-xs') </a> @endif </span>
                 <div class="dd3-content">
                  <label><span class="label-text"> <span class="{!! $checktag->status ? 'text-info' : 'text-danger' !!}" id="checktags-id-{{ $checktag->id }}"> {{ $checktag->checktag }} </span></span></label>
                </div></li>
			@endforeach 
              @endforeach   
@endif