<?php $__env->startSection('content'); ?>

<section id="content">
    <section class="hbox stretch">
        <?php echo $__env->make('partial.settings_menu', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <aside>
            <section class="vbox">

                <header class="header bg-white b-b clearfix">
                    <div class="row m-t-sm">


                    </div>
                </header>
                <section class="scrollable wrapper">

               
<section class="panel panel-default">
        <header class="panel-heading"><?php echo e(svg_image('solid/language')); ?> <?php echo trans('app.'.'translations'); ?>  
            - <?php echo e(ucwords($lang->name)); ?></header>
        <div class="table-responsive">
            <table id="table-translations-files" class="table table-striped">
                <thead>
                <tr>
                    <th class="col-xs-3"><?php echo trans('app.'.'file'); ?>  </th>
                    <th class="col-xs-1"><?php echo trans('app.'.'total'); ?>  </th>
                    <th class="col-options no-sort col-xs-1"><?php echo trans('app.'.'action'); ?>  </th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $file = pathinfo($file); ?>
                    <tr>
                        <td class="">
                            <a href="<?php echo e(route('translations.edit', ['locale' => $lang->code ,'file' => $file['filename']])); ?>"><?php echo e($file['basename']); ?></a>
                        </td>
                        
                        <td class=""><?php echo e(count(\Lang::get($file['filename'], [], $lang->code))); ?> Lines</td>
                        <td class="">
                            <a href="<?php echo e(route('translations.edit', ['locale' => $lang->code ,'file' => $file['filename']])); ?>">
                               <?php echo e(svg_image('solid/pencil-alt')); ?>
                           </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </section>

                </section>
            </section>
        </aside>
    </section>
    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen, open" data-target="#nav,html"></a>
</section>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>