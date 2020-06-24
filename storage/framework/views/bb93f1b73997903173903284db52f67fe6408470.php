<div class="row">
  <div class="col-lg-12"> 
  
  <?php echo Form::open(['route' => 'indexings.api.savesection', 'id' => 'createSection', 'class' => 'bs-example form-horizontal m-b-none']); ?>

  <?php /*?>{!! Form::open(['route' =>'indexings.api.savesection', 'data-toggle' => 'validator', 'class' => 'bs-example form-horizontal ajaxifyForm']) !!}<?php */?>
    <section class="panel panel-default">
      <header class="panel-heading">
        <header class="btn btn-primary btn-sm" style="margin-left:20px;"> <?php echo e(svg_image('solid/exclamation-circle')); ?> <?php echo trans('app.'.'sectionalertinfo'); ?>  &nbsp;&nbsp;[ PUBL: <span class="btn btn-warning btn-xs"><?php echo e($jobdata->publ); ?> </span> | Abstract: <span class="btn btn-warning btn-xs"><?php echo e($jobdata->abs); ?> </span> ]</header>
		
		<div class="btn btn-sm btn-warning  }} pull-right" >Section Count :  <span id="indexersectioncount"><?php echo e($sectioncount); ?></span></div>
      </header>
      <?php 
      $translations = Modules\Settings\Entities\Options::translations();
      $default_country = get_option('company_country');
      $disable = '';
      ?>
      
      <div class="panel-body">
        
		<input type="hidden" class="form-control" placeholder="jobid" name="jobid" value="<?php echo e($jobdata->id); ?>" />
		<input type="hidden" class="form-control" placeholder="pui" name="pui" value="<?php echo e($jobdata->pui); ?>" />
		<input type="hidden" class="form-control" placeholder="orderid" name="orderid" value="<?php echo e($jobdata->orderid); ?>" />
		<input type="hidden" class="form-control" placeholder="jobid" id="sectioncount" name="sectioncount" value="<?php echo e($sectioncount); ?>" />
		<input type="hidden" name="json" value="false"/>
		
        <div class="col-md-12"> <?php if(count($translations) > 0): ?>
          <div class="tab-content tab-content-fix">
            <div class="tab-pane fade in active" id="tab-english"> <?php endif; ?>
			
			
			<div id="frmindexsectionshow" class="">
			 <?php if($jobdata->publ == 'Yes' && $jobdata->abs == 'Yes'): ?>
			 <input type="hidden" class="form-control" placeholder="jobdatayestoall" name="jobdatayestoall" value="1" />
				  <div class="form-group">
					<div class="col-lg-3">
					  <label><?php echo trans('app.'.'section'); ?></label><span class="text-dark badge badge-info pull-right"><?php echo e(svg_image('solid/exclamation-circle')); ?> <?php echo trans('app.'.'totalsectionallowed'); ?></span>
					  <select class="select2-option form-control indexer_section" id="indexer_section" name="indexer_section" onChange="getClassification(this.value);" required>
						 <option selected="true" disabled="disabled">Select Section</option>
						<?php $__currentLoopData = embaseindex_section(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sectionval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option value="<?php echo e($sectionval['section_id']); ?>"><?php echo e($sectionval['sectionvalue']); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					  </select>
					</div>
					<div class="col-lg-3">
					  <label><?php echo trans('app.'.'publication'); ?> <span class="text-danger">*</span> </label>
					  <select class="select2-option form-control Selpublication" name="indexer_publication"  required>
						<option selected="true" disabled="disabled">Select Publication Choice</option>
						<option value="?">?</option>
						<option value="+">+</option>
					  </select>
					</div>
					<div class="col-lg-3">
					  <label><?php echo trans('app.'.'classification'); ?> <span class="text-danger">*</span> </label>
					  <select class="select2-option form-control classification" name="indexer_classification" required>
						<option selected="true" disabled="disabled">Select Classification</option>
					  </select>
					</div>
					<div class="col-lg-3">
					  <div class="form-group"><br />
						  <?php echo renderAjaxButtonSquare('save'); ?>

						<button type="submit" class="btn btn-danger">Clear</button>
					  </div>
					</div>
				  </div>
			  <?php else: ?> 
			    <input type="hidden" class="form-control" placeholder="jobdatayestoall" name="jobdatayestoall" value="0" />
			  	<div class="form-group">
					<div class="col-lg-5">
					  <label><?php echo trans('app.'.'section'); ?><span class="text-danger">*</span> </label><span class="text-dark badge badge-info pull-right"><?php echo e(svg_image('solid/exclamation-circle')); ?> <?php echo trans('app.'.'totalsectionallowed'); ?></span>
					  <select class="select2-option form-control indexer_section" id="indexer_section" name="indexer_section[]" multiple="multiple" data-maximum-selection-length="3">					  
						<?php $__currentLoopData = embaseindex_section(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sectionval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option value="<?php echo e($sectionval['section_id']); ?>"><?php echo e($sectionval['sectionvalue']); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
					  </select>
					  
					</div>
					<div class="col-lg-3 disabledbutton" >
					  <label><?php echo trans('app.'.'publication'); ?></label>
					  <select class="form-control" disabled>
						<option selected="true" disabled="disabled">Select Publication Choice</option>
					  </select>
					</div>
					<div class="col-lg-2 disabledbutton">
					  <label><?php echo trans('app.'.'classification'); ?></label>
					  <select class="form-control" disabled>
						<option selected="true" disabled="disabled">Classification</option>
					  </select>
					</div>
					<div class="col-lg-2">
					  <div class="form-group"><br />
						 <?php echo renderAjaxButtonSquare('add'); ?>

						<button type="submit" class="btn btn-danger">Clear</button>
					  </div>
					</div>
				  </div>
			  	 <?php endif; ?>
				 
				 
				 
				 <div class="sortable">
            		<div class="section-list" id="nestable">
					
			<?php if($jobdata->publ == 'Yes' && $jobdata->abs == 'Yes'): ?>		
              
			 <?php echo app('arrilot.widget')->run('Indexings\ShowSectionswithclassification', ['indexingsections' => DB::table('datasections')
			->join('embaseindex_sections', 'datasections.sectionid', '=', 'embaseindex_sections.id')
			->join('embaseindex_classifications', 'datasections.classid', '=', 'embaseindex_classifications.section_id')// you may add more joins
			->select('datasections.*', 'embaseindex_sections.sectionvalue', 'embaseindex_classifications.classvalue')
			->where('datasections.user_id', \Auth::id())->where('datasections.jobid', $jobdata->id)->where('datasections.orderid', $jobdata->orderid)->where('datasections.pui', $jobdata->pui)
			->get()]); ?>
			
			<?php else: ?> 
			
			 <?php echo app('arrilot.widget')->run('Indexings\ShowSections', ['indexingsections' => DB::table('datasections')
			->join('embaseindex_sections', 'datasections.sectionid', '=', 'embaseindex_sections.id')
			->select('datasections.*', 'embaseindex_sections.sectionvalue')
			->where('datasections.user_id', \Auth::id())->where('datasections.jobid', $jobdata->id)->where('datasections.orderid', $jobdata->orderid)->where('datasections.pui', $jobdata->pui)
			->get()]); ?>
			<?php endif; ?>
            			
            		</div>

            	</div>
			  
			 
			  </div>
              <?php if(count($translations) > 0): ?> </div>
          </div>
          <?php endif; ?> </div>
      </div>
      <!--<div class="panel-footer">
            <?php echo renderAjaxButton('save'); ?>

        </div>-->
    </section>
    <?php echo Form::close(); ?> </div>
</div>
<?php $__env->startPush('pagestyle'); ?>
<link rel=stylesheet href="<?php echo e(getAsset('plugins/nestable/nestable.css')); ?>">
<?php echo $__env->make('stacks.css.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('pagescript'); ?>

<?php echo $__env->make('stacks.js.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>




<?php if($sectioncount == langapp('sectioncnt')): ?>
<script>$('#frmindexsectionshow').find('input, textarea, button, select').attr('disabled','disabled');</script>
<?php else: ?>
<script>$('#frmindexsectionshow').find('input, textarea, button, select').removeAttr('disabled','disabled');</script>			 
<?php endif; ?>



<script>





$(document).ready(function () {
    $("#indexer_section").select2({
        maximumSelectionLength: <?php echo trans('app.'.'sectioncnt'); ?> - $('#sectioncount').val()
    });
});

function getClassification(val) {
	var selectedValues = $('#indexer_section').val();
	
	if(selectedValues !='') {
		axios.post('<?php echo e(get_option('site_url')); ?>api/v1/indexings/ajax/classification', {
			id: selectedValues
		})
		.then(function (response) {
			$('.classification').html(response.data);
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
	}
}
</script>
<?php $__env->stopPush(); ?>