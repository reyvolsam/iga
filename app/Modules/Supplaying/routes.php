<?php

Route::group(array('middleware' => 'loginIn', 'module' => 'Supplaying', 'namespace' => 'App\Modules\Supplaying\Controllers'), function() {

	Route::get('supplaying/provider/{provider_type}', 'ProviderController@ProviderIndex');
	Route::post('supplaying/provider/save', 'ProviderController@ProviderSave');
	Route::post('supplaying/provider/delete', 'ProviderController@ProviderDelete');
	Route::post('supplaying/provider/list', 'ProviderController@ProviderList');
	Route::post('supplaying/provider/list/select', 'ProviderController@ProviderListSelect');
	

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

	Route::get('supplaying/requisition', 'RequisitionController@RequisitionIndex');
	Route::post('supplaying/requisition/get_date', 'RequisitionController@RequisitionDate');
	Route::post('supplaying/requisition/product', 'RequisitionController@RequisitionProductList');
	Route::post('supplaying/requisition/save', 'RequisitionController@RequisitionSave');
	Route::post('supplaying/requisition/list', 'RequisitionController@RequisitionList');
	Route::post('supplaying/requisition/delete', 'RequisitionController@RequisitionDelete');
	Route::get('supplaying/requisition/pdf/{id}', 'RequisitionController@RequisitionPDF');
	
	Route::get('supplaying/requisition/validate', 'RequisitionController@RequisitionValidateIndex');
	Route::post('supplaying/requisition/validate/list', 'RequisitionController@RequisitionValidateList');

	Route::post('supplaying/requisition/notification/save', 'RequisitionController@RequisitionNotificationSave');

	Route::post('supplaying/requisition/order_buy/providers', 'RequisitionController@OrderBuyProviders');
	Route::post('supplaying/requisition/order_buy/save', 'RequisitionController@OrderBuySave');
	Route::post('supplaying/requisition/order_buy/finances/validate', 'RequisitionController@OrderBuyFinancesValidate');
	Route::post('supplaying/requisition/order_buy/finances/uploadticket', 'RequisitionController@OrderBuyFinancesUpload');


	Route::get('supplaying/order_buy', 'RequisitionController@OrderBuyIndex');
	Route::get('supplaying/order_buy/pdf/{id}', 'RequisitionController@OrderBuyPDF');
	Route::post('supplaying/order_buy/list', 'RequisitionController@OrderBuyList');
	Route::get('supplaying/order_buy/email', 'RequisitionController@OrderBuyEmail');

	
	//Route::post('supplaying/order_buy/save', 'RequisitionController@OrderBuySave');

	/*Route::get('supplaying/product/material/type', 'ProductController@ProductMaterialTypeIndex');
	Route::post('supplaying/product/material/type/save', 'ProductController@ProductMaterialTypeSave');
	Route::post('supplaying/product/material/type/list', 'ProductController@ProductMaterialTypeList');*/

    Route::resource('supplaying', 'SupplayingController');





    Route::get('supplaying/stock/raw_material', 'StockController@RawMaterialIndex');
    
});	