<div class="row">
    <div class="col-lg-12">
        <section class="m-xs">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expenses_create')): ?>
            <header class="panel-heading">
            <a href="<?php echo e(route('expenses.create', ['project' => $project->id])); ?>" class="btn btn-dark btn-sm" data-toggle="ajaxModal">
                    <?php echo e(svg_image('solid/plus')); ?> <?php echo trans('app.'.'create'); ?>  
            </a>
            </header>
        <?php endif; ?>


        <div id="ajaxData"></div>



            
        </section>
    </div>
</div>

<?php $__env->startPush('pagescript'); ?>
  <?php echo $__env->make('projects::_scripts._expenses', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>
