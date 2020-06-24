<div class="col-md-4">
    <div class="panel-body text-center">
        <div class="content-group-sm user-name">
            <h6 class="text-dark">
            <?php echo e($user->name); ?>

            </h6>
            <span class="display-block"><?php echo e($user->profile->job_title); ?></span>
        </div>
        <a href="#" class="thumb-md display-inline-block content-group-sm">
            <img src="<?php echo e($user->profile->photo); ?>" class="img-circle">
        </a>
        
        <p id="social-buttons" class="m-t-sm">
            <?php if($user->profile->website): ?>
            <a href="<?php echo e($user->profile->website); ?>" class="btn btn-rounded btn-sm btn-icon btn-success" target="_blank">
                <?php echo e(svg_image('solid/link')); ?>
            </a>
            <?php endif; ?>
            <?php if($user->profile->twitter ): ?>
            <a href="https://twitter.com/<?php echo e($user->profile->twitter); ?>" class="btn btn-rounded btn-sm btn-icon btn-info" target="_blank">
                <?php echo e(svg_image('brands/twitter')); ?>
            </a>
            <?php endif; ?>
            <?php if($user->profile->skype): ?>
            <a href="skype:<?php echo e($user->profile->skype); ?>" class="btn btn-rounded btn-sm btn-icon btn-primary">
                <?php echo e(svg_image('brands/skype')); ?>
            </a>
            <?php endif; ?>
            
        </p>
    </div>

    <h3><?php echo trans('app.'.'active'); ?> <?php echo trans('app.'.'notifications'); ?></h3>

    <div class="m-xs">
    <?php $__currentLoopData = $user->profile->channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $channel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <span class="label bg-<?php echo e(array_random(['info','success','danger','warning','primary','dracula'])); ?> tag-m"><?php echo e(svg_image('solid/bell')); ?> <?php echo e($channel); ?></span>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <table class="table table-borderless table-xs content-group-sm">
        <tbody>
            <?php if($user->profile->company > 0): ?>
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'company'); ?></td>
                <td class="text-right">
                    <span class="pull-right">
                        <a href="<?php echo e(route('clients.view', $user->profile->company)); ?>"><?php echo e($user->profile->business->name); ?></a>
                    </span>
                </td>
            </tr>
            <?php endif; ?>
            
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'email'); ?></td>
                <td class="text-right"><?php echo e($user->email); ?></td>
            </tr>
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'username'); ?></td>
                <td class="text-right"><?php echo e($user->username); ?></td>
            </tr>
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'last_login'); ?></td>
                <td class="text-right"><?php echo e(dateTimeFormatted($user->last_login)); ?></td>
            </tr>
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'role'); ?></td>
                <td class="text-right"><?php echo e($user->roles->pluck('name')); ?></td>
            </tr>
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'department'); ?></td>
                <td class="text-right"><?php echo e($user->departments->pluck('department.deptname')); ?></td>
            </tr>
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'mobile'); ?></td>
                <td class="text-right"><?php echo e($user->profile->mobile); ?></td>
            </tr>
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'phone'); ?>   #</td>
                <td class="text-right"><?php echo e($user->profile->phone); ?></td>
            </tr>
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'locale'); ?> </td>
                <td class="text-right"><?php echo e(ucfirst($user->locale)); ?></td>
            </tr>
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'hourly_rate'); ?> </td>
                <td class="text-right"><?php echo e($user->profile->hourly_rate); ?>/hr</td>
            </tr>
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'city'); ?></td>
                <td class="text-right"><?php echo e($user->profile->city); ?></td>
            </tr>
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'country'); ?></td>
                <td class="text-right"><a href="#"><?php echo e($user->profile->country); ?></a></td>
            </tr>
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'locale'); ?></td>
                <td class="text-right"><a href="#"><?php echo e($user->profile->locale); ?></a></td>
            </tr>
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'address'); ?></td>
                <td class="text-right"><?php echo e($user->profile->address); ?></td>
            </tr>
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'verified_at'); ?> </td>
                <td class="text-right"><?php echo e($user->email_verified_at); ?></td>
            </tr>
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'created_at'); ?> </td>
                <td class="text-right"><?php echo e(dateTimeFormatted($user->created_at)); ?></td>
            </tr>
            <tr>
                <td class="text-muted"><?php echo trans('app.'.'updated'); ?></td>
                <td class="text-right"><?php echo e(dateTimeFormatted($user->updated_at)); ?></td>
            </tr>

        </tbody>
    </table>

    

    <small class="text-uc text-xs text-muted">
    <?php echo trans('app.'.'vaults'); ?>
    <a href="<?php echo e(route('extras.vaults.create', ['module' => 'users', 'id' => $user->id])); ?>" class="btn btn-xs btn-danger pull-right" data-toggle="ajaxModal"><?php echo e(svg_image('solid/plus')); ?></a>
    </small>
    <div class="line"></div>
    <?php echo app('arrilot.widget')->run('Vaults\Show', ['vaults' => $user->vault]); ?>
</div>
<div class="col-md-8">
    <section class="slim-scroll" data-color="#333333" data-disable-fade-out="true" data-distance="0" data-height="700" data-size="5px">
        
        <section class="panel panel-default">
        <header class="panel-heading"><?php echo trans('app.'.'activities'); ?>  </header>
        
        <?php echo app('arrilot.widget')->run('Activities\Feed', ['activities' => $user->activities->take(100)]); ?>
    </section>
</section>

</div>