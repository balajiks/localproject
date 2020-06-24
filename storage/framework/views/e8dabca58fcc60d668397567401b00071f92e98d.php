<?php $__env->startSection('content'); ?>
<aside class="b-l bg" id="">
  <div class="panel-group m-b" id="accordion2">
    <ul class="list no-style" id="responses-list">
      <li class="panel panel-default" id="tokenize">
        <div class="panel-heading"><?php echo e(svg_image('solid/cogs')); ?> <?php echo trans('app.'.'sectiondetails'); ?> 
		<a href="<?php echo e(route('indexings.showmeta', ['id' => $jobdata->id])); ?>" class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?> pull-right" data-toggle="ajaxModal"> <?php echo e(svg_image('solid/paperclip')); ?> <?php echo trans('app.'.'openindexmanual'); ?> </a> 
		<a href="<?php echo e(route('indexings.showsource', ['id' => $jobdata->id])); ?>" target="_blank" class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?> pull-right"> <?php echo e(svg_image('solid/file-alt')); ?> <?php echo trans('app.'.'opensource'); ?> </a> 
		
		<a href="<?php echo e(route('indexings.showmeta', ['id' => $jobdata->id])); ?>" class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?> pull-right" data-toggle="ajaxModal"> <?php echo e(svg_image('solid/tachometer-alt')); ?> <?php echo trans('app.'.'metadata'); ?> </a> </div>
      </li>
    </ul>
  </div> 
  <ul class="dashmenu text-uc text-muted no-border no-radius nav pro-nav-tabs nav-tabs-dashed">
    <?php /*?> <a href="{{  route('indexings.create')  }}"  data-rel="tooltip" title="@langapp('create')" class="btn btn-sm btn-{{ get_option('theme_color') }}">
                @icon('solid/plus') @langapp('create')
            </a><?php */?>
    <li class="<?php echo e(($tab == 'section') ? 'active' : ''); ?>"> <a href="<?php echo e(route('indexings.addindexing', ['id' => $jobdata->id, 'tabmenu' => 'section', 'tab' => 'section'])); ?>"><?php echo e(svg_image('solid/home')); ?> <?php echo trans('app.'.'section'); ?> </a> </li>
    <li class="<?php echo e(($tab == 'medical') ? 'active' : ''); ?>"> <a href="<?php echo e(route('indexings.addindexing', ['id' => $jobdata->id, 'tab' => 'medical'])); ?>"><?php echo e(svg_image('solid/medkit')); ?> <?php echo trans('app.'.'medical'); ?> </a> </li>
    <li class="<?php echo e(($tab == 'drug') ? 'active' : ''); ?>"> <a href="<?php echo e(route('indexings.addindexing', ['id' => $jobdata->id, 'tab' => 'drug'])); ?>"><?php echo e(svg_image('solid/pills')); ?> <?php echo trans('app.'.'drug'); ?> </a> </li>
	<li class="<?php echo e(($tab == 'drugtradename') ? 'active' : ''); ?>"> <a href="<?php echo e(route('indexings.addindexing', ['id' => $jobdata->id, 'tab' => 'drugtradename'])); ?>"><?php echo e(svg_image('solid/pills')); ?> <?php echo trans('app.'.'drugtradename'); ?> </a> </li>
	
	 <li class="<?php echo e(($tab == 'mdt') ? 'active' : ''); ?>"> <a href="<?php echo e(route('indexings.addindexing', ['id' => $jobdata->id, 'tab' => 'mdt'])); ?>"><?php echo e(svg_image('solid/dna')); ?> <?php echo trans('app.'.'mdt'); ?> </a> </li>
	
	
    <li class="<?php echo e(($tab == 'ctn') ? 'active' : ''); ?>"> <a href="<?php echo e(route('indexings.addindexing', ['id' => $jobdata->id, 'tab' => 'ctn'])); ?>"><?php echo e(svg_image('solid/dna')); ?> <?php echo trans('app.'.'ctn'); ?> </a> </li>
    <li class="<?php echo e(($tab == 'msn') ? 'active' : ''); ?>"> <a href="<?php echo e(route('indexings.addindexing', ['id' => $jobdata->id, 'tab' => 'msn'])); ?>"><?php echo e(svg_image('solid/meh')); ?> <?php echo trans('app.'.'msn'); ?> </a> </li>
    <li class="<?php echo e(($tab == 'mdi') ? 'active' : ''); ?>"> <a href="<?php echo e(route('indexings.addindexing', ['id' => $jobdata->id, 'tab' => 'mdi'])); ?>"><?php echo e(svg_image('solid/heart')); ?> <?php echo trans('app.'.'mdi'); ?> </a> </li>
  </ul>
  
  <section class="padder"> <?php echo $__env->make('indexings::tab._'.$tab, \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?> </section>
  <?php /*?><section class="scrollable">
    <div class="slim-scroll" data-color="#333333" data-disable-fade-out="true" data-distance="0" data-height="auto" data-size="3px">
      
    </div>
  </section><?php */?>
</aside>
<aside class="aside-lg b-l">
  <section class="vbox">
    <section class="scrollable" id="feeds">
      <div class="slim-scroll padder" data-color="#333333" data-disable-fade-out="true" data-distance="0" data-height="auto" data-size="3px">
	 <?php echo $__env->make('indexings::_sidebar.'.$tabmenu, \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	  
	   </div>
    </section>
  </section>
</aside>
<?php $__env->startPush('pagescript'); ?>
<?php echo $__env->make('indexings::_ajax.sectionjs', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

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
	toastr.success(response.data.message, '<?php echo trans('app.'.'success'); ?> ');
	})
	.catch(function (error) {
	var errors = error.response.data.errors;
	var errorsHtml= '';
	$.each( errors, function( key, value ) {
	errorsHtml += '<li>' + value[0] + '</li>';
	});
	toastr.error( errorsHtml , '<?php echo trans('app.'.'response_status'); ?> ');
	});
	children.prepend(element);
	$('#processing-modal').modal('toggle');
	}, 1000);
	}
	event.preventDefault();
	});
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.indexerapp', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>