<?php if(($project->setting('show_project_gantt') && $project->isClient()) || isAdmin() || $project->isTeam()): ?>
<section class="scrollable">
	<section class="panel panel-default">
		<div class="m-xs">
			<div class="btn-group" data-toggle="buttons">
				<label class="btn btn-sm btn-default">
					<input type="radio" name="options" value="Quarter Day"><?php echo trans('app.'.'quarter_day'); ?>
				</label>
				<label class="btn btn-sm btn-default">
					<input type="radio" name="options" value="Half Day"><?php echo trans('app.'.'half_day'); ?>
				</label>
				<label class="btn btn-sm btn-default active">
					<input type="radio" name="options" value="Day"><?php echo trans('app.'.'day'); ?>
				</label>
				<label class="btn btn-sm btn-default">
					<input type="radio" name="options" value="Week"><?php echo trans('app.'.'week'); ?>
				</label>
				<label class="btn btn-sm btn-default">
					<input type="radio" name="options" value="Month"><?php echo trans('app.'.'month'); ?>
				</label>
			</div>
		</div>
		
		<div class="project-gantt"></div>
	</section>
</section>
<?php if($project->tasks->count() > 0): ?>

<?php $__env->startPush('pagescript'); ?>
<?php echo $__env->make('stacks.js.gantt', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<script>
	var tasks = [
	<?php $__currentLoopData = $project->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		{
			start: '<?php echo e($task->start_date); ?>',
			end: '<?php echo e($task->due_date); ?>',
			name: '<?php echo e($task->name); ?>',
			id: "<?php echo e($task->id); ?>",
			project: "<?php echo e($task->project_id); ?>",
			progress: <?php echo e($task->progress); ?>,
		},
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	];
	var gantt_chart = new Gantt(".project-gantt", tasks, {
		on_click: function (task) {
			
		},
on_date_change: function(task, start, end) {
	axios.put('/api/v1/tasks/'+task.id, {
		start_date: start,
		due_date: end,
		name:task.name,
		project_id:task.project
	})
	.then(function (response) {
		toastr.success( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
	})
	.catch(function (error) {
		toastr.error('Oops! Request failed to complete.', '<?php echo trans('app.'.'response_status'); ?> ');
	});
},
on_progress_change: function(task, progress) {
	axios.put('/api/v1/tasks/'+task.id, {
		progress: progress,
		start_date: task.start,
		due_date: task.end,
		name:task.name,
		project_id:task.project
	})
	.then(function (response) {
		toastr.success( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
	})
	.catch(function (error) {
		toastr.error('Oops! Request failed to complete.', '<?php echo trans('app.'.'response_status'); ?> ');
	});
},
on_view_change: function(mode) {
			
		}
	});
	gantt_chart.change_view_mode('Day');
	$(function() {
		$(".btn-group").on("change", "input[type='radio']", function() {
			var mode = $('input[name=options]:checked').val();
			gantt_chart.change_view_mode(mode);
		});
});
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php endif; ?>