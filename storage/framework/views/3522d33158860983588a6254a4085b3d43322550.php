<div class="table-responsive">
    <table id="table-ratings" class="table table-striped">
        <thead>
            <tr>
                <th><?php echo trans('app.'.'name'); ?></th>
                <th><?php echo trans('app.'.'project'); ?></th>
                <th><?php echo trans('app.'.'date'); ?></th>
                <th>Rating</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $ratings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>
                    <span class="thumb-xs avatar">
                        <img src="<?php echo e($rating->user->profile->photo); ?>" class="img-circle">
                    </span>
                    <a href="#" class=""><?php echo e($rating->user->name); ?></a>
                </td>
                <td><a href="<?php echo e($rating->reviewable->url); ?>"><?php echo e($rating->reviewable->name); ?></a></td>
                <td><?php echo e($rating->created_at->diffForHumans()); ?></td>
                <td><?php echo $rating->satisfied === 1 ? '<i class="fas fa-star text-success"></i> Great' : '<i class="far fa-star text-danger"></i> Bad'; ?></td>
                
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
$('#table-ratings').DataTable({
processing: true,
order: [[ 0, "desc" ]],
pageLength: 25
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->yieldPushContent('pagestyle'); ?>
<?php echo $__env->yieldPushContent('pagescript'); ?>