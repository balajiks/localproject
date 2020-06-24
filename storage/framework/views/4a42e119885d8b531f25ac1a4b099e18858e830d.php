<section class="panel panel-default">

 <?php if(isAdmin() || $project->isTeam() || $project->setting('show_timesheets')): ?>
        <?php echo $__env->make('timetracking::entries', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
   <?php endif; ?>
</section>
