<?php 
namespace App\Modules\Supplaying\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Modules\Supplaying\Models;

use App\Modules\Users\Controllers\UsersController;

use Validator;
use DB;

class OrdersProductionController extends Controller {

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
			$data['type'] 			= $op['type'];
			$data['stock_location'] = $op['stock_location'];

			$validator = Validator::make($data, [
	        			'type' 				=> 'required',
	        			'stock_location' 	=> 'required'
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
								$rp = DB::table('recipes')
										->where('product_type', '=', 'finished_product')
										->where('product_type_id', '=', $tp->id)
										->first();
								if($rp){
									$this->res['status'] = true;
									$this->res['product'] = $tp;
								} else {
									$this->res['msg'] = 'Este Producto no tiene Receta creada.';
								}
							} else {
								$this->res['msg'] = 'No hay Productos con estas Caracteristicas.';
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
								$rp = DB::table('recipes')
										->where('product_type', '=', 'finished_product')
										->where('product_type_id', '=', $tp->id)
										->first();
								if($rp){
									$this->res['status'] = true;
									$this->res['product'] = $tp;
								} else {
									$this->res['msg'] = 'Este Producto no tiene Receta creada.';
								}
							} else {
								$this->res['msg'] = 'No hay Productos con estas Caracteristicas.';
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
								$rp = DB::table('recipes')
										->where('product_type', '=', 'finished_product')
										->where('product_type_id', '=', $tp->id)
										->first();
								if($rp){
									$this->res['status'] = true;
									$this->res['product'] = $tp;
								} else {
									$this->res['msg'] = 'Este Producto no tiene Receta creada.';
								}
							} else {
								$this->res['msg'] = 'No hay Productos con estas Caracteristicas.';
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
								$rp = DB::table('recipes')
										->where('product_type', '=', 'finished_product')
										->where('product_type_id', '=', $tp->id)
										->first();
								if($rp){
									$this->res['status'] = true;
									$this->res['product'] = $tp;
								} else {
									$this->res['msg'] = 'Este Producto no tiene Receta creada.';
								}
							} else {
								$this->res['msg'] = 'No hay Productos con estas Caracteristicas.';
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
								$rp = DB::table('recipes')
										->where('product_type', '=', 'finished_product')
										->where('product_type_id', '=', $tp->id)
										->first();
								if($rp){
									$this->res['status'] = true;
									$this->res['product'] = $tp;
								} else {
									$this->res['msg'] = 'Este Producto no tiene Receta creada.';
								}
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
				$this->res['msg'] = 'Todos los campos son requeridos.';
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
				$success = false;
				$msg = '';
				$cont = 0;
				//$this->res['rs'] = '';
				foreach ($products as $kp => $vp) {
					$rp = DB::table('recipes')
							->where('product_type', '=', 'finished_product')
							->where('product_type_id', '=', $vp['product_id'])
							->first();
					$rp->products 	= str_replace("'", '"', $rp->products);
					$rp->products 	= json_decode($rp->products);
					if($rp){
						foreach ($rp->products as $krp => $vrp) {
							$table = '';
							if($vrp->type == 'finished_products'){
								$table = $vp['stock_location'];
							}
							if($vrp->type == 'semifinished_products'){
								$table = $vrp->type;
							}
							if($vrp->type == 'raw_material_products'){
								$table = $vrp->type;
							}
							$sp = DB::table($table)
									->where('id', '=', $vrp->id)
									->first();

							if($sp){
								$tp = 0;
								if((float)$sp->total > 0){
									$tp = $vp['quantity'] * $vrp->quantity;
									if((float)$sp->total > $tp){
										$success = true;
									} else {
										$msg = $msg.'<p>No hay suficiente en stock del producto: '.$vrp->description.' para fabricar el producto indicado.</p>';
										$cont++;
									}
								} else {
									$msg = $msg.'<p>El stock del producto: '.$vrp->description.' esta vacio.</p>';
									$cont++;
								}
							} else{
								$msg = $msg.'<p>El Producto: '.$vrp->description.' no existe.</p>';
								$cont++;
							}
						}
					} else {
						$msg = $msg.'<p>El Producto: '.$vp['product_description'].' no tiene receta.</p>';	
						$cont++;
					}
				}
				//$this->res['msg'] = $msg;
				if($cont == 0){
					$op_id = DB::table('orders_production')
								->insertGetId($data);
					foreach ($products as $kp => $vp) {
						$rp = DB::table('recipes')
								->where('product_type', '=', 'finished_product')
								->where('product_type_id', '=', $vp['product_id'])
								->first();

						$rp->products 	= str_replace("'", '"', $rp->products);
						$rp->products 	= json_decode($rp->products);
						foreach ($rp->products as $krp => $vrp) {
							$total_pieces = 0;
							$total_pieces = $vp['quantity'] * $vrp->quantity;
							$table = '';
							if($vrp->type == 'finished_products'){
								$table = $vp['stock_location'];
								$fp = DB::table($table)
											->where('finished_product_id', '=', $vrp->id)
											->first();
							}
							if($vrp->type == 'semifinished_products'){
								$table = $vrp->type;
								$fp = DB::table($table)
										->where('id', '=', $vrp->id)
										->first();
							}
							if($vrp->type == 'raw_material_products'){
								$table = $vrp->type;
								$fp = DB::table($table)
										->where('id', '=', $vrp->id)
										->first();
							}
							
							$final_total = 0;
							$final_total = $fp->total - $total_pieces;
							//$this->res['rs'] = $this->res['rs'].'/'.$total_pieces.'='.$vp['quantity'].'*'.$vrp->quantity.'-'.$final_total;
							if($vrp->type == 'finished_products'){
								DB::table($table)
										->where('finished_product_id', '=', $vrp->id)
										->update(['total' => $final_total]);
								$vrp->quantity = $total_pieces;
							}
							if($vrp->type == 'semifinished_products'){
								$fp = DB::table($table)
											->where('id', '=', $vrp->id)
											->update(['total' => $final_total]);
								$vrp->quantity = $total_pieces;
							}
							if($vrp->type == 'raw_material_products'){
								$fp = DB::table($table)
											->where('id', '=', $vrp->id)
											->update(['total' => $final_total]);
								$vrp->quantity = $total_pieces;
							}
						}

						$rp->products = json_encode($rp->products);

						DB::table('orders_production_products')
								->insert([
										'order_production_id'	=> $op_id,
										'product_id'			=> $vp['product_id'],
										'quantity'				=> $vp['quantity'],
										'stock_location'		=> $vp['stock_location'],
										'recipe'				=> $rp->products
									]);
					}
					self::OrderProductionList();
					$this->res['status'] = true;
					$this->res['msg'] = 'Orden de Producción Guardada Correctamente.';
				} else {
					$this->res['msg'] = $msg;
				}
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

}
