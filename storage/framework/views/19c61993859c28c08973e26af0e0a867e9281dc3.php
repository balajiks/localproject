<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-striped" id="projects-table">
            <thead>
                <tr>
                    <th class="hide"></th>
                    <th><?php echo trans('app.'.'title'); ?> </th>
                    <th class="col-date"><?php echo trans('app.'.'start_date'); ?> </th>
                    <th class="col-date"><?php echo trans('app.'.'due_date'); ?> </th>
                    <th><?php echo trans('app.'.'sub_total'); ?> </th>
                    <th><?php echo trans('app.'.'expenses'); ?> </th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $user->assignments->where('assignable_type', Modules\Projects\Entities\Project::class); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="display-none"><?php echo e($project->assignable->id); ?></td>
                    <td>
                        <a href="<?php echo e(route('projects.view', ['id' => $project->assignable->id])); ?>" class="text-ellipsis">
                            <?php echo e($project->assignable->name); ?>

                        </a>
                    </td>
                    <td class=""><?php echo e(dateFormatted($project->assignable->start_date)); ?></td>
                    <td class=""><?php echo e(dateFormatted($project->assignable->due_date)); ?></td>
                    <td class="text-semibold"><?php echo e(formatCurrency($project->assignable->currency, $project->assignable->sub_total)); ?></td>
                    <td class="text-semibold"><?php echo e(formatCurrency($project->assignable->currency, $project->assignable->total_expenses)); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php $__env->startPush('pagestyle'); ?>
        <?php echo $__env->make('stacks.css.datatables', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php $__env->stopPush(); ?>
    
    <?php $__env->startPush('pagescript'); ?>
        <?php echo $__env->make('stacks.js.datatables', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <script>
        $(function () {
            var table = $('#projects-table').DataTable({
            processing: true,
                order: [
                    [0, "desc"]
                ],
            });
        });
        </script>
    <?php $__env->stopPush(); ?>
</section>