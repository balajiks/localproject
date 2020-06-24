      
    

<ol class="dd-list slim-scroll">
<?php $__currentLoopData = $indexingsections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<li class="dd-item dd3-item active" data-id="<?php echo e($section->id); ?>" id="section-<?php echo e($section->id); ?>" >
  
  <span class="pull-right m-xs">
   
    <?php if($section->user_id === Auth::id()): ?>
    <a href="#" class="deleteSection" data-section-id="<?php echo e($section->id); ?>" title="<?php echo trans('app.'.'delete'); ?>">
      <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?>
    </a>
    <?php endif; ?>
  </span>
  
  <div class="dd3-content">
    <label>
      <span class="label-text">
        <span class="<?php echo $section->status ? 'text-info' : 'text-danger'; ?>" id="section-id-<?php echo e($section->id); ?>">
		  Section : <?php echo e($section->sectionvalue); ?> - <span class="label label-info">
		  <?php echo e(!empty($section->created_at) ? dateElapsed($section->created_at) : ''); ?></span>
		   
        </span>
      </span>
    </label>
    <p class="m-0-sm"><small class="text-muted small m-0-sm" data-rel="tooltip" title="<?php echo e($section->jobid); ?>"><?php echo e(svg_image('solid/calendar-alt')); ?> <?php echo e(dateTimeFormatted($section->created_at)); ?></small></p>
    
  </div>

   
</li>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ol>


</div>
