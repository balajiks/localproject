<?php $__currentLoopData = $data['medicaltermdata']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termsdata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<li class="dd-item dd3-item active" data-id="<?php echo e($termsdata->id); ?>" id="termsdata-<?php echo e($termsdata->id); ?>" > <span class="pull-right m-xs"> <a href="#" class=""> <?php echo e(svg_image('solid/pencil-alt', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php if($termsdata->user_id === Auth::id()): ?> <a href="#" class="deletemedicalterm" data-section-id="<?php echo e($termsdata->id); ?>" title="<?php echo trans('app.'.'delete'); ?>"> <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php endif; ?> </span>
  <div class="dd3-content">
    <label><span class="label-text"> <span class="<?php echo $termsdata->status ? 'text-info' : 'text-danger'; ?>" id="section-id-<?php echo e($termsdata->id); ?>"> <?php echo e($termsdata->medicalterm); ?>  </span></span></label><span class="btn btn-info btn-xs pull-right"><?php echo e($termsdata->termtype); ?></span>
  </div>
</li>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 