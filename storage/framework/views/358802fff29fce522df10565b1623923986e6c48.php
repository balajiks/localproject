<?php if(($project->setting('show_project_files') && $project->isClient()) || isAdmin() || $project->isTeam()): ?>
<section class="scrollable">

     
<header class="header clearfix">
                    <div class="row m-t-sm">
                        <div class="col-sm-12 m-b-xs">

                                <a href="<?php echo e(route('files.upload', ['module' => 'projects', 'id' => $project->id])); ?>"
                                   data-toggle="ajaxModal"
                                   class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?>">
                                   <?php echo e(svg_image('solid/cloud-upload-alt')); ?> <?php echo trans('app.'.'upload_file'); ?>  
                                </a>


                        </div>
                    </div>
                </header>

                <?php echo $__env->make('partial._show_files', ['files' => $project->files], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>


                                




        
        
        
</section>

<?php if(settingEnabled('filestack_active')): ?>
    <?php $__env->startPush('pagescript'); ?>
    <script src="https://static.filestackapi.com/v3/filestack.js"></script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>

<?php endif; ?>