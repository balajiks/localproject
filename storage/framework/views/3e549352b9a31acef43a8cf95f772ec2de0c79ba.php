<?php
// Field 3 - Saved Data
		$matchThese 			= ['user_id' => $user_id, 'jobid' => $jobid, 'orderid' => $orderid, 'pui' => $pui];
		$medicaltermdata 		= DB::table('medicaldevice')->where($matchThese)->get()->toArray();
		$medicaltermtypecount 	= DB::table('medicaldevice')->select('type', DB::raw('count(*) as total'))->where($matchThese)->groupBy('type')->pluck('total','type')->all();
		$sublinkcount 			= DB::table('medicaldevice')->select(DB::raw("(CHAR_LENGTH(sublink) - CHAR_LENGTH(REPLACE(sublink, ',', '')) + 1) as TotalValue"))->where($matchThese)->where('sublink', '<>', 'Null')->get()->toArray();
		
		$totalsublinkcnt = 0;
		foreach($sublinkcount as $cntval){
		   $totalsublinkcnt = $totalsublinkcnt + $cntval->TotalValue;
		}

		$medicaldata = array();
		foreach($medicaltermdata as $termgroup){
		   $medicaldata[$termgroup->type][] = $termgroup;
		}
		$data['medicaltermtypecount']   = $medicaltermtypecount;
		$data['medicaldata']   			= $medicaldata;
?>
<div class="col-lg-12">
  <div class="panel-group">
     <div class="panel panel-primary">
	 <div class="panel-heading"><?php echo trans('app.'.'medicalinfo'); ?></div>
       <div class="panel-body">
	   <div class="slim-scroll">
         <div class="form-group">
          <div class="col-md-6">
		  
        <header class="btn btn-primary btn-sm" > <?php echo e(svg_image('solid/exclamation-circle')); ?> Medical Device  Term (Major & Minor)</header>
      
		   <span class="btn btn-success btn-xs pull-right">Total: <span id="medtotalajax"><?php echo e(@$medicaltermtypecount['minor'] + @$medicaltermtypecount['major']); ?> </span></span><span class="btn btn-info btn-xs pull-right">Minor: <span id="medminortotalajax"><?php echo e(@$medicaltermtypecount['minor']); ?></span> </span><span class="btn btn-warning btn-xs pull-right">Major: <span id="medmajortotalajax"><?php echo e(@$medicaltermtypecount['major']); ?></span> </span>
             
			 <?php if(!empty($medicaldata)): ?>
              <?php $__currentLoopData = $medicaldata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$medicalterms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			  <ol class="dd-list" id="<?php echo e($key); ?>-mediallistdata">
              <li class="btn-warning" style="padding-left:20px;"><label><?php echo e($key); ?></label></li>
			  <li id="<?php echo e($key); ?>-listdata"></li>
              <?php $__currentLoopData = $medicalterms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termsdata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li class="dd-item dd3-item active" data-id="<?php echo e($termsdata->id); ?>" id="termsdata-<?php echo e($termsdata->id); ?>" > <span class="pull-right m-xs"> <a href="#" class=""> <?php echo e(svg_image('solid/pencil-alt', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php if($termsdata->user_id === Auth::id()): ?> <a href="#" class="deletemedicaldeviceterm" data-section-id="<?php echo e($termsdata->id); ?>" title="<?php echo trans('app.'.'delete'); ?>"> <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php endif; ?> </span>
                 <div class="dd3-content">
                  <label><span class="label-text"> <span class="<?php echo $termsdata->status ? 'text-info' : 'text-danger'; ?>" id="section-id-<?php echo e($termsdata->id); ?>"> <?php echo e($termsdata->deviceterm); ?> </span></span></label><span class="btn btn-info btn-xs pull-right"><?php echo e($termsdata->termtype); ?></span>
                </div></li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
			  </ol>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			 <?php else: ?>
			 <ol class="dd-list" id="major-mediallistdata"><li class="btn-warning" style="padding-left:20px;"><label>Major</label></li><li id="major-listdata"></li></ol>
			 <ol class="dd-list" id="minor-mediallistdata"><li class="btn-warning" style="padding-left:20px;"><label>Minor</label></li><li id="minor-listdata"></li></ol>
			 <?php endif; ?>
             
		   
		   
             
			
			
         </div>
          <div class="col-md-5">
		   <header class="btn btn-primary btn-sm" > <?php echo e(svg_image('solid/exclamation-circle')); ?> Sublinks </header>
      
		    <span class="btn btn-primary btn-xs pull-right">Count: <span id="medsublinktotalajax"><?php echo e($totalsublinkcnt); ?></span></span>
             <ol class="dd-list" id="sublink-listdata">
              <?php $__currentLoopData = $medicaltermdata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diseaseslink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			  <?php if($diseaseslink->sublink != 'Null'): ?>
			  	
			  	<?php if(strpos($diseaseslink->sublink, ',') !== false): ?>
					 <?php $__currentLoopData = explode(',', $diseaseslink->sublink); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selected): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					 	<li class="dd-item dd3-item active" data-id="<?php echo e($diseaseslink->id); ?>" id="termsdiseasesdata-<?php echo e($diseaseslink->id); ?>" > <span class="pull-right m-xs"> <a href="#" class=""> <?php echo e(svg_image('solid/pencil-alt', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php if($diseaseslink->user_id === Auth::id()): ?> <a href="#" class="deletemedicalterm" data-section-id="<?php echo e($diseaseslink->id); ?>" title="<?php echo trans('app.'.'delete'); ?>"> <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php endif; ?> </span>
					  <div class="dd3-content">
					  <label><span class="label-text"> <span class="<?php echo $diseaseslink->status ? 'text-info' : 'text-danger'; ?>" id="section-id-<?php echo e($diseaseslink->id); ?>"> <?php echo e($selected); ?> </span></span></label>
					</div>
				  </li>
					 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<?php else: ?>
					<li class="dd-item dd3-item active" data-id="<?php echo e($diseaseslink->id); ?>" id="termsdiseasesdata-<?php echo e($diseaseslink->id); ?>" > <span class="pull-right m-xs"> <a href="#" class=""> <?php echo e(svg_image('solid/pencil-alt', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php if($diseaseslink->user_id === Auth::id()): ?> <a href="#" class="delete<?php echo e($key); ?>" data-section-id="<?php echo e($diseaseslink->id); ?>" title="<?php echo trans('app.'.'delete'); ?>"> <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php endif; ?> </span>
					  <div class="dd3-content">
					  <label><span class="label-text"> <span class="<?php echo $diseaseslink->status ? 'text-info' : 'text-danger'; ?>" id="section-id-<?php echo e($diseaseslink->id); ?>"> <?php echo e($diseaseslink->sublink); ?> </span></span></label>
					</div>
				  </li>
				<?php endif; ?>
			  
			  <?php endif; ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ol>
                     </div>
        </div>
		
		</div>
                 </div>
    </div>
             </div>
</div>
