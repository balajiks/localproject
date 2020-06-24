<?php if(isAdmin() || can('projects_view_notes')): ?>
    <section class="panel panel-default">
        <header class="panel-heading"><?php echo e(svg_image('solid/pencil-alt')); ?> <?php echo trans('app.'.'notes'); ?></header>
        <?php echo Form::open(['route' => 'notes.project', 'class' => 'ajaxifyForm']); ?>


        <input type="hidden" name="project_id" value="<?php echo e($project->id); ?>">
        <aside>
            <textarea name="notes" class="form-control markdownEditor"><?php echo e($project->notes); ?></textarea>


        </aside>

        <hr>
        <div class="m-xs">
            <?php echo renderAjaxButton(); ?>

        </div>
    

    <?php echo Form::close(); ?>


    </section>
    
    <?php $__env->startPush('pagescript'); ?>
    <?php echo $__env->make('stacks.js.markdown', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>

<?php endif; ?>

