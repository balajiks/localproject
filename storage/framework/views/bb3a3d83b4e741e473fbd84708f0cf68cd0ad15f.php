<div class="row">
    <div class="col-lg-12">
        <?php echo Form::open(['route' =>'indexings.create', 'class' => 'bs-example form-horizontal ajaxifyForm']); ?>

        <section class="panel panel-default">
        <header class="panel-heading">
		<header class="btn btn-primary btn-sm" style="margin-left:20px;"> <?php echo e(svg_image('solid/exclamation-circle')); ?> <?php echo trans('app.'.'msntitle'); ?>  &nbsp;&nbsp;[ <span class="btn btn-warning btn-xs">MSN </span> ]</header></header>
        <?php 
        $translations = Modules\Settings\Entities\Options::translations();
        $default_country = get_option('company_country');
		$disable = '';
        ?>
		
		<?php if($jobdata->publ == 'Yes' && $jobdata->abs == 'Yes'): ?>
			$disable = 'disabled';
		<?php endif; ?>
        <div class="panel-body">
		
		<div class="col-md-12">
            <?php if(count($translations) > 0): ?>
            
            <div class="tab-content tab-content-fix">
                <div class="tab-pane fade in active" id="tab-english">
                    <?php endif; ?>
					
					
					<div class="col-md-6">
					
					
					<div class="form-group">
					<div class="col-lg-12">
						<label><?php echo trans('app.'.'repositoryname'); ?></label>
						 <select class="select2-option form-control" id="repository" name="repository">
						 <option selected="true" disabled="disabled">Select <?php echo trans('app.'.'repositoryname'); ?></option>
                                <?php $__currentLoopData = $repositoryname; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repository): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($repository->id); ?>"><?php echo e($repository->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
						</div>
					</div>
					
					<div class="form-group">
					<div class="col-lg-12">
                    <label>
                    <input type="radio" id="txtdrugmajor" name="fcttermindexing" value="1">
                    <span class="label-text text-info">A#####</span></label>
					<label>&nbsp;&nbsp;
                    <input type="radio" id="txtdrugminor" name="fcttermindexing" value="0">
                    <span class="label-text text-info">AA######</span></label>
					
					&nbsp;&nbsp;
                    <input type="radio" id="txtdrugminor" name="fcttermindexing" value="0">
                    <span class="label-text text-info">AA####</span></label>
					
					
					&nbsp;&nbsp;
                    <input type="radio" id="txtdrugminor" name="fcttermindexing" value="0">
                    <span class="label-text text-info">AA########</span></label>
					
					
					&nbsp;&nbsp;
                    <input type="radio" id="txtdrugminor" name="fcttermindexing" value="0">
                    <span class="label-text text-info">AAAAAA######</span></label>
					
					
					&nbsp;&nbsp;
                    <input type="radio" id="txtdrugminor" name="fcttermindexing" value="0">
                    <span class="label-text text-info">AAA#####</span></label>
					
					<?php echo trans('app.'.'msninfo'); ?>
                  </div>
					</div>
					
					
					
					<div class="form-group">
					<div class="col-lg-12">
						<label><?php echo trans('app.'.'msntitle'); ?></label>		
						<input type="text" id="txtmsntitle" class="form-control" placeholder="<?php echo trans('app.'.'msntitle'); ?>" name="txtmsntitle" disabled="disabled">
                                </div>
                     </div>
					
					<div class="form-group">
					<div class="col-lg-12">
								<div class="checkbox">
                                    <label class="padminus20">
                                        <input type="hidden" value="FALSE" name="msnrange"/>
                                        <input type="checkbox" name="msnrange" value="TRUE">
                                        <span class="label-text"><?php echo trans('app.'.'msnrange'); ?>  </span>
										
										<span><input type="text" id="txtmsnrange" class="form-control" placeholder="<?php echo trans('app.'.'msnrange'); ?>" name="txtmsnrange" disabled="disabled"></span>
                                    </label>
                                </div>
                                </div>
                     </div>
					 </div>
                    <?php if(count($translations) > 0): ?>
                </div>
                
            </div>
            <?php endif; ?>
        </div>
		</div>
        <div class="panel-footer">
            <?php echo renderAjaxButton('save'); ?>

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
$("input[name='msnrange']").click(function () {
		if($(this).prop("checked") == true){
			$("#txtmsnrange").removeAttr("disabled");
			$("#txtmsnrange").focus();
		} else {
			$("#txtmsnrange").val('');
			$("#txtmsnrange").attr("disabled", "disabled");
		}
	});


</script>
<?php $__env->stopPush(); ?>