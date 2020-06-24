<div class="col-lg-4">
    <section class="panel panel-default">
        <header class="panel-heading">
            <?php echo trans('app.'.'expenses'); ?>
        </header>
        <div class="panel-body text-center">
            <h4>
            <?php echo e(formatCurrency($project->currency, $project->total_expenses)); ?>

            </h4>
            <small class="text-muted block">
            <?php echo trans('app.'.'unbilled'); ?>
            </small>
            <div class="inline">
                <div class="easypiechart" data-bar-color="#afcf6f" data-line-cap="butt" data-line-width="30" data-percent="<?php echo e(percent($project->expensesPercent())); ?>" data-scale-color="#fff" data-size="150" data-track-color="#eee">
                    <span class="h2 step font25">
                        <?php echo e(percent($project->expensesPercent())); ?>

                    </span>%
                    <div class="easypie-text">
                        <?php echo trans('app.'.'billed'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <small>
            <?php echo e(svg_image('solid/shopping-basket')); ?>
            <?php echo trans('app.'.'expenses'); ?> <span class="text-muted">(<?php echo e($project->expenses->count()); ?>)</span>
            </small>
            
        </div>
    </section>
</div>