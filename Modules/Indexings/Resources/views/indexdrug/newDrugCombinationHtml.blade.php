
<input type="hidden" name="selecteddrugid" id="selecteddrugid" value="{{ $data['selecteddrugid'] }}"/>
<input type="hidden" name="field" value="{{ $data['field'] }}"/>

<h3 class="text-info" style="margin-top:10px;">@langapp('drugcombination')</h3>
<hr/>

<div class="col-sm-121">
  <div class="row" id="druglink_drugcombination">
	
	<div class="col-lg-12">
 	<select class="select2-option form-control" id="drugcombination" name="drugcombination[]" multiple="multiple" style="width:400px;">
	 @foreach($data['drugcombination'] as $drugcombination)
     <option value="{{ $drugcombination->drugterm }}">{{ $drugcombination->drugterm }}</option>
	 @endforeach
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
    
	$("#drugcombination").select2({
		placeholder: "Select ..",
		allowClear: true,
		
    });
	
	
	$('#drugcombination').val([ <?php echo $data['tblindex_drug']; ?> ]).trigger('change');
});

</script>
