<?php $__currentLoopData = $data['drugtradename']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termsdata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<li class="dd-item dd3-item active" data-id="<?php echo e($termsdata->id); ?>" id="termsdata-<?php echo e($termsdata->id); ?>" > <span class="pull-right m-xs"> <?php if($termsdata->user_id === Auth::id()): ?> <a href="#" class="deletetradeterm" data-section-id="<?php echo e($termsdata->id); ?>" title="<?php echo trans('app.'.'delete'); ?>"> <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php endif; ?> </span>
  <div class="dd3-content" onclick="ajaxtradename(<?php echo e($termsdata->id); ?>)" style="cursor:pointer">
    <label style="cursor:pointer"><span class="label-text text-info"><strong>Field Code : </strong>&nbsp;&nbsp; <span class="label label-info <?php echo $termsdata->status ? 'text-info' : 'text-danger'; ?>" id="section-id-<?php echo e($termsdata->id); ?>">_<?php echo e($termsdata->type); ?> </span></label>
    <label style="cursor:pointer"><span class="label-text text-info"><strong>Drug Manufacture : </strong>&nbsp;&nbsp; <span class="label label-info <?php echo $termsdata->status ? 'text-info' : 'text-danger'; ?>" ><?php echo e($termsdata->manufacturename); ?> </span></label>
    <label style="cursor:pointer"><span class="label-text text-info"><strong>Country : </strong>&nbsp;&nbsp; <span class="label label-info <?php echo $termsdata->status ? 'text-info' : 'text-danger'; ?>" ><?php echo e($termsdata->countrycode); ?> </span></label>
  </div>
</li>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
