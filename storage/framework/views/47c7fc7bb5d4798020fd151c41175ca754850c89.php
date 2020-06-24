<header class="bg-<?php echo e(get_option('top_bar_color')); ?> header navbar navbar-fixed-top-xs nav-z">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen" data-target="#nav">
                <?php echo e(svg_image('solid/bars')); ?>
            </a>
            <a href="<?php echo e(url('/')); ?>" class="navbar-brand">
                <?php $display = get_option('logo_or_icon'); ?>
                <?php if($display == 'logo' || $display == 'logo_title'): ?>
                <img src="<?php echo e(get_option('site_url')); ?><?php echo e(getStorageUrl(config('system.media_dir').'/'.get_option('company_logo'))); ?>" class="m-r-sm">
                <?php elseif($display == 'icon' || $display == 'icon_title'): ?>
                <i class="fa <?php echo e(get_option('site_icon')); ?>"></i>
                <?php endif; ?>
                <?php if($display == 'logo_title' || $display == 'icon_title'): ?>
                <?php if(get_option('website_name') == ''): ?>
                <?php echo e(get_option('company_name')); ?>

                <?php else: ?>
                <?php echo e(get_option('website_name')); ?>

                <?php endif; ?>
                <?php endif; ?>
            </a>
            <a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user">
                <?php echo e(svg_image('solid/cog')); ?>
            </a>
        </div>
        <ul class="nav navbar-nav hidden-xs" id="todolist">
            <li class="">
                
                <div class="m-t m-l-lg">
                    <a href="<?php echo e(route('calendar.todos')); ?>" class="" data-rel="tooltip" title="<?php echo trans('app.'.'todo'); ?>" data-placement="bottom">
                        <?php echo e(svg_image('solid/tasks')); ?>
                        <span class="badge badge-sm up bg-danger m-l-n-sm display-inline"><?php echo e(\Auth::user()->todoToday()); ?></span>
                    </a>
                    <?php if(Auth::user()->newchats->count()): ?>
                    <a href="<?php echo e(route('leads.index')); ?>" class="m-l" data-rel="tooltip" title="WhatsApp" data-placement="bottom">
                        <?php echo e(svg_image('brands/whatsapp', 'fa-lg text-success')); ?>
                        <span class="badge badge-sm up bg-dracula m-l-n-sm display-inline"><?php echo e(Auth::user()->newchats->count()); ?></span>
                    </a>
                    <?php endif; ?>

                    <a href="<?php echo e(route('calendar.appointments')); ?>" class="m-l" data-rel="tooltip" title="<?php echo trans('app.'.'appointments'); ?> " data-placement="bottom">
                        <?php echo e(svg_image('solid/calendar-check')); ?>
                    </a> 
                    
                </div>
                
            </li>
        </ul>


        <ul class="nav navbar-nav navbar-right hidden-xs nav-user">

            <?php if(count(runningTimers()) > 0): ?>
            <li class="">
                <a href="<?php echo e(route('timetracking.timers')); ?>" title="<?php echo trans('app.'.'timers'); ?>" data-toggle="ajaxModal" data-rel="tooltip" data-placement="bottom">  
                    <?php echo e(svg_image('solid/clock', 'fa-spin fa-lg text-warning')); ?>
                    <span class="badge badge-sm up bg-info m-l-n-sm display-inline"><?php echo e(count(runningTimers())); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php echo $__env->make('partial.notifications', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php if (\Illuminate\Support\Facades\Blade::check('admin')): ?>
            <li class="dropdown hidden-xs">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <?php echo e(svg_image('solid/search', 'fa-fw')); ?>
                </a>
                <section class="dropdown-menu aside-xl animated fadeInUp">
                    <section class="panel bg-white">
                        <form action="<?php echo e(route('search.app')); ?>" method="POST" role="search">
                            <?php echo csrf_field(); ?>

                            <div class="form-group wrapper m-b-none">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="keyword" placeholder="Type tag keyword">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-info btn-icon"><i class="fas fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </section>
                </section>
            </li>
            <?php endif; ?>


            
            
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="thumb-sm avatar pull-left">
                        <img src="<?php echo e(avatar()); ?>" class="img-circle">
                    </span>
                    <?php echo e(Auth::user()->name); ?> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu animated fadeInRight">
                    <li class="arrow top"></li>
                    <li><a href="<?php echo e(route('users.profile')); ?>"><?php echo trans('app.'.'settings'); ?>  </a></li>
                    <li><a href="<?php echo e(route('users.notifications')); ?>"><?php echo trans('app.'.'notifications'); ?> </a></li>
                   
                    <?php /*?>@if(config('system.remote_support') && isAdmin())
                    <li><a href="{{ route('support.ticket') }}" data-toggle="ajaxModal">Need Help?</a></li>
                    @endif<?php */?>
                    <li class="divider"></li>
                   
                    <li>
                        <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            <?php echo e(svg_image('solid/sign-out-alt')); ?> Logout
                        </a>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="display-none">
                            <?php echo e(csrf_field()); ?>

                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</header>