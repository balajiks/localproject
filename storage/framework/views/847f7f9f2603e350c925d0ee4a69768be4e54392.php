<?php $__env->startSection('content'); ?>
<section id="content">
    <section class="hbox stretch">

            <section class="vbox">
                <header class="header bg-white b-b b-light">
                    
                    <?php if(isAdmin() || can('events_create')): ?>
                    <a href="<?php echo e(route('calendar.create', ['module' => 'events'])); ?>" data-toggle="ajaxModal"
                        class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?>">
                    <?php echo e(svg_image('solid/calendar-plus')); ?> <?php echo trans('app.'.'add_event'); ?>  </a>
                    
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('calendar.ical')); ?>" data-toggle="ajaxModal" data-rel="tooltip" title="<?php echo trans('app.'.'download'); ?> " data-placement="bottom" class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?> pull-right">
                    <?php echo e(svg_image('solid/calendar-alt')); ?> iCal</a>
                    
                    
                </header>
                <section class="scrollable wrapper bg overflow-x-auto">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="overflow-hidden">
                                            <div id="todo-lists-basic-demo"
                                                class="lobilists-wrapper lobilists single-line sortable ps-container ps-theme-dark ps-active-x">

                                                <?php echo app('arrilot.widget')->run('Todos\Today'); ?>
                                                <?php echo app('arrilot.widget')->run('Todos\Tomorrow'); ?>
                                                <?php echo app('arrilot.widget')->run('Todos\ThisWeek'); ?>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </section>
            </section>

    </section>
    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen, open" data-target="#nav,html"></a>
</section>


<?php echo $__env->make('todos::_ajax.todojs', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>