<form id="frm-tasks" method="POST">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type="hidden" name="url" value="<?php echo e(url()->full()); ?>">
                    <table class="table table-striped" id="tasks-table">
                                <thead>
                                <tr>
                                    <th class="hide"></th>
                                    <th class="no-sort">
                                        <label>
                                            <input name="select_all" value="1" id="select-all" type="checkbox" />
                                            <span class="label-text"></span>
                                        </label>
                                    </th>

                                    <th class=""><?php echo trans('app.'.'task_name'); ?></th>
                                    <th class=""><?php echo trans('app.'.'total_time'); ?></th>
                                    <th class=""><?php echo trans('app.'.'hourly_rate'); ?></th>
                                    <th class="col-date"><?php echo trans('app.'.'due_date'); ?></th>
                                    <th class="no-sort"><?php echo trans('app.'.'progress'); ?></th>
                                    <th class="col-options no-sort"></th>

                                </tr>
                                </thead>
                                
                </table>

                <?php if(can('tasks_update') || can('tasks_complete')): ?>
                    <button type="submit" id="button" class="btn btn-sm btn-info m-xs" value="bulk-complete">
                        <span class="" data-rel="tooltip" title="Mark as Done"><?php echo e(svg_image('solid/check-circle')); ?> <?php echo trans('app.'.'done'); ?></span>
                    </button>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tasks_delete')): ?>
                    <button type="submit" id="button" class="btn btn-sm btn-danger m-xs" value="bulk-delete">
                        <span class="" data-rel="tooltip" title="Delete Selected"><?php echo e(svg_image('solid/trash-alt')); ?></span>
                    </button>
                <?php endif; ?>

        </form>


<?php $__env->startPush('pagestyle'); ?>
<?php echo $__env->make('stacks.css.datatables', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('pagescript'); ?>
<?php echo $__env->make('stacks.js.datatables', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<script>
$(function() {
    $('#tasks-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo route('tasks.all', ['id' => $project->id]); ?>',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "filter": '<?php echo e($filter); ?>',
            }
        },
        order: [[ 0, "desc" ]],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'chk', name: 'chk', orderable: false, searchable: false, sortable: false },
            { data: 'name', name: 'name' },
            { data: 'hours', name: 'hours', searchable: false, orderable: false},
            { data: 'hourly_rate', name: 'hourly_rate'},
            { data: 'due_date', name: 'due_date' },
            { data: 'progress', name: 'progress', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $("#frm-tasks button").click(function(ev){
        ev.preventDefault();
        if($(this).attr("value")=="bulk-complete"){
            var form = $("#frm-tasks").serialize();
            axios.post('<?php echo e(route('tasks.api.bulk.close')); ?>', form).then(function (response) {
                toastr.info(response.data.message, '<?php echo trans('app.'.'response_status'); ?> ');
                window.location.href = response.data.redirect;
            })
            .catch(function (error) {
                    showErrors(error);
                });
        }  
        if($(this).attr("value")=="bulk-delete"){
            var form = $("#frm-tasks").serialize();
            axios.post('<?php echo e(route('tasks.api.bulk.delete')); ?>', form).then(function (response) {
                toastr.warning(response.data.message, '<?php echo trans('app.'.'response_status'); ?> ');
                window.location.href = response.data.redirect;
            })
            .catch(function (error) {
                    showErrors(error);
                });
        }   
        function showErrors(error){
        var errors = error.response.data.errors;
        var errorsHtml= '';
        $.each( errors, function( key, value ) {
            errorsHtml += '<li>' + value[0] + '</li>';
        });
            toastr.error( errorsHtml , '<?php echo trans('app.'.'response_status'); ?> ');
        }
});

});
</script>
<?php $__env->stopPush(); ?>