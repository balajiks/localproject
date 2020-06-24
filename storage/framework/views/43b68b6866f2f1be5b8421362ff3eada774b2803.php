<?php $__env->startSection('content'); ?>
<section id="content">
    <section class="hbox stretch">
        <aside class="b-l">
            <section class="vbox">
                <header class="header bg-white b-b clearfix">
                    <div class="row m-t-sm">
                        <div class="col-sm-6">
                            <h4 class="m-t-sm pull-left"><?php echo e($project->name); ?>

                            <?php if($project->rating): ?>
                            <?php echo $__env->make('partial.five_star', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                            <?php endif; ?>
                            <?php if($project->isOverdue()): ?>
                            <span class="label label-danger small"><?php echo trans('app.'.'overdue'); ?></span>
                            <?php endif; ?>
                            </h4>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-animated pull-right">
                                <button type="button" class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?> dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <?php echo e(svg_image('solid/ellipsis-h')); ?>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if($project->client_id > 0): ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('invoices_create')): ?>
                                    <li>
                                        <a href="<?php echo e(route('projects.invoice', ['id' => $project->id])); ?>" data-toggle="ajaxModal" data-rel="tooltip" title="<?php echo trans('app.'.'invoice'); ?>">
                                            <?php echo e(svg_image('solid/file-invoice-dollar')); ?> <?php echo trans('app.'.'invoice'); ?>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reminders_create')): ?>
                                    <li>
                                        <a href="<?php echo e(route('calendar.reminder', ['module' => 'projects', 'id' => $project->id])); ?>" data-toggle="ajaxModal" data-rel="tooltip"  data-placement="bottom" title="<?php echo trans('app.'.'set_reminder'); ?>  ">
                                            <?php echo e(svg_image('solid/clock')); ?> <?php echo trans('app.'.'reminder'); ?>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('projects_copy')): ?>
                                    <li>
                                        <a href="<?php echo e(route('projects.copy', ['id' => $project->id])); ?>" data-toggle="ajaxModal" data-rel="tooltip" title="<?php echo trans('app.'.'copy'); ?>">
                                            <?php echo e(svg_image('solid/copy')); ?> <?php echo trans('app.'.'copy'); ?>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('projects_update')): ?>
                                    <li>
                                        <a href="<?php echo e(route('projects.edit', ['id' => $project->id])); ?>" data-rel="tooltip" title="<?php echo trans('app.'.'make_changes'); ?>">
                                            <?php echo e(svg_image('solid/pencil-alt')); ?> <?php echo trans('app.'.'edit'); ?>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <?php if(isAdmin() || $project->setting('show_project_links') || $project->isTeam()): ?>
                                    <li>
                                        <a href="<?php echo e(route('projects.view', ['id' => $project->id, 'tab' => 'links'])); ?>">
                                            <?php echo e(svg_image('solid/link')); ?> <?php echo trans('app.'.'links'); ?>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <?php if($project->client_id > 0 && (isAdmin() || can('projects_download'))): ?>
                                    <li>
                                        <a href="<?php echo e(route('projects.export', ['id' => $project->id])); ?>">
                                            <?php echo e(svg_image('regular/file-pdf')); ?> PDF
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('admin')): ?>
                                    <?php if(optional($project->company)->primary_contact): ?>
                                    <li>
                                        <a href="<?php echo e(route('users.impersonate', ['id' => $project->company->contact->id ])); ?>">
                                            <?php echo e(svg_image('solid/user-secret')); ?> As Client
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <?php if($project->auto_progress && $project->manager == \Auth::id()): ?>
                                    <li>
                                        <a data-toggle="ajaxModal" data-rel="tooltip" title="<?php echo trans('app.'.'mark_as_complete'); ?>"
                                            href="<?php echo e(route('projects.done', ['id' => $project->id])); ?>">
                                            <?php echo e(svg_image('solid/check-square')); ?> <?php echo trans('app.'.'done'); ?>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('projects_delete')): ?>
                                    <li>
                                        <a href="<?php echo e(route('projects.delete', ['id' => $project->id])); ?>"
                                            data-toggle="ajaxModal" data-rel="tooltip" title="<?php echo trans('app.'.'delete'); ?>">
                                            <?php echo e(svg_image('solid/trash-alt')); ?> <?php echo trans('app.'.'delete'); ?>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <?php if($project->isTeam() || isAdmin()): ?>
                            <a href="<?php echo e(route('timetracking.create', ['module' => 'projects', 'id' => $project->id])); ?>"
                                data-toggle="ajaxModal" class="btn btn-<?php echo e(get_option('theme_color')); ?> btn-sm pull-right">
                                <?php echo e(svg_image('solid/history')); ?> <?php echo trans('app.'.'time_entry'); ?>
                            </a>
                            <?php endif; ?>

                            
                            
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('projects_update')): ?>
                            <?php
                            $txt = $project->auto_progress ? 'auto_progress_off' : 'auto_progress_on';
                            ?>
                            <a href="<?php echo e(route('projects.autoprogress', ['id' => $project->id])); ?>" data-rel="tooltip" title="<?php echo trans('app.'.$txt); ?>" data-placement="bottom"  class="btn btn-sm btn-default pull-right">
                                <?php echo e(svg_image('solid/plane')); ?>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('timer_start')): ?>
                            
                            <?php if($project->timer_on): ?>
                            <a data-toggle="tooltip" data-original-title="<?php echo trans('app.'.'stop_timer'); ?>"
                                data-placement="bottom" href="<?php echo e(route('clock.stop', ['id' => $project->id, 'module' => 'projects'])); ?>" class="btn btn-default btn-sm pull-right">
                                <?php echo e(svg_image('solid/clock', 'fa-spin text-danger')); ?>
                            </a>
                            <?php else: ?>
                            <a data-toggle="tooltip" data-original-title="<?php echo trans('app.'.'start_timer'); ?>"
                                data-placement="bottom" href="<?php echo e(route('clock.start', ['id' => $project->id, 'module' => 'projects'])); ?>"
                                class="btn btn-sm btn-default pull-right"> <?php echo e(svg_image('solid/stopwatch', 'text-success')); ?>
                            </a>
                            <?php endif; ?>
                            <?php endif; ?>
                            <a data-rel="tooltip" title="<?php echo trans('app.'.'pin_sidebar'); ?>" data-placement="bottom" href="<?php echo e(route('users.pin', ['id' => $project->id, 'module' => 'projects'])); ?>"
                                class="btn btn-sm btn-default pull-right"> <?php echo e(svg_image('solid/bookmark')); ?>
                            </a>

                            <?php if($project->is_template && isAdmin()): ?>
                            <a href="<?php echo e(route('projects.fromtemplate', $project->id)); ?>" data-toggle="ajaxModal" title="<?php echo trans('app.'.'create_from_template'); ?>" data-rel="tooltip" class="btn btn-<?php echo e(get_option('theme_color')); ?> btn-sm pull-right">
                                <?php echo e(svg_image('solid/recycle')); ?> <?php echo trans('app.'.'use_template'); ?>
                            </a>
                            <?php endif; ?>

                        </div>
                    </div>
                </header>
                
                <section class="scrollable wrapper bg scrollpane">
                    <div class="sub-tab m-b-10">
                        <ul class="nav pro-nav-tabs nav-tabs-dashed">
                            <?php $__currentLoopData = projectMenu(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $perm = true; ?>
                            <?php if($menu->permission != ''): ?>
                            <?php $perm = $project->setting($menu->permission); ?>
                            <?php endif; ?>
                            <?php if($perm): ?>
                            <?php $timer_on = 0; ?>
                            <?php if($menu->module == 'project_timesheets'): ?>
                            <?php
                            $timer_on = $project->timesheets()->running()->count();
                            ?>
                            <?php endif; ?>
                            <?php endif; ?>
                            <li class="<?php echo e($tab == $menu->route ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('projects.view', ['id' => $project->id, 'tab' => $menu->route])); ?>">
                                    <?php echo trans('app.'.$menu->route); ?>
                                    <?php if($timer_on > 0): ?>
                                    <span class="m-r-xs"><?php echo e(svg_image('solid/sync-alt', 'fa-spin text-danger')); ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    
                    <?php echo $__env->make('projects::tab._'.$tab, \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </section>
            </section>
        </aside>
    </section>
    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen, open" data-target="#nav,html"></a>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>