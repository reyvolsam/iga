<?php

Route::group(array('middleware' => 'loginIn', 'module' => 'Finances', 'namespace' => 'App\Modules\Finances\Controllers'), function() {

	Route::get('finances/bank', 'FinancesController@FinancesBankIndex');
	Route::post('finances/bank/save', 'FinancesController@FinancesBankSave');
	Route::post('finances/bank/list', 'FinancesController@FinancesBankList');

    Route::resource('Finances', 'FinancesController');
    
});	