<div class="lobilist-wrapper ps-container ps-theme-dark ps-active-y kanban-col">
    <div id="lobilist-list-0"
        class="lobilist lobilist-default">
        <div class="lobilist-header ui-sortable-handle">
            <div class="lobilist-title text-ellipsis">
                <span class="arrow right"></span>👍 <?php echo trans('app.'.'today'); ?> - <span class="small text-muted"> <?php echo e(now()->toFormattedDateString()); ?></span>
            </div>
        </div>
        <div class="lobilist-body scrumboard slim-scroll" data-height="450" data-disable-fade-out="true"
            data-distance="0" data-size="5px"
            data-color="#333333">

        
            <ul class="lobilist-items ui-sortable list" id="today">
                <?php $todoCounter = 0; ?>
                <?php $__currentLoopData = Auth::user()->today()->pending()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $todo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li id="<?php echo e($todo->id); ?>" class="lobilist-item kanban-entry grab dd-item">
                    <div class="lobilist-item-title text-ellipsis font14">
                        <a href="<?php echo e($todo->todoable_url); ?>" class=""><?php echo e($todo->subject); ?></a>
                    </div>
                    <div class="lobilist-item-description text-muted">
                        <small class=""><?php echo e(svg_image('solid/clock')); ?>
                        <?php echo e(!empty($todo->due_date) ? dateElapsed($todo->due_date) : ''); ?>

                        </small>
                        <span class="pull-right">

                            <div class="form-check text-muted">
                                            <label>
                                                <input type="checkbox" class="checkItem" data-id="<?php echo e($todo->id); ?>"> 
                                                <span class="label-text"></span>
                                            </label>
                                        </div>
                        </span>
                    </div>
                    <div class="lobilist-item-duedate">
                        <?php echo e(dateFormatted($todo->due_date)); ?>

                    </div>
                    <span class="thumb-xs avatar lobilist-check">
                        <img src="<?php echo e($todo->agent->profile->photo); ?>" data-rel="tooltip" title="<?php echo e($todo->agent->name); ?>" data-placement="right" class="img-circle">
                    </span>
                    
                    
                </li>
                <?php $todoCounter++; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <div class="lobilist-footer">
            <strong>
            <?php echo e(Auth::user()->today()->done()->count()); ?> <?php echo trans('app.'.'done'); ?> 
            </strong>
            <strong class="pull-right">
            <?php echo e($todoCounter); ?> <?php echo trans('app.'.'pending'); ?> 
            </strong>
        </div>
    </div>
</div>