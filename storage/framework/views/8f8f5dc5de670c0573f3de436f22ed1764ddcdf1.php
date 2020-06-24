
<input type="hidden" name="selecteddrugid" id="selecteddrugid" value="<?php echo e($data['selecteddrugid']); ?>"/>
<input type="hidden" name="field" value="<?php echo e($data['field']); ?>"/>
<h3 class="text-info" style="margin-top:10px;"><?php echo trans('app.'.'drugdosage'); ?></h3>
<hr/>

<div class="col-sm-121">
  <div class="row" id="druglink_drugdosescheduleterm">
	
	<div class="col-lg-12">
 	<select class="select2-option form-control" id="drugdosescheduleterm" name="drugdosescheduleterm[]" style="width:400px;"><!--multiple="multiple"--> 
	 <?php $__currentLoopData = $data['dosescheduleterms']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosescheduleterm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
     <option value="<?php echo e($dosescheduleterm->name); ?>"><?php echo e($dosescheduleterm->name); ?></option>
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
	$("#drugdosescheduleterm").select2({
		placeholder: "Select ..",
		allowClear: true,
    });
	$('#drugdosescheduleterm').val([ <?php echo $data['tblindex_drug']; ?> ]).trigger('change');
});
</script>