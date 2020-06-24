<div class="table-responsive">
    <table id="table-projects" class="table table-striped">
        <thead>
            <tr>
                <th><?php echo trans('app.'.'title'); ?> </th>
                <th><?php echo trans('app.'.'client'); ?> </th>
                <th><?php echo trans('app.'.'amount'); ?> </th>
                <th><?php echo trans('app.'.'expenses'); ?> </th>
                <th><?php echo trans('app.'.'time_spent'); ?> </th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <tr>
                <td><a href="<?php echo e(route('projects.view', ['id' => $project->id])); ?>"><?php echo e($project->name); ?></a></td>
                <td><a href="<?php echo e(route('clients.view', ['id' => $project->client_id])); ?>"><?php echo e(optional($project->company)->name); ?></a></td>
                <td class="text-semibold"><?php echo e(formatCurrency($project->currency, $project->sub_total)); ?></td>
                <td class="text-semibold"><?php echo e(formatCurrency($project->currency, $project->total_expenses)); ?></td>
                <td><?php echo e(secToHours($project->billable_time)); ?></td>
                
            </tr>
            
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->startPush('pagestyle'); ?>
<?php echo $__env->make('stacks.css.datatables', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('pagescript'); ?>
<?php echo $__env->make('stacks.js.datatables', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<script>
$('#table-projects').DataTable({
processing: true,
order: [[ 0, "desc" ]],
pageLength: 25
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->yieldPushContent('pagestyle'); ?>
<?php echo $__env->yieldPushContent('pagescript'); ?>