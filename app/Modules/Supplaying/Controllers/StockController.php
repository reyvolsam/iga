<?php 
namespace App\Modules\Supplaying\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Modules\Supplaying\Models;

use App\Modules\Users\Controllers\UsersController;

use Validator;
use DB;

class StockController extends Controller {

	private $res = array();
	private $request;
  	private $ru;

	function __construct(Request $request)
	{
		$this->request = $request;
		$this->res['status'] = false;
		$uc = new UsersController($request);
		$this->ru = $uc->GetUserRole();
	}

	public function StockRawMaterialIndex()
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('supplaying') ) ){
				return view("Supplaying::stock.raw_material_entry", ['request' => $this->request, 'ru' => $this->ru]);	
		}
	}//StockRawMaterialIndex

	public function StockRawMaterialLoad()
	{
		try{
			$entry = DB::table('raw_material_move_entry')
						->orderBy('id', 'DESC')
						->get();
			if( count($entry) ){
				$providers = DB::table('providers')
								->select('id', 'name', 'comercial')
								->where('provider_type_id', '=', 1)
								->get();
				if( count($providers) ){
					$products = DB::table('raw_material_products')
									->select('id','code', 'name', 'description', 'unit')
									->get();
					if( count($products) ){
						$packs = DB::table('pack_send')
										->get();
						$this->res['entry'] = $entry;
						$this->res['providers'] = $providers;
						$this->res['packs'] = $packs;
						$this->res['products'] = $products;
						$this->res['date'] = date('Y-m-d');
						$this->res['status'] = true;
					}
				} else {
					$this->res['msg'] = 'No hay Proveedores de Materia Prima.';
				}
			} else {
				$this->res['msg'] = 'No hay Movimientos de Entrada de Materia Prima.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos'.$e;
		}
		return response()->json($this->res);
	}//StockRawMaterialLoad

	public function StockRawMaterialSave()
	{
		try{
			$move = $this->request->input('move');
			$products = $this->request->input('products');
			$ban = false;
			if( count($products) > 0){
				$data['move_entry_id'] 		= $move['id'];
				$data['date'] 				= $move['date'];
				switch ($move['id']) {
					case 1:
						$data['provider_id'] 		= $move['provider_id'];
						$data['invoice'] 			= $move['invoice'];
						$data['invoice_date'] 		= $move['invoice_date'];
						$data['pack_send_id'] 		= $move['pack_send_id'];
						$data['pack_send_invoice'] 	= $move['pack_send_invoice'];
						$data['pack_send_cost'] 	= $move['pack_send_cost'];

						$validator = Validator::make($data, [
				        				'move_entry_id' 	=> 'required',
				        				'date' 				=> 'required',
				        				'provider_id' 		=> 'required',
				        				'invoice' 			=> 'required',
				        				'invoice_date' 		=> 'required',
				        				'pack_send_id' 		=> 'required',
				        				'pack_send_invoice' => 'required',
				        				'pack_send_cost' 	=> 'required'

				        			]);
						$ban = true;
						break;
					case 2:
						$data['provider_id'] 		= $move['provider_id'];

						$validator = Validator::make($data, [
				        				'move_entry_id' 	=> 'required',
				        				'provider_id' 		=> 'required',
				        				'date' 				=> 'required'
				        			]);
						break;
					case 3:

						$data['provider_id'] 		= $move['provider_id'];
						$data['invoice'] 			= $move['invoice'];

						$validator = Validator::make($data, [
				        				'move_entry_id' 	=> 'required',
				        				'date' 				=> 'required',
				        				'provider_id' 		=> 'required',
				        				'inovice' 			=> 'required',
				        			]);
						$ban = true;
						break;
					case 4:
						$data['provider_id'] 		= $move['provider_id'];

						$validator = Validator::make($data, [
				        				'move_entry_id' 	=> 'required',
				        				'provider_id' 		=> 'required',
				        				'date' 				=> 'required'
				        			]);
						$ban = true;
						break;
					case 5:
						$data['provider_id'] 		= $move['provider_id'];

						$validator = Validator::make($data, [
				        				'move_entry_id' 	=> 'required',
				        				'provider_id' 		=> 'required',
				        				'date' 				=> 'required'
				        			]);
						$ban = true;
						break;
					case 6:
						$data['provider_id'] 				= $move['provider_id'];
						$data['order_production_number'] 	= $move['order_production_number'];
						$data['factory_product'] 			= $move['factory_product'];
						$data['factory_pieces'] 			= $move['factory_pieces'];

						$validator = Validator::make($data, [
				        				'move_entry_id' 			=> 'required',
				        				'date' 						=> 'required',
				        				'provider_id' 				=> 'required',
				        				'order_production_number' 	=> 'required',
				        				'factory_product' 			=> 'required',
				        				'factory_pieces' 			=> 'required'
				        			]);
						$ban = true;
						break;
					case 7:
						$data['provider_id'] 		= $move['provider_id'];

						$validator = Validator::make($data, [
				        				'move_entry_id' 	=> 'required',
				        				'provider_id' 		=> 'required',
				        				'date' 				=> 'required'
				        			]);
						$ban = true;
						break;
					default:
						$this->res['msg'] = 'Seleccione un Tipo de Movimiento.';		
						break;
				}
				if($ban){
					if ( !$validator->fails() ){
						$stock_entry_id = DB::table('raw_material_stock_entry')
								->insertGetId($data);
						foreach ($products as $kp => $vp) {
							$total = DB::table('raw_material_products')
									->select('total')
									->where('id', '=', $vp['id'])
									->first();
							
							$item = array(
									'stock_entry_id'	=> $stock_entry_id,
									'product_id'		=> $vp['id'],
									'quantity'			=> $vp['quantity'],
									'lote'				=> $vp['lote'],
									'succed'			=> 1
								);
							if($total){
								DB::table('raw_material_stock_entry_item')
										->insert($item);
								$total->total = (int)$total->total + (int)$vp['quantity'];
								DB::table('raw_material_products')
										->where('id', '=', $vp['id'])
										->update(['total'=> $total->total]);
								$this->StockRawMaterialList();
								$this->res['status'] = true;
								$this->res['msg'] = 'Movimiento de Entrada de Materia Prima Guardado Correctamente.';
							} else {
								$item['succed'] = 0;
								DB::table('raw_material_stock_entry_item')
										->insert($item);
							}

						}
					} else {
						$this->res['msg'] = 'Todos los Campos del Movimiento Selecionado son requeridos.';
					}
				}
			} else {
				$this->res['msg'] = 'Agrege una partida de Producto, para poder realizar esta Entrada de Materia Prima.';
			}

		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//StockRawMaterialSave

	public function StockRawMaterialList()
	{
		try{
			$srm = array();
			$srm = DB::table('raw_material_stock_entry_item AS rmsi')
					->select('rmse.id AS move_id', 'rmsi.quantity', 'rmsi.lote', 'rmse.date AS move_date', 'rmme.name AS move_entry_name', 'raw_material_products.description AS product_description', 'raw_material_products.code', 'raw_material_products.unit')
					->join('raw_material_stock_entry AS rmse', 'rmse.id', '=', 'rmsi.stock_entry_id')
					->join('raw_material_move_entry AS rmme', 'rmme.id', '=', 'rmse.move_entry_id')
					->join('raw_material_products', 'raw_material_products.id', '=', 'rmsi.product_id')
					->where('succed', '=', 1)
					->orderBy('rmse.id', 'DESC')
					->get();
			if( count($srm) ){
				$this->res['status'] = true;
				$this->res['data'] = $srm;
			} else {
				$this->res['msg'] = 'No hay Movimientos de Entrada de Materia Prima.';	
			}

		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//StockRawMaterialList

	public function StockRawMaterialView()
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('supplaying') ) ){
				return view("Supplaying::stock.index", ['request' => $this->request, 'ru' => $this->ru]);	
		}		
	}//StockRawMaterialView

	public function StockRawMaterialListStock()
	{
		try{
			$rmp = array();
			$rmp = DB::table('raw_material_products')
					->get();
			if( count($rmp) ){
				$this->res['status'] = true;
				$this->res['data'] = $rmp;
			} else {
				$this->res['msg'] = 'No hay Productos de Materia Prima.';	
			}

		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);		
	}//StockRawMaterialListStock


	public function ClientView()
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('supplaying') ) ){
				return view("Supplaying::client", ['request' => $this->request, 'ru' => $this->ru]);
		}
	}


}//StockController
