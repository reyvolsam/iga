<?php

Route::group(array('middleware' => 'loginIn', 'module' => 'Users', 'namespace' => 'App\Modules\Users\Controllers'), function() {
	
	Route::get('users/post', 'UsersController@UsersPost');
	Route::post('users/post/list', 'UsersController@UsersPostList');
	Route::post('users/post/save', 'UsersController@UsersPostSave');
	Route::post('users/post/delete', 'UsersController@UsersPostDelete');

	Route::get('users/requisition', 'UsersController@UsersRequisition');
	Route::post('users/requisition/save', 'UsersController@UsersRequisitionSave');
	Route::get('users/requisition/view/list', 'UsersController@UsersRequisitionViewList');
	Route::post('users/requisition/list', 'UsersController@UsersRequisitionList');
	Route::post('users/requisition/validate', 'UsersController@UsersRequisitionValidate');
	Route::post('users/requisition/data', 'UsersController@UsersRequisitionData');

	Route::get('users/requisition/validate/view/list', 'UsersController@UsersRequisitionViewValidate');

	Route::get('users/create/{id}', 'UsersController@UsersRequisitionRedirect');

    Route::resource('users', 'UsersController');

    Route::post('users/list', 'UsersController@UsersList');

    
});	