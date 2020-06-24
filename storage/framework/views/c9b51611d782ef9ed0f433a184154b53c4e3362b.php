<?php $__env->startSection('content'); ?>
<section id="content" class="bg">
  <section class="vbox">
    <header class="header panel-heading bg-white b-b b-light">
      <div class="btn-group">
        <button class="btn btn-<?php echo e(get_option('theme_color')); ?> btn-sm dropdown-toggle"
        data-toggle="dropdown"> <?php echo trans('app.'.'filter'); ?>
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          <li>
            <a href="<?php echo e(route('tasks.index', ['filter' => 'backlog'])); ?>"><?php echo trans('app.'.'backlog'); ?>  </a>
          </li>
          <li>
            <a href="<?php echo e(route('tasks.index', ['filter' => 'ongoing'])); ?>"><?php echo trans('app.'.'ongoing'); ?>  </a>
          </li>
          <li><a href="<?php echo e(route('tasks.index', ['filter' => 'done'])); ?>"><?php echo trans('app.'.'done'); ?>  </a></li>
          <li>
            <a href="<?php echo e(route('tasks.index', ['filter' => 'overdue'])); ?>"><?php echo trans('app.'.'overdue'); ?>  </a>
          </li>
          <li>
            <a href="<?php echo e(route('tasks.index', ['filter' => 'mine'])); ?>"><?php echo trans('app.'.'mine'); ?></a>
          </li>
          <li><a href="<?php echo e(route('tasks.index')); ?>"><?php echo trans('app.'.'all'); ?>  </a></li>
        </ul>
      </div>
      
    </header>
    <section class="scrollable wrapper" id="task-list">
      
      <div class="input-group m-b-sm">
        <input type="text" class="form-control search" placeholder="Search by Name, Due Date and Description">
        <span class="input-group-btn">
          <button type="submit" class="btn btn-<?php echo e(get_option('theme_color')); ?> btn-icon">
          <?php echo e(svg_image('solid/search')); ?>
          </button>
        </span>
      </div>
      <ul class="list-group gutter list-group-lg list-group-sp list">
        
        <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="list-group-item box-shadow tasklist">
          <div class="inline pull-right small">

            <ul class="list-unstyled team-info m-l-sm">
              <?php $__currentLoopData = $task->assignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li><img src="<?php echo e($assignee->user->profile->photo); ?>" data-toggle="tooltip" data-title="<?php echo e($assignee->user->name); ?>" data-placement="top"></li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>

            
          </div>
          <div class="clear">
            <span class="pull-left ">
              <label>
                <input type="checkbox" data-id="<?php echo e($task->id); ?>" <?php echo e($task->progress === 100 ? 'checked' : ''); ?>>
                <span class="label-text"><a href="<?php echo e(route('projects.view', ['id' => $task->project_id, 'tab' => 'tasks', 'item' => $task->id])); ?>" class="task-name">
                  <?php echo e(str_limit($task->name, 50)); ?> <?php echo $task->is_recurring ? '<i class="fas fa-sync fa-spin text-danger"></i>' : ''; ?>

                </a></span>
              </label>
            </span>
            
            
            
            
          </div>
          <div class="text-muted">
            <span class="task-desc"><?php echo e(str_limit(strip_tags($task->description), 100)); ?></span>
            <span class="label label-<?php echo e(($task->isOverdue()) ? 'danger' : 'success'); ?>">
              <?php echo e(svg_image('solid/calendar-check')); ?>
              <span class="due-date"><?php echo trans('app.'.'due'); ?> <?php echo e(dateElapsed($task->due_date)); ?></span>
            </span>
          </div>

          <div class="progress progress-xxs active m-xs">
              <div class="progress-bar progress-bar-success" data-rel="tooltip" title="<?php echo e($task->progress); ?>%" style="width: <?php echo e($task->progress); ?>%"></div>
          </div>

          

        </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
        
      </ul>
    </section>
  </section>
  <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
</section>
<?php $__env->startPush('pagestyle'); ?>
<?php echo $__env->make('stacks.css.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('pagescript'); ?>
<?php echo $__env->make('stacks.js.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<script src='<?php echo e(getAsset('plugins/apps/list.min.js')); ?>'></script>

<script>
  $('.tasklist input[type="checkbox"]').change(function () {
      var id = $(this).data().id;
      var progress = $(this).is(":checked");
      var formData = {
          'id': id,
          'done': progress,
      };
            axios.post('/api/v1/tasks/'+id+'/progress', formData)
          .then(function (response) {
              toastr.success(response.data.message, '<?php echo trans('app.'.'response_status'); ?> ');
            })
            .catch(function (error) {
              toastr.error('There was an error processing your request.' , '<?php echo trans('app.'.'response_status'); ?> ');
          });

});

var options = {
valueNames: [ 'task-name', 'task-desc', 'due-date' ]
};
var senderList = new List('task-list', options);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>