<div class="row">
    <div class="col-lg-12">
        <section class="m-xs">

            <div id="ajaxData"></div>




        </section>
    </div>
</div>

<?php $__env->startPush('pagescript'); ?>
  <?php echo $__env->make('projects::_scripts._invoices', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>
