<?php if(($project->setting('show_project_calendar') && $project->isClient()) || isAdmin() || $project->isTeam()): ?>
<section class="scrollable">
    <?php if(can('projects_update') || $project->isTeam() || $project->setting('show_project_calendar')): ?>
        <div class="calendar" id="cal"></div>
    <?php endif; ?>
</section>


<?php $__env->startPush('pagestyle'); ?>
    <?php echo $__env->make('stacks.css.fullcalendar', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('pagescript'); ?>
<?php echo $__env->make('stacks.js.fullcalendar', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('calendar::project', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>

<?php endif; ?>