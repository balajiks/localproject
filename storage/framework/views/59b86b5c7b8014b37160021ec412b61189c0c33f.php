<div class="col-md-12">
    <header class="panel-heading">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('files_create')): ?>
        <a href="<?php echo e(route('files.upload', ['module' => 'clients', 'id' => $company->id])); ?>"
            class="btn btn-<?php echo e(get_option('theme_color')); ?> btn-sm" data-toggle="ajaxModal"
            data-placement="left" title="<?php echo trans('app.'.'upload_file'); ?>  ">
        <?php echo e(svg_image('solid/cloud-upload-alt')); ?> <?php echo trans('app.'.'upload_file'); ?>  </a>
        <?php endif; ?>
        
    </header>
    
    
    <?php echo $__env->make('partial._show_files', ['files' => $company->files], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php if(settingEnabled('filestack_active')): ?>
        <?php $__env->startPush('pagescript'); ?>
            <script src="https://static.filestackapi.com/v3/filestack.js"></script>
        <?php $__env->stopPush(); ?>
    <?php endif; ?>
</div>