<?php $__env->startSection('content'); ?>
<section id="content">

  <section class="hbox stretch">
    <aside class="aside-lg" id="subNav">
      <header class="dk header b-b">
        <div class="wrapper"><?php echo trans('app.'.'messages'); ?> <a href="<?php echo e(route('messages.new')); ?>" class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?> pull-right">
        <?php echo e(svg_image('solid/paper-plane')); ?> <?php echo trans('app.'.'send'); ?></a></div>
        
      </header>
        <section class="scrollable">
          <section class="slim-scroll msg-thread" data-height="500px" id="sender-list">
            <?php echo $__env->make('messages::partials.search', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php echo $__env->make('messages::threads', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
          </section>
        </section>
      </aside>       
      <aside class="bg-light lter b-l" id="email-list">
        <section class="vbox">
          <header class="header bg-white b-b clearfix">
            <div class="row m-t-sm">
              
            </div>
          </header>
          <section class="scrollable wrapper bg">
            <?php echo Form::open(['route' => ['message.send'], 'class' => 'ajaxifyForm bs-example', 'data-toggle' => 'validator', 'files' => true]); ?>


           
              <section class="panel panel-default">
                <header class="panel-heading"><?php echo e(svg_image('solid/info-circle')); ?> <?php echo e(__('An email will be sent to notify the user.')); ?>

                </header>
                <div class="panel-body">
                  
                  <div class="form-group">
                    <label class="control-label"><?php echo trans('app.'.'users'); ?> <span class="text-danger">*</span>
                    </label>
                    <select class="select2-option width100" multiple="multiple" name="to[]">
                      <?php if(can('messages_send_to_all')): ?>
                      <?php $__currentLoopData = app('user')->select('id','username', 'name')->where('id', '!=', Auth::id())->offHoliday()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php else: ?>
                      <?php $__currentLoopData = app('user')->role('admin')->where('id', '!=', Auth::id())->offHoliday()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="control-label"><?php echo trans('app.'.'message'); ?> <span class="text-danger">*</span></label>
                    
                    <textarea name="message" class="form-control markdownEditor"></textarea>
                    
                  </div>
                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('files_create')): ?>
                  <div class="form-group">
                    <label class="control-label"><?php echo trans('app.'.'files'); ?>
                    </label>
                    <input type="file" name="uploads[]" multiple>
                  </div>
                  <?php endif; ?>
                </div>
                <div class="panel-footer">
                  <?php echo renderAjaxButton('send'); ?>

                </div>
              </section>
             <?php echo Form::close(); ?>

          </section>
        </section>
      </aside>
    </section>
    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
  </section>
  <?php $__env->startPush('pagestyle'); ?>
  <?php echo $__env->make('stacks.css.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php $__env->stopPush(); ?>
  <?php $__env->startPush('pagescript'); ?>
  <?php echo $__env->make('stacks.js.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php echo $__env->make('stacks.js.markdown', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php echo $__env->make('stacks.js.list', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <script>
  var options = {
    valueNames: [ 'sender-name' ]
  };
  var senderList = new List('sender-list', options);
  </script>
  <?php $__env->stopPush(); ?>

  <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>