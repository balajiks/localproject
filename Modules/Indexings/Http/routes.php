<?php

Route::group(
    ['middleware' => 'web', 'prefix' => 'indexings', 'namespace' => 'Modules\Indexings\Http\Controllers'],
    function () {
        //Route::get('/', 'IndexingCustomController@index')->name('indexings.index')->middleware('can:menu_indexings');
		Route::get('/', 'IndexingCustomController@index')->name('indexings.index')->middleware('can:menu_indexings');
        Route::get('/create', 'IndexingCustomController@create')->name('indexings.create');
        Route::get('/view/{indexing}/{tab?}/{option?}', 'IndexingCustomController@view')->name('indexings.view');
        Route::get('/delete/{indexing}', 'IndexingCustomController@delete')->name('indexings.delete')->middleware('can:indexings_delete');
		Route::get('/add/{tab?}/{option?}', 'IndexingCustomController@addindexing')->name('indexings.addindexing');
		
		Route::get('/showmeta/{indexing}', 'IndexingCustomController@showmeta')->name('indexings.showmeta');
		Route::get('/showsource/{indexing}', 'IndexingCustomController@showsource')->name('indexings.showsource');
		
		Route::get('/sectioncreate', 'IndexingCustomController@sectioncreate')->name('indexings.sectioncreate');

        Route::get('/next-stage/{indexing}', 'IndexingCustomController@nextStage')->name('indexings.nextstage')->middleware('can:indexings_update');
        Route::get('/edit/{indexing}', 'IndexingCustomController@edit')->name('indexings.edit')->middleware('can:indexings_update');

        Route::post('bulk-delete', 'IndexingCustomController@bulkDelete')->name('indexings.bulk.delete')->middleware('can:indexings_delete');
        Route::post('bulk-email', 'IndexingCustomController@bulkEmail')->name('indexings.bulk.email')->middleware('can:indexings_update');
        Route::post('bulk-send', 'IndexingCustomController@sendBulk')->name('indexings.bulk.send');

        Route::get('/import', 'IndexingCustomController@import')->name('indexings.import')->middleware('can:indexings_create');
        Route::get('import/callback', 'IndexingCustomController@importGoogleContacts')->name('indexings.import.callback')->middleware('can:indexings_create');
        Route::get('/export', 'IndexingCustomController@export')->name('indexings.export')->middleware('can:menu_indexings');
        Route::post('csvmap', 'IndexingCustomController@parseImport')->name('indexings.csvmap')->middleware('can:indexings_create');
        Route::post('csvprocess', 'IndexingCustomController@processImport')->name('indexings.csvprocess')->middleware('can:indexings_create');

        Route::post('table-json', 'IndexingCustomController@tableData')->name('indexings.data')->middleware('can:menu_indexings');

        Route::get('/convert/{indexing}', 'IndexingCustomController@convert')->name('indexings.convert')->middleware('can:deals_create');

        Route::get('/consent/{indexing}', 'IndexingCustomController@sendConsent')->name('indexings.consent')->middleware('can:indexings_create');
        Route::get('/whatsapp-consent/{indexing}', 'IndexingCustomController@sendWhatsappConsent')->name('indexings.consent.whatsapp')->middleware('can:indexings_create');
        Route::get('/consent-accept/{token}', 'IndexingConsentController@accept')->name('indexings.consent.accept');
        Route::get('/consent-decline/{token}', 'IndexingConsentController@decline')->name('indexings.consent.decline');

        Route::post('/email-delete', 'IndexingCustomController@ajaxDeleteMail')->name('indexings.email.delete');
        Route::post('/email/{indexing}', 'IndexingCustomController@email')->name('indexings.email');
        Route::post('/emails/reply', 'IndexingCustomController@replyEmail')->name('indexings.emailReply');
    }
);
