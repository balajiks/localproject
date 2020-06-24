<?php $__env->startPush('pagescript'); ?>
	<script>

/*-----------------------------------------------------------------------------------------------------------*/
/*----------------------------------Field Section Indexing --------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
        $('#createSection').on('submit', function(e) {
            $(".formSaving").html('Processing..<i class="fas fa-spin fa-spinner"></i>');
			
            e.preventDefault();
            var indexersection, data;
            indexersection = $(this);
            data = indexersection.serialize();

            axios.post('<?php echo e(route('indexings.api.savesection')); ?>', data)
            .then(function (response) {
                    $('.section-list').prepend(response.data.html);
					$('#indexer_section').val(null).trigger('change');
					console.log(response);
					
					var maxselect = <?php echo trans('app.'.'sectioncnt'); ?> - response.data.count;
					$("#indexer_section").select2({
        				maximumSelectionLength: maxselect
    				});
					
					$("#sectioncount").val(response.data.count);
					$("#indexersectioncount").html(response.data.count);
					
					if(response.data.count == <?php echo trans('app.'.'sectioncnt'); ?>){
						$("#frmindexsectionshow").addClass("disabled");
						$("#frmindexsectionshow").attr("disabled", "disabled");	
					} else {
						$("#frmindexsectionshow").removeClass("disabled");
						$("#frmindexsectionshow").removeAttr("disabled");
						$("#indexer_section").focus();
					}
					
                    toastr.success( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
                    $(".formSaving").html('<i class="fas fa-save"></i> <?php echo trans('app.'.'save'); ?> </span>');
                    tag[0].reset();
            })
            .catch(function (error) {
                var errors = error.response.data.errors;
                var errorsHtml= '';
                $.each( errors, function( key, value ) {
                errorsHtml += '<li>' + value[0] + '</li>';
                });
                toastr.error( errorsHtml , '<?php echo trans('app.'.'response_status'); ?> ');
                $(".formSaving").html('<i class="fas fa-sync"></i> Try Again');
        });

        });

		$('.section-list').on('click', '.deleteSection', function (e) {
            e.preventDefault();
            var section, id;

            section = $(this);
            id = section.data('section-id');

            if(!confirm('Do you want to delete this message?')) {
                return false;
            }
            axios.delete('<?php echo e(get_option("site_url")); ?>api/v1/indexings/'+id+'/sectionindex', {
                    id: id,
            })
            .then(function (response) {
						var sectioncnt = $('#sectioncount').val() -1;
						$('#indexersectioncount').val(sectioncnt);
						
						$("#sectioncount").val(sectioncnt);
						$("#indexersectioncount").html(sectioncnt);
						
						alert(<?php echo trans('app.'.'sectioncnt'); ?> - $('#indexersectioncount').val());
						$("#indexer_section").select2({
							maximumSelectionLength: <?php echo trans('app.'.'sectioncnt'); ?> - $('#indexersectioncount').val()
						});
			
                    toastr.warning( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
                    $('#section-' + id).hide(500, function () {
                        $(this).remove();
                    });
            })
            .catch(function (error) {
                toastr.error( 'Oops! Something went wrong!' , '<?php echo trans('app.'.'response_status'); ?> ');
        });

        });
		
		
/*-----------------------------------------------------------------------------------------------------------*/
/*----------------------------------Field Section Medical --------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/		
		
		$('#createMedical').on('submit', function(e) {
            $(".formSaving").html('Processing..<i class="fas fa-spin fa-spinner"></i>');
            e.preventDefault();
            var indexermedical, data;
            indexermedical = $(this);
            
			
			var medical_checktags = [];  
           $('.mmtctselectdata').each(function(){  
                if($(this).is(":checked"))  
                {  
                     medical_checktags.push($(this).val());  
                }  
           });  
		   data = data +medical_checktags;
		   data = indexermedical.serialize();
            axios.post('<?php echo e(route('indexings.api.savemedical')); ?>', data)
            .then(function (response) {
					$("#txtmedicalterm").val('');
					$('#indexer_diseaseslink').val(null).trigger('change');
					$("#txtmedicalmajor").prop("checked", false);
					$("#txtmedicalminor").prop("checked", false);
					$("#hide_mmtct").val("FALSE");
					
					$('#dieseasesenablelink').addClass("disabledbutton");
					$('#txtmedicalterm').prop("disabled", true);
					
					if(response.data.type == '1') {
                    	$('#major-listdata').prepend(response.data.htmlmedicalterm);	
					} else {
						$('#minor-listdata').prepend(response.data.htmlmedicalterm);
					}
					 
					$('#diseases-listdata').prepend(response.data.htmldiseases); 
					$('#checktags-listdata').prepend(response.data.htmlchecktag);					
									
					$("#medchecktagtotalajax").html(response.data.checktagcount);
					$("#medminortotalajax").html(response.data.minorcount);
					$("#medmajortotalajax").html(response.data.majorcount);					
					$("#medtotalajax").html(response.data.totalmedcountterm);					
					$("#meddiseasestotalajax").html(response.data.diseasescount);					
					
					console.log(response);
                    toastr.success( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
                    $(".formSaving").html('<i class="fas fa-save"></i> <?php echo trans('app.'.'save'); ?> </span>');
                    tag[0].reset();
            })
            .catch(function (error) {
                var errors = error.response.data.errors;
                var errorsHtml= '';
                $.each( errors, function( key, value ) {
                errorsHtml += '<li>' + value[0] + '</li>';
                });
                toastr.error( errorsHtml , '<?php echo trans('app.'.'response_status'); ?> ');
                $(".formSaving").html('<i class="fas fa-sync"></i> Try Again');
        });
        });
		
		
		$('#major-mediallistdata').on('click', '.deletemedicalterm', function (e) {
            e.preventDefault();
            var medical, id;

            medical = $(this);
            id 		= 	medical.data('section-id');
			jobid 	=  $('#jobid').val();
			orderid =  $('#orderid').val();

            if(!confirm('Do you want to delete this message?')) {
                return false;
            }
            axios.delete('<?php echo e(get_option("site_url")); ?>api/v1/indexings/'+id+'/'+jobid+'/'+orderid+'/medicaltermindex', {
                    id: id,
					jobid: jobid,
					orderid: orderid,
            })
            .then(function (response) {
			
					$("#medchecktagtotalajax").html(response.data.checktagcount);
					$("#medminortotalajax").html(response.data.minorcount);
					$("#medmajortotalajax").html(response.data.majorcount);					
					$("#medtotalajax").html(response.data.totalmedcountterm);					
					$("#meddiseasestotalajax").html(response.data.diseasescount);
					$('#termsdata-' + id).hide(500, function () {
                        $(this).remove();
                    });	
					
					$('#termsdiseasesdata-' + id).hide(500, function () {
                        $(this).remove();
                    });
			
			
			
                    toastr.warning( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
                    
					
            })
            .catch(function (error) {
                toastr.error( 'Oops! Something went wrong!' , '<?php echo trans('app.'.'response_status'); ?> ');
        });

        });
		
		$('#minor-mediallistdata').on('click', '.deletemedicalterm', function (e) {
            e.preventDefault();
            var medical, id;

            medical = $(this);
            id 		= 	medical.data('section-id');
			jobid 	=  $('#jobid').val();
			orderid =  $('#orderid').val();

            if(!confirm('Do you want to delete this message?')) {
                return false;
            }
            axios.delete('<?php echo e(get_option("site_url")); ?>api/v1/indexings/'+id+'/'+jobid+'/'+orderid+'/medicaltermindex', {
                    id: id,
					jobid: jobid,
					orderid: orderid,
            })
            .then(function (response) {
			
					$("#medchecktagtotalajax").html(response.data.checktagcount);
					$("#medminortotalajax").html(response.data.minorcount);
					$("#medmajortotalajax").html(response.data.majorcount);					
					$("#medtotalajax").html(response.data.totalmedcountterm);					
					$("#meddiseasestotalajax").html(response.data.diseasescount);
					$('#termsdata-' + id).hide(500, function () {
                        $(this).remove();
                    });	
					
					$('#termsdiseasesdata-' + id).hide(500, function () {
                        $(this).remove();
                    });
			
                    toastr.warning( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
                    
					
            })
            .catch(function (error) {
                toastr.error( 'Oops! Something went wrong!' , '<?php echo trans('app.'.'response_status'); ?> ');
        });

        });
		
		$('#diseases-listdata').on('click', '.deletemedicalterm', function (e) {
            e.preventDefault();
            var medical, id;

            medical = $(this);
            id 		= 	medical.data('section-id');
			jobid 	=  $('#jobid').val();
			orderid =  $('#orderid').val();

            if(!confirm('Do you want to delete this message?')) {
                return false;
            }
            axios.delete('<?php echo e(get_option("site_url")); ?>api/v1/indexings/'+id+'/'+jobid+'/'+orderid+'/medicaltermindex', {
                    id: id,
					jobid: jobid,
					orderid: orderid,
            })
            .then(function (response) {
			
					$("#medchecktagtotalajax").html(response.data.checktagcount);
					$("#medminortotalajax").html(response.data.minorcount);
					$("#medmajortotalajax").html(response.data.majorcount);					
					$("#medtotalajax").html(response.data.totalmedcountterm);					
					$("#meddiseasestotalajax").html(response.data.diseasescount);
					
					$('#termsdiseasesdata-' + id).hide(500, function () {
                        $(this).remove();
                    });	
					
					$('#termsdata-' + id).hide(500, function () {
                        $(this).remove();
                    });
			
                    toastr.warning( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
                    
					
            })
            .catch(function (error) {
                toastr.error( 'Oops! Something went wrong!' , '<?php echo trans('app.'.'response_status'); ?> ');
        });

        });
		
		$('#checktag-mediallistdata').on('click', '.deletechecktag', function (e) {
            e.preventDefault();
            var checktags, id;

            checktags 	= 	$(this);
            id 			= 	checktags.data('section-id');
			jobid 		=  	$('#jobid').val();
			orderid 	=  	$('#orderid').val();

            if(!confirm('Do you want to delete this message?')) {
                return false;
            }
            axios.delete('<?php echo e(get_option("site_url")); ?>api/v1/indexings/'+id+'/'+jobid+'/'+orderid+'/medicalchecktagtermindex', {
                    id: id,
					jobid: jobid,
					orderid: orderid,
            })
            .then(function (response) {
			
					$("#medchecktagtotalajax").html(response.data.checktagcount);
					$("#medminortotalajax").html(response.data.minorcount);
					$("#medmajortotalajax").html(response.data.majorcount);					
					$("#medtotalajax").html(response.data.totalmedcountterm);					
					$("#meddiseasestotalajax").html(response.data.diseasescount);
					$('#checktag-' + id).hide(500, function () {
                        $(this).remove();
                    });	
			
                    toastr.warning( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
                    
					
            })
            .catch(function (error) {
                toastr.error( 'Oops! Something went wrong!' , '<?php echo trans('app.'.'response_status'); ?> ');
        });

        });
		

/*-----------------------------------------------------------------------------------------------------------*/
/*----------------------------------Field Section Medical --------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/		

	$('#createDrug').on('submit', function(e) {
            $(".formSaving").html('Processing..<i class="fas fa-spin fa-spinner"></i>');
            e.preventDefault();
            var indexerdrug, data;
            indexerdrug = $(this);
			
		    data = indexerdrug.serialize();
            axios.post('<?php echo e(route('indexings.api.savedrug')); ?>', data)
            .then(function (response) {
					console.log(response);
					<!--return false;-->
					$("#txtdrugmedicalterm").val('');
					$("#txtdrugmajor").prop("checked", false);
					$("#txtdrugminor").prop("checked", false);
					
					if(response.data.type == '1') {
                    	$('#major-listdata').prepend(response.data.htmldrugterm);	
					} else {
						$('#minor-listdata').prepend(response.data.htmldrugterm);
					}
					 
									
					$("#drugminortotalajax").html(response.data.minorcount);
					$("#drugmajortotalajax").html(response.data.majorcount);					
					$("#drugtotalajax").html(response.data.totaldrugcountterm);					
					
					console.log(response);
                    toastr.success( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
                    $(".formSaving").html('<i class="fas fa-save"></i> <?php echo trans('app.'.'save'); ?> </span>');
                    indexerdrug[0].reset();
            })
            .catch(function (error) {
                var errors = error.response.data.errors;
                var errorsHtml= '';
                $.each( errors, function( key, value ) {
                errorsHtml += '<li>' + value[0] + '</li>';
                });
                toastr.error( errorsHtml , '<?php echo trans('app.'.'response_status'); ?> ');
                $(".formSaving").html('<i class="fas fa-sync"></i> Try Again');
        });
        });
		
		
		$('#createDrugLinks').on('submit', function(e) {
            $("#savebtn").html('Processing..<i class="fas fa-spin fa-spinner"></i>');
            e.preventDefault();
            var indexerdruglinks, data;
            indexerdruglinks = $(this);
			
		    data = indexerdruglinks.serialize();
            axios.post('<?php echo e(route('indexings.api.savedruglinks')); ?>', data)
            .then(function (response) {
					$("#tabcontent").empty().append(response.data.htmldrugterm);
					$("#preloader").hide();
					
                    toastr.success( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
                    $("#savebtn").html('<i class="fas fa-save"></i> <?php echo trans('app.'.'save'); ?> </span>');
					 
            })
            .catch(function (error) {
                var errors = error.response.data.errors;
                var errorsHtml= '';
                $.each( errors, function( key, value ) {
                errorsHtml += '<li>' + value[0] + '</li>';
                });
                toastr.error( errorsHtml , '<?php echo trans('app.'.'response_status'); ?> ');
                $("#savebtn").html('<i class="fas fa-sync"></i> Try Again');
        });
        });
		
		
		$('#createDrugTradeName').on('submit', function(e) {
            $("#savebtn").html('Processing..<i class="fas fa-spin fa-spinner"></i>');
            e.preventDefault();
            var indexerdruglinks, data;
            indexerdruglinks = $(this);
			
		    data = indexerdruglinks.serialize();
            axios.post('<?php echo e(route('indexings.api.savedrugtradename')); ?>', data)
            .then(function (response) {
					$('.drugtradename-list').prepend(response.data.htmldrugterm);
					
					alert(response.data.action);
			if(response.data.action == 'update') {
				alert(response.data.action);
				$('#termsdata-' + response.data.id).hide(500, function () {
					$(this).remove();
				});
				$('#ajaxtradename-listdata').hide(500, function () {
					$(this).remove();
				});
			}
					
                    toastr.success( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
                    $("#savebtn").html('<i class="fas fa-save"></i> <?php echo trans('app.'.'save'); ?> </span>');
					indexerdruglinks[0].reset(); 
            })
            .catch(function (error) {
                var errors = error.response.data.errors;
                var errorsHtml= '';
                $.each( errors, function( key, value ) {
                errorsHtml += '<li>' + value[0] + '</li>';
                });
                toastr.error( errorsHtml , '<?php echo trans('app.'.'response_status'); ?> ');
                $("#savebtn").html('<i class="fas fa-sync"></i> Try Again');
        });
        });
		
		
		$('.drugtradename-list').on('click', '.deletetradeterm', function (e) {
            e.preventDefault();
            var tradeterm, id;

            tradeterm = $(this);
            id = tradeterm.data('termsdata-id');

            if(!confirm('Do you want to delete this message?')) {
                return false;
            }
            axios.delete('<?php echo e(get_option("site_url")); ?>api/v1/indexings/'+id+'/drugtrademanufacture', {
                    id: id,
            })
            .then(function (response) {
				toastr.warning( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
				$('#ajaxtradename-listdata').hide(500, function () {
					$(this).remove();
				});
				$('#termsdata-' + id).hide(500, function () {
					$(this).remove();
				});
            })
            .catch(function (error) {
                toastr.error( 'Oops! Something went wrong!' , '<?php echo trans('app.'.'response_status'); ?> ');
        });

        });	
		
		
	
		$('#ajaxdrugtradename').on('click', '.deleteajaxtradelink', function (e) {
            e.preventDefault();
            var tradeterm, id, value;

            tradeterm = $(this);
            value = tradeterm.data('termsdata-id');
			id = $('#selectedmanuid').val();
			

            if(!confirm('Do you want to delete this message?')) {
                return false;
            }
            axios.delete('<?php echo e(get_option("site_url")); ?>api/v1/indexings/'+id+'/'+value+'/deletedrugtradename', {
                    id: id,
					value: value,
            })
            .then(function (response) {
				toastr.warning( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
				$('#ajaxtradelink-' + value).hide(500, function () {
					$(this).remove();
				});
				
            })
            .catch(function (error) {
                toastr.error( 'Oops! Something went wrong!' , '<?php echo trans('app.'.'response_status'); ?> ');
        });

        });	



/*-----------------------------------------------------------------------------------------------------------*/
/*----------------------------------Field Device Trade Name--------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/		

		$('#createDeviceTradeName').on('submit', function(e) {
            $("#savebtn").html('Processing..<i class="fas fa-spin fa-spinner"></i>');
            e.preventDefault();
            var indexerdevicelinks, data;
            indexerdevicelinks = $(this);
			
		    data = indexerdevicelinks.serialize();
            axios.post('<?php echo e(route('indexings.api.savedevicetradename')); ?>', data)
            .then(function (response) {
				$('.devicetradename-list').prepend(response.data.htmldrugterm);
				if(response.data.action == 'update') {
					alert(response.data.action);
					$('#termsdata-' + response.data.id).hide(500, function () {
						$(this).remove();
					});
					$('#ajaxtradename-listdata').hide(500, function () {
						$(this).remove();
					});
				}
				toastr.success( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
				$("#savebtn").html('<i class="fas fa-save"></i> <?php echo trans('app.'.'save'); ?> </span>');
				indexerdevicelinks[0].reset(); 
            })
            .catch(function (error) {
                var errors = error.response.data.errors;
                var errorsHtml= '';
                $.each( errors, function( key, value ) {
                errorsHtml += '<li>' + value[0] + '</li>';
                });
                toastr.error( errorsHtml , '<?php echo trans('app.'.'response_status'); ?> ');
                $("#savebtn").html('<i class="fas fa-sync"></i> Try Again');
        });
        });	
		
		$('#ajaxdevicetradename').on('click', '.deleteajaxtradelink', function (e) {
            e.preventDefault();
            var tradeterm, id, value;

            tradeterm = $(this);
            value = tradeterm.data('termsdata-id');
			id = $('#selectedmanuid').val();
			

            if(!confirm('Do you want to delete this message?')) {
                return false;
            }
            axios.delete('<?php echo e(get_option("site_url")); ?>api/v1/indexings/'+id+'/'+value+'/deletedevicetradename', {
                    id: id,
					value: value,
            })
            .then(function (response) {
				toastr.warning( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
				$('#ajaxtradelink-' + value).hide(500, function () {
					$(this).remove();
				});
				
            })
            .catch(function (error) {
                toastr.error( 'Oops! Something went wrong!' , '<?php echo trans('app.'.'response_status'); ?> ');
        });

        });	
		
/*-----------------------------------------------------------------------------------------------------------*/
/*----------------------------------Field Section CTN --------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/		
	$('#createCtn').on('submit', function(e) {
            $(".formSaving").html('Processing..<i class="fas fa-spin fa-spinner"></i>');
            e.preventDefault();
            var indexerctn, data;
            indexerctn = $(this);
			
		    data = indexerctn.serialize();
            axios.post('<?php echo e(route('indexings.api.savectn')); ?>', data)
            .then(function (response) {
					
					$('#trailnumberlist').prepend(response.data.htmlctnterm);				
					
					console.log(response);
                    toastr.success( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
                    $(".formSaving").html('<i class="fas fa-save"></i> <?php echo trans('app.'.'save'); ?> </span>');
                    indexerctn[0].reset();
            })
            .catch(function (error) {
                var errors = error.response.data.errors;
                var errorsHtml= '';
                $.each( errors, function( key, value ) {
                errorsHtml += '<li>' + value[0] + '</li>';
                });
                toastr.error( errorsHtml , '<?php echo trans('app.'.'response_status'); ?> ');
                $(".formSaving").html('<i class="fas fa-sync"></i> Try Again');
        });
        });
	
	$('.ctn-list').on('click', '.deleteCtn', function (e) {
            e.preventDefault();
            var section, id;

            section = $(this);
            id = section.data('ctn-id');

            if(!confirm('Do you want to delete this message?')) {
                return false;
            }
            axios.delete('<?php echo e(get_option("site_url")); ?>api/v1/indexings/'+id+'/drugctnindex', {
                    id: id,
            })
            .then(function (response) {
				toastr.warning( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
				$('#ctn-' + id).hide(500, function () {
					$(this).remove();
				});
            })
            .catch(function (error) {
                toastr.error( 'Oops! Something went wrong!' , '<?php echo trans('app.'.'response_status'); ?> ');
        });

        });	
	
	
	
	
	
/*-----------------------------------------------------------------------------------------------------------*/
/*----------------------------------Field Section CTN --------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/		
	
	$('#createMedicalIndexing').on('submit', function(e) {
            $(".formSaving").html('Processing..<i class="fas fa-spin fa-spinner"></i>');
            e.preventDefault();
            var indexerctn, data;
            indexerctn = $(this);
			
		    data = indexerctn.serialize();
            axios.post('<?php echo e(route('indexings.api.savemedicalindexing')); ?>', data)
            .then(function (response) {
					
					$("#txtdeviceterm").val('');
					$("#sublink").val('');
					$('#indexer_sublink').val(null).trigger('change');
					$("#txtmedicalmajor").prop("checked", false);
					$("#txtmedicalminor").prop("checked", false);
					
					$('#devicelink').addClass("disabledbutton");
					$('#txtdeviceterm').prop("disabled", true);
					
					if(response.data.type == '1') {
                    	$('#major-listdata').prepend(response.data.htmlmedicalterm);	
					} else {
						$('#minor-listdata').prepend(response.data.htmlmedicalterm);
					}
					 
					$('#sublink-listdata').prepend(response.data.htmldiseases); 
									
					$("#medminortotalajax").html(response.data.minorcount);
					$("#medmajortotalajax").html(response.data.majorcount);					
					$("#medtotalajax").html(response.data.totalmedcountterm);					
					$("#meddiseasestotalajax").html(response.data.diseasescount);				
					
					console.log(response);
                    toastr.success( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
                    $(".formSaving").html('<i class="fas fa-save"></i> <?php echo trans('app.'.'save'); ?> </span>');
                    indexerctn[0].reset();
            })
            .catch(function (error) {
                var errors = error.response.data.errors;
                var errorsHtml= '';
                $.each( errors, function( key, value ) {
                errorsHtml += '<li>' + value[0] + '</li>';
                });
                toastr.error( errorsHtml , '<?php echo trans('app.'.'response_status'); ?> ');
                $(".formSaving").html('<i class="fas fa-sync"></i> Try Again');
        });
        });
	
	
	
	
	$('#major-mediallistdata').on('click', '.deletemedicaldeviceterm', function (e) {
            e.preventDefault();
            var medical, id;

            medical = $(this);
            id 		= 	medical.data('section-id');
			jobid 	=  $('#jobid').val();
			orderid =  $('#orderid').val();

            if(!confirm('Do you want to delete this message?')) {
                return false;
            }
            axios.delete('<?php echo e(get_option("site_url")); ?>api/v1/indexings/'+id+'/'+jobid+'/'+orderid+'/medicaldevicetermindex', {
                    id: id,
					jobid: jobid,
					orderid: orderid,
            })
            .then(function (response) {
			
					$("#medchecktagtotalajax").html(response.data.checktagcount);
					$("#medminortotalajax").html(response.data.minorcount);
					$("#medmajortotalajax").html(response.data.majorcount);					
					$("#medtotalajax").html(response.data.totalmedcountterm);					
					$("#meddiseasestotalajax").html(response.data.diseasescount);
					$('#termsdata-' + id).hide(500, function () {
                        $(this).remove();
                    });	
					
					$('#termsdiseasesdata-' + id).hide(500, function () {
                        $(this).remove();
                    });
			
			
			
                    toastr.warning( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
                    
					
            })
            .catch(function (error) {
                toastr.error( 'Oops! Something went wrong!' , '<?php echo trans('app.'.'response_status'); ?> ');
        });

        });
		
		$('#minor-mediallistdata').on('click', '.deletemedicaldeviceterm', function (e) {
            e.preventDefault();
            var medical, id;

            medical = $(this);
            id 		= 	medical.data('section-id');
			jobid 	=  $('#jobid').val();
			orderid =  $('#orderid').val();

            if(!confirm('Do you want to delete this message?')) {
                return false;
            }
            axios.delete('<?php echo e(get_option("site_url")); ?>api/v1/indexings/'+id+'/'+jobid+'/'+orderid+'/medicaldevicetermindex', {
                    id: id,
					jobid: jobid,
					orderid: orderid,
            })
            .then(function (response) {
			
					$("#medchecktagtotalajax").html(response.data.checktagcount);
					$("#medminortotalajax").html(response.data.minorcount);
					$("#medmajortotalajax").html(response.data.majorcount);					
					$("#medtotalajax").html(response.data.totalmedcountterm);					
					$("#meddiseasestotalajax").html(response.data.diseasescount);
					$('#termsdata-' + id).hide(500, function () {
                        $(this).remove();
                    });	
					
					$('#termsdiseasesdata-' + id).hide(500, function () {
                        $(this).remove();
                    });
			
                    toastr.warning( response.data.message , '<?php echo trans('app.'.'response_status'); ?> ');
                    
					
            })
            .catch(function (error) {
                toastr.error( 'Oops! Something went wrong!' , '<?php echo trans('app.'.'response_status'); ?> ');
        });

        });
	
	
	
	
	
	
	
	
	
	
		
	</script>
<?php $__env->stopPush(); ?>