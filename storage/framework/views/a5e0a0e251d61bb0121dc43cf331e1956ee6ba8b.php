<script type="text/javascript">
    $(document).ready(function () {
        $('#cal').fullCalendar({
            eventAfterRender: function (event, element, view) {
                $(element).attr('data-toggle', 'ajaxModal').addClass('ajaxModal');
            },
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            themeSystem: 'bootstrap3',
            nowIndicator: true,
            timezone: '<?php echo e(get_option('timezone')); ?>',
            eventSources: [
                {
                    events: [
                        <?php $__currentLoopData = $project->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        {
                            title: '<?php echo e(addslashes($task->name)); ?>',
                            start: '<?php echo e(date('Y-m-d', strtotime($task->due_date))); ?>',
                            end: '<?php echo e(date('Y-m-d', strtotime($task->due_date))); ?>',
                            url: '<?php echo e(route('calendar.view', ['id' => $task->id, 'module' => 'tasks'])); ?>',
                            color: '#3869D4'
                        },
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $project->schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        {
                            title: '<?php echo e(addslashes($event->event_name)); ?>',
                            start: '<?php echo e(date('Y-m-d', strtotime($event->start_date))); ?>',
                            end: '<?php echo e(date('Y-m-d', strtotime($event->end_date))); ?>',
                            url: '<?php echo e(route('calendar.view', ['id' => $event->id, 'module' => 'events'])); ?>',
                            color: '<?php echo e($event->color); ?>'
                        },
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    ],
                    color: '#7266BA',
                    textColor: 'white'
                }
            ]
        });
    });
</script>
