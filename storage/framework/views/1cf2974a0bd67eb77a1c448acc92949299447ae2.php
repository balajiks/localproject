<?php
// Field 3 - Saved Data
		$matchThese 			= ['user_id' => $user_id, 'jobid' => $jobid, 'orderid' => $orderid];
		$drugtradename 			= DB::table('drugtradename')->where($matchThese)->get()->toArray();
		$drugtradenametypecount = DB::table('drugtradename')->select('type', DB::raw('count(*) as total'))->where($matchThese)->groupBy('type')->pluck('total','type')->all();
		$data['drugtradename']   		= $drugtradename;
		$data['drugtradenametypecount'] = $drugtradenametypecount;
?>
        
		
<div class="col-lg-12">
  <div class="panel-group">
    <div class="panel panel-primary">
      <div class="panel-heading"><?php echo trans('app.'.'indexeddrugtradename'); ?></div>
      <div class="panel-body">
        <div class="slim-scroll">
          <div class="form-group">
            <div class="col-md-6">
              <ol class="drugtradename-list"  style="margin:0px; padding:0px;">
                <?php $__currentLoopData = $drugtradename; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termsdata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="dd-item dd3-item " data-id="<?php echo e($termsdata->id); ?>" id="termsdata-<?php echo e($termsdata->id); ?>" > <span class="pull-right m-xs"> <?php if($termsdata->user_id === Auth::id()): ?> <a href="#" class="deletetradeterm" data-termsdata-id="<?php echo e($termsdata->id); ?>" title="<?php echo trans('app.'.'delete'); ?>"> <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php endif; ?> </span>
                  <div class="dd3-content indexedmaterm"  id="ajaxterm-<?php echo e($termsdata->id); ?>" onclick="ajaxtradename(<?php echo e($termsdata->id); ?>)" style="cursor:pointer">
                    <label style="cursor:pointer"><span class="label-text text-info"><strong>Field Code : </strong>&nbsp;&nbsp; <span class="label label-info <?php echo $termsdata->status ? 'text-info' : 'text-danger'; ?>" id="section-id-<?php echo e($termsdata->id); ?>">_<?php echo e($termsdata->type); ?> </span></span></label>
					
					 <label style="cursor:pointer"><span class="label-text text-info"><strong>Drug Manufacture : </strong>&nbsp;&nbsp; <span class="label label-info <?php echo $termsdata->status ? 'text-info' : 'text-danger'; ?>" ><?php echo e($termsdata->manufacturename); ?> </span></span></label>
					 
					 <label style="cursor:pointer"><span class="label-text text-info"><strong>Country : </strong>&nbsp;&nbsp; <span class="label label-info <?php echo $termsdata->status ? 'text-info' : 'text-danger'; ?>" ><?php echo e($termsdata->countrycode); ?> </span></span></label>
					
					
                     </div>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </ol>
            </div>
			
			 <div class="col-md-6" id="ajaxdrugtradename">
			
			 </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
