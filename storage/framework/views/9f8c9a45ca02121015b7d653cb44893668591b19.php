 

<?php $__env->startSection('content'); ?>


<section id="content">
    <section class="hbox stretch">
        <aside>
            <section class="vbox">
                <section class="scrollable wrapper bg">
                    <section class="panel panel-default">

                        <header class="panel-heading">

                            <?php echo $__env->make('analytics::report_header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                        </header>

                        <div class="panel-body">



<?php
$start_date = date('F d, Y', strtotime($range[0]));
$end_date = date('F d, Y', strtotime($range[1]));
?>


                                <section class="panel panel-default">
                                    <header class="panel-heading">Project Feedback</header>
                                    <div class="row wrapper">
                                        <div class="col-sm-10 m-b-xs">
                                            <form>

                                                <div class="inline v-middle col-md-6">

                                                    <input type="text" class="form-control" id="reportrange" name="range">
                                                </div>




                                            </form>

                                        </div>


                                    </div>
                                    
                                    
                                    <div id="ajaxData"></div>
                                    


                                </section>








                        </div>

                    </section>
                </section>


            </section>
        </aside>
    </section>
    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
</section>

<?php $__env->startPush('pagescript'); ?>
<?php echo $__env->make('analytics::_daterangepicker', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<script type="text/javascript">
$('#reportrange').change(function(event) {
    loadData(event);
}).change();
function loadData(val) {
axios.post('<?php echo e(route('reports.projects.feedback')); ?>', {
    range: $('#reportrange').val(),
})
.then(function (response) {
    $('#ajaxData').html(response.data.html);
})
.catch(function (error) {
    toastr.error( 'Failed loading data' , '<?php echo trans('app.'.'response_status'); ?> ');
});
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>