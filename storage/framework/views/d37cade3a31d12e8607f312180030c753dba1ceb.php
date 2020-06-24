<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?php echo trans('app.'.'make_changes'); ?>  </h4>
        </div>

        <?php echo Form::open(['route' => ['todos.api.update', $todo->id], 'class' => 'bs-example form-horizontal ajaxifyForm', 'method' => 'PUT']); ?>


        <input type="hidden" name="id" value="<?php echo e($todo->id); ?>">
        <input type="hidden" name="url" value="<?php echo e(url()->previous()); ?>">
        <div class="modal-body">

            <div class="form-group">
                <label class="col-lg-3 control-label"><?php echo trans('app.'.'subject'); ?> <span class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <input type="text" class="form-control ta" name="subject" value="<?php echo e($todo->subject); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-3 control-label"><?php echo trans('app.'.'date'); ?> <span class="text-danger">*</span></label>
                <div class="col-lg-9">

                    <div class="input-group date">
                        <input type="text" class="form-control datetimepicker-input"
                               value="<?php echo e(timePickerFormat($todo->due_date)); ?>" name="due_date"
                               data-date-format="DD-MM-YYYY hh:mm A" data-date-start-date="0d" required>
                        <div class="input-group-addon">
                            <?php echo e(svg_image('solid/calendar-alt', 'text-muted')); ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label class="col-lg-3 control-label"><?php echo trans('app.'.'assigned'); ?>  </label>
                <div class="col-lg-9">

                    <select name="assignee" class="select2-option form-control">
                        <?php $__currentLoopData = app('user')->select('id', 'username', 'name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                            <option value="<?php echo e($user->id); ?>" <?php echo e($user->id == $todo->assignee ? 'selected="selected"' : ''); ?>><?php echo e($user->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-3 control-label"><?php echo trans('app.'.'notes'); ?></label>
                <div class="col-lg-9">
                    <textarea class="form-control markdownEditor" data-hidden-buttons='["cmdCode", "cmdQuote"]' name="notes"><?php echo e($todo->notes); ?></textarea>
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
    <?php echo $__env->make('stacks.css.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('pagescript'); ?>
    <?php echo $__env->make('stacks.js.datepicker', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('stacks.js.markdown', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('stacks.js.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script type="text/javascript">
    $('.datetimepicker-input').datetimepicker({showClose: true});
</script>
<?php echo $__env->make('partial.ajaxify', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->yieldPushContent('pagestyle'); ?>
<?php echo $__env->yieldPushContent('pagescript'); ?>