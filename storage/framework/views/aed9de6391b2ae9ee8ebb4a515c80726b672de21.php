<section class="col-md-12">

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('estimates_create')): ?>
    <header class="panel-heading">
        <a href="<?php echo e(route('estimates.create', ['client' => $company->id])); ?>" class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?>">
            <?php echo e(svg_image('solid/file-alt')); ?> <?php echo trans('app.'.'create'); ?>  
        </a>
    </header>
    <?php endif; ?>

    <div id="ajaxData"></div>
    
</section>
<?php $__env->startPush('pagescript'); ?>
<?php echo $__env->make('clients::_scripts._estimates', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>