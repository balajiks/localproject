<ol class="dd-list" id="trailnumberlist">
  <?php $__currentLoopData = $indexingctn; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ctn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <li class="dd-item dd3-item active" data-id="<?php echo e($ctn->id); ?>" id="ctn-<?php echo e($ctn->id); ?>" > <span class="pull-right m-xs"> <?php if($ctn->user_id === Auth::id()): ?> <a href="#" class="deleteCtn" data-ctn-id="<?php echo e($ctn->id); ?>" title="<?php echo trans('app.'.'delete'); ?>"> <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php endif; ?> </span>
    <div class="dd3-content">
      <label> <span class="label-text"> <span class="<?php echo $ctn->status ? 'text-info' : 'text-danger'; ?>" id="ctn-id-<?php echo e($ctn->id); ?>"> Registry Name : <?php echo e($ctn->registryname); ?> <br />
      Clinical Trail Number  : <?php echo e($ctn->trailnumber); ?>

      - <span class="label label-info"> <?php echo e(!empty($ctn->created_at) ? dateElapsed($ctn->created_at) : ''); ?></span> </span> </span> </label>
      <p class="m-0-sm"><small class="text-muted small m-0-sm" data-rel="tooltip" title="<?php echo e($ctn->jobid); ?>"><?php echo e(svg_image('solid/calendar-alt')); ?> <?php echo e(dateTimeFormatted($ctn->created_at)); ?></small></p>
    </div>
  </li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ol>
