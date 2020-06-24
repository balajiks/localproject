<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?php echo e(svg_image('solid/plus')); ?> <?php echo trans('app.'.'create'); ?>  </h4>
        </div>

        <?php echo Form::open(['route' => 'todos.api.subtask', 'class' => 'form-horizontal ajaxifyForm']); ?>


        <input type="hidden" name="user_id" value="<?php echo e(Auth::id()); ?>">
        <input type="hidden" name="order" value="0">
        <input type="hidden" name="parent" value="<?php echo e($parent->id); ?>">
        <input type="hidden" name="assignee" value="<?php echo e($parent->assignee); ?>">
        <input type="hidden" name="url" value="<?php echo e(url()->previous()); ?>">


        <div class="modal-body">


             <div class="form-group">
                <label class="col-lg-3 control-label"><?php echo trans('app.'.'subject'); ?> <span class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" name="subject" placeholder="Send Proposal" required>
                </div>
            </div>


             <div class="form-group">
                <label class="col-lg-3 control-label"><?php echo trans('app.'.'date'); ?> <span class="text-danger">*</span></label>
                <div class="col-lg-9">

                    <div class="input-group date">
                        <input type="text" class="form-control datetimepicker-input"
                               value="<?php echo e(timePickerFormat(now()->addHours(1))); ?>" name="due_date"
                               data-date-format="DD-MM-YYYY hh:mm A" data-date-start-date="0d" required>
                        <div class="input-group-addon">
                            <?php echo e(svg_image('solid/calendar-alt', 'text-muted')); ?>
                        </div>
                    </div>
                </div>
            </div>

            


            <div class="form-group">
                <label class="col-lg-3 control-label"><?php echo trans('app.'.'notes'); ?>   </label>
                <div class="col-lg-9">
                    <textarea class="form-control markdownEditor" data-hidden-buttons='["cmdCode", "cmdQuote"]' name="notes"></textarea>
                </div>
            </div>
            
        
        </div>
        <div class="modal-footer">

            <?php echo closeModalButton(); ?>

            <?php echo renderAjaxButton(); ?>


        </div>

        <?php echo Form::close(); ?>

    </div>
</div>

<?php $__env->startPush('pagestyle'); ?>
    <?php echo $__env->make('stacks.css.datepicker', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('pagescript'); ?>
    <?php echo $__env->make('stacks.js.datepicker', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('stacks.js.markdown', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('partial.ajaxify', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script type="text/javascript">
    $('.datetimepicker-input').datetimepicker({showClose: true});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->yieldPushContent('pagestyle'); ?>
<?php echo $__env->yieldPushContent('pagescript'); ?>