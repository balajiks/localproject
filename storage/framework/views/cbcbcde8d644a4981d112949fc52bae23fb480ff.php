<li class="dd-item dd3-item active" data-id="<?php echo e($secdata[0]->id); ?>" id="section-<?php echo e($secdata[0]->id); ?>" >
  
  <span class="pull-right m-xs">
    <a href="#" class="">
      <?php echo e(svg_image('solid/pencil-alt', 'icon-muted fa-fw m-r-xs')); ?>
    </a>
    
    
    <?php if($secdata[0]->user_id === Auth::id()): ?>
    <a href="#" class="deleteSection" data-section-id="<?php echo e($secdata[0]->id); ?>" title="<?php echo trans('app.'.'delete'); ?>">
      <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?>
    </a>
    <?php endif; ?>
  </span>
  
  <div class="dd3-content">
    <label>
      <span class="label-text">
        <span class="<?php echo $secdata[0]->status ? 'text-info' : 'text-danger'; ?>" id="section-id-<?php echo e($secdata[0]->id); ?>">
		  Section : <?php echo e($secdata[0]->sectionvalue); ?> - <span class="label label-info">
		  <?php echo e(!empty($secdata[0]->created_at) ? dateElapsed($secdata[0]->created_at) : ''); ?></span>
		   
        </span>
      </span>
    </label>
    <p class="m-0-sm"><small class="text-muted small m-0-sm" data-rel="tooltip" title="<?php echo e($secdata[0]->jobid); ?>"><?php echo e(svg_image('solid/calendar-alt')); ?> <?php echo e(dateTimeFormatted($secdata[0]->created_at)); ?></small></p>
    
  </div>

   
</li>
