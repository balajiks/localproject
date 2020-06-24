<div class="col-lg-4">
                    <section class="panel panel-default">
                        <header class="panel-heading">
                            <?php echo trans('app.'.'budget'); ?>
                        </header>
                        <div class="panel-body text-center">
                            <h4 class="small">
                            <?php echo e(secToHours($project->billable_time)); ?>

                            
                            </h4>
                            <small class="text-muted block">
                            <?php echo trans('app.'.'estimated_hours'); ?>  <?php echo e($project->estimate_hours); ?>

                            </small>
                            <div class="inline">
                                <div class="easypiechart" data-line-width="16" data-loop="false" data-percent="<?php echo e(percent($project->used_budget)); ?>" data-size="150">
                                    <span class="h2 step">
                                        <?php echo e(percent($project->used_budget)); ?>

                                    </span>
                                    %
                                    <div class="easypie-text">
                                        <?php echo trans('app.'.'used'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <small>
                            <?php echo e(svg_image('solid/chart-line', 'text-success')); ?> <?php echo e($project->used_budget > 100 ? 'Over Budget' : 'On Budget'); ?>

                            </small>
                        </div>
                    </section>
                </div>