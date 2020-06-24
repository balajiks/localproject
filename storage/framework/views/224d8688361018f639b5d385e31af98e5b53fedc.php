<li class="dd-item dd3-item" data-id="<?php echo e($todo->id); ?>" id="todo-<?php echo e($todo->id); ?>" >
  
  <span class="pull-right m-xs">
    <a href="<?php echo e(route('todo.edit', $todo->id)); ?>" data-toggle="ajaxModal">
      <?php echo e(svg_image('solid/pencil-alt', 'icon-muted fa-fw m-r-xs')); ?>
    </a>
     <a href="<?php echo e(route('todo.subtask', ['id' => $todo->id])); ?>" data-toggle="ajaxModal">
      <?php echo e(svg_image('solid/plus', 'icon-muted fa-fw m-r-xs')); ?>
    </a>
    <?php if($todo->assignee == Auth::id()): ?>
    <a href="#" class="deleteTodo" data-todo-id="<?php echo e($todo->id); ?>" title="<?php echo trans('app.'.'delete'); ?>">
      <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?>
    </a>
    <?php endif; ?>
  </span>
  1
  <div class="dd3-content">
    <span class="m-t-0">
      <label>
        <input type="checkbox" data-id="<?php echo e($todo->id); ?>" <?php echo $todo->completed ? 'checked' : ''; ?>>
        <span class="label-text <?php echo $todo->completed ? 'text-success' : 'text-danger'; ?>" id="todo-id-<?php echo e($todo->id); ?>">
          <?php echo e($todo->subject); ?> <small class="text-muted small m-l-sm" data-rel="tooltip" title="<?php echo e($todo->agent->name); ?>"><?php echo e(svg_image('solid/calendar-alt')); ?> <?php echo e(dateTimeFormatted($todo->due_date)); ?></small>
        </span>
      </label>
    </span>
    <p class="m-xs"><?php echo parsedown($todo->notes); ?></p>
  </div>
  
</li>