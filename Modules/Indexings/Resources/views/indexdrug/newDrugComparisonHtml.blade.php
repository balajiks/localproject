
<input type="hidden" name="selecteddrugid" id="selecteddrugid" value="{{ $data['selecteddrugid'] }}"/>
<input type="hidden" name="field" value="{{ $data['field'] }}"/>

<h3 class="text-info" style="margin-top:10px;">@langapp('drugcomparison')</h3>
<hr/>


<div class="col-sm-121">
  <div class="row" id="druglink_drugcomparison">
	
	<div class="col-lg-12">
 	<select class="select2-option form-control" id="drugcomparison" name="drugcomparison[]" multiple="multiple" style="width:400px;">
	 @foreach($data['drugcomparison'] as $drugcomparison)
     <option value="{{ $drugcomparison->drugterm }}">{{ $drugcomparison->drugterm }}</option>
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
    
	$("#drugcomparison").select2({
		placeholder: "Select ..",
		allowClear: true,
		
    });
	
	
	$('#drugcomparison').val([ <?php echo $data['tblindex_drug']; ?> ]).trigger('change');
});

</script>
