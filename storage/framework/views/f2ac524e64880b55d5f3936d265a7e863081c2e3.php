<?php $__env->startSection('content'); ?>
<section id="content" class="bg">
    <section class="vbox">
        <header class="header bg-white b-b clearfix">
            <div class="row m-t-sm">
                <div class="col-sm-12 m-b-xs">
                    <p class="h3"><?php echo e($indexing->name); ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('indexings_delete')): ?>
                        <a href="<?php echo e(route('indexings.delete', ['id' => $indexing->id])); ?>"
                            class="btn btn-danger btn-sm pull-right" data-toggle="ajaxModal" data-rel="tooltip"
                        title="<?php echo trans('app.'.'delete'); ?>  "><?php echo e(svg_image('solid/trash-alt')); ?></a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reminders_create')): ?>
                        <a class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?> pull-right" data-toggle="ajaxModal" data-rel="tooltip"  data-placement="bottom" href="<?php echo e(route('calendar.reminder', ['module' => 'indexings', 'id' => $indexing->id])); ?>" title="<?php echo trans('app.'.'set_reminder'); ?>  ">
                            <?php echo e(svg_image('solid/clock')); ?>
                        </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('indexings_update')): ?>
                        <a href="<?php echo e(route('indexings.edit', ['id' => $indexing->id])); ?>"
                            class="btn btn-<?php echo e(get_option('theme_color')); ?> btn-sm pull-right" data-rel="tooltip"
                            data-toggle="ajaxModal" title="<?php echo trans('app.'.'edit'); ?>  " data-placement="left">
                        <?php echo e(svg_image('solid/pencil-alt')); ?> <?php echo trans('app.'.'edit'); ?>  </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('deals_create')): ?>
                        <a href="<?php echo e(route('indexings.convert', ['id' => $indexing->id])); ?>"
                            class="btn btn-<?php echo e(get_option('theme_color')); ?> btn-sm pull-right" data-rel="tooltip"
                            data-toggle="ajaxModal" title="<?php echo trans('app.'.'convert'); ?>  " data-placement="left">
                            <?php echo e(svg_image('solid/check-circle')); ?> <?php echo trans('app.'.'convert'); ?>
                        </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('indexings_update')): ?>
                        <a href="<?php echo e(route('indexings.nextstage', ['id' => $indexing->id])); ?>"
                            class="btn btn-<?php echo e(get_option('theme_color')); ?> btn-sm pull-right" data-rel="tooltip"
                            data-toggle="ajaxModal" title="<?php echo trans('app.'.'move_stage'); ?>  " data-placement="left">
                        <?php echo e(svg_image('solid/arrow-alt-circle-right')); ?> <?php echo trans('app.'.'next_stage'); ?>  </a>
                        <?php endif; ?>
                        
                    </p>
                </div>
            </div>
        </header>
        <section class="scrollable wrapper">
            
            <?php /*?><div class="sub-tab m-b-10">
                <ul class="nav pro-nav-tabs nav-tabs-dashed">
                    <li class="{{  ($tab == 'overview') ? 'active' : '' }}">
                        <a href="{{  route('indexings.view', ['id' => $indexing->id, 'tab' => 'overview'])  }}">@icon('solid/home') @langapp('overview')  </a>
                    </li>

                    <li class="{{  ($tab == 'calls') ? 'active' : ''  }}">
                                <a href="{{  route('indexings.view', ['id' => $indexing->id, 'tab' => 'calls'])  }}">@icon('solid/phone') @langapp('calls')  
                                </a>
                            </li>

                    <li class="{{  ($tab == 'conversations') ? 'active' : ''  }}">
                        <a href="{{  route('indexings.view', ['id' => $indexing->id, 'tab' => 'conversations'])  }}">
                            @icon('solid/envelope-open') @langapp('emails')
                            {!! $indexing->has_email ? '<i class="fas fa-bell text-danger"></i>' : '' !!}
                        </a>
                    </li>
                    <li class="{{  ($tab == 'activity') ? 'active' : ''  }}">
                        <a href="{{  route('indexings.view', ['id' => $indexing->id, 'tab' => 'activity'])  }}">
                            @icon('solid/history') @langapp('activity')
                            {!! $indexing->has_activity ? '<i class="fas fa-bell text-danger"></i>' : '' !!}
                        </a>
                    </li>
                    <li class="{{  ($tab == 'files') ? 'active' : ''  }}">
                        <a href="{{  route('indexings.view', ['id' => $indexing->id, 'tab' => 'files'])  }}">
                        @icon('solid/file-alt') @langapp('files')  </a>
                    </li>
                    <li class="{{  ($tab == 'comments') ? 'active' : ''  }}">
                        <a href="{{  route('indexings.view', ['id' => $indexing->id, 'tab' => 'comments'])  }}">
                            @icon('solid/comments') @langapp('comments')
                        </a>
                    </li>
                    <li class="{{  ($tab == 'calendar') ? 'active' : ''  }}">
                        <a href="{{  route('indexings.view', ['id' => $indexing->id, 'tab' => 'calendar'])  }}">
                            @icon('solid/calendar-alt') @langapp('calendar')
                        </a>
                    </li>
                    <li class="{{  ($tab == 'whatsapp') ? 'active' : ''  }}">
                        <a href="{{  route('indexings.view', ['id' => $indexing->id, 'tab' => 'whatsapp'])  }}">
                            @icon('brands/whatsapp','text-success') Whatsapp
                        </a>
                    </li>
                </ul>
            </div><?php */?>
            
        </section>
    </section>
</section>
<?php $__env->startPush('pagescript'); ?>
<?php echo $__env->make('stacks.js.markdown', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>