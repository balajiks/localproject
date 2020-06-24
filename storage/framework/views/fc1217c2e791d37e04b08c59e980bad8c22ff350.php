<?php
// Field 4 - Saved Data
		$matchThese 			= ['user_id' => $user_id, 'jobid' => $jobid, 'orderid' => $orderid];
		$drugtermdata 			= DB::table('index_drug')->where($matchThese)->get()->toArray();
		$drugtermtypecount 		= DB::table('index_drug')->select('type', DB::raw('count(*) as total'))->where($matchThese)->groupBy('type')->pluck('total','type')->all();
		

		$drugdata = array();
		foreach($drugtermdata as $termgroup){
		   $drugdata[$termgroup->type][] = $termgroup;
		}
		$data['drugtermtypecount']  = $drugtermtypecount;
		$data['drugdata']   		= $drugdata;
?>
           <div class="col-lg-12">
  <div class="panel-group">
               <div class="panel panel-primary">
      <div class="panel-heading"> <?php echo trans('app.'.'drugsublink'); ?></div>
      <div class="panel-body">
                   <div class="slim-scroll">
          <div class="form-group">
                       <div class="col-md-12">
					   <div class="row">
              <span class="btn btn-success btn-xs">Total: <span id="drugtotalajax"><?php echo e(@$drugtermtypecount['minor'] + @$drugtermtypecount['major']); ?> </span></span><span class="btn btn-info btn-xs">Minor: <span id="drugminortotalajax"><?php echo e(@$drugtermtypecount['minor']); ?></span> </span><span class="btn btn-warning btn-xs">Major: <span id="drugmajortotalajax"><?php echo e(@$drugtermtypecount['major']); ?></span> </span>
			  </div><br />
			  <div class="row">
			   <?php if(!empty($drugdata)): ?>
              <?php $__currentLoopData = $drugdata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$drugterms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <ol class="dd-list" id="<?php echo e($key); ?>-druglistdata">
                           <li class="btn-warning" style="padding-left:20px;">
                  <label><?php echo e($key); ?></label>
                </li>
                          
                           <?php $__currentLoopData = $drugterms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termsdata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                           <li class="dd-item dd3-item active" data-id="<?php echo e($termsdata->id); ?>" id="drugtermsdata-<?php echo e($termsdata->id); ?>" onclick="selecteddrugdata('<?php echo e($termsdata->id); ?>','<?php echo e($termsdata->drugterm); ?>','<?php echo e($termsdata->type); ?>')" > <span class="pull-right m-xs">  <?php if($termsdata->user_id === Auth::id()): ?> <a href="#" class="deletedrugterm" data-section-id="<?php echo e($termsdata->id); ?>" title="<?php echo trans('app.'.'delete'); ?>"> <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php endif; ?> </span>
                  <div class="dd3-content" id="drugtermshighlight-<?php echo e($termsdata->id); ?>">
                               <label><span class="label-text active"> <span class="<?php echo $termsdata->status ? 'text-info' : 'text-danger'; ?>" id="drug-id-<?php echo e($termsdata->id); ?>"> <?php echo e($termsdata->drugterm); ?> </span></span></label>
                               <span class="btn btn-info btn-xs pull-right"><?php echo e($termsdata->termtype); ?></span> </div>
                </li>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                         </ol>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <?php else: ?>
              <ol class="dd-list" id="major-druglistdata">
                           <li class="btn-warning" style="padding-left:20px;">
                  <label>Major</label>
                </li>
                           <li id="major-listdata"></li>
                         </ol>
              <ol class="dd-list" id="minor-druglistdata">
                           <li class="btn-warning" style="padding-left:20px;">
                  <label>Minor</label>
                </li>
                           <li id="minor-listdata"></li>
                         </ol>
              <?php endif; ?> </div></div>
                     </div>
        </div>
                 </div>
    </div>
             </div>
</div>
