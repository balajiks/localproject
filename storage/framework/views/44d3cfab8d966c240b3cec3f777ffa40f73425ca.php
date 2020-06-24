<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?php echo e(langapp('department')); ?></h4>
        </div>
        <?php echo Form::open(['route' => 'departments.save', 'class' => 'bs-example form-horizontal', 'id' => 'saveDepartment']); ?>

        
        <div class="modal-body">
            <div class="form-group">
                <label class="col-lg-4 control-label"><?php echo e(langapp('name')); ?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" placeholder="Sales" name="deptname">
                </div>
            </div>

            

            <ul class="list-group gutter list-group-lg list-group-sp sortable" id="departmentList">

                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="list-group-item" draggable="true" id="department-<?php echo e($department->deptid); ?>">
                    <span class="pull-right">
                    <a href="<?php echo e(route('departments.edit', $department->deptid)); ?>" data-toggle="ajaxModal" data-dismiss="modal">
                            <?php echo e(svg_image('solid/pencil-alt', 'icon-muted fa-fw m-r-xs')); ?>
                    </a>
                        <a href="#" class="deleteDepartment" data-department-id="<?php echo e($department->deptid); ?>">
                            <?php echo e(svg_image('solid/times', 'icon-muted fa-fw')); ?>
                        </a>
                    </span>

                    <span class="pull-left media-xs"><?php echo e(svg_image('solid/arrows-alt', 'm-r-sm')); ?></span>

                    <div class="clear"><?php echo e($department->deptname); ?></div>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                
            </ul>
        </div>
        <div class="modal-footer">

            <?php echo closeModalButton(); ?>

            <?php echo renderAjaxButton('save'); ?>


        </div>

    <?php echo Form::close(); ?>

</div>

</div>

<?php $__env->startPush('pagescript'); ?>
    <script>
    $('#saveDepartment').on('submit', function(e) {
        $(".formSaving").html('Processing..<i class="fas fa-spin fa-spinner"></i>');

        e.preventDefault();
        var tag, data;
        tag = $(this);
        data = tag.serialize();


        axios.post('<?php echo e(route('departments.save')); ?>', data)
          .then(function (response) {
            $('#departmentList').append(response.data.html);
                toastr.info( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
                $(".formSaving").html('<i class="fas fa-paper-plane"></i> <?php echo trans('app.'.'send'); ?> </span>');
                tag[0].reset();
          })
          .catch(function (error) {
            var errors = error.response.data.errors;
            var errorsHtml= '';
            $.each( errors, function( key, value ) {
                errorsHtml += '<li>' + value[0] + '</li>'; 
            });
            toastr.error( errorsHtml , '<?php echo trans('app.'.'response_status'); ?> ');
            $(".formSaving").html('<i class="fas fa-sync"></i> Try Again</span>');
        });
        

    });


    $('body').on('click', '.deleteDepartment', function (e) {
        e.preventDefault();
        var tag, id;

        tag = $(this);
        id = tag.data('department-id');

        if(!confirm('Do you want to delete this message?')) {
            return false;
        }

        axios.post('<?php echo e(route('departments.delete')); ?>', {
            "id":id
        })
          .then(function (response) {
                toastr.warning( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
                $('#department-' + id).hide(500, function () {
                    $(this).remove();
                });
          })
          .catch(function (error) {
            toastr.error('Oops! Request failed to complete.', '<?php echo trans('app.'.'response_status'); ?> ');
        });

        
    });  
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->yieldPushContent('pagescript'); ?>