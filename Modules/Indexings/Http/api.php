<?php

Route::group(
    ['middleware' => 'auth:api', 'prefix' => 'api/v1', 'namespace' => 'Modules\Indexings\Http\Controllers\Api\v1'],
    function () {
        Route::get('indexings/{id}/calls', 'IndexingsApiController@calls')->name('indexings.api.calls')->middleware('can:menu_indexings');
        Route::get('indexings/{id}/todos', 'IndexingsApiController@todos')->name('indexings.api.todos')->middleware('can:menu_indexings');
        Route::get('indexings/{id}/comments', 'IndexingsApiController@comments')->name('indexings.api.comments')->middleware('can:menu_indexings');
        Route::post('indexings/{id}/nextstage', 'IndexingsApiController@nextStage')->name('indexings.api.next.stage')->middleware('can:indexings_update');
        Route::post('indexings/{id}/movestage', 'IndexingsApiController@moveStage')->name('indexings.api.movestage')->middleware('can:indexings_update');
        Route::post('indexings/{id}/convert', 'IndexingsApiController@convert')->name('indexings.api.convert')->middleware('can:deals_create');
        Route::get('indexings', 'IndexingsApiController@index')->name('indexings.api.index')->middleware('can:menu_indexings');
        Route::get('indexings/{id}', 'IndexingsApiController@show')->name('indexings.api.show')->middleware('can:menu_indexings');
       // Route::post('indexings', 'IndexingsApiController@save')->name('indexings.api.save')->middleware('can:indexings_create');
        Route::put('indexings/{id}', 'IndexingsApiController@update')->name('indexings.api.update')->middleware('can:indexings_update');
        Route::delete('indexings/{id}', 'IndexingsApiController@delete')->name('indexings.api.delete')->middleware('can:indexings_delete');
		
		
		Route::post('indexings/savesection', 'IndexingsApiController@savesection')->name('indexings.api.savesection');
		Route::post('indexings/savemedical', 'IndexingsApiController@savemedical')->name('indexings.api.savemedical');
		Route::post('indexings/savedrug', 'IndexingsApiController@savedrug')->name('indexings.api.savedrug');
		Route::post('indexings/savedruglinks', 'IndexingsApiController@savedruglinks')->name('indexings.api.savedruglinks');
		Route::post('indexings/savedrugtradename', 'IndexingsApiController@savedrugtradename')->name('indexings.api.savedrugtradename');
		Route::post('indexings/savedevicetradename', 'IndexingsApiController@savedevicetradename')->name('indexings.api.savedevicetradename');
		
		Route::post('indexings/savemedicaldeviceindexing', 'IndexingsApiController@savemedicaldeviceindexing')->name('indexings.api.savemedicaldeviceindexing');
		
		Route::post('indexings/savectn', 'IndexingsApiController@savectn')->name('indexings.api.savectn');
		
		Route::post('indexings/ajax/classification', 'IndexingsApiController@classification')->name('indexings.api.classification')->middleware('can:menu_indexings');
		Route::post('indexings/ajax/terms', 'IndexingsApiController@terms')->name('indexings.api.terms')->middleware('can:menu_indexings');
		Route::post('indexings/ajax/termdrug', 'IndexingsApiController@termdrug')->name('indexings.api.termdrug')->middleware('can:menu_indexings');
		Route::post('indexings/ajax/termemmans', 'IndexingsApiController@termemmans')->name('indexings.api.termemmans')->middleware('can:menu_indexings');
		Route::post('indexings/ajax/termcountry', 'IndexingsApiController@termcountry')->name('indexings.api.termcountry')->middleware('can:menu_indexings');
		Route::post('indexings/ajax/tradenamedata', 'IndexingsApiController@tradenamedata')->name('indexings.api.tradenamedata')->middleware('can:menu_indexings');
		Route::post('indexings/ajax/devicetradenamedata', 'IndexingsApiController@devicetradenamedata')->name('indexings.api.devicetradenamedata')->middleware('can:menu_indexings');
		Route::post('indexings/ajax/termdevice', 'IndexingsApiController@termdevice')->name('indexings.api.termdevice')->middleware('can:menu_indexings');
		
		Route::post('indexings/ajax/sublink', 'IndexingsApiController@sublink')->name('indexings.api.sublink')->middleware('can:menu_indexings');
		
		Route::delete('indexings/{id}/sectionindex', 'IndexingsApiController@deletesection')->name('indexings.api.deletesection')->middleware('can:indexings_delete');
		Route::delete('indexings/{id}/{jobid}/{orderid}/medicaltermindex', 'IndexingsApiController@deletemedical')->name('indexings.api.deletemedical')->middleware('can:indexings_delete');
		Route::delete('indexings/{id}/{jobid}/{orderid}/medicalchecktagtermindex', 'IndexingsApiController@deletemedicalchecktag')->name('indexings.api.deletemedicalchecktag')->middleware('can:indexings_delete');
		
		Route::delete('indexings/{id}/drugctnindex', 'IndexingsApiController@deletectn')->name('indexings.api.deletectn')->middleware('can:indexings_delete');
		
		Route::delete('indexings/{id}/drugtrademanufacture', 'IndexingsApiController@drugtrademanufacture')->name('indexings.api.drugtrademanufacture')->middleware('can:indexings_delete');
		
		Route::delete('indexings/{id}/{value}/deletedrugtradename', 'IndexingsApiController@deletedrugtradename')->name('indexings.api.deletedrugtradename')->middleware('can:indexings_delete');
		
		Route::delete('indexings/{id}/{value}/deletedevicetradename', 'IndexingsApiController@deletedevicetradename')->name('indexings.api.deletedevicetradename')->middleware('can:indexings_delete');
		
		Route::post('indexings/frmdrugotherfield', 	'IndexingsApiController@frmdrugotherfield')->name('indexings.api.frmdrugotherfield');
		Route::post('indexings/frmdrugtherapy', 	'IndexingsApiController@frmdrugtherapy')->name('indexings.api.frmdrugtherapy');
		Route::post('indexings/frmdrugdoseinfo', 	'IndexingsApiController@frmdrugdoseinfo')->name('indexings.api.frmdrugdoseinfo');
		Route::post('indexings/frmrouteofdrug', 	'IndexingsApiController@frmrouteofdrug')->name('indexings.api.frmrouteofdrug');
		Route::post('indexings/frmdosefrequency', 	'IndexingsApiController@frmdosefrequency')->name('indexings.api.frmdosefrequency');
		Route::post('indexings/frmdrugcombination', 'IndexingsApiController@frmdrugcombination')->name('indexings.api.frmdrugcombination');
		Route::post('indexings/frmadversedrug', 	'IndexingsApiController@frmadversedrug')->name('indexings.api.frmadversedrug');
		Route::post('indexings/frmdrugcomparison', 	'IndexingsApiController@frmdrugcomparison')->name('indexings.api.frmdrugcomparison');
		Route::post('indexings/frmdrugdosage', 		'IndexingsApiController@frmdrugdosage')->name('indexings.api.frmdrugdosage');
		Route::post('indexings/frmdruginteraction', 'IndexingsApiController@frmdruginteraction')->name('indexings.api.frmdruginteraction');
		Route::post('indexings/frmdrugpharma', 		'IndexingsApiController@frmdrugpharma')->name('indexings.api.frmdrugpharma');
		Route::post('indexings/frmdrugtradename', 	'IndexingsApiController@frmdrugtradename')->name('indexings.api.frmdrugtradename');		
		Route::post('indexings/savemedicalindexing', 'IndexingsApiController@savemedicalindexing')->name('indexings.api.savemedicalindexing');
		
		Route::delete('indexings/{id}/{jobid}/{orderid}/medicaldevicetermindex', 'IndexingsApiController@deletemedicaldevice')->name('indexings.api.deletemedicaldevice')->middleware('can:indexings_delete');
		
		Route::post('indexings/ajax/esvsentences', 'IndexingsApiController@esvsentences')->name('indexings.api.esvsentences')->middleware('can:menu_indexings');
		Route::post('indexings/saveesvdata', 'IndexingsApiController@saveesvdata')->name('indexings.api.saveesvdata');
		
		
    }
);
