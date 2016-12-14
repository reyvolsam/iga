<?php 
namespace App\Modules\Supplaying\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Modules\Supplaying\Models;

use App\Modules\Users\Controllers\UsersController;

use Validator;
use DB;

class SupplayingController extends Controller {

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


	public function OrderProductionView()
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('supplaying') ) ){
				return view("Supplaying::order_production", ['request' => $this->request, 'ru' => $this->ru]);				
		}
	}//OrderProductionView

	public function OrderProductionData()
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
				$this->res['date'] = date('Y-m-d H:i:s');

				$this->res['status'] = true;
			}else {
				$this->res['msg'] = 'No hay Tipos de Producto Registrados.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//OrderProductionData

	public function OrderProductionValidate()
	{
		try{
			$op = $this->request->input('order_production');
			$data['type'] = $op['type'];

			$validator = Validator::make($data, [
	        			'type' 			=> 'required'
			]);
			if ( !$validator->fails() ){
				switch ($data['type']) {
					case 1:
						$data['color_id'] 			= $op['color'];
						$data['product_type_id'] 	= 1;
						$validator = Validator::make($data, [
		        			'color_id' 			=> 'required',
		        			'product_type_id' 	=> 'required'
		        		]);
						if ( !$validator->fails() ){
							$tp = DB::table('finished_products')
										->where('product_type_id', '=', $data['product_type_id'])
										->where('color_id', '=', $data['color_id'])
										->first();
							if($tp){
								$this->res['status'] = true;
								$this->res['product'] = $tp;
							} else {
								$this->res['msg'] = 'No hay Prodcutos con estas Caracteristicas.';
							}
						} else {
							$this->res['msg'] = 'Todos los Campos son obligatorios.';	
						}
					break;
					case 2:
						$data['color_id'] 			= $op['color'];
						$data['adjust_id'] 			= $op['adjust'];
						$data['model_id'] 			= $op['model'];
						$data['class_id'] 			= $op['class'];
						$data['product_type_id'] 	= 2;

						$validator = Validator::make($data, [
		        			'color_id' 			=> 'required',
		        			'adjust_id' 		=> 'required',
		        			'model_id' 			=> 'required',
		        			'class_id' 			=> 'required',
		        			'product_type_id' 	=> 'required'
		        		]);
						if ( !$validator->fails() ){
							$tp = DB::table('finished_products')
										->where('product_type_id', '=', $data['product_type_id'])
										->where('color_id', '=', $data['color_id'])
										->where('adjust_id', '=', $data['adjust_id'])
										->where('model_id', '=', $data['model_id'])
										->where('class_id', '=', $data['class_id'])
										->first();
							if($tp){
								$this->res['status'] = true;
								$this->res['product'] = $tp;
							} else {
								$this->res['msg'] = 'No hay Prodcutos con estas Caracteristicas.';
							}
						} else {
							$this->res['msg'] = 'Todos los Campos son obligatorios.';	
						}
					break;
					case 10:
						$data['adjust_id'] 			= $op['adjust'];
						$data['product_type_id'] 	= 10;
						
						$validator = Validator::make($data, [
		        			'adjust_id' 		=> 'required',
		        			'product_type_id' 	=> 'required'
		        		]);
						if ( !$validator->fails() ){
							$tp = DB::table('finished_products')
										->where('product_type_id', '=', $data['product_type_id'])
										->where('adjust_id', '=', $data['adjust_id'])
										->first();
							if($tp){
								$this->res['status'] = true;
								$this->res['product'] = $tp;
							} else {
								$this->res['msg'] = 'No hay Prodcutos con estas Caracteristicas.';
							}
						} else {
							$this->res['msg'] = 'Todos los Campos son obligatorios.';	
						}
					break;
					case 11:
						$data['adjust_id'] 			= $op['adjust'];
						$data['product_type_id'] 	= 11;
						
						$validator = Validator::make($data, [
		        			'adjust_id' 		=> 'required',
		        			'product_type_id' 	=> 'required'
		        		]);
						if ( !$validator->fails() ){
							$tp = DB::table('finished_products')
										->where('product_type_id', '=', $data['product_type_id'])
										->where('adjust_id', '=', $data['adjust_id'])
										->first();
							if($tp){
								$this->res['status'] = true;
								$this->res['product'] = $tp;
							} else {
								$this->res['msg'] = 'No hay Prodcutos con estas Caracteristicas.';
							}
						} else {
							$this->res['msg'] = 'Todos los Campos son obligatorios.';	
						}
					break;
					case 12:
						$data['feature_id'] = 5;
						$data['color_id'] 			= $op['color'];
						$data['model_id'] 			= $op['model'];
						$data['feets_id'] 			= $op['class'];
						$data['product_type_id'] 	= 12;
						
						$validator = Validator::make($data, [
		        			'color_id' 			=> 'required',
		        			'model_id' 			=> 'required',
		        			'feets_id' 			=> 'required',
		        			'product_type_id' 	=> 'required'
		        		]);
						if ( !$validator->fails() ){
							$tp = DB::table('finished_products')
										->where('product_type_id', '=', $data['product_type_id'])
										->where('color_id', '=', $data['color_id'])
										->where('model_id', '=', $data['model_id'])
										->where('feets_id', '=', $data['feets_id'])
										->get();
							if($tp){
								$this->res['status'] = true;
								$this->res['product'] = $tp;
							} else {
								$this->res['msg'] = 'No hay Prodcutos con estas Caracteristicas.';
							}
						} else {
							$this->res['msg'] = 'Todos los Campos son obligatorios.';	
						}
					break;
					default:
						$this->res['msg'] = 'Caigo Aqui :(';
					break;
				}
			} else {
				$this->res['msg'] = 'Selecciona un Tipo de Producto.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//OrderProductionValidate

	public function OrderProductionSave()
	{
		try{
			$order_production = $this->request->input('order_production');
			$products = $this->request->input('products');

			$data['date'] 			= $order_production['date'];
			$data['observations'] 	= $order_production['observations'];
			$data['total_pieces'] 	= $order_production['total_pieces'];

			$validator = Validator::make($data, [
    			'date' 				=> 'required',
    			'observations' 		=> 'required',
    			'total_pieces' 		=> 'required'
    		]);
			if ( !$validator->fails() ){
				$op_id = DB::table('orders_production')
							->insertGetId($data);
				foreach ($products as $kp => $vp) {

					DB::table('orders_production_products')
							->insert([
									'order_production_id'	=> $op_id,
									'product_id'			=> $vp['product_id'],
									'quantity'				=> $vp['quantity']
								]);
				}
				$this->res['status'] = true;
				$this->res['msg'] = 'Orden de Producción Guardada Correctamente.';
			} else {
				$this->res['msg'] = 'Todos los campos son obligatorios.';	
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//OrderProductionSave

	public function OrderProductionList()
	{
		try{
			$opl = DB::table('orders_production')
					->get();
			if( count($opl) > 0 ){
				$this->res['status'] = true;
				$this->res['data'] = $opl;
			} else {
				$this->res['msg'] = 'No hay Ordenes de Producción hasta el momento.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//OrderProductionList

	public function RequisitionIndex()
	{
		try{
			if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
				|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('supplaying') ) ){
					return view("Supplaying::requisition", ['request' => $this->request, 'ru' => $this->ru]);				
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//RequisitionIndex

	public function RequisitionDate()
	{
		try{
			$this->res['status'] = true;
			$this->res['data'] = date('Y-m-d');
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//RequisitionDate

	public function RequisitionProductList()
	{
		try{
			$type = $this->request->input('type');
			$pl = array();

			switch ($type) {
				case 'raw_material':
						$pl = DB::table('raw_material_products')
									->select('id', 'description', 'unit')
									->get();
						$msg = 'No hay Productos Materia Prima.';
					break;
				case 'finished_product':
						$pl = DB::table('finished_products')
									->select('id', 'description', 'unit')
									->get();
						$msg = 'No hay Productos de Producto Terminado.';
					break;
				case 'others':
						$msg = 'No hay Productos Varios.';
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
	}//RequisitionProductList

	public function RequisitionSave()
	{
		try{
			$requirement = $this->request->input('requirement');
			$products = $this->request->input('products');

			$data['id'] 			= $requirement['id'];
			$data['requested_date'] = $requirement['requested_date'];
			$data['required_date'] 	= $requirement['required_date'];
			$data['use'] 			= $requirement['use'];
			$data['observations'] 	= $requirement['observations'];
			$data['subtotal'] 		= $requirement['subtotal'];
			$data['iva'] 			= $requirement['iva'];
			$data['total'] 			= $requirement['total'];

			$validator = Validator::make($data, [
    			'requested_date' 		=> 'required',
    			'required_date' 		=> 'required',
    			'use' 					=> 'required',
    			'observations' 			=> 'required'
    		]);
			if ( !$validator->fails() ){
				if( count($products) >0 ){
					$data['products'] = json_encode($products); 
					if($data['id'] == null){
						$data['created_at'] = date('Y-m-d H:i:s');
						$data['user_id'] = \Sentry::getUser()->id;
						DB::table('requisitions')
								->insert($data);
						$this->res['status'] = true;
						$this->RequirementListInterface($pag);
						$this->res['msg'] = 'Requisición Guardada Correctamente';
					} else {
						$data['updated_at'] = date('Y-m-d H:i:s');
						DB::table('requisitions')
								->where('id', '=', $data['id'])
								->update($data);
						$this->res['status'] = true;
						$this->RequirementListInterface($pag);
						$this->res['msg'] = 'Requisición Actualizada Correctamente';
					}
				} else {
					$this->res['msg'] = 'Introduzca por lo menos, un Producto la Requisición.';
				}
			} else {
				$this->res['msg'] = 'Todos los campos de la Requisición son obligatorios.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//RequisitionSave

	public function RequirementListInterface($pag)
	{
		try{
			$rowsPerPage = 3;
			$total_rows = 0;
			$total_paginas = 0;
			$offset = ($pag - 1) * $rowsPerPage;
			if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) ){
				$cl = DB::table('requisitions')
						->orderBy('id', 'desc')
						->skip($offset)
						->take($rowsPerPage)
						->get();
				$total_rows = DB::table('requisitions')
									->count();
				$msg = 'No hay Requisiciones hasta el momento.';
			} else {
				$cl = DB::table('requisitions')
						->where('user_id', '=', \Sentry::getUser()->id)
						->orderBy('id', 'desc')
						->skip($offset)
						->take($rowsPerPage)
						->get();
				$total_rows = DB::table('requisitions')
									->where('user_id', '=', \Sentry::getUser()->id)
									->count();
				$msg = 'No hay Requisiciones hasta el momento.';
			}
			foreach ($cl as $kc => $vc) {
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
				$this->res['msg'] = $msg;
			}
		} catch (\Exception $e) {
			$this->res['msg'] = '¡Error!.'.$e;
		}
		return response()->json($this->res);
	}//RequirementListInterface

	public function RequisitionList()
	{
		try{
			$pag = $this->request->input('page');

			$this->RequirementListInterface($pag);
		} catch (\Exception $e) {
			$this->res['msg'] = '¡Error!.'.$e;
		}
		return response()->json($this->res);
	}//RequirementList

	public function RequisitionDelete()
	{
		try{
			$id = $this->request->input('id');

			DB::table('requisitions')
					->where('id', '=', $id)
					->delete();

			$this->res['status'] = true;
			$this->res['msg'] = 'Requisición Eliminada Correctamente.';
		} catch (\Exception $e) {
			$this->res['msg'] = '¡Error!.'.$e;
		}
		return response()->json($this->res);
	}//RequisitionDelete

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
