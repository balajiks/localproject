<div class="row">
  <div class="col-lg-12"> 
  
  {!! Form::open(['route' => 'indexings.api.savesection', 'id' => 'createSection', 'class' => 'bs-example form-horizontal m-b-none']) !!}
  <?php /*?>{!! Form::open(['route' =>'indexings.api.savesection', 'data-toggle' => 'validator', 'class' => 'bs-example form-horizontal ajaxifyForm']) !!}<?php */?>
    <section class="panel panel-default">
      <header class="panel-heading">
        <header class="btn btn-primary btn-sm" style="margin-left:20px;"> @icon('solid/exclamation-circle') @langapp('sectionalertinfo')  &nbsp;&nbsp;[ PUBL: <span class="btn btn-warning btn-xs">{{$jobdata->publ}} </span> | Abstract: <span class="btn btn-warning btn-xs">{{$jobdata->abs}} </span> ]</header>
		
		<div class="btn btn-sm btn-warning  }} pull-right" >Section Count :  <span id="indexersectioncount">{{$sectioncount}}</span></div>
      </header>
      @php 
      $translations = Modules\Settings\Entities\Options::translations();
      $default_country = get_option('company_country');
      $disable = '';
      @endphp
      
      <div class="panel-body">
        
		<input type="hidden" class="form-control" placeholder="jobid" name="jobid" value="{{$jobdata->id}}" />
		<input type="hidden" class="form-control" placeholder="pui" name="pui" value="{{$jobdata->pui}}" />
		<input type="hidden" class="form-control" placeholder="orderid" name="orderid" value="{{$jobdata->orderid}}" />
		<input type="hidden" class="form-control" placeholder="jobid" id="sectioncount" name="sectioncount" value="{{$sectioncount}}" />
		<input type="hidden" name="json" value="false"/>
		
        <div class="col-md-12"> @if (count($translations) > 0)
          <div class="tab-content tab-content-fix">
            <div class="tab-pane fade in active" id="tab-english"> @endif
			
			
			<div id="frmindexsectionshow" class="">
			 @if ($jobdata->publ == 'Yes' && $jobdata->abs == 'Yes')
			 <input type="hidden" class="form-control" placeholder="jobdatayestoall" name="jobdatayestoall" value="1" />
				  <div class="form-group">
					<div class="col-lg-3">
					  <label>@langapp('section')</label><span class="text-dark badge badge-info pull-right">@icon('solid/exclamation-circle') @langapp('totalsectionallowed')</span>
					  <select class="select2-option form-control indexer_section" id="indexer_section" name="indexer_section" onChange="getClassification(this.value);" required>
						 <option selected="true" disabled="disabled">Select Section</option>
						@foreach(embaseindex_section() as $sectionval)
						<option value="{{ $sectionval['section_id'] }}">{{ $sectionval['sectionvalue'] }}</option>
						@endforeach
					  </select>
					</div>
					<div class="col-lg-3">
					  <label>@langapp('publication') @required </label>
					  <select class="select2-option form-control Selpublication" name="indexer_publication"  required>
						<option selected="true" disabled="disabled">Select Publication Choice</option>
						<option value="?">?</option>
						<option value="+">+</option>
					  </select>
					</div>
					<div class="col-lg-3">
					  <label>@langapp('classification') @required </label>
					  <select class="select2-option form-control classification" name="indexer_classification" required>
						<option selected="true" disabled="disabled">Select Classification</option>
					  </select>
					</div>
					<div class="col-lg-3">
					  <div class="form-group"><br />
						  {!!  renderAjaxButtonSquare('save')  !!}
						<button type="submit" class="btn btn-danger">Clear</button>
					  </div>
					</div>
				  </div>
			  @else 
			    <input type="hidden" class="form-control" placeholder="jobdatayestoall" name="jobdatayestoall" value="0" />
			  	<div class="form-group">
					<div class="col-lg-5">
					  <label>@langapp('section')@required </label><span class="text-dark badge badge-info pull-right">@icon('solid/exclamation-circle') @langapp('totalsectionallowed')</span>
					  <select class="select2-option form-control indexer_section" id="indexer_section" name="indexer_section[]" multiple="multiple" data-maximum-selection-length="3">					  
						@foreach(embaseindex_section() as $sectionval)
						<option value="{{ $sectionval['section_id'] }}">{{ $sectionval['sectionvalue'] }}</option>
						@endforeach 
					  </select>
					  
					</div>
					<div class="col-lg-3 disabledbutton" >
					  <label>@langapp('publication')</label>
					  <select class="form-control" disabled>
						<option selected="true" disabled="disabled">Select Publication Choice</option>
					  </select>
					</div>
					<div class="col-lg-2 disabledbutton">
					  <label>@langapp('classification')</label>
					  <select class="form-control" disabled>
						<option selected="true" disabled="disabled">Classification</option>
					  </select>
					</div>
					<div class="col-lg-2">
					  <div class="form-group"><br />
						 {!!  renderAjaxButtonSquare('add')  !!}
						<button type="submit" class="btn btn-danger">Clear</button>
					  </div>
					</div>
				  </div>
			  	 @endif
				 
				 
				 
				 <div class="sortable">
            		<div class="section-list" id="nestable">
					
			@if ($jobdata->publ == 'Yes' && $jobdata->abs == 'Yes')		
              
			 @widget('Indexings\ShowSectionswithclassification', ['indexingsections' => DB::table('datasections')
			->join('embaseindex_sections', 'datasections.sectionid', '=', 'embaseindex_sections.id')
			->join('embaseindex_classifications', 'datasections.classid', '=', 'embaseindex_classifications.section_id')// you may add more joins
			->select('datasections.*', 'embaseindex_sections.sectionvalue', 'embaseindex_classifications.classvalue')
			->where('datasections.user_id', \Auth::id())->where('datasections.jobid', $jobdata->id)->where('datasections.orderid', $jobdata->orderid)->where('datasections.pui', $jobdata->pui)
			->get()])
			
			@else 
			
			 @widget('Indexings\ShowSections', ['indexingsections' => DB::table('datasections')
			->join('embaseindex_sections', 'datasections.sectionid', '=', 'embaseindex_sections.id')
			->select('datasections.*', 'embaseindex_sections.sectionvalue')
			->where('datasections.user_id', \Auth::id())->where('datasections.jobid', $jobdata->id)->where('datasections.orderid', $jobdata->orderid)->where('datasections.pui', $jobdata->pui)
			->get()])
			@endif
            			
            		</div>

            	</div>
			  
			 
			  </div>
              @if (count($translations) > 0) </div>
          </div>
          @endif </div>
      </div>
      <!--<div class="panel-footer">
            {!!  renderAjaxButton('save')  !!}
        </div>-->
    </section>
    {!! Form::close() !!} </div>
</div>
@push('pagestyle')
<link rel=stylesheet href="{{ getAsset('plugins/nestable/nestable.css') }}">
@include('stacks.css.form')
@endpush
@push('pagescript')

@include('stacks.js.form')




@if ($sectioncount == langapp('sectioncnt'))
<script>$('#frmindexsectionshow').find('input, textarea, button, select').attr('disabled','disabled');</script>
@else
<script>$('#frmindexsectionshow').find('input, textarea, button, select').removeAttr('disabled','disabled');</script>			 
@endif



<script>





$(document).ready(function () {
    $("#indexer_section").select2({
        maximumSelectionLength: @langapp('sectioncnt') - $('#sectioncount').val()
    });
});

function getClassification(val) {
	var selectedValues = $('#indexer_section').val();
	
	if(selectedValues !='') {
		axios.post('{{ get_option('site_url') }}api/v1/indexings/ajax/classification', {
			id: selectedValues
		})
		.then(function (response) {
			$('.classification').html(response.data);
			toastr.success(response.data.message, '@langapp('success') ');
		})
		.catch(function (error) {
			var errors = error.response.data.errors;
			var errorsHtml= '';
			$.each( errors, function( key, value ) {
				errorsHtml += '<li>' + value[0] + '</li>';
			});
			toastr.error( errorsHtml , '@langapp('response_status') ');
		});
	}
}
</script>
@endpush