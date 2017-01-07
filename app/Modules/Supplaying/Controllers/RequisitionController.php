<?php 
namespace App\Modules\Supplaying\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Modules\Supplaying\Models;

use App\Modules\Users\Controllers\UsersController;

use Mail;
use Validator;
use DB;
use PDF;
use DateTime;

class RequisitionController extends Controller {

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

	public function RequisitionIndex()
	{
		try{
			return view("Supplaying::requisition", ['request' => $this->request, 'ru' => $this->ru]);
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.';
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
			$requirement = $this->request->input('requisition');
			$products = $this->request->input('products');
			$pag = $this->request->input('page');
			$filter_user = $this->request->input('filter_user');

			$data['id'] 			= $requirement['id'];
			$data['requested_date'] = $requirement['requested_date'];
			$data['required_date'] 	= $requirement['required_date'];
			$data['use'] 			= $requirement['use'];
			$data['observations'] 	= $requirement['observations'];
			$data['subtotal'] 		= $requirement['subtotal'];
			$data['iva'] 			= $requirement['iva'];
			$data['total'] 			= $requirement['total'];

			$data['final_order'] 		= 0;
			$data['finances_validate'] 	= 0;
			$data['pre_order'] 			= 0;
			$data['notifications'] 		= '[]';

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
						$data['updated_at'] = date('Y-m-d H:i:s');
						$data['user_id'] = \Sentry::getUser()->id;
						$g = DB::table('users_groups')
								->select('groups.id', 'groups.slug')
								->join('groups', 'groups.id', '=', 'users_groups.group_id')
								->where('users_groups.user_id', '=', $data['user_id'])
								->first();
						$data['group_id'] = $g->id;

						DB::table('requisitions')
								->insert($data);
						$this->res['status'] = true;
						$this->RequisitionListInterface($pag, $filter_user);
						$this->res['msg'] = 'Requisición Guardada Correctamente';
					} else {
							$rqe = DB::table('requisitions')
								->where('id', '=', $data['id'])
								->first();
						if($rqe){
							$data['updated_at'] = date('Y-m-d H:i:s');
							DB::table('requisitions')
									->where('id', '=', $data['id'])
									->update($data);
							$this->res['status'] = true;
							$this->RequisitionListInterface($pag, $filter_user);
							$this->res['msg'] = 'Requisición Actualizada Correctamente';
						} else {
							$this->res['msg'] = 'Esta Requisición ya no existe, recarge la pagina.';
						}
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

	public function RequisitionListInterface($pag, $filter_user)
	{
		try{
			$rowsPerPage = 10;
			$total_rows = 0;
			$total_paginas = 0;
			$offset = ($pag - 1) * $rowsPerPage;
			if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) || \Sentry::getUser()->inGroup( \Sentry::findGroupByName('supplaying') ) ){
				$q = DB::table('requisitions')
							->select('requisitions.id', 'requisitions.requested_date', 'requisitions.required_date', 'requisitions.use', 'requisitions.observations', 'requisitions.products', 'requisitions.user_id', 'groups.slug AS group_name', 'requisitions.subtotal', 'requisitions.iva', 'requisitions.total', 'requisitions.created_at', 'requisitions.updated_at', 'requisitions.finances_validate', 'requisitions.pre_order', 'requisitions.date', 'requisitions.pay_conditions', 'requisitions.provider_id', 'requisitions.deliver_place', 'requisitions.new_place', 'requisitions.order_observations', 'requisitions.ticket_pay_file', 'requisitions.notifications')
							->join('groups', 'groups.id', '=', 'requisitions.group_id');
				
				$q2 = DB::table('requisitions');

				if($filter_user != 'all'){
					$q = $q->where('groups.id', '=', $filter_user);
					$q2 = $q2->where('group_id', '=', $filter_user);
				}

				$cl = $q->where('finances_validate', '!=', 1)
						->orderBy('requisitions.id', 'desc')
						->skip($offset)
						->take($rowsPerPage)
						->get();

				$total_rows = $q2->count();

				$msg = 'No hay Requisiciones hasta el momento.';
			} else {
				$cl = DB::table('requisitions')
						->select('requisitions.id', 'requisitions.requested_date', 'requisitions.required_date', 'requisitions.use', 'requisitions.observations', 'requisitions.products', 'requisitions.user_id', 'groups.slug AS group_name', 'requisitions.subtotal', 'requisitions.iva', 'requisitions.total', 'requisitions.created_at', 'requisitions.updated_at', 'requisitions.pre_order', 'requisitions.date', 'requisitions.pay_conditions', 'requisitions.provider_id', 'requisitions.deliver_place', 'requisitions.new_place', 'requisitions.order_observations', 'requisitions.ticket_pay_file', 'requisitions.notifications')
						->join('groups', 'groups.id', '=', 'requisitions.group_id')
						->where('requisitions.user_id', '=', \Sentry::getUser()->id)
						->where('finances_validate', '!=', 1)
						->orderBy('requisitions.id', 'desc')
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

				$vc->notifications 	= str_replace("'", '"', $vc->notifications);
				$vc->notifications 	= json_decode($vc->notifications);
				$vc->notifications_total = 0;
				if($vc->notifications != null){
					foreach ($vc->notifications as $kn => $vn){
						if ($vn->seen == 0){
							$vc->notifications_total++;
						}
					}
				}
				$datetime1 = new DateTime(Date('Y-m-d H:i:s'));
				$datetime2 = new DateTime($vc->required_date);
				$interval = $datetime1->diff($datetime2);
				$vc->left_days = $interval->format('%R%a');
				$r = str_split($vc->left_days);
				if($r[0] == '+'){
					$vc->left_days = $r[1];
				}
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
			$filter_user = $this->request->input('filter_user');
			$pag = $this->request->input('page');

			$this->RequisitionListInterface($pag, $filter_user);
		} catch (\Exception $e) {
			$this->res['msg'] = '¡Error!.'.$e;
		}
		return response()->json($this->res);
	}//RequirementList

	public function RequisitionDelete()
	{
		try{
			$id = $this->request->input('id');
			$filter_user = $this->request->input('filter_user');
			$pag = $this->request->input('page');

			DB::table('requisitions')
					->where('id', '=', $id)
					->delete();

			$this->res['status'] = true;
			$this->RequisitionListInterface($pag, $filter_user);
			$this->res['msg'] = 'Requisición Eliminada Correctamente.';
		} catch (\Exception $e) {
			$this->res['msg'] = '¡Error!.'.$e;
		}
		return response()->json($this->res);
	}//RequisitionDelete


	public function RequisitionPDF($id)
	{
		//try{
			$r = DB::table('requisitions')
						->select('requisitions.id', 'requisitions.requested_date', 'requisitions.required_date', 'requisitions.use', 'requisitions.observations', 'requisitions.products', 'users.name', 'users.first_name', 'users.last_name', 'groups.slug AS group_name', 'requisitions.subtotal', 'requisitions.iva', 'requisitions.total')
						->join('users', 'users.id', '=', 'requisitions.user_id')
						->join('groups', 'groups.id', '=', 'requisitions.group_id')
						->where('requisitions.id', '=', $id)
						->first();
			$r->products = json_decode($r->products);

			//echo "<pre>".print_r($r, true)."</pre>";
	        //$pdf = App::make('dompdf');
	        //$pdf->loadHTML( View::make('almacen.pdf')->with(array('data' => $data, 'nombre_sol' => $nombre_sol, 'apellidos_sol' => $apellidos_sol)) );
	        //return $pdf->download('requisicion'.$id.'.pdf'); 

			//$pdf = make('dompdf.wrapper');
			$pdf = PDF::loadView('Supplaying::requisition_pdf', ['data' => $r, ]);
			return $pdf->download('requisition'.$id.'.pdf');
			//PDF::loadHTML( $this->request->view('Supplaying::requisition_pdf')->with(array('data' => $r, 'nombre_sol' => \Sentry::getUser()->first_name, 'apellidos_sol' => \Sentry::getUser()->last_name)) );
			//return PDF::download('requisicion'.$id.'.pdf');
		/*} catch (\Exception $e) {
			$this->res['msg'] = '¡Error!.'.$e;
		}*/
		//return response()->json($this->res);
	}//RequisitionPDF


	public function RequisitionValidateIndex()
	{
		try{
			if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
				|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('supplaying') ) 
				|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('finance') )){
					return view("Supplaying::requisition_validate", ['request' => $this->request, 'ru' => $this->ru]);				
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//

	public function RequisitionValidateInterface($pag, $filter_user)
	{
		try{
			$rowsPerPage = 10;
			$total_rows = 0;
			$total_paginas = 0;
			$offset = ($pag - 1) * $rowsPerPage;
			if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) || \Sentry::getUser()->inGroup( \Sentry::findGroupByName('finance') ) ){
				$q = DB::table('requisitions')
							->select('requisitions.id', 'requisitions.requested_date', 'requisitions.required_date', 'requisitions.use', 'requisitions.observations', 'requisitions.products', 'requisitions.user_id', 'groups.slug AS group_name', 'requisitions.subtotal', 'requisitions.iva', 'requisitions.total', 'requisitions.created_at', 'requisitions.updated_at', 'requisitions.finances_validate', 'requisitions.pre_order', 'requisitions.date', 'requisitions.pay_conditions', 'requisitions.provider_id', 'requisitions.deliver_place', 'requisitions.new_place', 'requisitions.order_observations', 'requisitions.ticket_pay_file', 'providers.contacts AS provider_contacts')
							->join('groups', 'groups.id', '=', 'requisitions.group_id')
							->join('providers', 'providers.id', '=', 'requisitions.provider_id');
				
				$q2 = DB::table('requisitions')
							->where('requisitions.finances_validate', '=', 0)
							->where('pre_order', '=', 1);

				if($filter_user != 'all'){
					$q = $q->where('groups.id', '=', $filter_user);
					$q2 = $q2->where('group_id', '=', $filter_user);
				}

				$cl = $q->where('requisitions.finances_validate', '=', 0)
						->where('pre_order', '=', 1)
						->orderBy('requisitions.id', 'desc')
						->skip($offset)
						->take($rowsPerPage)
						->get();

				$total_rows = $q2->count();

				$msg = 'No hay Ordenes de Compra hasta el momento.';
			}
			foreach ($cl as $kc => $vc) {
				$vc->products 	= str_replace("'", '"', $vc->products);
				$vc->products 	= json_decode($vc->products);

				$vc->provider_contacts = str_replace("'", '"', $vc->provider_contacts);
				$vc->provider_contacts 	= json_decode($vc->provider_contacts);
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
	}//RequisitionValidateList

	public function RequisitionValidateList()
	{
		try{
			$filter_user = $this->request->input('filter_user');
			$pag = $this->request->input('page');

			$this->RequisitionValidateInterface($pag, $filter_user);
		} catch (\Exception $e) {
			$this->res['msg'] = '¡Error!.'.$e;
		}
		return response()->json($this->res);
	}//RequisitionValidateList

	public function OrderBuyIndex()
	{
		try{
			return view("Supplaying::order_buy", ['request' => $this->request, 'ru' => $this->ru]);				
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//OrderBuyIndex

	public function OrderBuyProviders()
	{
		try{
			$p = DB::table('providers')
					->where('provider_type_id', '=', 1)
					->get();
			if( count($p) > 0 ){
				$this->res['data'] = $p;
				$this->res['status'] = true;
			} else {
				$this->res['msg'] = 'No hay Proveedores.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//OrderBuyProviders

	public function OrderBuySave()
	{
		try{
			$data 				= $this->request->input('order_buy');
			$id 				= $this->request->input('id');
			$pag 				= $this->request->input('page');
			$filter_user 		= $this->request->input('filter_user');

			$validator = Validator::make($data, [
    			'date' 					=> 'required',
    			'pay_conditions' 		=> 'required',
    			'provider_id' 			=> 'required',
    			'deliver_place' 		=> 'required',
    			'order_observations' 	=> 'required'
    		]);
    		$ban = false;

			if ( !$validator->fails() ){
				if($data['deliver_place'] == 'other'){
					if( !empty($data['new_place']) ){
						$ban = true;
					} else {
						$msg = 'Introduzca un nuevo lugar de entrega.';
					}
				} else {
					$ban = true;
				}
				if($ban == true){
					$data['pre_order'] = 1;
					DB::table('requisitions')
							->where('id', '=', $id)
							->update($data);
					$this->res['status'] = true;
					$this->RequisitionListInterface($pag, $filter_user);
					$this->res['msg'] = 'Requisición Convertida a Orden de Compra.';
				} else {
					$this->res['msg'] = $msg;	
				}

			} else {
				$this->res['msg'] = 'Todos los Campos son requeridos.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//OrderBuySave

	public function OrderBuyFinancesValidate()
	{
		try{
			$id 				= $this->request->input('order_id');
			$pag 				= $this->request->input('page');
			$filter_user 		= $this->request->input('filter_user');
			$provider_list		= $this->request->input('provider_list');
			$ban_email = false;
			$msg = '';
			
			if( count($provider_list) > 0 ){
				foreach ($provider_list as $kpl => $vpl) {
					$validator = Validator::make(['email' => $vpl['email']], 
												['email' => 'required|email']);
					if ( !$validator->fails() ){
						$ban_email = true;
					} else {
						$msg = 'Debe seleccionar un Proveedor con un Correo Electronico valido.';
					}
				}
				if($ban_email == true){
					$provider_list = json_encode($provider_list); 

					DB::table('requisitions')
							->where('id', '=', $id)
							->update( ['finances_validate' => 1, 'provider_email' => $provider_list] );

					$this->request->session()->put('order.id', $id);
					$this->RequisitionListInterface($pag, $filter_user);
					$this->res['status'] = true;
				} else {
					$this->res['msg'] = $msg;
				}
			} else {
				$this->res['msg'] = 'Seleccione un Contacto.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//OrderBuyFinancesValidate

	public function OrderBuyFinancesUpload()
	{
		$data = array();
		$files = array();
		$id = $this->request->session()->get('order.id');
		$uploaddir = 'order_buy_ticket/';

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
	 					$t = DB::table('requisitions')
	 								->where('id', '=', $id)
	 								->first();
						if( count($t) == 1 ){
							DB::table('requisitions')
	 								->where('id', '=', $id)
	 								->update(array(
	 										'ticket_pay_file' => $actual_image_name
	 									));
	 						/*$rq = DB::table('requisitions')
	 									->where('id', '=', $id)
	 									->first();

							$rq->provider_email 	= str_replace("'", '"', $rq->provider_email);
							$rq->provider_email 	= json_decode($rq->provider_email);

							foreach ($rq->provider_email as $kpe => $vpe) {

							}*/

							$this->res['status'] = true;
							$this->res['msg'] = 'Boucher de Requisición Subida Correctamente.';
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
	}//OrderBuyFinancesUpload

	public function OrderBuyList()
	{
		try{
			$pag 				= $this->request->input('page');
			$rowsPerPage = 10;
			$total_rows = 0;
			$total_paginas = 0;
			$offset = ($pag - 1) * $rowsPerPage;
			
			if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) || \Sentry::getUser()->inGroup( \Sentry::findGroupByName('supplaying') ) || \Sentry::getUser()->inGroup( \Sentry::findGroupByName('finance') ) ){
				$ob = DB::table('requisitions')
							->select('requisitions.id', 'requisitions.requested_date', 'requisitions.required_date', 'requisitions.use', 'requisitions.observations', 'requisitions.products', 'requisitions.user_id', 'groups.slug AS group_name', 'requisitions.subtotal', 'requisitions.iva', 'requisitions.total', 'requisitions.created_at', 'requisitions.updated_at', 'requisitions.date', 'requisitions.pay_conditions', 'requisitions.provider_id', 'requisitions.deliver_place', 'requisitions.new_place', 'requisitions.order_observations', 'requisitions.ticket_pay_file', 'requisitions.pre_order', 'requisitions.finances_validate', 'requisitions.provider_email')
							->join('groups', 'groups.id', '=', 'requisitions.group_id')
							->where('requisitions.pre_order', '=', 1)
							->where('requisitions.finances_validate', '=', 1)
							->orderBy('requisitions.id', 'desc')
							->skip($offset)
							->take($rowsPerPage)
							->get();
				
				$total_rows = DB::table('requisitions')
							->where('pre_order', '=', 1)
							->where('finances_validate', '=', 1)
							->count();
			} else {
				$ob = DB::table('requisitions')
							->select('requisitions.id', 'requisitions.requested_date', 'requisitions.required_date', 'requisitions.use', 'requisitions.observations', 'requisitions.products', 'requisitions.user_id', 'groups.slug AS group_name', 'requisitions.subtotal', 'requisitions.iva', 'requisitions.total', 'requisitions.created_at', 'requisitions.updated_at', 'requisitions.date', 'requisitions.pay_conditions', 'requisitions.provider_id', 'requisitions.deliver_place', 'requisitions.new_place', 'requisitions.order_observations', 'requisitions.ticket_pay_file', 'requisitions.pre_order', 'requisitions.finances_validate', 'requisitions.provider_email')
							->join('groups', 'groups.id', '=', 'requisitions.group_id')
							->where('requisitions.pre_order', '=', 1)
							->where('requisitions.finances_validate', '=', 1)
							->where('requisitions.user_id', '=', \Sentry::getUser()->id)
							->orderBy('requisitions.id', 'desc')
							->skip($offset)
							->take($rowsPerPage)
							->get();
				
				$total_rows = DB::table('requisitions')
							->where('pre_order', '=', 1)
							->where('finances_validate', '=', 1)
							->where('user_id', '=', \Sentry::getUser()->id)
							->count();
			}
			foreach ($ob as $ko => $vo) {
				$vo->products 	= str_replace("'", '"', $vo->products);
				$vo->products 	= json_decode($vo->products);

				$vo->provider_email 	= str_replace("'", '"', $vo->provider_email);
				$vo->provider_email 	= json_decode($vo->provider_email);
			}
			if( count($ob) > 0 ){
				if($rowsPerPage <= $total_rows){
					$total_paginas = 1;
				}
				$total_paginas = ceil($total_rows / $rowsPerPage);
				$this->res['tp'] = $total_paginas;
				$this->res['status'] = true;
				$this->res['data'] = $ob;
				$this->res['msg'] = '';
			} else {
				$this->res['msg'] = 'No hay Ordenes de Compra.';
			}
			$this->res['status'] = true;
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//OrderBuyList

	public function OrderBuyEmail()
	{
		try{
			$user = \Sentry::getUser();
			/*$rq = DB::table('requisitions')
							->where('id', '=', 3)
							->first();

			$rq->provider_email 	= str_replace("'", '"', $rq->provider_email);
			$rq->provider_email 	= json_decode($rq->provider_email);*/

			/*foreach ($rq->provider_email AS $kpe => $vpe) {
				Mail::send('emails.order_buy', ['user' => $user, 'vpe' => $vpe], function ($m) use ($vpe) {
		            $m->from('samuel43_7@hotmail.com', 'Your Application');

		            $m->to($vpe->email, $vpe->name)->subject('Your Reminder!');
		        });
			}*/
				Mail::send('emails.order_buy', ['user' => $user], function ($m) use ($user) {
		            $m->from('samuel43_7@hotmail.com', 'Your Application');

		            $m->to($user->email, $user->name)->subject('Your Reminder!');
		        });
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
	}//OrderBuyEmail

	public function RequisitionNotificationSave()
	{
		try{
			$data = array();

			$notifications 			= $this->request->input('notifications');
			$id 					= $this->request->input('id');

			$notifications = json_encode($notifications); 

			$data['notifications'] = $notifications;

			DB::table('requisitions')
					->where('id', '=', $id)
					->update($data);
			$this->res['status'] = true;
			$this->res['msg'] = 'Notificaciones actualizadas correctamente.';
			$this->NotificationsListInterface($id);

		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//RequisitionNotificationSave

	public function NotificationsListInterface($id)
	{
		try{
			$rq = DB::table('requisitions')
					->select('notifications')
					->where('id', '=', $id)
					->first();

			if($rq){
				$rq->notifications = str_replace("'", '"', $rq->notifications);
				$rq->notifications 	= json_decode($rq->notifications);
				if( count($rq->notifications) > 0){
					$this->res['status'] = true;
					$this->res['data'] = $rq->notifications;
				} else {
					$this->res['msg'] = 'No hay Notificaciones';
				}
			} else {
				$this->res['msg'] = 'La Requisición no existe.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//NotificationsList()

	public function NotificationsList()
	{
		try{
			$id = $this->request->input('id');

			$this->NotificationsListInterface();
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//NotificationsList()

}//RequisitionController