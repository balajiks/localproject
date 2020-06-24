<div class="">
    <?php if($project->client_id <= 0): ?>
    <div class="alert alert-danger">
        <button class="close" data-dismiss="alert" type="button">×</button>
        <?php echo e(svg_image('solid/info-circle')); ?> <?php echo e(__('No Client attached to this project.')); ?>

    </div>
    <?php endif; ?>
    
    <div>
        <strong>
        <?php echo trans('app.'.'progress'); ?>
        </strong>
        <div class="pull-right">
            <strong class="<?php echo e(($project->progress < 100) ? 'text-danger' : 'text-success'); ?>">
            <?php echo e($project->progress); ?>%
            </strong>
        </div>
    </div>
    <div class="progress-xxs mb-0 <?php echo e(($project->progress != '100') ? 'progress-striped active' : ''); ?> inline-block progress">
        <div class="progress-bar progress-bar-<?php echo e(get_option('theme_color')); ?> " data-original-title="<?php echo e($project->progress); ?>%" data-toggle="tooltip" style="width: <?php echo e($project->progress); ?>%">
        </div>
    </div>

    <?php echo app('arrilot.async-widget')->run('Projects.TimerChart', ['project' => $project->id]); ?>

    
    <div class="row m-t-sm">
        <div class="col-lg-4 b-r">
            <div class="m-t m-b">
                <div class="line"></div>
<small class="text-uc text-xs text-muted"><?php echo trans('app.'.'name'); ?></small>
<div class="m-xs"><?php echo e($project->name); ?></div>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('projects_view_clients')): ?>
                <?php if($project->client_id > 0): ?>
            <div class="line"></div>
            <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'client'); ?></small>
            <div class="m-xs">
                <a href="<?php echo e(route('clients.view', ['id' => $project->client_id])); ?>"><?php echo e($project->company->name); ?></a>
            </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if($project->deal_id > 0): ?>
            <div class="line"></div>
            <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'deal'); ?></small>
            <div class="m-xs">
                <a href="<?php echo e(route('deals.view', $project->deal_id)); ?>"><?php echo e($project->deal->name); ?></a>
            </div>
                
            <?php endif; ?>

            <?php if($project->contract_id > 0): ?>
            <div class="line"></div>
            <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'contract'); ?></small>
            <div class="m-xs">
                <a href="<?php echo e(route('contracts.view', ['id' => $project->contract_id])); ?>"><?php echo e($project->contract->contract_title); ?></a>
            </div>
                
            <?php endif; ?>

            <div class="line"></div>
            <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'information'); ?></small>
            <div class="m-xs">
                <span class="text-muted"><?php echo trans('app.'.'start_date'); ?>: </span>
                <span class=""><?php echo e(dateTimeFormatted($project->start_date)); ?></span>
            </div>
            
            <div class="m-xs">
                <span class="text-muted"><?php echo trans('app.'.'due_date'); ?>: </span>
                <?php if(!empty($project->due_date)): ?>

                <span class=""><?php echo e(dateTimeFormatted($project->due_date)); ?>

                <?php if(time() > strtotime($project->due_date) && $project->progress < 100): ?>
                    <span class="badge bg-danger">
                        <?php echo trans('app.'.'overdue'); ?>
                    </span>
                    <?php endif; ?>
                </span>
                <?php else: ?>
                    <?php echo trans('app.'.'ongoing'); ?>
                <?php endif; ?>
            </div>

            <?php if($project->progress < 100): ?>
            <div class="m-xs">
                <span class="text-muted"><?php echo trans('app.'.'deadline'); ?>: </span>
                <span class=""><?php echo e((time() > strtotime($project->due_date)) ? '- ' : ''); ?> <?php echo e(dueInDays($project->due_date)); ?> <?php echo trans('app.'.'days'); ?></span>
            </div>
            <?php endif; ?>

            <div class="m-xs">
                <span class="text-muted"><?php echo trans('app.'.'status'); ?>: </span>
                <span class=""><?php echo e($project->status); ?></span>
            </div>


            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('projects_view_cost')): ?>
            <div class="line"></div>
            <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'cost'); ?></small>
            <div class="m-xs">
                <span class="text-muted"><?php echo trans('app.'.'estimated_hours'); ?>: </span>
                <span class=""><?php echo e($project->estimate_hours); ?> <small><?php echo trans('app.'.'hours'); ?></small></span>
            </div>

            <div class="m-xs">
                <span class="text-muted"><?php echo trans('app.'.'hourly_rate'); ?>: </span>
                <span class=""><?php echo e($project->hourly_rate); ?><small>/hr</small></span>
            </div>

            <div class="m-xs">
                <span class="text-muted"><?php echo trans('app.'.'estimated_price'); ?>: </span>
                <span class=""><?php echo e(formatCurrency($project->currency, $project->estimate_hours *  $project->hourly_rate)); ?></span>
            </div>

            <div class="m-xs">
                <span class="text-muted"><?php echo trans('app.'.'used_budget'); ?>: </span>
                <span class="badge bg-<?php echo e(($project->used_budget > 100) ? 'danger' : 'success'); ?>">
                    <?php echo e($project->used_budget); ?>%
                </span>
            </div>

            <?php endif; ?>

          
                
    
            <div class="line"></div>
            <?php if($project->isTeam() || isAdmin() || can('projects_view_team')): ?>
            <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'team_members'); ?></small>

                <ul class="media-list m-t-md">
                    <?php $__currentLoopData = $project->assignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="media">
                        <span class="pull-right text-muted">
                            <?php echo e(svg_image('regular/clock', 'text-primary')); ?> <?php echo e($member->user->profile->hourly_rate); ?>/ hr

                            <?php if(isAdmin() || Auth::id() == $project->manager): ?>
                            <a href="<?php echo e(route('teams.manager', ['project_id' => $project->id, 'member_id' => $member->user->id])); ?>" class="m-r-xs" data-rel="tooltip" title="Modify Project Manager"><?php echo e(svg_image('solid/user-tie', $member->user->id == $project->manager ? 'text-danger' : 'text-muted')); ?></a>
                            <?php endif; ?>

                            <?php if(isAdmin() || Auth::id() == $project->manager): ?>
                            <a href="<?php echo e(route('teams.remove', ['project_id' => $project->id, 'member_id' => $member->user->id])); ?>" data-toggle="ajaxModal"><?php echo e(svg_image('regular/trash-alt')); ?></a>
                            <?php endif; ?>

                        </span>
                        <span class="pull-left thumb-xs m-r-xs">
                            <a href="#">
                                <img alt="" class="img-sm img-circle" src="<?php echo e($member->user->profile->photo); ?>">
                            </a>
                        </span>
                        <div class="media-body media-middle m-l-xs">
                            <?php echo e($member->user->name); ?>

                            <div class="media-annotation">
                                <?php echo e($member->user->profile->job_title); ?>

                            </div>
                        </div>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>

        <?php endif; ?>

<small class="text-uc text-xs text-muted">
    <?php echo trans('app.'.'vaults'); ?>
    <a href="<?php echo e(route('extras.vaults.create', ['module' => 'projects', 'id' => $project->id])); ?>" class="btn btn-xs btn-danger pull-right" data-toggle="ajaxModal"><?php echo e(svg_image('solid/plus')); ?></a>
</small>
<div class="line"></div>
<?php echo app('arrilot.widget')->run('Vaults\Show', ['vaults' => $project->vault]); ?>

 <?php if($project->isTeam() || isAdmin()): ?>
                <div class="line"></div>
                <small class="text-uc text-xs text-muted"><?php echo trans('app.'.'tags'); ?></small>
                    <?php
                    $data['tags'] = $project->tags;
                    ?>
                    <?php echo $__env->make('partial.tags', $data, \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php endif; ?>


                <h4 class="font-thin"><?php echo trans('app.'.'description'); ?></h4>
                <div class="line"></div>
                <div class="m-t-sm with-responsive-img">
                    <?php echo parsedown($project->description); ?>
                </div>
               
            </div>
        </div>
        <div class="col-lg-8">
            <div class="row">

                <?php echo app('arrilot.widget')->run('Projects.BudgetChart', ['project' => $project]); ?>
                <?php echo app('arrilot.widget')->run('Projects.TaskChart', ['project' => $project]); ?>
               
                
            </div>
            <section class="slim-scroll" data-color="#333333" data-disable-fade-out="true" data-distance="0" data-height="600" data-size="5px">
                
                    <section class="panel panel-default">
            <header class="panel-heading"><?php echo trans('app.'.'activities'); ?>  </header>
                
                 <?php echo app('arrilot.widget')->run('Activities\Feed', ['activities' => $project->activities]); ?>

        </section>
            </section>
        </div>
    </div>
</div>

<?php $__env->startPush('pagescript'); ?>
    <script>
$(function () {
    $('.deleteConfirm').click(function (e) {
        e.preventDefault();
        if (window.confirm("Are you sure?")) {
            location.href = this.href;
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
