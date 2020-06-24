<?php $__env->startSection('content'); ?>

<section id="content">
    <section class="hbox stretch">
        <?php echo $__env->make('partial.settings_menu', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <aside>
            <section class="vbox">

                <section class="scrollable wrapper bg">



<form method="POST" accept-charset="UTF-8" id="form-strings" class="form-horizontal">


<section class="panel panel-default">
    <header class="panel-heading padder-v"><?php echo e(svg_image('solid/language')); ?>

        <?php echo trans('app.'.'translations'); ?>   | <a
                href="<?php echo e(route('translations.view', ['locale' => $lang->code])); ?>"><?php echo e(ucwords($lang->name)); ?></a>
         | <?php echo e(mb_strtolower($filename)); ?>.php
        <button type="submit" id="save-translation" class="btn btn-sm btn-<?php echo e(get_option('theme_color')); ?> pull-right saving"><?php echo e(svg_image('solid/check')); ?> <?php echo trans('app.'.'save'); ?>
        </button>
    </header>
    <div class="table-responsive">
        <table id="table-strings" class="table table-striped b-t b-light AppendDataTables">
            <thead>
            <tr>
                <th class="col-xs-5">English</th>
                <th class="col-xs-7"><?php echo e(ucwords($lang->name)); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $keys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e(trans($filename.'.'.$key, [], 'en')); ?></td>
                    <td><input class="form-control" width="100%" type="text"
                               value="<?php echo e($value); ?>"
                               name="<?php echo e($key); ?>"/></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>




    </section>

</form>

            </section>
        </aside>
    </section>
    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen, open" data-target="#nav,html"></a>
</section>

<?php $__env->startPush('pagescript'); ?>
<script>
    $('#save-translation').on('click', function (e) {
        e.preventDefault();
        $(".saving").html('Saving..<i class="fas fa-spin fa-spinner"></i>');

        axios.post('<?php echo e(route('translations.save')); ?>', {
            "locale":'<?php echo e($lang->code); ?>', 
            "filename":'<?php echo e($filename); ?>', 
            "json": JSON.stringify($('#form-strings').serializeArray())
        }).then(function (response) {
            $(".saving").html('<i class="fas fa-check"></i> <?php echo trans('app.'.'save'); ?> </span>');
            toastr.success("<?php echo trans('app.'.'translation_updated_successfully'); ?> ", "<?php echo trans('app.'.'response_status'); ?> ");
            window.location.href = response.data.redirect;
        }).catch(function (error) {
            var errors = error.response.data.errors;
                var errorsHtml= '';
                $.each( errors, function( key, value ) {
                    errorsHtml += '<li>' + value[0] + '</li>'; 
                });
                toastr.error( errorsHtml , '<?php echo trans('app.'.'response_status'); ?> ');
                $(".saving").html('<i class="fas fa-sync"></i> Try Again</span>');
        }); 
            });
        </script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>