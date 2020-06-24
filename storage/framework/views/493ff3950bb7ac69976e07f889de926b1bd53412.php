<input type="hidden" id="selectedmanuid" value="<?php echo e($data["selectedid"]); ?>"/>
<ol class="dd-list" id="ajaxtradename-listdata">
  <?php $__currentLoopData = $data['devicetradename']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tradelink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <li class="dd-item dd3-item" data-id="<?php echo e($tradelink); ?>" id="ajaxtradelink-<?php echo e($tradelink); ?>" > <span class="pull-right m-xs"> <a href="#" class="deleteajaxtradelink" data-termsdata-id="<?php echo e($tradelink); ?>" title="<?php echo trans('app.'.'delete'); ?>"> <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?> </a> </span>
    <div class="dd3-content">
      <label><span class="label-text"> <span class="text-info" id="ajaxtradelink-id-<?php echo e($tradelink); ?>"> <?php echo e($tradelink); ?> </span></span></label>
    </div>
  </li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ol>
