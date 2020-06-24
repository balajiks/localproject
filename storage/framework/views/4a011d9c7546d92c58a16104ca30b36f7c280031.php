<div class="row">
    <div class="col-lg-12">
		<?php echo Form::open(['route' => 'indexings.api.savectn', 'id' => 'createCtn', 'class' => 'bs-example form-horizontal m-b-none']); ?>

        <section class="panel panel-default">
        <header class="panel-heading">
		<header class="btn btn-primary btn-sm" style="margin-left:20px;"> <?php echo e(svg_image('solid/exclamation-circle')); ?> <?php echo trans('app.'.'ctnalertinfo'); ?>  </header>
      </header>
        <?php 
        $translations = Modules\Settings\Entities\Options::translations();
        $default_country = get_option('company_country');
		$disable = '';
        ?>
		
		<input type="hidden" class="form-control" placeholder="jobid" name="jobid" id="jobid" value="<?php echo e($jobdata->id); ?>" />
		<input type="hidden" class="form-control" placeholder="pui" name="pui" id="pui" value="<?php echo e($jobdata->pui); ?>" />
		<input type="hidden" class="form-control" placeholder="orderid" name="orderid" id="orderid" value="<?php echo e($jobdata->orderid); ?>" />
		<input type="hidden" name="json" value="false"/>

		
        <div class="panel-body">
		<div class="col-md-12">
            <?php if(count($translations) > 0): ?>
            
            <div class="tab-content tab-content-fix">
                <div class="tab-pane fade in active" id="tab-english">
                    <?php endif; ?>
					
					<div class="form-group">
					<div class="col-lg-4">
						<label><?php echo trans('app.'.'registername'); ?></label>
						 <select class="select2-option form-control" id="registryname" name="registryname">
						 <option selected="true" disabled="disabled">Select <?php echo trans('app.'.'registername'); ?></option>
                                <?php $__currentLoopData = $registries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $register): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($register->name); ?>"><?php echo e($register->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
						</div>
						<div class="col-lg-4">
						<label><?php echo trans('app.'.'labelctn'); ?></label>
						 <input type="text" id="clinicaltrailnumber" class="form-control" placeholder="<?php echo trans('app.'.'labelctn'); ?>" name="clinicaltrailnumber"  autocomplete="off"/>
						</div>
					</div>
                    
                    <?php if(count($translations) > 0): ?>
                </div>
                
            </div>
            <?php endif; ?>
        </div>
		</div>
        <div class="panel-footer">
            <?php echo renderAjaxButtonSquare('save'); ?>

        </div>
		
		<div class="sortable">
		<div class="ctn-list" id="nestable">
 <?php echo app('arrilot.widget')->run('Indexings\ShowCtn', ['indexingctn' => DB::table('ctn')->where('user_id', \Auth::id())->where('jobid', $jobdata->id)->where('orderid', $jobdata->orderid)->where('pui', $jobdata->pui)->get()]); ?>
		</div>
	</div>
    </section>
    <?php echo Form::close(); ?>

	
	
	
</div>

</div>

<?php $__env->startPush('pagestyle'); ?>
<?php echo $__env->make('stacks.css.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('pagescript'); ?>
<?php echo $__env->make('stacks.js.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<script>
$(".Selpublication").select2({
    placeholder: "Select a state",
    allowClear: true
});

function getClassification(val) {
	var selectedValues = $('#indexer_section').val();
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


</script>
<?php $__env->stopPush(); ?>