<section class="col-md-12">
	<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('invoices_create')): ?>
	<header class="panel-heading">
		<a href="<?php echo e(route('invoices.create', ['client' => $company->id])); ?>" class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?>">
			<?php echo e(svg_image('solid/file-invoice-dollar')); ?> <?php echo trans('app.'.'create'); ?>  
		</a>
	</header>
	<?php endif; ?>
	<div id="ajaxData"></div>
	
</section>

<?php $__env->startPush('pagestyle'); ?>
	<?php echo $__env->make('stacks.css.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('pagescript'); ?>
<?php echo $__env->make('clients::_scripts._invoices', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('stacks.js.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>