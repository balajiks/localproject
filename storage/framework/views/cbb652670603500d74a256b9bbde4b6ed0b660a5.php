
<input type="hidden" name="selecteddrugid" id="selecteddrugid" value="<?php echo e($data['selecteddrugid']); ?>"/>
<input type="hidden" name="field" value="<?php echo e($data['field']); ?>"/>
<h3 class="text-info" style="margin-top:10px;"><?php echo trans('app.'.'routeofdrug'); ?></h3>
<hr/>

<div class="col-sm-121">
  <div class="row" id="druglink_routeofdrug">
	
	<div class="col-lg-12">
 	<select class="select2-option form-control" id="routeofdrug" name="routeofdrug[]" multiple="multiple" style="width:400px;">
	 <?php $__currentLoopData = $data['routedrugadmin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $routedrugadmin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
     <option value="<?php echo e($routedrugadmin->name); ?>"><?php echo e($routedrugadmin->name); ?></option>
	 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
     </select>
	</div>							  
  </div>
</div>
<div class="form-group">
  <div class="col-sm-10"><br />
    <br />
    <button type="submit" id="savebtn" class="btn btn-primary"><i class="fas fa-paper-plane"></i>&nbsp;Save</button>
  </div>
</div>
<script>
$(document).ready(function () {
    
	$("#routeofdrug").select2({
		placeholder: "Select ..",
		allowClear: true,
		
    });
	
	
	$('#routeofdrug').val([ <?php echo $data['tblindex_drug']; ?> ]).trigger('change');
});

</script>
