
<input type="hidden" name="selecteddrugid" id="selecteddrugid" value="{{ $data['selecteddrugid'] }}"/>
<input type="hidden" name="field" value="{{ $data['field'] }}"/>
<h3 class="text-info" style="margin-top:10px;">@langapp('drugtherapy')</h3>
<hr/>

<div class="col-sm-121">
  <div class="row" id="druglink_drugtherapy">
	<div class="col-lg-12">
	  <label class="control-label" for="fname">@langapp('drugtherapy'):</label>
	  <input type="text" class="form-control" id="txtdrugtherapy" placeholder="@langapp('drugtherapy')" name="txtdrugtherapy" value="{{ @$data['txtdrugtherapy']}}" style="width:400px;">
	</div>
	<div class="col-lg-12">
	<label class="control-label" for="fname">Indexed Medical Terms:</label>
 	<select class="select2-option form-control" id="drugtherapy" name="drugtherapy[]" multiple="multiple" style="width:400px;">
	 @foreach($data['indexed_medical_term'] as $medical_term)
     <option value="{{ $medical_term->medicalterm }}">{{ $medical_term->medicalterm }}</option>
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
    
	$("#drugtherapy").select2({
		placeholder: "Select ..",
		allowClear: true,
		
    });
	
	
	$('#drugtherapy').val([ <?php echo $data['tblindex_drug']; ?> ]).trigger('change');
});

</script>
