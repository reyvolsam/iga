<?php 
namespace App\Modules\Quality\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Modules\Users\Controllers\UsersController;

use Validator;
use DB;

class RecipeController extends Controller {

	private $res = array();
	private $ru;
	private $request;

	function __construct(Request $request)
	{
		$this->request = $request;
		$this->res['status'] = false;
		$uc = new UsersController($request);
		$this->ru = $uc->GetUserRole();
	}//__construct

	public function RecipesIndex()
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('quality') ) ){
				return view("Quality::recipes", ['request' => $this->request, 'ru' => $this->ru]);				
		}
	}//OrderProductionView

	public function RecipeGetData()
	{
		try{
			$sfpl = DB::table('semifinished_products')
						->select('id', 'name')
						->get();

			if( count($sfpl) > 0 ){
				$fpl = DB::table('product_type')
							->get();
				if( count($fpl) > 0 ){
					$adjust = DB::table('features')
									->where('feature_id', '=', 3)
									->get();
					$model = DB::table('features')
									->where('feature_id', '=', 2)
									->get();
					$class = DB::table('features')
									->where('feature_id', '=', 1)
									->get();
					$color = DB::table('features')
									->where('feature_id', '=', 4)
									->get();
					$feets = DB::table('features')
									->where('feature_id', '=', 5)
									->get();

					$this->res['status'] = true;
					$this->res['sfpl'] = $sfpl;
					$this->res['fpl'] = $fpl;
					$this->res['adjust'] = $adjust;
					$this->res['model'] = $model;
					$this->res['class'] = $class;
					$this->res['color'] = $color;
					$this->res['feets'] = $feets;
				} else {
					$this->res['msg'] = 'No hay Productos Terminados.';
				}
			} else {
				$this->res['msg'] = 'No hay Productos Semiterminado.';
			}

		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}


	public function RecipeGetProduct()
	{
		try{
			$product_type = $this->request->input('product_type');

			switch ($product_type) {
				case 'raw_material':
						$pl = DB::table('raw_material_products')
									->select('id', 'name', 'description', 'unit')
									->get();
						$msg = 'No hay Productos Materia Prima.';
					break;
				case 'semifinished_products':
						$pl = DB::table('semifinished_products')
									->select('id', 'name', 'description', 'unit')
									->get();
						$msg = 'No hay Productos de Producto Semiterminado.';
					break;
				case 'finished_products':
						$pl = DB::table('finished_products')
									->select('id', 'description as name', 'description', 'unit')
									->get();
						$msg = 'No hay Productos de Producto Terminado.';
					break;
				default:
						$msg = 'Error tipo de producto perdido.';
					break;
			}
			if( !empty($pl) ){
				$this->res['data'] = $pl;
				$this->res['status'] = true;
				$this->res['msg'] = '';
			} else {
				$this->res['msg'] = $msg;
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//RecipeGetProduct

	public function RecipeSave()
	{
		try{
			$pag = $this->request->input('page');
			$recipe_id = $this->request->input('recipe')['id'];
			$data['product_type'] = $this->request->input('recipe')['product_type'];
			$semifinished_product_id = $this->request->input('recipe')['semifinished_product_id'];
			$finished_product_id = $this->request->input('recipe')['finished_product_id'];

			/*$data['adjust_id'] = $this->request->input('recipe')['adjust_id'];
			$data['class_id'] = $this->request->input('recipe')['class_id'];
			$data['model_id'] = $this->request->input('recipe')['model_id'];
			$data['color_id'] = $this->request->input('recipe')['color_id'];
			$data['feets_id'] = $this->request->input('recipe')['feets_id'];*/

			$data['products'] = $this->request->input('products');
			$ban = false;
			if(!empty($data['product_type'])){
				if( $data['product_type'] == 'semifinished_product'){
					if( !empty($semifinished_product_id) ){
						$data['product_type_id'] = $semifinished_product_id;
						$ban = true;
					}
				} elseif($data['product_type'] == 'finished_product'){
					if( !empty($finished_product_id) ){
						//product_type_iD ES EL ID DE PRODUCTO EN LA TABLA DE finished_products APUNTA DIRECTAMENTE AL PRODUCTO TERMINADO.
						$data['product_type_id'] = $finished_product_id;
						$ban = true;
					}
				}
				
				if($ban == true){
					$rc = DB::table('recipes')
								->where('product_type', 'LIKE', $data['product_type'])
								->where('product_type_id', '=', $data['product_type_id'])
								->get();
					if( count($rc) == 0 ){
						if( count($data['products']) > 0 ){
							$data['products'] = json_encode($data['products']);
							if( $recipe_id == null){
								$data['created_at'] = date('Y-m-d H:m:i');
								$data['updated_at'] = date('Y-m-d H:m:i'); 
								DB::table('recipes')
										->insert($data);
								$this->InterfaceRecipeList($pag);
								$this->res['status'] = true;
								$this->res['msg'] = 'Receta Guardada Correctamente.';
							} else {
								$data['updated_at'] = date('Y-m-d H:m:i');
								DB::table('recipes')
										->where('id', '=', $recipe_id)
										->update($data);
								$this->InterfaceRecipeList($pag);
								$this->res['status'] = true;
								$this->res['msg'] = 'Receta Actualizada Correctamente.';
							}
						} else {
							$this->res['msg'] = 'Seleccione un Producto para esta Receta.';
						}
					} else {
						$this->res['msg'] = 'Este Producto ya tiene Receta.';	
					}
				} else {
					$this->res['msg'] = 'Seleccione una Tipo de Producto.';
				}
			} else {
				$this->res['msg'] = 'Seleccione una Tipo de Producto.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//RecipeSave	

	private function InterfaceRecipeList($pag)
	{
		try{
			$rowsPerPage = 3;
			$total_rows = 0;
			$total_paginas = 0;
			$offset = ($pag - 1) * $rowsPerPage;

			$cl = DB::table('recipes')
						->orderBy('id', 'desc')
						->skip($offset)
						->take($rowsPerPage)
						->get();
			$total_rows = DB::table('clients')
								->count();

			foreach ($cl as $kc => $vc) {
				if($vc->product_type == 'semifinished_product'){
					$sp = DB::table('semifinished_products')
								->where('id', '=', $vc->product_type_id)
								->first();
					$vc->product_type_name = 'Producto Semiterminado';
					$vc->product_type = 'semifinished_product';
					$vc->product_name = $sp->name;
					$vc->product_description = $sp->description;
				}
				if($vc->product_type == 'finished_product'){
					/*switch ($vc->product_type_id) {
						case 1:
							$fp = DB::table('finished_products')
										->where('product_type_id', '=', $vc->product_type_id)
										->where('color_id', '=', $vc->color_id)
										->first();
							break;
						case 2:
							$fp = DB::table('finished_products')
										->where('product_type_id', '=', $vc->product_type_id)
										->where('adjust_id', '=', $vc->adjust_id)
										->where('class_id', '=', $vc->class_id)
										->where('model_id', '=', $vc->model_id)
										->where('color_id', '=', $vc->color_id)
										->first();
							break;
						case 10:
							$fp = DB::table('finished_products')
										->where('product_type_id', '=', $vc->product_type_id)
										->where('adjust_id', '=', $vc->adjust_id)
										->first();
							break;
						case 11:
							$fp = DB::table('finished_products')
										->where('product_type_id', '=', $vc->product_type_id)
										->where('adjust_id', '=', $vc->adjust_id)
										->first();
							break;
						case 12:
							$fp = DB::table('finished_products')
										->where('product_type_id', '=', $vc->product_type_id)
										->where('model_id', '=', $vc->model_id)
										->where('feets_id', '=', $vc->feets_id)
										->where('color_id', '=', $vc->color_id)
										->first();
							break;
						default:
							break;
					}*/
					$fp = DB::table('finished_products')
								->where('id', '=', $vc->product_type_id)
								->first();
					$pt = DB::table('product_type')
								->where('id', '=', $fp->product_type_id)
								->first();
					$vc->product_type_name = 'Producto Terminado';
					$vc->product_type = 'finished_product';
					$vc->product_name = $fp->description;
					$vc->product_description = $fp->description;
					$vc->product_type_id = $fp->product_type_id;
					$vc->adjust_id = $fp->adjust_id;
					$vc->class_id = $fp->class_id;
					$vc->model_id = $fp->model_id;
					$vc->color_id = $fp->color_id;
					$vc->feets_id = $fp->feets_id;
					$vc->adjust = $pt->adjust;
					$vc->class = $pt->class;
					$vc->model = $pt->model;
					$vc->color = $pt->color;
					$vc->feets = $pt->feets;
				}
				$vc->products 	= str_replace("'", '"', $vc->products);
				$vc->products 	= json_decode($vc->products);
			}
			if( count($cl) > 0 ){
				if($rowsPerPage <= $total_rows){
					$total_paginas = 1;
				}
				$total_paginas = ceil($total_rows / $rowsPerPage);
				$this->res['tp'] = $total_paginas;
				$this->res['status'] = true;
				$this->res['data'] = $cl;
				$this->res['msg'] = '';
			} else {
				$this->res['msg'] = 'No hay Recetas registradas hasta el momento.';
			}

		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//InterfaceRecipeList

	public function RecipeList()
	{
		try{
			$pag = $this->request->input('page');
			$this->InterfaceRecipeList($pag);
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//RecipeList

	public function RecipeFeature()
	{
		try{
			$recipe = $this->request->input('recipe');

			switch ($recipe['finished_product_type_id']) {
				case 1:
						$fp = DB::table('finished_products')
								->where('product_type_id', '=', $recipe['finished_product_type_id'])
								->where('color_id', '=', $recipe['color_id'])
								->first();
						if($fp){
							$this->res['product_id'] = $fp->id;
							$this->res['status'] = true;
						} else {
							$this->res['msg'] = 'No existe un Producto con estas Caracteristicas.';
						}
					break;
				case 2:
						$fp = DB::table('finished_products')
								->where('product_type_id', '=', $recipe['finished_product_type_id'])
								->where('adjust_id', '=', $recipe['adjust_id'])
								->where('class_id', '=', $recipe['class_id'])
								->where('model_id', '=', $recipe['model_id'])
								->where('color_id', '=', $recipe['color_id'])
								->first();
						if($fp){
							$this->res['product_id'] = $fp->id;
							$this->res['status'] = true;
						} else {
							$this->res['msg'] = 'No existe un Producto con estas Caracteristicas.';
						}
					break;
				case 10:
						$fp = DB::table('finished_products')
								->where('product_type_id', '=', $recipe['finished_product_type_id'])
								->where('adjust_id', '=', $recipe['adjust_id'])
								->first();
						if($fp){
							$this->res['product_id'] = $fp->id;
							$this->res['status'] = true;
						} else {
							$this->res['msg'] = 'No existe un Producto con estas Caracteristicas.';
						}
					break;
				case 11:
						$fp = DB::table('finished_products')
								->where('product_type_id', '=', $recipe['finished_product_type_id'])
								->where('adjust_id', '=', $recipe['adjust_id'])
								->first();
						if($fp){
							$this->res['product_id'] = $fp->id;
							$this->res['status'] = true;
						} else {
							$this->res['msg'] = 'No existe un Producto con estas Caracteristicas.';
						}
					break;
				case 12:
						$fp = DB::table('finished_products')
								->where('product_type_id', '=', $recipe['finished_product_type_id'])
								->where('model_id', '=', $recipe['model_id'])
								->where('feets_id', '=', $recipe['feets_id'])
								->where('color_id', '=', $recipe['color_id'])
								->first();
						if($fp){
							$this->res['product_id'] = $fp->id;
							$this->res['status'] = true;
						} else {
							$this->res['msg'] = 'No existe un Producto con estas Caracteristicas.';
						}
					break;
				default:
					$this->res['product_id'] = null;
					$this->res['msg'] = 'Todos los Campos del Producto son requeridos.';
					break;
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//RecipeFeature

	public function RecipeDelete()
	{
		try{
			$id = $this->request->input('id');
			$pag = $this->request->input('page');
			$r = DB::table('recipes')
						->where('id', '=', $id)
						->get();
			if( count($r) > 0 ){
				DB::table('recipes')
						->where('id', '=', $id)
						->delete();
				$this->InterfaceRecipeList($pag);

				$this->res['status'] = true;
				$this->res['msg'] = 'Receta Eliminada Correctamente.';
			} else {
				$this->res['msg'] = 'Esta Receta no existe.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//RecipeDelete

}//RecipeController
