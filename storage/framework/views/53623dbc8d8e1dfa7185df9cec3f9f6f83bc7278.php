<section class="panel panel-default">
    <?php if($task->project_id == $project->id): ?>
    <header class="header bg-white b-b clearfix">
        <div class="col-sm-12 m-b-xs m-t-sm">
            <?php if($task->progress < 100 && $isTeam): ?>
            <?php if($task->timerRunning()): ?>
            <a class="btn btn-sm btn-danger"
                href="<?php echo e(route('clock.stop', ['id' => $task->id, 'module' => 'tasks'])); ?>">
                <?php echo e(svg_image('solid/stop-circle')); ?> <?php echo trans('app.'.'stop'); ?>
            </a>
            <?php else: ?>
            <a class="btn btn-sm btn-success" href="<?php echo e(route('clock.start', ['id' => $task->id, 'module' => 'tasks'])); ?>">
                <?php echo e(svg_image('solid/play')); ?> <?php echo trans('app.'.'start'); ?>
            </a>
            <?php endif; ?>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reminders_create')): ?>
            <a href="<?php echo e(route('calendar.reminder', ['module' => 'tasks', 'id' => $task->id])); ?>" class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?>" data-toggle="ajaxModal" data-rel="tooltip"  data-placement="bottom" title="<?php echo trans('app.'.'set_reminder'); ?>">
                <?php echo e(svg_image('solid/clock')); ?>
            </a>
            <?php endif; ?>
            <?php if(can('tasks_update') || $task->user_id == \Auth::id()): ?>
            <a href="<?php echo e(route('tasks.edit', $task->id)); ?>" class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?>" data-toggle="ajaxModal" data-rel="tooltip" title="<?php echo trans('app.'.'make_changes'); ?>">
                <?php echo e(svg_image('solid/pencil-alt')); ?> <?php echo trans('app.'.'edit'); ?>
            </a>
            <?php endif; ?>
            <div class="btn-group btn-group-animated">
                <button type="button" class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?> dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <?php echo e(svg_image('solid/ellipsis-h')); ?>
                </button>
                <ul class="dropdown-menu">
                    <?php if($task->progress < 100 && $isTeam || can('tasks_complete')): ?>
                    <li>
                        <a href="<?php echo e(route('tasks.close', $task->id)); ?>" data-rel="tooltip" title="<?php echo trans('app.'.'mark_as_complete'); ?>">
                            <?php echo e(svg_image('solid/check-circle')); ?> <?php echo trans('app.'.'mark_as_complete'); ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="<?php echo e(route('files.upload', ['module' => 'tasks', 'id' => $task->id])); ?>" data-toggle="ajaxModal">
                            <?php echo e(svg_image('solid/upload')); ?> <?php echo trans('app.'.'upload_file'); ?>
                        </a>
                    </li>
                    <?php if(can('tasks_update') || $task->user_id == \Auth::id()): ?>
                    <li>
                        <a href="<?php echo e(route('users.pin', ['id' => $task->id, 'module' => 'tasks'])); ?>" data-rel="tooltip" title="<?php echo trans('app.'.'pin_sidebar'); ?>" data-placement="top">
                            <?php echo e(svg_image('solid/bookmark')); ?> <?php echo trans('app.'.'pin_sidebar'); ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(can('tasks_create')): ?>
                    <li>
                        <a href="<?php echo e(route('tasks.copy', $task->id)); ?>" data-toggle="ajaxModal" data-rel="tooltip" title="<?php echo trans('app.'.'copy'); ?>" data-placement="top">
                            <?php echo e(svg_image('solid/copy')); ?> <?php echo trans('app.'.'copy'); ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    
                    
                </ul>
            </div>
            <?php if(can('tasks_delete') || $task->user_id == \Auth::id()): ?>
            <a href="<?php echo e(route('tasks.delete', $task->id)); ?>"
                data-toggle="ajaxModal" title="<?php echo trans('app.'.'delete'); ?>" class="btn btn-sm btn-danger pull-right">
                <?php echo e(svg_image('solid/trash-alt')); ?> <?php echo trans('app.'.'delete'); ?>
            </a>
            <?php endif; ?>
            
        </div>
    </header>
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <div class="panel-body">
                    
                    <aside class="col-lg-4 b-r">
                        <section class="scrollable">
                            <div class="clearfix m-b">
                                <a href="#" class="pull-left thumb m-r">
                                    <img src="<?php echo e($task->user->profile->photo); ?>" class="img-circle" data-toggle="tooltip" data-title="<?php echo e($task->user->name); ?>" data-placement="right">
                                </a>
                                <div class="clear">
                                    <div class="h3 m-t-xs m-b-xs"><?php echo e($task->user->name); ?></div>
                                    
                                    <?php echo e(svg_image('solid/tasks', 'text-muted')); ?> <?php echo e($task->name); ?>

                                    
                                </div>
                            </div>
                            <?php if($task->is_recurring): ?>
                            <div class="alert alert-danger hidden-print">
                                <?php echo e(svg_image('solid/calendar-alt')); ?> This task will recur on <?php echo e(dateFormatted($task->recurring->next_recur_date)); ?>

                            </div>
                            <?php endif; ?>
                            
                            <div class="panel wrapper panel-success">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <a href="#">
                                            <span class="m-b-xs block"><?php echo trans('app.'.'total_time'); ?>  </span>
                                            <small class="text-muted"><?php echo e(secToHours($task->time)); ?></small>
                                        </a>
                                    </div>
                                    <div class="col-xs-6">
                                        <a href="#">
                                            <span class="m-b-xs block"><?php echo trans('app.'.'estimated_hours'); ?>  </span>
                                            <small class="text-muted"><?php echo e($task->estimated_hours); ?> <?php echo trans('app.'.'hours'); ?>  </small>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="progress progress-xs <?php echo e($task->progress != '100' ? 'progress-striped active' : ''); ?> m-t-sm">
                                <div class="progress-bar progress-bar-<?php echo e(get_option('theme_color')); ?>" data-toggle="tooltip" data-original-title="<?php echo e($task->progress); ?>%" style="width: <?php echo e($task->progress); ?>%"></div>
                            </div>
                            <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'hourly_rate'); ?></small>
                            <div class="m-sm text-dark"><?php echo e($task->hourly_rate); ?>/ hr</div>
                            <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'estimated_price'); ?></small>
                            <div class="m-sm text-dark"><?php echo e($task->est_price); ?></div>
                            <div>
                                <?php if($task->milestone_id > 0): ?>
                                <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'milestone'); ?>  </small>
                                <p>
                                    <a href="<?php echo e(route('projects.view', ['id' => $task->project_id, 'tab' => 'milestones', 'item' => $task->milestone_id])); ?>">
                                        <?php echo e(optional($task->AsMilestone)->milestone_name); ?>

                                    </a>
                                </p>
                                <?php endif; ?>
                                <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'start_date'); ?></small>
                                <p>
                                    <label class="label label-success">
                                        <?php echo e(dateFormatted($task->created_at)); ?>

                                    </label>
                                </p>
                                <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'end_date'); ?></small>
                                <p>
                                    <label class="label label-danger">
                                        <?php echo e(dateFormatted($task->due_date)); ?>

                                    </label>
                                </p>

                                <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'created_at'); ?></small>
                                <p>
                                    <label class="label label-default">
                                        <?php echo e(dateFormatted($task->created_at)); ?>

                                    </label>
                                </p>

                                <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'description'); ?>  </small>
                                <?php echo parsedown($task->description); ?>
                                <?php if($project->isTeam() || can('projects_view_team')): ?>
                                <div class="line"></div>
                                <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'team_members'); ?>  </small>
                                
                                
                                <ul class="list-unstyled team-info m-sm">
                                    <?php $__currentLoopData = $task->assignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><img src="<?php echo e($assignee->user->profile->photo); ?>" data-toggle="tooltip" data-title="<?php echo e($assignee->user->name); ?>" data-placement="top"></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                                
                                
                                <?php endif; ?>
                                
                                <?php if($isTeam || isAdmin()): ?>
                                <div class="line"></div>
                                <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'tags'); ?></small>
                                
                                <?php
                                $data['tags'] = $task->tags;
                                ?>
                                <?php echo $__env->make('partial.tags', $data, \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                                
                                <?php endif; ?>
                                <small class="text-uc text-xs text-muted">
                                <?php echo trans('app.'.'vaults'); ?>
                                <a href="<?php echo e(route('extras.vaults.create', ['module' => 'tasks', 'id' => $task->id])); ?>" class="btn btn-xs btn-danger pull-right" data-toggle="ajaxModal"><?php echo e(svg_image('solid/plus')); ?></a>
                                </small>
                                <div class="line"></div>
                                <?php echo app('arrilot.widget')->run('Vaults\Show', ['vaults' => $task->vault]); ?>
                                <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'files'); ?>  </small>
                                <?php echo $__env->make('partial._show_files', ['files' => $task->files, 'limit' => true], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                                
                                
                            </div>
                        </section>
                    </aside>
                    <aside class="col-lg-8">
                        <section class="scrollable">
                            <div id="tabs">
                                <ul class="nav nav-tabs" id="prodTabs">
                                    <li class="active"><a href="#tab_comments"><?php echo trans('app.'.'comments'); ?></a></li>
                                    <li><a href="#tab_todos" data-url="<?php echo e(get_option('site_url')); ?>tasks/ajax/todos/<?php echo e($task->id); ?>"><?php echo trans('app.'.'todo'); ?></a></li>
                                    <li><a href="#tab_timesheets" data-url="<?php echo e(get_option('site_url')); ?>tasks/ajax/timesheets/<?php echo e($task->id); ?>"><?php echo trans('app.'.'timesheets'); ?></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div id="tab_comments" class="tab-pane active">
                                        <section class="comment-list block">
                                            <article class="comment-item" id="comment-form">
                                                <a class="pull-left thumb-sm avatar">
                                                    <img src="<?php echo e(avatar()); ?>" class="img-circle">
                                                </a>
                                                <span class="arrow left"></span>
                                                <section class="comment-body">
                                                    <section class="panel panel-default">
                                                        <?php echo app('arrilot.widget')->run('Comments\CreateWidget', ['commentable_type' => 'tasks' , 'commentable_id' => $task->id, 'hasFiles' => true]); ?>
                                                        
                                                        
                                                    </section>
                                                </section>
                                            </article>
                                            
                                            <?php echo app('arrilot.widget')->run('Comments\ShowComments', ['comments' => $task->comments]); ?>
                                            
                                            
                                            
                                        </section>
                                    </div>
                                    <div id="tab_todos" class="tab-pane active"></div>
                                    <div id="tab_timesheets" class="tab-pane active"></div>
                                </div>
                            </div>
                            
                            
                        </section>
                    </aside>
                    <?php endif; ?>
                    
                </div>
            </section>
        </div>
    </div>
    <?php $__env->startPush('pagescript'); ?>
    <?php echo $__env->make('stacks.js.markdown', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('comments::_ajax.ajaxify', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script>
    $('#tabs').on('click','.tablink,#prodTabs a',function (e) {
    e.preventDefault();
    var url = $(this).attr("data-url");
    if (typeof url !== "undefined") {
        var pane = $(this), href = this.hash;
    $(href).load(url,function(result){
        pane.tab('show');
    });
    } else {
        $(this).tab('show');
    }
    });
    </script>
    <?php $__env->stopPush(); ?>