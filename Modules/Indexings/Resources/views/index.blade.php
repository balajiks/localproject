@extends('layouts.indexerapp')
@section('content')
<aside class="b-l bg" id="">
  <div class="panel-group m-b" id="accordion2">
    <ul class="list no-style" id="responses-list">
      <li class="panel panel-default" id="tokenize">
        <div class="panel-heading">@icon('solid/cogs') @langapp('sectiondetails') 
		<a href="{{  route('indexings.showmeta', ['id' => $jobdata->id])  }}" class="btn btn-sm btn-{{ get_option('theme_color')  }} pull-right" data-toggle="ajaxModal"> @icon('solid/paperclip') @langapp('openindexmanual') </a> 
		<a href="{{  route('indexings.showsource', ['id' => $jobdata->id])  }}" target="_blank" class="btn btn-sm btn-{{ get_option('theme_color')  }} pull-right"> @icon('solid/file-alt') @langapp('opensource') </a> 
		
		<a href="{{  route('indexings.showmeta', ['id' => $jobdata->id])  }}" class="btn btn-sm btn-{{ get_option('theme_color')  }} pull-right" data-toggle="ajaxModal"> @icon('solid/tachometer-alt') @langapp('metadata') </a> </div>
      </li>
    </ul>
  </div> 
  <ul class="dashmenu text-uc text-muted no-border no-radius nav pro-nav-tabs nav-tabs-dashed">
    <?php /*?> <a href="{{  route('indexings.create')  }}"  data-rel="tooltip" title="@langapp('create')" class="btn btn-sm btn-{{ get_option('theme_color') }}">
                @icon('solid/plus') @langapp('create')
            </a><?php */?>
    <li class="{{  ($tab == 'section') ? 'active' : '' }}"> <a href="{{  route('indexings.addindexing', ['id' => $jobdata->id, 'tabmenu' => 'section', 'tab' => 'section'])  }}">@icon('solid/home') @langapp('section') </a> </li>
    <li class="{{  ($tab == 'medical') ? 'active' : '' }}"> <a href="{{  route('indexings.addindexing', ['id' => $jobdata->id, 'tab' => 'medical'])  }}">@icon('solid/medkit') @langapp('medical') </a> </li>
    <li class="{{  ($tab == 'drug') ? 'active' : '' }}"> <a href="{{  route('indexings.addindexing', ['id' => $jobdata->id, 'tab' => 'drug'])  }}">@icon('solid/pills') @langapp('drug') </a> </li>
	<li class="{{  ($tab == 'drugtradename') ? 'active' : '' }}"> <a href="{{  route('indexings.addindexing', ['id' => $jobdata->id, 'tab' => 'drugtradename'])  }}">@icon('solid/pills') @langapp('drugtradename') </a> </li>
	
	 <li class="{{  ($tab == 'mdt') ? 'active' : '' }}"> <a href="{{  route('indexings.addindexing', ['id' => $jobdata->id, 'tab' => 'mdt'])  }}">@icon('solid/dna') @langapp('mdt') </a> </li>
	
	
    <li class="{{  ($tab == 'ctn') ? 'active' : '' }}"> <a href="{{  route('indexings.addindexing', ['id' => $jobdata->id, 'tab' => 'ctn'])  }}">@icon('solid/dna') @langapp('ctn') </a> </li>
    <li class="{{  ($tab == 'msn') ? 'active' : '' }}"> <a href="{{  route('indexings.addindexing', ['id' => $jobdata->id, 'tab' => 'msn'])  }}">@icon('solid/meh') @langapp('msn') </a> </li>
    <li class="{{  ($tab == 'mdi') ? 'active' : '' }}"> <a href="{{  route('indexings.addindexing', ['id' => $jobdata->id, 'tab' => 'mdi'])  }}">@icon('solid/heart') @langapp('mdi') </a> </li>
  </ul>
  
  <section class="padder"> @include('indexings::tab._'.$tab) </section>
  <?php /*?><section class="scrollable">
    <div class="slim-scroll" data-color="#333333" data-disable-fade-out="true" data-distance="0" data-height="auto" data-size="3px">
      
    </div>
  </section><?php */?>
</aside>
<aside class="aside-lg b-l">
  <section class="vbox">
    <section class="scrollable" id="feeds">
      <div class="slim-scroll padder" data-color="#333333" data-disable-fade-out="true" data-distance="0" data-height="auto" data-size="3px">
	 @include('indexings::_sidebar.'.$tabmenu)
	  
	   </div>
    </section>
  </section>
</aside>
@push('pagescript')
@include('indexings::_ajax.sectionjs')

<script type="text/javascript">
$(document).ready(function () {
var kanbanCol = $('.scrumboard');
draggableInit();
});

function draggableInit() {
	var sourceId;
	$('[draggable=true]').bind('dragstart', function (event) {
	sourceId = $(this).parent().attr('id');
	event.originalEvent.dataTransfer.setData("text/plain", event.target.getAttribute('id'));
	});
	$('.scrumboard').bind('dragover', function (event) {
	event.preventDefault();
	});
	$('.scrumboard').bind('drop', function (event) {
	var children = $(this).children();
	var targetId = children.attr('id');
	if (sourceId != targetId) {
	var elementId = event.originalEvent.dataTransfer.getData("text/plain");
	$('#processing-modal').modal('toggle');
	setTimeout(function () {
	var element = document.getElementById(elementId);
	id = element.getAttribute('id');
	axios.post('/api/v1/indexings/'+id+'/movestage', {
	id: id,
	target: targetId
	})
	.then(function (response) {
	toastr.success(response.data.message, '@langapp('success') ');
	})
	.catch(function (error) {
	var errors = error.response.data.errors;
	var errorsHtml= '';
	$.each( errors, function( key, value ) {
	errorsHtml += '<li>' + value[0] + '</li>';
	});
	toastr.error( errorsHtml , '@langapp('response_status') ');
	});
	children.prepend(element);
	$('#processing-modal').modal('toggle');
	}, 1000);
	}
	event.preventDefault();
	});
}
</script>
@endpush
@endsection