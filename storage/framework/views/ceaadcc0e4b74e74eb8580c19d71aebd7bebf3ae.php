<?php $__currentLoopData = $data['ctntermdata']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termsdata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


<li class="dd-item dd3-item active" data-id="<?php echo e($termsdata->id); ?>" id="ctn-<?php echo e($termsdata->id); ?>" >
  
  <span class="pull-right m-xs">
    
    <?php if($termsdata->user_id === Auth::id()): ?>
    <a href="#" class="deleteCtn" data-ctn-id="<?php echo e($termsdata->id); ?>" title="<?php echo trans('app.'.'delete'); ?>">
      <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?>
    </a>
    <?php endif; ?>
  </span>
  
  <div class="dd3-content">
    <label>
      <span class="label-text">
        <span class="<?php echo $termsdata->status ? 'text-info' : 'text-danger'; ?>" id="ctn-id-<?php echo e($termsdata->id); ?>">
		  Registry Name : <?php echo e($termsdata->registryname); ?> <br />
		  Clinical Trail Number  : <?php echo e($termsdata->registryname); ?>

- <span class="label label-info">
		  <?php echo e(!empty($termsdata->created_at) ? dateElapsed($termsdata->created_at) : ''); ?></span>
		   
        </span>
      </span>
    </label>
    <p class="m-0-sm"><small class="text-muted small m-0-sm" data-rel="tooltip" title="<?php echo e($termsdata->jobid); ?>"><?php echo e(svg_image('solid/calendar-alt')); ?> <?php echo e(dateTimeFormatted($termsdata->created_at)); ?></small></p>
    
  </div>

   
</li>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 