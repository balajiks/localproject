<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?php echo trans('app.'.'make_changes'); ?></h4>
        </div>
        <?php echo Form::open(['route' => ['tasks.api.update', $task->id], 'class' => 'ajaxifyForm bs-example form-horizontal', 'method' => 'PUT']); ?>

        <div class="modal-body">
            <input type="hidden" name="id" value="<?php echo e($task->id); ?>">
            <input type="hidden" name="project_id" value="<?php echo e($task->project_id); ?>">
            <div class="form-group">
                <label class="col-lg-4 control-label"><?php echo trans('app.'.'task_name'); ?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="<?php echo e($task->name); ?>" name="name">
                </div>
            </div>
            <?php if($task->AsProject->isTeam() || can('milestones_create')): ?>
            <div class="form-group">
                <label class="col-lg-4 control-label"><?php echo trans('app.'.'milestone'); ?></label>
                <div class="col-lg-8">
                    <select name="milestone_id" class="form-control">
                        <option value="0"><?php echo trans('app.'.'none'); ?> </option>
                        <?php $__currentLoopData = $task->AsProject->milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($m->id); ?>"<?php echo e($task->milestone_id === $m->id ? ' selected="selected"' : ''); ?>><?php echo e($m->milestone_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                    <label class="col-lg-4 control-label"><?php echo trans('app.'.'stage'); ?></label>
                    <div class="col-lg-8">
                        <select name="stage_id" class="form-control">
                            <option value="">---None---</option>
                            <?php $__currentLoopData = App\Entities\Category::select('id', 'name')->tasks()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($stage->id); ?>" <?php echo e($stage->id == $task->stage_id ? 'selected' : ''); ?>><?php echo e($stage->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

            <?php endif; ?>
            <div class="form-group">
                <label class="col-lg-4 control-label"><?php echo trans('app.'.'description'); ?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <textarea name="description" class="form-control ta"><?php echo e($task->description); ?></textarea>
                </div>
            </div>
            <?php if($task->AsProject->isTeam() || can('tasks_update')): ?>
            <div class="form-group">
                <label class="col-lg-4 control-label"><?php echo trans('app.'.'progress'); ?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <div id="progress-slider"></div>
                    <input id="progress" type="hidden" value="<?php echo e($task->progress); ?>" name="progress"/>
                </div>
            </div>
            <?php endif; ?>
            <div class="form-group">
                <label class="col-lg-4 control-label"><?php echo trans('app.'.'start_date'); ?></label>
                <div class="col-lg-8">
                    <div class="input-group">
                        <input class="datepicker-input form-control" size="16" type="text"
                        value="<?php echo e(datePickerFormat($task->start_date)); ?>"
                        name="start_date"
                        data-date-format="<?php echo e(get_option('date_picker_format')); ?>"
                        required>
                        <label class="input-group-addon btn" for="date">
                            <?php echo e(svg_image('solid/calendar-alt', 'text-muted')); ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label"><?php echo trans('app.'.'due_date'); ?></label>
                <div class="col-lg-8">
                    <div class="input-group">
                        <input class="datepicker-input form-control" size="16" type="text"
                        value="<?php echo e(datePickerFormat($task->due_date)); ?>"
                        name="due_date" data-date-format="<?php echo e(get_option('date_picker_format')); ?>" data-date-start-date="moment()" required>
                        <label class="input-group-addon btn" for="date">
                            <?php echo e(svg_image('solid/calendar-alt', 'text-muted')); ?>
                        </label>
                    </div>
                </div>
            </div>
            <?php if(isAdmin() || $task->AsProject->isTeam()): ?>
            <div class="form-group">
                <label class="col-lg-4 control-label"><?php echo trans('app.'.'hourly_rate'); ?></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control money" value="<?php echo e($task->hourly_rate); ?>" name="hourly_rate">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label"><?php echo trans('app.'.'estimated_hours'); ?></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control money" value="<?php echo e($task->estimated_hours); ?>" name="estimated_hours">
                </div>
            </div>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users_assign')): ?>
            <div class="form-group">
                <label class="col-lg-4 control-label"><?php echo trans('app.'.'assigned'); ?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <select class="select2-option form-control" multiple="multiple" name="team[]" required>
                        <?php $__currentLoopData = $task->AsProject->assignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($member->user_id); ?>" <?php echo e($task->isTeam($member->user_id) ? 'selected' : ''); ?>>
                            <?php echo e($member->user->name); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if(optional($task->AsProject->company)->primary_contact > 0): ?>
                            <option value="<?php echo e($task->AsProject->company->primary_contact); ?>" <?php echo e($task->isTeam($task->AsProject->company->primary_contact) ? 'selected' : ''); ?>>
                                <?php echo e($task->AsProject->company->contact->name); ?>

                            </option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <?php endif; ?>


            <div class="form-group">
                                        <label class="col-md-4 control-label"><?php echo trans('app.'.'recur_frequency'); ?></label>
                                        <div class="col-md-8">
                                            <select name="recurring[frequency]" class="form-control" id="frequency">
                                                <option value="none" <?php echo e($task->is_recurring ? 'selected' : ''); ?>><?php echo trans('app.'.'none'); ?>  </option>
                                                <option value="1"<?php echo e($task->is_recurring && $task->recurring->frequency == '1' ? ' selected' : ''); ?>><?php echo trans('app.'.'daily'); ?></option>
                                                <option value="7"<?php echo e($task->is_recurring && $task->recurring->frequency == '7' ? ' selected' : ''); ?>><?php echo trans('app.'.'week'); ?></option>
                                                <option value="30"<?php echo e($task->is_recurring && $task->recurring->frequency == '30' ? ' selected' : ''); ?>><?php echo trans('app.'.'month'); ?></option>
                                                <option value="90"<?php echo e($task->is_recurring && $task->recurring->frequency == '90' ? ' selected' : ''); ?>><?php echo trans('app.'.'quarter'); ?></option>
                                                <option value="180"<?php echo e($task->is_recurring && $task->recurring->frequency == '180' ? ' selected' : ''); ?>><?php echo trans('app.'.'six_months'); ?></option>
                                                <option value="365"<?php echo e($task->is_recurring && $task->recurring->frequency == '365' ? ' selected' : ''); ?>><?php echo trans('app.'.'year'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="recurring" class="<?php echo e(!$task->is_recurring ? 'display-none' : ''); ?>">
                                        <?php
                                        $recurStarts = $task->is_recurring ? $task->recurring->recur_starts : today()->toDateString();
                                        $recurEnds = $task->is_recurring ? $task->recurring->recur_ends : today()->addYears(1)->toDateString();
                                        ?>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php echo trans('app.'.'start_date'); ?></label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input class="datepicker-input form-control" size="16" type="text"
                                                    value="<?php echo e(datePickerFormat($recurStarts)); ?>"
                                                    name="recurring[recur_starts]"
                                                    data-date-format="<?php echo e(get_option('date_picker_format')); ?>"
                                                    required>
                                                    <label class="input-group-addon btn" for="date">
                                                        <?php echo e(svg_image('solid/calendar-alt', 'text-muted')); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php echo trans('app.'.'end_date'); ?></label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input class="datepicker-input form-control" size="16" type="text"
                                                    value="<?php echo e(datePickerFormat($recurEnds)); ?>"
                                                    name="recurring[recur_ends]"
                                                    data-date-format="<?php echo e(get_option('date_picker_format')); ?>"
                                                    required>
                                                    <label class="input-group-addon btn" for="date">
                                                        <?php echo e(svg_image('solid/calendar-alt', 'text-muted')); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

            <?php if($task->AsProject->isTeam() || can('tasks_update')): ?>
            <div class="form-group">
                <label class="col-lg-4 control-label"><?php echo trans('app.'.'visible_to_client'); ?> </label>
                <div class="col-lg-8">
                    <select name="visible" class="form-control">
                        <option value="1" <?php echo e($task->visible === 1 ? ' selected' : ''); ?>><?php echo trans('app.'.'yes'); ?></option>
                        <option value="0" <?php echo e($task->visible === 0 ? ' selected' : ''); ?>><?php echo trans('app.'.'no'); ?></option>
                    </select>
                </div>
            </div>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tasks_update')): ?>
            <div class="form-group">
                
                <label class="col-lg-4 control-label"><?php echo trans('app.'.'tags'); ?>  </label>
                <div class="col-lg-8">
                    <select class="select2-tags form-control" name="tags[]" multiple>
                        <?php $__currentLoopData = App\Entities\Tag::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tag->name); ?>" <?php echo e(in_array($tag->id, array_pluck($task->tags->toArray(), 'id')) ? ' selected="selected"' : ''); ?>>
                            <?php echo e($tag->name); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            
            <?php endif; ?>
        </div>
        <div class="modal-footer">
            <?php echo closeModalButton(); ?>

            <?php echo renderAjaxButton('edit'); ?>

        </div>
        <?php echo Form::close(); ?>

    </div>
</div>
<?php $__env->startPush('pagestyle'); ?>
    <?php echo $__env->make('stacks.css.nouislider', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('stacks.css.datepicker', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('stacks.css.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('pagescript'); ?>
    <?php echo $__env->make('stacks.js.nouislider', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('stacks.js.datepicker', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('stacks.js.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('partial.ajaxify', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script>
    $('.money').maskMoney({allowZero: true, thousands: '', allowNegative: true});
    $('#frequency').change(function () {
        if ($("#frequency").val() === "none") {
            $("#recurring").hide();
        } else {
            $("#recurring").show();
        }
    });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->yieldPushContent('pagestyle'); ?>
<?php echo $__env->yieldPushContent('pagescript'); ?>