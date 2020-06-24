<?php
// Field 3 - Saved Data
		$matchThese 			= ['user_id' => $user_id, 'jobid' => $jobid, 'orderid' => $orderid];
		$checktagdata 			= DB::table('index_medical_checktag')->where($matchThese)->get()->toArray();
		$medicaltermdata 		= DB::table('index_medical_term')->where($matchThese)->get()->toArray();
		$medicaltermtypecount 	= DB::table('index_medical_term')->select('type', DB::raw('count(*) as total'))->where($matchThese)->groupBy('type')->pluck('total','type')->all();
		$diseasescount 			= DB::table('index_medical_term')->select(DB::raw("(CHAR_LENGTH(diseaseslink) - CHAR_LENGTH(REPLACE(diseaseslink, ',', '')) + 1) as TotalValue"))->where($matchThese)->where('diseaseslink', '<>', 'Null')->get()->toArray();
		
		$totaldiseasescnt = 0;
		foreach($diseasescount as $cntval){
		   $totaldiseasescnt = $totaldiseasescnt + $cntval->TotalValue;
		}

		$medicaldata = array();
		foreach($medicaltermdata as $termgroup){
		   $medicaldata[$termgroup->type][] = $termgroup;
		}
		$data['checktagdata']   		= $checktagdata;
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
		  
        <header class="btn btn-primary btn-sm" > <?php echo e(svg_image('solid/exclamation-circle')); ?> Medical Term (Major & Minor)</header>
      
		   <span class="btn btn-success btn-xs pull-right">Total: <span id="medtotalajax"><?php echo e(@$medicaltermtypecount['minor'] + @$medicaltermtypecount['major'] + count(@$checktagdata)); ?> </span></span><span class="btn btn-dracula btn-xs pull-right">Checktags: <span id="medchecktagtotalajax"><?php echo e(count(@$checktagdata)); ?></span> </span><span class="btn btn-info btn-xs pull-right">Minor: <span id="medminortotalajax"><?php echo e(@$medicaltermtypecount['minor']); ?></span> </span><span class="btn btn-warning btn-xs pull-right">Major: <span id="medmajortotalajax"><?php echo e(@$medicaltermtypecount['major']); ?></span> </span>
             
			 <?php if(!empty($medicaldata)): ?>
              <?php $__currentLoopData = $medicaldata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$medicalterms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			  <ol class="dd-list" id="<?php echo e($key); ?>-mediallistdata">
              <li class="btn-warning" style="padding-left:20px;"><label><?php echo e($key); ?></label></li>
			  <li id="<?php echo e($key); ?>-listdata"></li>
              <?php $__currentLoopData = $medicalterms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termsdata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li class="dd-item dd3-item active" data-id="<?php echo e($termsdata->id); ?>" id="termsdata-<?php echo e($termsdata->id); ?>" > <span class="pull-right m-xs"> <a href="#" class=""> <?php echo e(svg_image('solid/pencil-alt', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php if($termsdata->user_id === Auth::id()): ?> <a href="#" class="deletemedicalterm" data-section-id="<?php echo e($termsdata->id); ?>" title="<?php echo trans('app.'.'delete'); ?>"> <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php endif; ?> </span>
                 <div class="dd3-content">
                  <label><span class="label-text"> <span class="<?php echo $termsdata->status ? 'text-info' : 'text-danger'; ?>" id="section-id-<?php echo e($termsdata->id); ?>"> <?php echo e($termsdata->medicalterm); ?> </span></span></label><span class="btn btn-info btn-xs pull-right"><?php echo e($termsdata->termtype); ?></span>
                </div></li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
			  </ol>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			 <?php else: ?>
			 <ol class="dd-list" id="major-mediallistdata"><li class="btn-warning" style="padding-left:20px;"><label>Major</label></li><li id="major-listdata"></li></ol>
			 <ol class="dd-list" id="minor-mediallistdata"><li class="btn-warning" style="padding-left:20px;"><label>Minor</label></li><li id="minor-listdata"></li></ol>
			 <?php endif; ?>
             
		   
		   
              <ol class="dd-list" id="checktag-mediallistdata">
              <li class="btn-warning" style="padding-left:20px;"><label>Minor Term (_ib) CheckTags</label></li>
			  <li id="checktags-listdata"></li>
              <?php $__currentLoopData = $checktagdata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $checktag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li class="dd-item dd3-item active" data-id="<?php echo e($checktag->id); ?>" id="checktag-<?php echo e($checktag->id); ?>" > <span class="pull-right m-xs"> <a href="#" class=""> <?php echo e(svg_image('solid/pencil-alt', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php if($checktag->user_id === Auth::id()): ?> <a href="#" class="deletechecktag" data-section-id="<?php echo e($checktag->id); ?>" title="<?php echo trans('app.'.'delete'); ?>"> <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php endif; ?> </span>
                 <div class="dd3-content">
                  <label><span class="label-text"> <span class="<?php echo $checktag->status ? 'text-info' : 'text-danger'; ?>" id="checktags-id-<?php echo e($checktag->id); ?>"> <?php echo e($checktag->checktag); ?> </span></span></label>
                </div></li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
             
            </ol>
			
			
         </div>
          <div class="col-md-5">
		   <header class="btn btn-primary btn-sm" > <?php echo e(svg_image('solid/exclamation-circle')); ?> Diseases links </header>
      
		    <span class="btn btn-primary btn-xs pull-right">Count: <span id="meddiseasestotalajax"><?php echo e($totaldiseasescnt); ?></span></span>
             <ol class="dd-list" id="diseases-listdata">
              <?php $__currentLoopData = $medicaltermdata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diseaseslink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			  <?php if($diseaseslink->diseaseslink != 'Null'): ?>
			  	
			  	<?php if(strpos($diseaseslink->diseaseslink, ',') !== false): ?>
					 <?php $__currentLoopData = explode(',', $diseaseslink->diseaseslink); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selected): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					 	<li class="dd-item dd3-item active" data-id="<?php echo e($diseaseslink->id); ?>" id="termsdiseasesdata-<?php echo e($diseaseslink->id); ?>" > <span class="pull-right m-xs"> <a href="#" class=""> <?php echo e(svg_image('solid/pencil-alt', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php if($diseaseslink->user_id === Auth::id()): ?> <a href="#" class="deletemedicalterm" data-section-id="<?php echo e($diseaseslink->id); ?>" title="<?php echo trans('app.'.'delete'); ?>"> <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php endif; ?> </span>
					  <div class="dd3-content">
					  <label><span class="label-text"> <span class="<?php echo $diseaseslink->status ? 'text-info' : 'text-danger'; ?>" id="section-id-<?php echo e($diseaseslink->id); ?>"> <?php echo e($selected); ?> </span></span></label>
					</div>
				  </li>
					 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<?php else: ?>
					<li class="dd-item dd3-item active" data-id="<?php echo e($diseaseslink->id); ?>" id="termsdiseasesdata-<?php echo e($diseaseslink->id); ?>" > <span class="pull-right m-xs"> <a href="#" class=""> <?php echo e(svg_image('solid/pencil-alt', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php if($diseaseslink->user_id === Auth::id()): ?> <a href="#" class="delete<?php echo e($key); ?>" data-section-id="<?php echo e($diseaseslink->id); ?>" title="<?php echo trans('app.'.'delete'); ?>"> <?php echo e(svg_image('solid/times', 'icon-muted fa-fw m-r-xs')); ?> </a> <?php endif; ?> </span>
					  <div class="dd3-content">
					  <label><span class="label-text"> <span class="<?php echo $diseaseslink->status ? 'text-info' : 'text-danger'; ?>" id="section-id-<?php echo e($diseaseslink->id); ?>"> <?php echo e($diseaseslink->diseaseslink); ?> </span></span></label>
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
