
<input type="hidden" name="selecteddrugid" id="selecteddrugid" value="<?php echo e($data['selecteddrugid']); ?>"/>
<input type="hidden" name="field" value="<?php echo e($data['field']); ?>"/>

<div class="col-sm-121">
  <div class="row" id="druglink_druginteraction">
	<div class="col-lg-12">
	  <label class="control-label" for="fname"><?php echo trans('app.'.'druginteraction'); ?>:</label>
	  <input type="text" class="form-control" id="txtdruginteraction" placeholder="<?php echo trans('app.'.'indexterm'); ?>" name="txtdruginteraction" value="<?php echo e(@$data['txtdruginteraction']); ?>" style="width:400px;">
	</div>
	<div class="col-lg-12">
	<label class="control-label" for="fname">Indexed Drug Terms:</label>
 	<select class="select2-option form-control" id="druginteraction" name="druginteraction[]" multiple="multiple" style="width:400px;">
	 <?php $__currentLoopData = $data['druginteraction']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medical_term): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
     <option value="<?php echo e($medical_term->drugterm); ?>"><?php echo e($medical_term->drugterm); ?></option>
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
	$("#druginteraction").select2({
		placeholder: "Select ..",
		allowClear: true,
    });
	$('#druginteraction').val([ <?php echo $data['tblindex_drug']; ?> ]).trigger('change');
});

</script>
