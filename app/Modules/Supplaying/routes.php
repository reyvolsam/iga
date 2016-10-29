<?php

Route::group(array('middleware' => 'loginIn', 'module' => 'Supplaying', 'namespace' => 'App\Modules\Supplaying\Controllers'), function() {

	Route::get('supplaying/provider/{provider_type}', 'ProviderController@ProviderIndex');
	Route::post('supplaying/provider/save', 'ProviderController@ProviderSave');
	Route::post('supplaying/provider/list', 'ProviderController@ProviderList');

	Route::get('supplaying/product/{product_type}', 'ProductController@ProductIndex');
	Route::get('supplaying/product/material/type', 'ProductController@ProductMaterialTypeIndex');
	Route::post('supplaying/product/material/type/save', 'ProductController@ProductMaterialTypeSave');
	Route::post('supplaying/product/material/type/list', 'ProductController@ProductMaterialTypeList');

	Route::get('supplaying/product/internal_code/{internal_code_type}', 'ProductController@InternalCodeIndex');

    Route::resource('supplaying', 'SupplayingController');

    Route::get('supplaying/stock/raw_material', 'StockController@RawMaterialIndex');
    
});	