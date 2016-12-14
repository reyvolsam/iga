<?php 
namespace App\Modules\Supplaying\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Modules\Supplaying\Models;

use App\Modules\Users\Controllers\UsersController;

use Validator;
use DB;

class ProductController extends Controller {

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

	public function ProductIndex($product_type)
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('supplaying') ) ){
				return view("Supplaying::product", ['request' => $this->request, 'ru' => $this->ru, 'product_type' => $product_type]);	
		}
	}//ProdutList

	public function SaveProduct()
	{
		try{
			$type = null;
			$id 					= $this->request->input('id');
			$type 					= $this->request->input('type');
			
			switch ($type) {
				case 'raw_material':
						$table = 'raw_material_products';

						$data['code'] 			= $this->request->input('code');
						$data['name'] 			= $this->request->input('name');
						$data['provider_id'] 	= $this->request->input('provider_id');
						$data['max'] 			= $this->request->input('max');
						$data['min'] 			= $this->request->input('min');
						$data['unit'] 			= $this->request->input('unit');
						$data['description'] 	= $this->request->input('description');
						$data['updated_at'] 	= date('Y-m-d');
						$data['total'] 			= 0;

						$validator = Validator::make($data, [
				        			'code' 			=> 'required',
				        			'name' 			=> 'required',
				        			'provider_id' 	=> 'required',
				        			'max' 			=> 'required|integer',
				        			'min' 			=> 'required|integer',
				        			'unit' 			=> 'required',
				        			'description' 	=> 'required'
				        		]);
						if ( !$validator->fails() ){
							if( $data['max'] > $data['min'] ){
								$checkcode = DB::table($table)
												->where('code', '=', $data['code'])
												->get();
								if( count($checkcode) == 0 ){
									if($id == null){
										$data['created_at'] 	= date('Y-m-d');
										$product_id = DB::table($table)
															->insertGetId($data);
										$this->request->session()->put('product.id', $product_id);
										$this->request->session()->put('product.type', $table);
										$this->res['msg'] = 'Materia Prima Guardada Correctamente.';
										$this->res['status'] = true;
									} else {
										$product_id = DB::table($table)
															->where('id', '=', $id)
															->update($data);
										$this->request->session()->put('product.id', $product_id);
										$this->request->session()->put('product.type', $table);
										$this->res['msg'] = 'Materia Prima Actualizada Correctamente.';
										$this->res['status'] = true;
									}
								} else {
									$this->res['msg'] = 'Codigo de Producto repetido.';
								}
							} else {
								$this->res['msg'] = 'El Valor Maximo tiene que ser Mayor a Minimo.';	
							}
						} else {
							$this->res['data'] = $data;
							$this->res['msg'] = 'Todos los campos son obligatorios.';
						}
					break;
				case 'semifinished_product':
						$table = 'semifinished_products';

						$data['name'] 			= strtoupper($this->request->input('name'));
						$data['provider_id'] 	= $this->request->input('provider_id');
						$data['max'] 			= $this->request->input('max');
						$data['min'] 			= $this->request->input('min');
						$data['unit'] 			= $this->request->input('unit');
						$data['weigth'] 		= $this->request->input('weigth');
						$data['description'] 	= strtoupper($this->request->input('description'));
						$data['updated_at'] 	= date('Y-m-d');

						$data['total'] 			= 0;

						$validator = Validator::make($data, [

				        			'name' 			=> 'required',
				        			'provider_id' 	=> 'required',
				        			'max' 			=> 'required|integer',
				        			'min' 			=> 'required|integer',
				        			'weigth' 		=> 'required',
				        			'unit' 			=> 'required',
				        			'description' 	=> 'required'
				        		]);
						if ( !$validator->fails() ){
							if( $data['max'] > $data['min'] ){
								$checkname = DB::table($table)
												->where('name', '=', $data['name'])
												->get();
								if( count($checkname) == 0 ){
									if($id == null){
										$data['created_at'] 	= date('Y-m-d');
										$product_id = DB::table($table)
															->insertGetId($data);
										$this->request->session()->put('product.id', $product_id);
										$this->request->session()->put('product.type', $table);
										$this->res['msg'] = 'Producto Semiterminado Guardad0 Correctamente.';
										$this->res['status'] = true;
									} else {
										$product_id = DB::table($table)
															->where('id', '=', $id)
															->update($data);
										$this->request->session()->put('product.id', $product_id);
										$this->request->session()->put('product.type', $table);
										$this->res['msg'] = 'Producto Semiterminado Actualizado Correctamente.';
										$this->res['status'] = true;
									}
								} else {
									$this->res['msg'] = 'Nombre de Producto repetido.';
								}
							} else {
								$this->res['msg'] = 'El Valor Maximo tiene que ser Mayor a Minimo.';	
							}
						} else {
							$this->res['msg'] = 'Todos los campos son obligatorios.';
						}
					break;
				case 'finished_product':
						$table = 'finished_products';

						$data['product_type_id'] 		= $this->request->input('product_type_id');
						$data['adjust_id'] 				= $this->request->input('adjust_id');
						$data['class_id'] 				= $this->request->input('class_id');
						$data['model_id'] 				= $this->request->input('model_id');
						$data['color_id'] 				= $this->request->input('color_id');
						$data['feets_id'] 				= $this->request->input('feets_id');

						$data['coatza_min'] 			= $this->request->input('coatza_min');
						$data['coatza_max']				= $this->request->input('coatza_max');
						$data['coatza_max_ped'] 		= $this->request->input('coatza_max_ped');
						$data['coatza_status']			= $this->request->input('coatza_status');

						$data['guadalajara_min'] 		= $this->request->input('guadalajara_min');
						$data['guadalajara_max'] 		= $this->request->input('guadalajara_max');
						$data['guadalajara_max_ped']	= $this->request->input('guadalajara_max_ped');
						$data['guadalajara_status'] 	= $this->request->input('guadalajara_status');

						$data['provider_id'] 			= $this->request->input('provider_id');
						$data['unit'] 					= $this->request->input('unit');
						$data['brand'] 					= strtoupper($this->request->input('brand'));
						$data['description'] 			= strtoupper($this->request->input('description'));
						$data['product_use'] 			= $this->request->input('use');

						$data['updated_at'] 			= date('Y-m-d');

						$validator = Validator::make($data, [

				        			'product_type_id' 		=> 'required',
				        			'coatza_min' 			=> 'required',
				        			'coatza_max' 			=> 'required',
				        			'coatza_max_ped' 		=> 'required',
				        			'coatza_status' 		=> 'required',
				        			'guadalajara_min' 		=> 'required',
				        			'guadalajara_max' 		=> 'required',
				        			'guadalajara_max_ped' 	=> 'required',
				        			'guadalajara_status' 	=> 'required',
				        			'provider_id' 			=> 'required',
				        			'unit' 					=> 'required',
				        			'brand' 				=> 'required',
				        			'description' 			=> 'required',
				        			'product_use' 			=> 'required'
				        		]);
						if ( !$validator->fails() ){
							$checkfeatures = DB::table($table)
													->where('adjust_id', '=', $data['adjust_id'])
													->where('class_id', '=', $data['class_id'])
													->where('model_id', '=', $data['model_id'])
													->where('color_id', '=', $data['color_id'])
													->where('feets_id', '=', $data['feets_id'])
													->get();
							if( count($checkfeatures) == 0 ){
								$data['created_at'] 	= date('Y-m-d');
								if($id == null){
									$product_id = DB::table($table)
														->insertGetId(array(
										        			'product_type_id' 		=> $data['product_type_id'],
										        			'adjust_id' 			=> $data['adjust_id'],
										        			'class_id' 				=> $data['class_id'],
										        			'model_id' 				=> $data['model_id'],
										        			'color_id' 				=> $data['color_id'],
										        			'feets_id' 				=> $data['feets_id'],
										        			'provider_id' 			=> $data['provider_id'],
										        			'unit' 					=> $data['unit'],
										        			'brand' 				=> $data['brand'],
										        			'description' 			=> $data['description'],
										        			'product_use' 			=> $data['use']
														));
									DB::table('finished_products_coatza')
													->insert(array(
														'finished_product_id' => $product_id,
														'min' 		=> $data['coatza_min'],
														'max' 		=> $data['coatza_max'],
														'prod_max' 	=> $data['coatza_max_ped'],
														'status' 	=> $data['coatza_status']
													));
									DB::table('finished_products_guadalajara')
													->insert(array(
														'finished_product_id' => $product_id,
														'min' 		=> $data['guadalajara_min'],
														'max' 		=> $data['guadalajara_max'],
														'prod_max' 	=> $data['guadalajara_max_ped'],
														'status' 	=> $data['guadalajara_status']
													));
									$this->request->session()->put('product.id', $product_id);
									$this->request->session()->put('product.type', $table);

									$this->res['status'] = true;
									$this->res['msg'] = 'Producto Terminado Guardado Creado Correctamente.';
								} else {
									$this->res['status'] = true;
									$this->res['msg'] = 'Producto Terminado Actualizado Correctamente.';
								}
							} else {
								$this->res['msg'] = 'Un producto con estas caracteristicas ya ha sido ingresado anteriormente revise sus caracteristicas.';
							}
						} else {
							$this->res['msg'] = 'Todos los campos son obligatorios.';
						}
					break;
				default:
						$this->res['msg'] = 'Caigo Aqui :(';
					break;
				}
		} catch (\Exception $e) {
			$this->res['data'] = $data;
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//SaveProduct

	public function UploadTechnicalProduct()
	{
		$data = array();
		$files = array();
		$id = $this->request->session()->get('product.id');
		$table = $this->request->session()->get('product.type');
		$uploaddir = 'technical_file/';

		if(count($_FILES) > 0 ){
			foreach($_FILES as $file){
				$porciones = explode(".", $file['name']);
				$ext = $porciones[count($porciones)-1];
				unset($porciones[count($porciones)-1]);
				$name = implode("", $porciones);
				$file['name'] = $name.'.'.$ext;

				list($txt, $ext) = explode(".", $file['name']);
				$rand = rand(1, 500);
				$actual_image_name = $rand."_".time().".".$ext;
				if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($actual_image_name))){
					try{
	 					$t = DB::table($table)
	 								->where('id', '=', $id)
	 								->first();
						if( count($t) == 1 ){
								DB::table($table)
	 								->where('id', '=', $id)
	 								->update(array(
	 										'technical_file' => $actual_image_name
	 									));
							$this->res['status'] = true;
							$this->res['msg'] = 'Ficha Tecnica Subida Correctamente.';
						}
					} catch (\Exception $e) {
						$this->res['msg'] = 'Error en la Base de Datos.'.$e;
					}
				} else {
				    $this->res['msg'] = 'Error al momento de subir el archivo, intente mas tarde.';
				}
			}
		} else {
			$this->res['msg'] = 'Selecciona un Archivo.';
		}
		echo json_encode($this->res);
	}//UploadFileProduct

	public function UploadImgProduct()
	{
		$data = array();
		$files = array();
		$id = $this->request->session()->get('product.id');
		$table = $this->request->session()->get('product.type');
		$uploaddir = 'img_product/';

		if(count($_FILES) > 0 ){
			foreach($_FILES as $file){
				$porciones = explode(".", $file['name']);
				$ext = $porciones[count($porciones)-1];
				unset($porciones[count($porciones)-1]);
				$name = implode("", $porciones);
				$file['name'] = $name.'.'.$ext;

				list($txt, $ext) = explode(".", $file['name']);
				$rand = rand(1, 500);
				$actual_image_name = $rand."_".time().".".$ext;
				if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($actual_image_name))){
					try{
	 					$t = DB::table($table)
	 								->where('id', '=', $id)
	 								->first();
						if( count($t) == 1 ){
								DB::table($table)
	 								->where('id', '=', $id)
	 								->update(array(
	 										'img_product' => $actual_image_name
	 									));
							$this->res['status'] = true;
							$this->res['msg'] = 'Imagen del Producto Subida Correctamente.';
						}
					} catch (\Exception $e) {
						$this->res['msg'] = 'Error en la Base de Datos.'.$e;
					}
				} else {
				    $this->res['msg'] = 'Error al momento de subir el archivo, intente mas tarde.';
				}
			}
		} else {
			$this->res['msg'] = 'Selecciona un Archivo.';
		}
		echo json_encode($this->res);
	}//UploadImgProduct

	public function ProductList()
	{
		try {
			$type = null;
			$type 					= $this->request->input('type');
			$pag 					= $this->request->input('page');
			
			$rowsPerPage = 10;
			$total_rows = 0;
			$offset = ($pag - 1) * $rowsPerPage;

			switch ($type) {
				case 'raw_material':
					$l = DB::table('raw_material_products')
							->select('raw_material_products.id', 'raw_material_products.technical_file', 'raw_material_products.code', 'raw_material_products.name', 'raw_material_products.max', 'raw_material_products.min', 'raw_material_products.unit', 'raw_material_products.description', 'providers.id AS provider_id', 'providers.name as provider_name')
							->join('providers', 'providers.id', '=', 'raw_material_products.provider_id')
							->orderBy('id', 'desc')
							->skip($offset)
							->take($rowsPerPage)
							->get();

					$total_rows = DB::table('raw_material_products')
										->count();

				break;
				case 'semifinished_product':
					$l = DB::table('semifinished_products')
							->select('semifinished_products.id', 'semifinished_products.technical_file', 'semifinished_products.name', 'semifinished_products.max', 'semifinished_products.min', 'semifinished_products.unit', 'semifinished_products.weigth', 'semifinished_products.description', 'semifinished_products.total', 'providers.id AS provider_id', 'providers.name as provider_name')
							->join('providers', 'providers.id', '=', 'semifinished_products.provider_id')
							->orderBy('id', 'desc')
							->skip($offset)
							->take($rowsPerPage)
							->get();
					$total_rows = DB::table('semifinished_products')
										->count();
					break;
				case 'finished_product':
					$l = DB::table('finished_products')
							->select('finished_products.id', 'finished_products.img_product', 'finished_products.technical_file', 'finished_products.adjust_id', 'finished_products.model_id', 'finished_products.color_id', 'finished_products.class_id', 'finished_products.feets_id', 'product_type.id AS product_type_id', 'product_type.name AS product_type_name','finished_products.unit', 'finished_products.brand', 'finished_products.description', 'finished_products.product_use','providers.id AS provider_id', 'providers.name as provider_name')
							->join('providers', 'providers.id', '=', 'finished_products.provider_id')
							->join('product_type', 'product_type.id', '=', 'finished_products.product_type_id')
							->orderBy('id', 'desc')
							->skip($offset)
							->take($rowsPerPage)
							->get();
					$total_rows = DB::table('finished_products')
										->count();
					foreach ($l as $kl => $vl) {
						$r = DB::table('features')
									->where('id', '=', $vl->adjust_id)
									->first();
						if($r){$vl->adjust = $r->name;}
						$r = DB::table('features')
									->where('id', '=', $vl->model_id)
									->first();
						 if($r){ $vl->model = $r->name;}
						$r = DB::table('features')
									->where('id', '=', $vl->class_id)
									->first();
						if($r){$vl->class = $r->name;}
						$r = DB::table('features')
									->where('id', '=', $vl->color_id)
									->first();
						if($r){$vl->color = $r->name;}
						$r = DB::table('features')
									->where('id', '=', $vl->feets_id)
									->first();
						if($r){$vl->feets = $r->name;}
						
					}
					break;
				default:
					$this->res['msg'] = 'Caigo Aqui :(';
				break;
			}

				if( count($l) > 0 ){
					if($rowsPerPage <= $total_rows){
						$total_paginas = 1;
					}
					$total_paginas = ceil($total_rows / $rowsPerPage);
					$this->res['tp'] = $total_paginas;
					$this->res['data'] = $l;
					$this->res['status'] = true;
				} else {
					$this->res['msg'] = 'No hay Productos registrados.';
				}
				
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//ProductList

	public function ProductTypeView()
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('supplaying') ) ){
				return view("Supplaying::product_type", ['request' => $this->request, 'ru' => $this->ru]);	
		}
	}//ProductTypeView

	public function ProductTypeSave()
	{
		try{
			$data['name'] 			= strtoupper($this->request->input('name'));
			$data['description'] 	= strtoupper($this->request->input('description'));
			$data['class'] 			= strtoupper($this->request->input('class'));
			$data['model'] 			= strtoupper($this->request->input('model'));
			$data['color'] 			= strtoupper($this->request->input('color'));
			$data['feets'] 			= strtoupper($this->request->input('feets'));
			$data['adjust'] 		= strtoupper($this->request->input('adjust'));

			$validator = Validator::make($data, [

	        			'name' 			=> 'required',
	        			'description' 	=> 'required'
	        		]);
			if ( !$validator->fails() ){
				DB::table('product_type')
						->insert($data);
				$this->ProductTypeList();
				$this->res['status'] = true;
				$this->res['msg'] = 'Tipo de Producto guardado Correctamente.';
			} else {
				$this->res['data'] = $data;
				$this->res['msg'] = 'Todos los campos son obligatorios.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//ProductTypeSave

	public function ProductTypeList()
	{
		try{
			$pl = DB::table('product_type')
						->get();

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
			if( count($pl) > 0 ){
				$this->res['data'] = $pl;
				$this->res['adjust'] = $adjust;
				$this->res['model'] = $model;
				$this->res['class'] = $class;
				$this->res['color'] = $color;
				$this->res['feets'] = $feets;

				$this->res['status'] = true;
			}else {
				$this->res['msg'] = 'No hay Tipos de Producto Registrados.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//ProductTypeList

	public function ProductTypeFeatureIndex($feature)
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('supplaying') ) ){
				return view("Supplaying::feature", ['request' => $this->request, 'ru' => $this->ru, 'feature' => $feature]);
		}
	}//ProductTypeFeatureIndex

	public function ProductTypeFeatureSave()
	{
		try{
			$data['name'] 			= strtoupper( $this->request->input('name') );
			$data['description'] 	= strtoupper( $this->request->input('description') );
			$type 					= $this->request->input('type');

			$validator = Validator::make($data, [

	        			'name' 			=> 'required',
	        			'description' 	=> 'required'
	        		]);
			if ( !$validator->fails() ){
				switch ($type) {
					case 'class':
						$data['feature_id'] = 1;
					break;
					case 'model':
						$data['feature_id'] = 2;
					break;
					case 'adjust':
						$data['feature_id'] = 3;
					break;
					case 'color':
						$data['feature_id'] = 4;
					break;
					case 'feets':
						$data['feature_id'] = 5;
					break;
					default:
						$this->res['msg'] = 'Caigo Aqui :(';
					break;
				}
					DB::table('features')
							->insert($data);
					$l = DB::table('features')
								->where('feature_id', '=', $data['feature_id'])
								->get();
					$this->res['data'] = $l;
					$this->res['status'] = true;
					$this->res['msg'] = 'Caracteristica de Producto Guardada Correctamente.';
			} else {
				$this->res['msg']	= 'Todos los campos son obligatorios.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//ProductTypeFeatureSave

	public function ProductTypeFeatureList()
	{
		try{
			$type 			= $this->request->input('type');

			switch ($type) {
				case 'class':
					$data['feature_id'] = 1;
				break;
				case 'model':
					$data['feature_id'] = 2;
				break;
				case 'adjust':
					$data['feature_id'] = 3;
				break;
				case 'color':
					$data['feature_id'] = 4;
				break;
				case 'feets':
					$data['feature_id'] = 5;
				break;
				default:
					$this->res['msg'] = 'Caigo Aqui :(';
				break;
			}
			$lf = DB::table('features')
						->where('feature_id', '=', $data['feature_id'])
						->get();

			if( count($lf) > 0 ){
				$this->res['data'] = $lf;
				$this->res['status'] = true;
			} else {
				$this->res['msg']	= 'No hay Caracteristicas.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//ProductTypeFeatureList

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view("Supplying::index");
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
