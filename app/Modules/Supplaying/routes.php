<?php

Route::group(array('middleware' => 'loginIn', 'module' => 'Supplaying', 'namespace' => 'App\Modules\Supplaying\Controllers'), function() {

	Route::get('supplaying/provider/{provider_type}', 'ProviderController@ProviderIndex');
	Route::post('supplaying/provider/save', 'ProviderController@ProviderSave');
	Route::post('supplaying/provider/delete', 'ProviderController@ProviderDelete');
	Route::post('supplaying/provider/list', 'ProviderController@ProviderList');

	Route::get('supplaying/product/{product_type}', 'ProductController@ProductIndex');
	Route::post('supplaying/product/save/', 'ProductController@SaveProduct');
	Route::post('supplaying/product/list', 'ProductController@ProductList');
	Route::post('supplaying/product/save/technical', 'ProductController@UploadTechnicalProduct');
	Route::post('supplaying/product/save/img', 'ProductController@UploadImgProduct');
	Route::get('supplaying/product/type/index', 'ProductController@ProductTypeView');
	Route::post('supplaying/product/type/save', 'ProductController@ProductTypeSave');
	Route::post('supplaying/product/type/list', 'ProductController@ProductTypeList');
	
	Route::get('supplaying/product/type/feature/{feature}', 'ProductController@ProductTypeFeatureIndex');
	Route::post('supplaying/product/type/feature/save', 'ProductController@ProductTypeFeatureSave');
	Route::post('supplaying/product/type/feature/list', 'ProductController@ProductTypeFeatureList');
	
	Route::get('supplaying/stock/raw_material/entry', 'StockController@StockRawMaterialIndex');
	Route::post('supplaying/stock/raw_material/entry/load', 'StockController@StockRawMaterialLoad');
	Route::post('supplaying/stock/raw_material/entry/save', 'StockController@StockRawMaterialSave');
	Route::post('supplaying/stock/raw_material/entry/list', 'StockController@StockRawMaterialList');

	Route::get('supplaying/stock/raw_material/index', 'StockController@StockRawMaterialView');
	Route::post('supplaying/stock/raw_material/list', 'StockController@StockRawMaterialListStock');
	
	
	Route::get('supplaying/order_production', 'SupplayingController@OrderProductionView');
	Route::post('supplaying/order_production/save', 'SupplayingController@OrderProductionSave');
	Route::post('supplaying/order_production/list', 'SupplayingController@OrderProductionList');
	Route::post('supplaying/order_production/data', 'SupplayingController@OrderProductionData');
	Route::post('supplaying/order_production/validate', 'SupplayingController@OrderProductionValidate');

	Route::get('supplaying/requisition', 'SupplayingController@RequisitionIndex');
	Route::post('supplaying/requisition/get_date', 'SupplayingController@RequisitionDate');
	Route::post('supplaying/requisition/product', 'SupplayingController@RequisitionProductList');
	Route::post('supplaying/requisition/save', 'SupplayingController@RequisitionSave');
	Route::post('supplaying/requisition/list', 'SupplayingController@RequisitionList');
	Route::post('supplaying/requisition/delete', 'SupplayingController@RequisitionDelete');

	/*Route::get('supplaying/product/material/type', 'ProductController@ProductMaterialTypeIndex');
	Route::post('supplaying/product/material/type/save', 'ProductController@ProductMaterialTypeSave');
	Route::post('supplaying/product/material/type/list', 'ProductController@ProductMaterialTypeList');*/

    Route::resource('supplaying', 'SupplayingController');





    Route::get('supplaying/stock/raw_material', 'StockController@RawMaterialIndex');
    
});	