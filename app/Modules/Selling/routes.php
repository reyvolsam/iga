<?php

Route::group(array('middleware' => 'loginIn', 'module' => 'Selling', 'namespace' => 'App\Modules\Selling\Controllers'), function() {

	Route::get('selling/clients', 'SellingController@ClientsIndex');
	Route::post('selling/clients/list', 'SellingController@ClientsList');
	Route::post('selling/users_list', 'SellingController@ClientsUsersList');
	Route::post('selling/banks_list', 'SellingController@ClientsBanksList');
	Route::post('selling/send_list', 'SellingController@ClientsSendsList');
	Route::post('selling/clients/save', 'SellingController@ClientsSave');
	Route::post('selling/clients/delete', 'SellingController@ClientDelete');

    Route::resource('selling', 'SellingController');
    
});	