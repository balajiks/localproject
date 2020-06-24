<?php if(!empty($data['checktagdata'])): ?>
<?php $__currentLoopData = $data['checktagdata']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $checktags): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php $__currentLoopData = $checktags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $checktag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li class="dd-item dd3-item active" data-id="<?php echo e($checktag->id); ?>" id="checktag-<?php echo e($checktag->id); ?>" > <span class="pull-right m-xs"> <a href="#" class=""> <?php echo e(svg_image('solid/pencil-alt', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php if($checktag->user_id === Auth::id()): ?> <a href="#" class="deletechecktag" data-section-id="<?php echo e($checktag->id); ?>" title="<?php echo trans('app.'.'delete'); ?>"> <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php endif; ?> </span>
                 <div class="dd3-content">
                  <label><span class="label-text"> <span class="<?php echo $checktag->status ? 'text-info' : 'text-danger'; ?>" id="checktags-id-<?php echo e($checktag->id); ?>"> <?php echo e($checktag->checktag); ?> </span></span></label>
                </div></li>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   
<?php endif; ?>