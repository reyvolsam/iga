<?php

Route::group(array('middleware' => 'loginIn', 'module' => 'Quality', 'namespace' => 'App\Modules\Quality\Controllers'), function() {

	Route::get('quality/recipes', 'RecipeController@RecipesIndex');
	Route::post('quality/recipes/get_data', 'RecipeController@RecipeGetData');
	Route::post('quality/recipes/product', 'RecipeController@RecipeGetProduct');
	Route::post('quality/recipes/save', 'RecipeController@RecipeSave');
	Route::post('quality/recipes/list', 'RecipeController@RecipeList');
	Route::post('quality/recipes/feature', 'RecipeController@RecipeFeature');
	Route::post('quality/recipes/delete', 'RecipeController@RecipeDelete');

    Route::resource('quality', 'QualityController');
    
});	