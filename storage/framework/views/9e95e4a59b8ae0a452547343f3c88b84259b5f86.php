<div class="m-xs">
      <article class="chat-item" id="chat-form">
            <a class="pull-left thumb-sm avatar">
                  <img src="<?php echo e(avatar()); ?>" class="img-circle">
            </a>
            <section class="chat-body">
                  <?php echo Form::open(['route' => 'todos.api.save', 'id' => 'createTodo', 'class' => 'm-b-none']); ?>

                  
                  <input type="hidden" name="user_id" value="<?php echo e(Auth::id()); ?>">
                  <input type="hidden" name="assignee" value="<?php echo e(Auth::id()); ?>">
                  <input type="hidden" name="module_id" value="<?php echo e($task->id); ?>">
                  <input type="hidden" name="module" value="tasks">
                  <input type="hidden" name="url" value="<?php echo e(url()->previous()); ?>">
                  <input type="hidden" name="json" value="false">
                  <input type="hidden" name="due_date" value="<?php echo e(now()->addDays(7)); ?>">
                  <div class="input-group">
                        <input type="text" class="form-control" name="subject" placeholder="Add a new todo...">
                        <span class="input-group-btn">
                              <button class="btn btn-info formSaving submit" type="submit"> <?php echo e(svg_image('solid/check-circle')); ?> <?php echo trans('app.'.'save'); ?>  </button>
                        </span>
                  </div>
                  <?php echo Form::close(); ?>

            </section>
      </article>
</div>
<div class="sortable">
      <div class="todo-list" id="nestable">
            <?php echo app('arrilot.widget')->run('Todos\ShowTodos', ['todos' => $task->todos()->where(function ($query) {
            $query->where('user_id', Auth::id())->orWhere('assignee', Auth::id());
            })->get()]); ?>

      </div>
</div>
<?php $__env->startPush('pagestyle'); ?>
<link rel=stylesheet href="<?php echo e(getAsset('plugins/nestable/nestable.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('pagescript'); ?>
<?php echo $__env->make('todos::_ajax.todojs', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->yieldPushContent('pagestyle'); ?>
<?php echo $__env->yieldPushContent('pagescript'); ?>