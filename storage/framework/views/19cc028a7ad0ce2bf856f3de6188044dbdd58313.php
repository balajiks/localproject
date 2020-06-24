<?php $__env->startSection('content'); ?>
<section id="content" class="bg">
  <section class="vbox">
    <header class="header bg-white b-b clearfix">
      <div class="row m-t-sm">
        <div class="col-sm-5 m-b-xs m-t-xs">
          <span class="h3"><?php echo trans('app.'.'contacts'); ?></span>
          
        </div>
        <div class="col-sm-7 m-b-xs">
          
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('contacts_create')): ?>
          
          <div class="btn-group pull-right">
            <button class="btn btn-<?php echo e(get_option('theme_color')); ?> btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php echo trans('app.'.'import'); ?> <span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="<?php echo e(route('contacts.import', ['type' => 'contacts'])); ?>" data-toggle="ajaxModal"><?php echo trans('app.'.'csv_file'); ?></a></li>
              <li><a href="<?php echo e(route('contacts.import', ['type' => 'google'])); ?>">Google <?php echo trans('app.'.'contacts'); ?></a></li>
            </ul>
          </div>
          
          <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('contacts_view')): ?>
          <a href="<?php echo e(route('contacts.export')); ?>" class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?> pull-right">
            <?php echo e(svg_image('solid/download')); ?> CSV
          </a>
          <?php endif; ?>
          
        </div>
      </div>
    </header>
    
    <section class="scrollable wrapper scrollpane">
      
      <?php $__currentLoopData = $contacts->chunk(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="row">
        <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-lg-3 col-md-6">
          <div class="thumbnail">
            <div class="thumb thumb-rounded">
              <a href="<?php echo e(route('contacts.view', $contact->user_id)); ?>">
                <img src="<?php echo e($contact->photo); ?>" alt="" class="avatar-img">
              </a>
              
            </div>
            
            <div class="caption text-center">
              <h6>
              <a href="<?php echo e(route('contacts.view', $contact->user_id)); ?>" >
                <?php echo e($contact->name); ?>

              </a>
              <span class="display-block text-muted m-xs"><?php echo e($contact->job_title); ?></span>
              <?php if($contact->company > 0): ?>
              <span class="display-block text-muted m-xs"><?php echo e(optional($contact->business)->name); ?></span>
              <?php endif; ?>
              </h6>
              <p class="m-t-sm">
                
                <?php if(!empty($contact->twitter)): ?>
                <a href="<?php echo e($contact->twitter); ?>" target="_blank" class="btn btn-rounded btn-twitter btn-icon"><?php echo e(svg_image('brands/twitter')); ?></a>
                <?php endif; ?>
                <?php if(!empty($contact->skype)): ?>
                <a href="skype:<?php echo e($contact->skype); ?>?call" class="btn btn-rounded btn-info btn-icon"><?php echo e(svg_image('brands/skype')); ?></a>
                <?php endif; ?>
                <a href="<?php echo e(route('contacts.email', $contact->user_id)); ?>" class="btn btn-rounded btn-dracula btn-icon" data-toggle="ajaxModal" data-rel="tooltip" title="<?php echo trans('app.'.'send_email'); ?>"><?php echo e(svg_image('solid/paper-plane')); ?></a>
                
              </p>
              
            </div>
          </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      
    </section>
  </section>
  <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>