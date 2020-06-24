<section class="m-sm">
    <div id="ajaxData"></div>
    
</section>
<?php $__env->startPush('pagescript'); ?>
<?php echo $__env->make('clients::_scripts._subscriptions', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>