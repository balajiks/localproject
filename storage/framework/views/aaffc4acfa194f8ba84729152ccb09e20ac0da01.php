<section class="panel panel-default">
  <header class="header b-b clearfix">
    <div class="m-t-sm">
      <?php if(session('taskview') == 'table'): ?>
      
      <div class="btn-group">
        <button class="btn btn-<?php echo e(get_option('theme_color')); ?> btn-sm dropdown-toggle"
        data-toggle="dropdown"> <?php echo trans('app.'.'filter'); ?>
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          <li>
            <a href="<?php echo e(route('projects.view', ['project' => $project->id, 'tab' => 'tasks', 'item' => null, 'filter' => 'backlog'])); ?>"><?php echo trans('app.'.'backlog'); ?>  </a>
          </li>
          <li>
            <a href="<?php echo e(route('projects.view', ['project' => $project->id, 'tab' => 'tasks', 'item' => null, 'filter' => 'ongoing'])); ?>"><?php echo trans('app.'.'ongoing'); ?>  </a>
          </li>
          <li><a href="<?php echo e(route('projects.view', ['project' => $project->id, 'tab' => 'tasks', 'item' => null, 'filter' => 'done'])); ?>"><?php echo trans('app.'.'done'); ?>  </a></li>
          <li>
            <a href="<?php echo e(route('projects.view', ['project' => $project->id, 'tab' => 'tasks', 'item' => null, 'filter' => 'overdue'])); ?>"><?php echo trans('app.'.'overdue'); ?>  </a>
          </li>
          <li>
            <a href="<?php echo e(route('projects.view', ['project' => $project->id, 'tab' => 'tasks', 'item' => null, 'filter' => 'mine'])); ?>"><?php echo trans('app.'.'mine'); ?></a>
          </li>
          <li><a href="<?php echo e(route('projects.view', ['project' => $project->id, 'tab' => 'tasks', 'item' => null, 'filter' => 'all'])); ?>"><?php echo trans('app.'.'all'); ?>  </a></li>
        </ul>
      </div>
      <?php endif; ?>
      <?php if(can('tasks_create') || $project->isTeam() || $project->setting('client_add_tasks')): ?>
      <a href="<?php echo e(route('tasks.create', ['id' => $project->id])); ?>"
        data-toggle="ajaxModal" class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?>">
        <?php echo e(svg_image('solid/plus')); ?> <?php echo trans('app.'.'create'); ?>
      </a>
      <?php endif; ?>
      <div class="pull-right">
        <div class="btn-group">
          <a href="<?php echo e(route('set.view.type', ['type' => 'tasks', 'view' => 'table'])); ?>" data-rel="tooltip" title="Table" data-placement="bottom" class="btn btn-sm btn-default">
            <?php echo e(svg_image('solid/th')); ?>
          </a>
          <a href="<?php echo e(route('set.view.type', ['type' => 'tasks', 'view' => 'kanban'])); ?>" data-rel="tooltip" title="Kanban" data-placement="bottom" class="btn btn-sm btn-default">
            <?php echo e(svg_image('solid/align-justify')); ?>
          </a>
        </div>
        <?php if (\Illuminate\Support\Facades\Blade::check('admin')): ?>
        <a href="<?php echo e(route('settings.stages.show', 'tasks')); ?>" data-toggle="ajaxModal" class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?>" data-rel="tooltip" title="<?php echo trans('app.'.'stages'); ?>" data-placement="bottom">
          <?php echo e(svg_image('solid/cogs')); ?>
        </a>
        <?php endif; ?>
      </div>
      
    </div>
  </header>
  <?php echo $__env->make('tasks::partial._'.session('taskview', 'table').'_view', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  
</section>