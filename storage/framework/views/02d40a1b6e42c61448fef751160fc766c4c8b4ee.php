<ol class="dd-list list-group">
<?php $__currentLoopData = $indexingsections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<li class="dd-item dd3-item list-group-item list-group-item-action active" data-id="<?php echo e($section->id); ?>" id="section-<?php echo e($section->id); ?>" >
  
  <span class="pull-right m-xs">
    <a href="#" data-toggle="ajaxModal">
      <?php echo e(svg_image('solid/pencil-alt', 'icon-muted fa-fw m-r-xs')); ?>
    </a>
    aSs
    
    <?php if($section->user_id === Auth::id()): ?>
    <a href="#" class="deleteSection" data-todo-id="<?php echo e($section->id); ?>" title="<?php echo trans('app.'.'delete'); ?>">
      <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?>
    </a>
    <?php endif; ?>
  </span>
  
  <div class="dd3-content">
    <label>
      <span class="label-text">
        <span class="<?php echo $section->status ? 'text-success' : 'text-danger'; ?>" id="section-id-<?php echo e($section->id); ?>">
          <?php echo e($section->pui); ?>

		  <?php echo e($section->orderid); ?>

		  <?php echo e($section->sectionvalue); ?>

		   <small class="text-muted small m-l-sm" data-rel="tooltip" title="<?php echo e($section->jobid); ?>"><?php echo e(svg_image('solid/calendar-alt')); ?> <?php echo e(dateTimeFormatted($section->created_at)); ?></small>
        </span>
      </span>
    </label>
    <p class="m-xs"><?php echo parsedown($section->sectionid); ?></p>
    
  </div>

   
</li>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ol>



<div class="list-group">
    <a href="#" class="list-group-item list-group-item-action active">
        <i class="fa fa-home"></i> Home
    </a>
    <a href="#" class="list-group-item list-group-item-action">
        <i class="fa fa-camera"></i> Pictures <span class="badge badge-pill badge-primary pull-right">145</span>
    </a>
    <a href="#" class="list-group-item list-group-item-action">
        <i class="fa fa-music"></i> Music <span class="badge badge-pill badge-primary pull-right">50</span>
    </a>
    <a href="#" class="list-group-item list-group-item-action">
        <i class="fa fa-film"></i> Videos <span class="badge badge-pill badge-primary pull-right">8</span>
    </a>
</div>