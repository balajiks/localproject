<div class="row">
  <div class="col-lg-12"> {!! Form::open(['route' => 'indexings.api.savemedical', 'id' => 'createMedical', 'class' => 'bs-example form-horizontal m-b-none']) !!}
    <section class="panel panel-default">
    <header class="panel-heading">
      <header class="btn btn-primary btn-sm" style="margin-left:20px;"> @icon('solid/exclamation-circle') @langapp('medicalalertinfo')  &nbsp;&nbsp;[ <span class="btn btn-warning btn-xs">@langapp('medicalinfo') </span> ]</header>
    </header>
    @php 
    $translations = Modules\Settings\Entities\Options::translations();
    $default_country = get_option('company_country');
    $disable = '';
    @endphp
    
    @if ($jobdata->publ == 'Yes' && $jobdata->abs == 'Yes')
    $disable = 'disabled';
    @endif
    <div class="panel-body">
      <input type="hidden" class="form-control" placeholder="jobid" name="jobid" id="jobid" value="{{$jobdata->id}}" />
      <input type="hidden" class="form-control" placeholder="pui" name="pui" id="pui" value="{{$jobdata->pui}}" />
      <input type="hidden" class="form-control" placeholder="orderid" name="orderid" id="orderid" value="{{$jobdata->orderid}}" />
      <input type="hidden" name="json" value="false"/>
      <div class="row">
        <div class="col-md-5">
          <div class="form-group">
            <div class="col-lg-6">
              <label>
              <input type="radio" id="txtmedicalmajor" name="medicaltermindexing" value="1">
              <span class="label-text text-info">@langapp('mmt')</span></label>
            </div>
            <div class="col-lg-6">
              <label>
              <input type="radio" id="txtmedicalminor" name="medicaltermindexing" value="0">
              <span class="label-text text-info">@langapp('mmit')</span></label>
            </div>
          </div>
          <div class="form-group">
            <div class="col-lg-12">
              <div class="frmSearch">
                <input type="hidden" id="txtmedicaltermtype" class="form-control"  name="txtmedicaltermtype"  autocomplete="off">
                <input type="text" id="txtmedicalterm" class="form-control" placeholder="Medical / Disease Term" name="txtmedicalterm" disabled="disabled" autocomplete="off">
                <div id="suggesstion-box"></div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-lg-12 disabledbutton"  id="dieseasesenablelink">
              <label>@langapp('diseaseslink')</label>
				<select class="select2-option form-control diseaseslink" id="indexer_diseaseslink" name="indexer_diseaseslink[]" multiple>
					@foreach($diseaseslink as $diseases)
					<option value="{{ $diseases->name }}">{{ $diseases->name }}</option>
					@endforeach
				</select>
            </div>
            <div class="col-lg-12">
              <label></label>
              <br />
              {!!  renderAjaxButtonSquare('save')  !!} </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="panel-group">
            <div class="panel panel-primary">
              <div class="panel-body">
                <div class="form-group">
                  <label class="col-lg-5 control-label">@langapp('mmtct') </label>
                  <div class="col-lg-6">
                    <label class="switch">
                    <input type="hidden" value="FALSE" name="hide_mmtct"/>
                    <input type="checkbox" id="hide_mmtct" name="hide_mmtct" value="TRUE" />
                    <span></span> </label>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-lg-6 panel panel-warning disabledbutton"  id="disablepanel">
                    <div class="slim-scroll" style="height:200px !important">
                      <?php
						foreach ($mmt_ct_list as $key => $values){
							 echo '<div class="checkbox"><label><span class="label-text btn btn-info btn-xs">'.$key.'</span></label></div>';
							 foreach ($values as $value) 
							 {
								 echo '<div class="checkbox" onclick="showchecktagdesc(\'checktags'.$value->id.'\')"><input type="hidden" value="'.$value->description.'" id="checktags'.$value->id.'"/> <label class="pad40"><input type="hidden" value="'.$value->name.'" /><input type="checkbox" class="mmtctselectdata" name="medicalchecktags[]" value="'.$value->name.'"><span class="label-text">'.$value->name.'</span></label></div>';
							 }
						 }
					?>
                    </div>
                  </div>
                  <div class="col-lg-6" id="chktagdesc"> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sortable">
        <div class="section-list" id="nestable"> @widget('Indexings\ShowMedicals', ['user_id' => \Auth::id(), 'jobid' => $jobdata->id, 'orderid' => $jobdata->orderid]) </div>
      </div>
      @if (count($translations) > 0) </div>
    @endif </div>
  </section>
  {!! Form::close() !!} </div>
</div>
@push('pagestyle')
<style>
.frmSearch {border: 1px solid #a8d4b1;margin: 2px 0px;border-radius:4px;}
#termList{float:left;list-style:none;width:94%;padding:0; position: absolute; z-index:1;}
#termList li{padding: 5px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
#termList li:hover{background:#ece3d2;cursor: pointer;}
#search-box{padding: 5px;border: #a8d4b1 1px solid;border-radius:4px;}
</style>
<link rel=stylesheet href="{{ getAsset('plugins/nestable/nestable.css') }}">
@include('stacks.css.form')
@endpush
@push('pagescript')
@include('stacks.js.form')
<script>
$("#disablepanel").addClass("disabledbutton");
$(function () {
        $("input[name='medicaltermindexing']").click(function () {
            if ($("#txtmedicalmajor").is(":checked")) {
                $("#txtmedicalterm").removeAttr("disabled");
				$('#txtmedicalterm').prop("disabled", false);
                $('#txtmedicalterm').attr('placeholder', "@langapp('mmt')");
				<!--$("#dieseasesenablelink").removeClass("disabledbutton");-->	
				$("#txtmedicalterm").focus();
            } else {
                $('#txtmedicalterm').attr('placeholder', "@langapp('mmit')");	
				$('#txtmedicalterm').prop("disabled", false);
				$("#txtmedicalterm").removeAttr("disabled");
				<!--$("#checkenablelink").removeClass("disabledbutton");-->
				$("#txtmedicalterm").focus();	
            }
        });
    });
	
	$('#hide_mmtct').click(function(){
		if($(this).prop("checked") == true){
			$("#disablepanel").removeClass("disabledbutton");
			
		} else if($(this).prop("checked") == false){
			$("#disablepanel").addClass("disabledbutton");
		}
	});
	
	function showchecktagdesc(selectedcheckboxval){
		var innerdata = '<div data-collapsed="0" class="panel panel-primary"><div class="panel-heading"> <div class="panel-title">Reference Information!!</div> </div> <div class="panel-body"> <p>'+document.getElementById(selectedcheckboxval).value+'</p> </div> </div> </div>';
		$('#chktagdesc').html(innerdata);
	}
	
	
$(document).ready(function(){  

$('#txtmedicalterm').keyup(function(){  
           var keyvalue = $(this).val();  
		   if(keyvalue !='') {
				axios.post('{{ get_option('site_url') }}api/v1/indexings/ajax/terms', {
					searchterm: keyvalue
				})
				.then(function (response) {
					$('#suggesstion-box').fadeIn();  
                    $('#suggesstion-box').html(response.data); 
					<!--toastr.success(response.data.message, '@langapp('success') ');-->
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
      }); 
 });  
function selectedTerms(name,term){
	$("#txtmedicalterm").val(name);
	$("#txtmedicaltermtype").val(term);
	$("#suggesstion-box").hide();
	if(term != 'DIS'){
		$('#dieseasesenablelink').addClass("disabledbutton");
	} else {
	 	$("#dieseasesenablelink").removeClass("disabledbutton");
	} 
}
</script>
@endpush