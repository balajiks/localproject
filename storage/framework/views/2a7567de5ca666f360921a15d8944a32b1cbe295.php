<div class="col-lg-4">
                    <section class="panel panel-default">
                        <header class="panel-heading">
                            <?php echo trans('app.'.'tasks'); ?>
                        </header>
                        <div class="panel-body text-center">
                            <h4>
                            <?php echo e($project->tasks->where('progress', '<', 100)->count()); ?>

                            <small>
                            <?php echo trans('app.'.'pending'); ?>
                            </small>
                            </h4>
                            <small class="text-muted block">
                            <?php echo e($project->tasks->where('progress', 100)->count()); ?> <?php echo trans('app.'.'done'); ?>
                            </small>
                            <div class="inline">
                                <div class="easypiechart" data-line-width="6" data-loop="false" data-percent="<?php echo e(percent($project->taskDonePercent())); ?>" data-size="150">
                                    <span class="h2 step">
                                        <?php echo e(percent($project->taskDonePercent())); ?>

                                    </span>
                                    %
                                    <div class="easypie-text">
                                        <?php echo trans('app.'.'done'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <small>
                            <?php echo e(svg_image('solid/tasks')); ?>
                            <?php echo trans('app.'.'tasks'); ?> <span class="text-muted">(<?php echo e($project->tasks->count()); ?>)</span>
                            </small>
                        </div>
                    </section>
                </div>