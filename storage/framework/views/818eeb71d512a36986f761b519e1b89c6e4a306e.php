<?php if(($project->setting('show_project_tasks') && $project->isClient()) || can('projects_view_tasks') || $project->isTeam()): ?>
    
    <?php if(!is_null($item)): ?>
        <?php 
        $task = Modules\Tasks\Entities\Task::findOrFail($item); 
        $data['isTeam'] = $task->isTeam();
        $data['task'] = $task;
        ?>
        <?php echo $__env->make('tasks::view', $data, \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php else: ?>
        <?php
            $data['filter'] = request('filter');
        ?>
        <?php echo $__env->make('tasks::show_all', $data, \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php endif; ?>

   
<?php endif; ?>
