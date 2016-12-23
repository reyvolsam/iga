<?php 
namespace App\Modules\Selling\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Modules\Selling\Models;

use App\Modules\Users\Controllers\UsersController;

use Validator;
use DB;

class SellingController extends Controller {

	private $res = array();
	private $ru;
	private $request;

	function __construct(Request $request)
	{
		$this->request = $request;
		$this->res['status'] = false;
		$this->res['msg'] = '';
		$uc = new UsersController($request);
		$this->ru = $uc->GetUserRole();
	}//__construct

	public function ClientsIndex()
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('selling') ) ){
				return view("Selling::clients", ['request' => $this->request, 'ru' => $this->ru]);	
		}
	}//ClientsIndex

	public function ClientsUsersList()
	{
		try{
			$ul = DB::table('users_groups')
					->select( DB::raw("CONCAT(users.name, ' ',users.first_name, ' ', users.last_name) AS name, users.id") )
					->join('users', 'users.id', '=', 'users_groups.user_id')
					->where('group_id', '=', 6)
					->get();

			if( count($ul) > 0 ){
				$this->res['data'] = $ul;
				$this->res['status'] = true;
			} else {
				$this->res['msg'] = 'No hay Ejecutivos de Ventas registrados';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//ClientsUsersList

	public function ClientsBanksList()
	{
		try{
			$b = DB::table('banks')
					->get();
			if( count($b) > 0 ){
				$this->res['status'] = true;
				$this->res['data'] = $b;
			} else {
				$this->res['msg'] = 'No hay Bancos registrados.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//ClientsBanksList

	public function ClientsSendsList()
	{
		try{
			$s = DB::table('pack_send')
					->get();
			if( count($s) > 0 ){
				$this->res['status'] = true;
				$this->res['data'] = $s;
			} else {
				$this->res['msg'] = 'No hay Paqueterias registrados.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//ClientsSendsList

	public function ClientsSave()
	{
		try{
			$pag = $this->request->input('page');

			if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) ){
				$data['user_id'] = $this->request->input('client')['user_id'];	
			} else {
				$data['user_id'] = \Sentry::getUser()->id;
			}
			$id 					= $this->request->input('client')['id'];
			$data['type_company'] 	= $this->request->input('client')['type_company'];
			$data['pay_type'] 		= $this->request->input('client')['pay_type'];
			$data['rfc'] 			= $this->request->input('client')['rfc'];
			$data['razon_social'] 	= $this->request->input('client')['razon_social'];
			$data['tradename'] 		= $this->request->input('client')['tradename'];
			$data['web'] 			= $this->request->input('client')['web'];
			$data['pay_method'] 	= $this->request->input('client')['pay_method'];
			
			$validator = Validator::make($data, [
	        			'type_company' 	=> 'required',
	        			'rfc' 			=> 'required',
	        			'razon_social' 	=> 'required',
	        			'tradename' 	=> 'required',
	        			'web' 			=> 'required',
	        			'pay_method' 	=> 'required'
	        		]);
			$data['banks'] 			= $this->request->input('banks');
			$data['contacts'] 		= $this->request->input('contacts');
			$data['fiscal'] 		= $this->request->input('fiscal');
			$data['send'] 			= $this->request->input('send');

	      	if ( !$validator->fails() ){
	      		$this->res['msg'] = '';
	      		if( count($data['contacts']) > 0 ){
	      			if( count($data['banks']) > 0 ){
						if( count($data['fiscal']) > 0 ){
							if( count($data['send']) == 0 ){
								$this->res['msg'] = '<p>El Cliente se guardara, pero no se podra utilizar hasta que se agreguen los Datos de Envio.</p>';
							}
							$data['user_creator_id'] = \Sentry::getUser()->id;
							$data['banks'] = json_encode($data['banks']); 
							$data['contacts'] = json_encode($data['contacts']); 
							$data['fiscal'] = json_encode($data['fiscal']);
							$data['send'] = json_encode($data['send']);

							if($id == null){
								$data['updated_at'] = date('Y-m-d H:i:s');
								$data['created_at'] = date('Y-m-d H:i:s');
								DB::table('clients')
										->insert($data);
								$this->InterfaceClientsList($pag);
								$this->res['msg'] .= '<p><b>Cliente Guardado Correctamente.</b></p>';
								$this->res['status'] = true;
							} else {
								$data['updated_at'] = date('Y-m-d H:i:s');
								DB::table('clients')
										->where('id', '=', $id)
										->update($data);
								$this->InterfaceClientsList($pag);
								$this->res['msg'] .= '<p><b>Cliente Actualizado Correctamente.</b></p>';
								$this->res['status'] = true;
							}
						} else {
							$this->res['msg'] = 'Debe de Proporcionar los Datos Fiscales, para el Cliente.';
						}
	      			} else {
		      			$this->res['msg'] = 'Debe de Proporcionar una Cuenta de Banco por lo menos, para el Cliente.';
		      		}
	    	  	} else {
	    	  		$this->res['msg'] = 'Debe de Proporcionar un Contacto por lo menos, para el Cliente.';
	      		}
	      	} else {
	      		$this->res['msg'] = 'Todos los Campos deben estar llenos.';
	      	}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//ClientsSave

	public function InterfaceClientsList($pag)
	{
		try{
			$rowsPerPage = 3;
			$total_rows = 0;
			$total_paginas = 0;
			$offset = ($pag - 1) * $rowsPerPage;

			if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) ){
				$cl = DB::table('clients')
						->orderBy('id', 'desc')
						->skip($offset)
						->take($rowsPerPage)
						->get();
				$total_rows = DB::table('clients')
									->count();
				$msg = 'No hay Clientes Registrados.';
			} else {
				$cl = DB::table('clients')
						->where('user_id', '=', \Sentry::getUser()->id)
						->orderBy('id', 'desc')
						->skip($offset)
						->take($rowsPerPage)
						->get();
				$total_rows = DB::table('clients')
									->where('user_id', '=', \Sentry::getUser()->id)
									->count();
				$msg = 'No tienes Clientes Registrados.';
			}

			foreach ($cl as $kc => $vc) {
				$vc->contacts 	= str_replace("'", '"', $vc->contacts);
				$vc->contacts 	= json_decode($vc->contacts);
				$vc->banks 		= str_replace("'", '"', $vc->banks);
				$vc->banks 		= json_decode($vc->banks);
				$vc->fiscal 	= str_replace("'", '"', $vc->fiscal);
				$vc->fiscal 	= json_decode($vc->fiscal);
				$vc->send 		= str_replace("'", '"', $vc->send);
				$vc->send 		= json_decode($vc->send);
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
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
	}//InterfaceClientsList

	public function ClientsList()
	{
		try{
			$pag = $this->request->input('page');

			$this->InterfaceClientsList($pag);
		} catch (\Exception $e) {
			$this->res['msg'] = '¡Error!.'.$e;
		}
		return response()->json($this->res);
	}//ClientsList

	public function ClientDelete()
	{
		try{
			$id = $this->request->input('id');
			$pag = $this->request->input('page');
			$c = DB::table('clients')
						->where('id', '=', $id)
						->get();
			if( count($c) > 0 ){
				DB::table('clients')
						->where('id', '=', $id)
						->delete();
				$this->InterfaceClientsList($pag);

				$this->res['status'] = true;
				$this->res['msg'] = 'Cliente Eliminado Correctamente.';
			} else {
				$this->res['msg'] = 'Este Cliente no existe.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = '¡Error!.'.$e;
		}
		return response()->json($this->res);
	}//ClientDelete

	public function ClientPC()
	{
		try{
			$pc = $this->request->input('pc');

			if( !empty($pc) ){
				$cpl = DB::table('CodigosPostales')
						->where('CodigoPostal', '=', $pc)
						->get();
				if( count($cpl) > 0 ){
					$this->res['data'] = $cpl;
					$this->res['status'] = true;
				} else {
					$this->res['msg'] = 'No existe este Codigo Postal.';
				}
			} else {
				$this->res['msg'] = 'Introduzca un Codigo Postal Valido.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = '¡Error!.'.$e;
		}
		return response()->json($this->res);
	}//ClientPC

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view("Selling::index");
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
		try{

		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.'.$e;
		}
		return response()->json($this->res);
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
