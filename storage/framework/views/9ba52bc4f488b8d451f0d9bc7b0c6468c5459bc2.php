<header class="header bg-white b-b clearfix">
    <div class="row m-t-sm">
        <div class="col-sm-12 m-b-xs">
            
            <div class="m-b-sm">
                <div class="btn-group">
                    <button class="btn btn-<?php echo e(get_option('theme_color')); ?> btn-sm dropdown-toggle"
                    data-toggle="dropdown"><?php echo e(svg_image('solid/sort')); ?> <?php echo trans('app.'.'filter'); ?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo e(url('/')); ?>/projects/view/30/timesheets?filter=all"><?php echo trans('app.'.'all'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo e(url('/')); ?>/projects/view/30/timesheets?filter=billable"><?php echo trans('app.'.'invoiceable'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(url('/')); ?>/projects/view/30/timesheets?filter=unbillable"><?php echo trans('app.'.'noninvoiceable'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(url('/')); ?>/projects/view/30/timesheets?filter=billed"><?php echo trans('app.'.'billed'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(url('/')); ?>/projects/view/30/timesheets?filter=unbilled"><?php echo trans('app.'.'unbilled'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(url('/')); ?>/projects/view/30/timesheets?filter=active"><?php echo trans('app.'.'active'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="pull-right">
                    <?php if($project->isTeam() || isAdmin()): ?>
                    <a href="<?php echo e(route('timetracking.create', ['module' => 'projects', 'id' => $project->id])); ?>"
                        data-toggle="ajaxModal" class="btn btn-<?php echo e(get_option('theme_color')); ?> btn-sm">
                        <?php echo e(svg_image('solid/plus')); ?> <?php echo trans('app.'.'create'); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    
<form id="frm-timesheet" method="POST">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type="hidden" name="url" value="<?php echo e(url()->full()); ?>">
    <div class="table-responsive">
        <table id="timesheet-table" class="table table-striped">
            <thead>
                <tr>
                    <th class="hide"></th>
                    <th class="no-sort">
                                        <label>
                                            <input name="select_all" value="1" id="select-all" type="checkbox" />
                                            <span class="label-text"></span>
                                        </label>
                    </th>
                    <th class=""><?php echo trans('app.'.'name'); ?>  </th>
                    <th class=""><?php echo trans('app.'.'user'); ?></th>
                    <th class=""><?php echo trans('app.'.'total_time'); ?>  </th>
                    <th class="col-date"><?php echo trans('app.'.'start'); ?>  </th>
                    <th class="col-date"><?php echo trans('app.'.'stop'); ?>  </th>
                    <th class="col-date"><?php echo trans('app.'.'date'); ?>  </th>
                    <th class="no-sort"></th>
                </tr>
            </thead>
            
        </table>
                <?php if(isAdmin() || can('timer_delete')): ?>
                    <button type="submit" id="button" class="btn btn-sm btn-danger m-xs" value="bulk-delete">
                        <span class="" data-rel="tooltip" title="Delete Selected"><?php echo e(svg_image('solid/trash-alt')); ?></span>
                    </button>
                <?php endif; ?>

        </form>
    </div>
    <?php $__env->startPush('pagestyle'); ?>
        <?php echo $__env->make('stacks.css.datatables', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php $__env->stopPush(); ?>
    <?php $__env->startPush('pagescript'); ?>
    <?php echo $__env->make('stacks.js.datatables', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script>
$(function() {
    $('#timesheet-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo route('timetracking.all', ['id' => $project->id]); ?>',
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
            { data: 'user', name: 'user', searchable: false, orderable: false},
            { data: 'total_time', name: 'total_time'},
            { data: 'start', name: 'start' },
            { data: 'stop', name: 'stop' },
            { data: 'date', name: 'date'},
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $("#frm-timesheet button").click(function(ev){
        ev.preventDefault();
        if($(this).attr("value")=="bulk-delete"){
            var form = $("#frm-timesheet").serialize();
            axios.post('<?php echo e(route('timers.api.bulk.delete')); ?>', form).then(function (response) {
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