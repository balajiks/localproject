<div class="row">
  <div class="col-lg-12">
    <section class="panel panel-default">
      <header class="panel-heading">
        <header class="btn btn-primary btn-sm" style="margin-left:20px;"> <?php echo e(svg_image('solid/exclamation-circle')); ?> <?php echo trans('app.'.'drugtradeinfo'); ?>  &nbsp;&nbsp;[ <span class="btn btn-warning btn-xs"><?php echo trans('app.'.'drugtradename'); ?> </span> ]</header>
      </header>
      <?php 
      $translations = Modules\Settings\Entities\Options::translations();
      $default_country = get_option('company_country');
      $disable = '';
      ?>
      
      <?php if($jobdata->publ == 'Yes' && $jobdata->abs == 'Yes'): ?>
      $disable = 'disabled';
      <?php endif; ?>
      <div class="panel-body">
        <div class="col-md-12"> 
              <?php echo Form::open(['route' => 'indexings.api.savedrugtradename', 'id' => 'createDrugTradeName', 'class' => 'bs-example form-horizontal m-b-none']); ?>

              <div class="col-md-6">
                <input type="hidden" class="form-control" placeholder="jobid" name="jobid" id="jobid" value="<?php echo e($jobdata->id); ?>" />
                <input type="hidden" class="form-control" placeholder="pui" name="pui" id="pui" value="<?php echo e($jobdata->pui); ?>" />
                <input type="hidden" class="form-control" placeholder="orderid" name="orderid" id="orderid" value="<?php echo e($jobdata->orderid); ?>" />
                <input type="hidden" name="json" value="false"/>
				<input type="hidden" id="id" name="id" value="0"/>
                <div class="form-group">
                  <div class="col-lg-2">
                    <label>
                    <input type="radio" id="txtdrugma" name="termDTNindexing" value="1">
                    <span class="label-text text-info"><?php echo trans('app.'.'ma'); ?></span></label>
                  </div>
                  <div class="col-lg-2">
                    <label>
                    <input type="radio" id="txtdrugtr" name="termDTNindexing" value="0">
                    <span class="label-text text-info"><?php echo trans('app.'.'tr'); ?></span></label>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-lg-12">
                    <div class="form-group" id="divmaname">
                      <div class="col-lg-6">
                        <label class="control-label" for="fname">Drug Manufacture Name</label>
                        <input type="text" class="form-control" id="txtdrugmanufacturename" placeholder="Drug Manufacture Name" name="txtdrugmanufacturename" disabled="disabled" value="<?php echo e(@$data['txtdrugmanufacturename']); ?>" autocomplete="off" />
                        <div id="suggesstiondrungtrade-box"></div>
                      </div>
                      <div class="col-lg-5">
                        <label class="control-label" for="fname">Country Code</label>
                        <input type="text" class="form-control" id="txtcountrycode" placeholder="Country code" name="txtcountrycode" disabled="disabled" value="<?php echo e(@$data['txtcountrycode']); ?>" autocomplete="off" />
                        <div id="suggesstioncountry-box"></div>
                      </div>
                    </div>
					</div>
					
					<div class="col-lg-12">
                    <div class="form-group" id="divtrname">
                      <div class="col-lg-6 input_fields_wrap" >
                        <label class="control-label" for="fname">Drug Trade Name</label>
                        <input type="text" class="form-control" id="txtdrugtradename" placeholder="Drug Trade Name" name="txtdrugtradename[]" disabled="disabled" value="<?php echo e(@$data['txtdrugtradename']); ?>" autocomplete="off" />
                      </div>
                      <div class="col-lg-2">
                        <label class="control-label" for="fname">&nbsp;</label>
                        <div class="add_field_button btn btn-success btn-xs" style="margin-top:25px;">Add More</div>
                      </div>
                    </div>
					</div>
					
					
					
					
					
				  </div>
                  <div class="col-lg-4" id="hidebtn"> <br />
<?php echo renderAjaxButtonSquare('save'); ?> </div>
                </div>
              
			  
			  <div class="col-md-6">
			  <div class="form-group">
					<div class="drugtrade-list" id="nestable"> <?php echo app('arrilot.widget')->run('Indexings\ShowDrugTradeName', ['user_id' => \Auth::id(), 'jobid' => $jobdata->id, 'orderid' => $jobdata->orderid]); ?> </div>
					
					</div>
			  </div>
              <?php echo Form::close(); ?>

              
             
          </div>
      </div>
      <div class="panel-footer"> </div>
    </section>
  </div>
</div>
<?php $__env->startPush('pagestyle'); ?>
<style>
.frmSearch {border: 1px solid #a8d4b1;margin: 2px 0px;border-radius:4px;}
#termList{float:left;list-style:none;width:94%;padding:0; position: absolute; z-index:1; overflow-y:scroll; height:250px;}
#termList li{padding: 5px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
#termList li:hover{background:#ece3d2;cursor: pointer;}
#search-box{padding: 5px;border: #a8d4b1 1px solid;border-radius:4px;}
.activeli {  background:#c9cba3 !important;}
.active,.dd3-content { cursor:pointer;}
.color-box{margin:15px 0;padding-left:20px}
.space{margin-bottom:25px!important}
.shadow{background:#F7F8F9;padding:3px;margin:10px 0}
.info-tab{float:left;margin-left:-23px}

.info-tab{width:40px;height:40px;display:inline-block;position:relative;top:8px}
.info-tab::before,.info-tab::after{display:inline-block;color:#fff;line-height:normal;font-family:"icomoon";position:absolute}
.info-tab i::before,.info-tab i::after{content:"";display:inline-block;position:absolute;left:0;bottom:-15px;transform:rotateX(60deg)}
.info-tab i::before{width:20px;height:20px;box-shadow:inset 12px 0 13px rgba(0,0,0,0.5)}
.info-tab i::after{width:0;height:0;border:12px solid transparent;border-bottom-color:#fff;border-left-color:#fff;bottom:-18px}


.tip-icon{background:#92CD59}
.tip-box{color:#2e5014;background:#d5efc2}
.tip-box a{color:#439800}


.alert {
  border-radius: 0;
  -webkit-border-radius: 0;
  box-shadow: 0 1px 2px rgba(0,0,0,0.11);
  display: table;
  width: 100%;
  padding-left:60px !important;
}

.alert-white {
  background-image: linear-gradient(to bottom, #fff, #f9f9f9);
  border-top-color: #d8d8d8;
  border-bottom-color: #bdbdbd;
  border-left-color: #cacaca;
  border-right-color: #cacaca;
  color: #404040;
  padding-left: 61px;
  position: relative;
}

.alert-white.rounded {
  border-radius: 3px;
  -webkit-border-radius: 3px;
}

.alert-white.rounded .icon {
  border-radius: 3px 0 0 3px;
  -webkit-border-radius: 3px 0 0 3px;
}

.alert-white .icon {
  text-align: center;
  width: 45px;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  border: 1px solid #bdbdbd;
  padding-top: 15px;
}


.alert-white .icon:after {
  -webkit-transform: rotate(45deg);
  -moz-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  -o-transform: rotate(45deg);
  transform: rotate(45deg);
  display: block;
  content: '';
  width: 10px;
  height: 10px;
  border: 1px solid #bdbdbd;
  position: absolute;
  border-left: 0;
  border-bottom: 0;
  top: 50%;
  right: -6px;
  margin-top: -3px;
  background: #fff;
}

.alert-white .icon i {
  font-size: 20px;
  color: #fff;
  left: 12px;
  margin-top: -10px;
  position: absolute;
  top: 50%;
}
/*============ colors ========*/
.alert-success {
  color: #3c763d;
  background-color: #dff0d8;
  border-color: #d6e9c6;
}

.alert-white.alert-success .icon, 
.alert-white.alert-success .icon:after {
  border-color: #54a754;
  background: #60c060;
}

.alert-info {
  background-color: #d9edf7;
  border-color: #98cce6;
  color: #3a87ad;
}

.alert-white.alert-info .icon, 
.alert-white.alert-info .icon:after {
  border-color: #3a8ace;
  background: #4d90fd;
}


.alert-white.alert-warning .icon, 
.alert-white.alert-warning .icon:after {
  border-color: #d68000;
  background: #fc9700;
}

.alert-warning {
  background-color: #fcf8e3;
  border-color: #f1daab;
  color: #c09853;
}

.alert-danger {
  background-color: #f2dede;
  border-color: #e0b1b8;
  color: #b94a48;
}

.alert-white.alert-danger .icon, 
.alert-white.alert-danger .icon:after {
  border-color: #ca452e;
  background: #da4932;
}



#preloader
{
	position: absolute;
	top: 150px;
	left: 250px;
	z-index: 100;
	padding: 5px;
	text-align: center;
	background-color: #FFFFFF;
	border: 1px solid #000000;
}
</style>
<link rel=stylesheet href="<?php echo e(getAsset('plugins/nestable/nestable.css')); ?>">
<?php echo $__env->make('stacks.css.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('pagescript'); ?>
<?php echo $__env->make('stacks.js.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<script>

$("#divmaname").hide();
$("#divtrname").hide();
$("#hidebtn").hide();


$(function () {
	$("input[name='termDTNindexing']").click(function () {
	$("#hidebtn").show();
		if ($("#txtdrugma").is(":checked")) {
			$("#divmaname").show();
			$("#divtrname").show();
			$("#txtdrugmanufacturename").removeAttr("disabled");
			$("#txtcountrycode").removeAttr("disabled");
			$("#txtdrugtradename").removeAttr("disabled");
			$("#txtdrugmanufacturename").focus();
		} else {
			$("#divmaname").hide();
			$("#divtrname").show();
			$("#txtdrugtradename").removeAttr("disabled");
			$("#txtdrugtradename").focus();
		
			
		}
	});
});

$('#txtdrugmanufacturename').keyup(function(){
   var keyvalue = $(this).val();  
   if(keyvalue !='') {
		axios.post('<?php echo e(get_option('site_url')); ?>api/v1/indexings/ajax/termemmans', {
			searchterm: keyvalue
		})
		.then(function (response) {
			$('#suggesstiondrungtrade-box').fadeIn();  
			$('#suggesstiondrungtrade-box').html(response.data); 
			<!--toastr.success(response.data.message, '<?php echo trans('app.'.'success'); ?> ');-->
		})
		.catch(function (error) {
			var errors = error.response.data.errors;
			var errorsHtml= '';
			$.each( errors, function( key, value ) {
				errorsHtml += '<li>' + value[0] + '</li>';
			});
			toastr.error( errorsHtml , '<?php echo trans('app.'.'response_status'); ?> ');
		});
	}
}); 

function selectedemmansTerms(name){
	$("#txtdrugmanufacturename").val(name);
	$("#suggesstiondrungtrade-box").hide();
}
$('#txtcountrycode').keyup(function(){  
 var keyvalue = $(this).val();  
   if(keyvalue !='') {
		axios.post('<?php echo e(get_option('site_url')); ?>api/v1/indexings/ajax/termcountry', {
			searchterm: keyvalue
		})
		.then(function (response) {
			$('#suggesstioncountry-box').fadeIn();  
			$('#suggesstioncountry-box').html(response.data); 
			<!--toastr.success(response.data.message, '<?php echo trans('app.'.'success'); ?> ');-->
		})
		.catch(function (error) {
			var errors = error.response.data.errors;
			var errorsHtml= '';
			$.each( errors, function( key, value ) {
				errorsHtml += '<li>' + value[0] + '</li>';
			});
			toastr.error( errorsHtml , '<?php echo trans('app.'.'response_status'); ?> ');
		});
	}
});

function ajaxtradename(val){
   if(val !='') {
   
    $("[id^=ajaxterm]").removeClass("activeli");
	$("#ajaxterm-"+val).addClass("activeli");
   
   
		axios.post('<?php echo e(get_option('site_url')); ?>api/v1/indexings/ajax/tradenamedata', {
			selectedterm: val
		})
		.then(function (response) {
			$('#ajaxdrugtradename').fadeIn();  
			$('#ajaxdrugtradename').prepend(response.data.htmldrugterm);
			
			
		if (response.data.type == 'ma') {
			$('#txtdrugma').prop('checked',true);
			$('#txtdrugtr').prop('checked',false);
			$("#divmaname").show();
			$("#divtrname").show();
			$("#txtdrugmanufacturename").val(response.data.manufacturename);
			$("#txtcountrycode").val(response.data.countrycode);
			$("#txtdrugtradename").removeAttr("disabled");
			$("#id").val(val);
			$("#hidebtn").show();
			
			
		} else {
			$('#txtdrugma').prop('checked',false);
			$('#txtdrugtr').prop('checked',true);
			$("#divmaname").hide();
			$("#divtrname").show();
			$("#txtdrugtradename").removeAttr("disabled");
			$("#txtdrugtradename").focus();
			$("#hidebtn").show();
		}
			
			 
			<!--toastr.success(response.data.message, '<?php echo trans('app.'.'success'); ?> ');-->
		})
		.catch(function (error) {
			var errors = error.response.data.errors;
			var errorsHtml= '';
			$.each( errors, function( key, value ) {
				errorsHtml += '<li>' + value[0] + '</li>';
			});
			toastr.error( errorsHtml , '<?php echo trans('app.'.'response_status'); ?> ');
		});
	}


}
	  
function selectedCountry(name){
	$("#txtcountrycode").val(name);
	$("#suggesstioncountry-box").hide();
}



	var max_fields      = 10;
	var wrapper   		= $(".input_fields_wrap"); 
	var add_button      = $(".add_field_button");
	
	var x = 1; 
	
	
	
	$(add_button).click(function(e){ 
		e.preventDefault();
		if(x < max_fields){
			x++; 
				$(wrapper).append('<div><input type="text" class="form-control" id="txtdrugtradename" placeholder="Drug Trade Name" name="txtdrugtradename[]" value="<?php echo e(@$data['txtdrugtradename']); ?>" autocomplete="off" /><a href="#" class="remove_field btn btn-danger btn-xs">Remove</a></div>'); 
		}
	});
	
	$(wrapper).on("click",".remove_field", function(e){ 
		e.preventDefault(); $(this).parent('div').remove(); x--;
	})
	



		
</script>
<?php $__env->stopPush(); ?>