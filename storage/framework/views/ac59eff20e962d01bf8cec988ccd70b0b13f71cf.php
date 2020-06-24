<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?php echo e(svg_image('solid/clock')); ?> <?php echo trans('app.'.'time_entry'); ?> - <span class="text-info"><?php echo e($entry->user->name); ?></span> </h4>
        </div>
        <div class="modal-body">

            <?php if($entry->is_started): ?>
                <div class="pull-right">
                <?php echo e(svg_image('solid/clock', 'fa-spin fa-5x text-danger')); ?>
                </div>

                <?php endif; ?>

            <p><?php echo trans('app.'.'name'); ?>  : <strong><?php echo e($entry->timeable->name); ?></strong></p>
            <?php if($entry->task_id > 0): ?>
            <p><?php echo trans('app.'.'task'); ?>  : <a href="<?php echo e(route('projects.view',['id' => $entry->timeable->id,'tab' => 'tasks', 'item' => $entry->task_id])); ?>"><?php echo e(optional($entry->task)->name); ?></a></p>
            <?php endif; ?>
            <p><?php echo trans('app.'.'date'); ?>  : <strong><?php echo e($entry->created_at->toDayDateTimeString()); ?></strong></p>
            <p><?php echo trans('app.'.'billable'); ?>  : <strong><?php echo e($entry->billable ? langapp('yes') : langapp('no')); ?></strong></p>
            <p><?php echo trans('app.'.'billed'); ?>  : <strong><?php echo e($entry->billed ? langapp('yes') : langapp('no')); ?></strong></p>
            <p><?php echo trans('app.'.'start'); ?>  : <?php echo e(svg_image('solid/clock', 'text-success')); ?> <?php echo e($entry->start ? dateTimeFormatted( dateFromUnix($entry->start) ) : ''); ?> </p>
            <p><?php echo trans('app.'.'stop'); ?>  : <?php echo e(svg_image('solid/clock', 'text-danger')); ?> <?php echo e($entry->end ? dateTimeFormatted( dateFromUnix($entry->end) ) : ''); ?> </p>
            <p><?php echo trans('app.'.'total_time'); ?>  : <strong><?php echo e(secToHours($entry->worked)); ?></strong> </p>

            <blockquote><?php echo parsedown($entry->notes); ?></blockquote>




        </div>

    </div>    


</div>