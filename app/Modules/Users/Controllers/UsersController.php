<?php 
namespace App\Modules\Users\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Modules\Users\Models;

use App\Modules\Users\Models\Users;
use App\Modules\Users\Models\Posts;

use Validator;
use DB;

class UsersController extends Controller {

  	private $res = array();
  	private $request;
  	private $ru;

	function __construct(Request $request){
		$this->request = $request;
		$this->res['status'] = false;
		$this->ru = $this->GetUserRole();
		
	}

	public function GetUserRole()
	{
		if( \Sentry::check() ){
			$this->ru = DB::table('users_groups')
							->select('groups.slug AS group_slug', 'groups.name AS group_name')
							->where('user_id', '=', \Sentry::getUser()->id)
							->join('groups', 'groups.id', '=', 'users_groups.group_id')
							->first();
			return $this->ru;
		} else {
			$this->res['msg'] = 'Inicie Sesión para seguir trabajando.';
		}
	}//get_user_role

	public function UsersPost()
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('human_resources') ) ){
				return view("Users::post", ['request' => $this->request, 'ru' => $this->ru]);	
		}
		
	}

	public function UsersPostList()
	{
		$groups = array();
		$less = $this->request->input('less');
		try{
			if($less == null){
				$groups = DB::table('groups')
							->where('id', '!=', 1)
							->get();
				if( count($groups) ){
					$this->res['status'] = true;
					$this->res['data'] = $groups;
				} else {
					$this->res['msg'] = 'No hay Puestos.';
				}
			} else {
				$groups = DB::table('groups')
								->where('id', '!=', 1)
								->where('id', '!=', $less)
								->get();
				if( count($groups) ){
					$this->res['status'] = true;
					$this->res['data'] = $groups;
				} else {
					$this->res['msg'] = 'No hay Puestos.';
				}
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos'.$e;
		}
		return response()->json($this->res);
	}//UsersPostList

	public function UsersPostSave()
	{
		$post_name = $this->request->input('post_name');
		$chief_id = $this->request->input('chief_id');
		$id = $this->request->input('id');

		$data = array(
					'name' 		=> $post_name,
					'chief_id' 	=> $chief_id
				);
		$validator = Validator::make($data, [
	        			'name' 			=> 'required',
	        		]);
		try{
			if ( !$validator->fails() ){
				if($id == null){
		 			$post = new Posts;
					$post->name = $data['name'];
					$post->chief_id = $data['chief_id'];
					$post->save();

					$this->res['msg'] = 'Puesto creado Correctamente.';
					$this->res['status'] = true;
					$this->UsersPostList();
				} else {
		 			$post = Posts::find($id);
					$post->name = $data['name'];
					$post->chief_id = $data['chief_id'];
					$post->save();

					$this->res['msg'] = 'Puesto actualizado Correctamente.';
					$this->res['status'] = true;
					$this->UsersPostList();
				}
			} else {
				$this->res['msg'] = 'El Nombre del puesto es requerido.';	
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos'.$e;
		}
		return response()->json($this->res);
	}//UsersPostSave

	public function UsersPostDelete()
	{
		$id = $this->request->input('id');
		try{
			$post = Posts::where('chief_id', '=', $id)
							->get();
			if( count($post) > 0 ){
				$this->res['msg'] = 'Este Puesto esta siendo utilizado.';
			} else {
				$post = Posts::find($id);
				$post->delete();
				$this->res['status'] = true;
				$this->res['msg'] = 'Puesto Eliminado Correctamente.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos.';
		}
		return response()->json($this->res);
	}//UsersPostDelete

	public function UsersRequisition()
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('human_resources') ) ){
				return view("Users::requisition", ['request' => $this->request, 'ru' => $this->ru]);	
		}
	}//UsersRequisition

	public function UsersRequisitionSave()
	{
		$date 				= $this->request->input('date');
		$vacant_information = $this->request->input('vacant_information');
		$vacant_for 		= $this->request->input('vacant_for');
		$vacant_begin_date 	= $this->request->input('vacant_begin_date');
		$group_id 			= $this->request->input('group_id');
		$required_person 	= $this->request->input('required_person');

		$data = array(
					'date' 					=> $date,
					'vacant_information' 	=> $vacant_information,
					'vacant_for'		 	=> $vacant_for,
					'vacant_begin_date'		=> $vacant_begin_date,
					'group_id'				=> $group_id,
					'required_person'		=> $required_person,
					'user_id_creator'		=> \Sentry::getUser()->id
				);

		$validator = Validator::make($data, [
	        			'date' 					=> 'required',
	        			'vacant_information' 	=> 'required',
	        			'vacant_for' 			=> 'required',
	        			'vacant_begin_date' 	=> 'required',
	        			'group_id' 				=> 'required',
	        			'required_person' 		=> 'required|integer'
	        		]);
		try{
			if ( !$validator->fails() ){
				$id = DB::table('requisition_user')
					->insertGetId($data);
				$this->res['msg'] = 'La Requisición fue creada con Exito, ID: #'.$id;	
				$this->res['status'] = true;
			} else {
				$this->res['msg'] = 'Todos los campos son requeridos.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos'.$e;
		}
		return response()->json($this->res);
	}//UsersRequisitionSave

	public function UsersRequisitionViewList()
	{

		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('human_resources') ) ){
				return view("Users::requisition_list", ['request' => $this->request, 'ru' => $this->ru]);
		}

	}//UsersRequisitionList

	public function UsersRequisitionList()
	{
		try{
			$rqu = DB::table('requisition_user')
					->select('requisition_user.id', 'requisition_user.group_id', 'groups.slug AS group_name', 'requisition_user.date', 'requisition_user.vacant_information', 'requisition_user.vacant_for', 'requisition_user.vacant_begin_date', 'requisition_user.required_person', 'requisition_user.review_flag', 'requisition_user.autorize_flag')
					->join('groups', 'groups.id', '=', 'requisition_user.group_id')
					->orderBy('id', 'desc')
					->get();
			if($rqu){
				foreach ($rqu as $kr => $vr) {
					$vr->root = 0;
					if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) ){
						$vr->root = 1;
					}
					$vr->human_resources = 0;
					if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('human_resources') ) ){
						$vr->human_resources = 1;
					}
					$vr->finance = 0;
					if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('finance') ) ){
						$vr->finance = 1;
					}
					
				}
				$this->res['data'] = $rqu;
				$this->res['status'] = true;
			} else {

			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos'.$e;
		}
		return response()->json($this->res);
	}//UsersRequisitionList

	public function UsersRequisitionViewValidate()
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('management') )
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('finance') ) ){
				return view("Users::requisition_validate", ['request' => $this->request, 'ru' => $this->ru]);	
		}		
	}//UsersRequisitionViewValidate

	public function UsersRequisitionValidate()
	{
		try{
			$id = $this->request->input('id');

			if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) ){
				$rqu = DB::table('requisition_user')
							->where('id', '=', $id)
							->update( array('review_flag' => 1, 'autorize_flag' => 1) );
							$this->UsersRequisitionList();
				$this->res['msg'] = 'Requisición Validada Correctamente.';
				$this->res['status'] = true;
			} elseif( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('management') ) ){
				$rqu = DB::table('requisition_user')
							->where('id', '=', $id)
							->update( array('autorize_flag' => 1) );
				$this->UsersRequisitionList();
				$this->res['msg'] = 'Requisición Validada Correctamente.';
				$this->res['status'] = true;
			} elseif( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('finance') ) ){
				$rqu = DB::table('requisition_user')
							->where('id', '=', $id)
							->update( array('review_flag' => 1) );
				$this->UsersRequisitionList();
				$this->res['msg'] = 'Requisición Validada Correctamente.';
				$this->res['status'] = true;
			} else {
				$this->res['msg'] = 'No tienes permiso de hace esta operacion.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos'.$e;
		}
		return response()->json($this->res);
	}//UsersRequisitionValidate


	public function UsersRequisitionRedirect($id)
	{
		$this->request->session()->put('req.id', $id);
		
		return redirect('users/create');
	}//UsersRequisitionRedirect

	public function UsersRequisitionData()
	{
		try{
			$id = null;
			$ids = $this->request->session()->get('req.id');	
			$idi = $this->request->input('id');

			if ( $idi == null ) {
				if( $this->request->session()->has('req.id') ){
					$id = $ids;
				} else {
					$this->res['msg'] = 'Introduza un ID Valido.';
				}
			} else {
				$id = $idi;
			}

			$rqu = DB::table('requisition_user')
						->select('requisition_user.id', 'requisition_user.group_id', 'groups.slug AS group_name', 'requisition_user.date', 'requisition_user.vacant_information', 'requisition_user.vacant_for', 'requisition_user.vacant_begin_date', 'requisition_user.required_person', 'requisition_user.review_flag', 'requisition_user.autorize_flag')
						->join('groups', 'groups.id', '=', 'requisition_user.group_id')
						->orderBy('requisition_user.id', 'desc')
						->where('requisition_user.id', '=', $id)
								->first();
			if($rqu){
				$this->request->session()->forget('req.id');
				$this->res['data'] = $rqu;
				$this->res['status'] = true;
			} else {
				$this->res['msg'] = 'El ID de la Requisición no existe, por favor Introduzca uno Valido.';
			}

		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos'.$e;
		}
		return response()->json($this->res);
	}//UsersRequisitionData

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('human_resources') ) ){
				return view("Users::index", ['request' => $this->request, 'ru' => $this->ru]);	
		}
		
	}

	public function UsersList(){
		try{
			$rowsPerPage = 15; 
			$pag = $this->request->input('pa');

			$total_rows = 0;
			$offset = 0;
			$ul = array();
			$total_paginas = 1;
			$offset = ($pag - 1) * $rowsPerPage;
			
			$total_rows = Users::where('id', '!=', \Sentry::getUser()->id)->count();

			if($total_rows > 0 ){
				$users = Users::select('users.id', 'users.name', 'users.first_name', 'users.last_name', 'users.email', 'groups.slug AS group_name')
								->join('users_groups', 'users_groups.user_id', '=', 'users.id')
								->join('groups', 'groups.id', '=', 'users_groups.group_id')
								->where('users.id', '!=', \Sentry::getUser()->id);

				$ul = $users->skip($offset)
								->take($rowsPerPage)
								->get();

				if($ul){
					if($rowsPerPage <= $total_rows){
						$total_paginas = 1;
					}
					$total_paginas = ceil($total_rows / $rowsPerPage);

					$this->res['tp'] = $total_paginas;
					$this->res['data'] = $ul;
					$this->res['status'] = true;
				} else {
					$this->res['msg'] = "No hay Empleados hasta el momento.";
				}
			} else {
				$this->res['msg'] = 'No hay Otros Empleados hasta el momento';	
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos'.$e;
		}
		return response()->json($this->res);
	}//UsersList

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('human_resources') ) ){
				return view("Users::create", ['request' => $this->request, 'ru' => $this->ru]);	
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = array();
		
		$d = $this->request->input('requisition_data');
		if( isset($d['required_person']) ){
			$data['req_id'] 					= $d['id'];
			$data['required_person'] 			= $d['required_person'];

			if( $data['required_person'] > 0){

				$data['name'] 					= $this->request->input('name');
				$data['first_name'] 			= $this->request->input('first_name');
				$data['last_name'] 				= $this->request->input('last_name');
				$data['email'] 					= $this->request->input('email');

				$data['nss'] 					= $this->request->input('nss');
				$data['curp'] 					= $this->request->input('curp');
				$data['rfc'] 					= $this->request->input('rfc');
				$data['country'] 				= $this->request->input('country');
				$data['state'] 					= $this->request->input('state');
				$data['city'] 					= $this->request->input('city');
				$data['colony'] 				= $this->request->input('colony');
				$data['street'] 				= $this->request->input('street');
				$data['number'] 				= $this->request->input('number');
				$data['engagement_date'] 		= $this->request->input('engagement_date');
				$data['period_pay'] 			= $this->request->input('period_pay');
				$data['sdi'] 					= $this->request->input('sdi');
				$data['contract_type'] 			= $this->request->input('contract_type');
				$data['working_day_type'] 		= $this->request->input('working_day_type');
				$data['area'] 					= $this->request->input('area');
				$data['group_id'] 				= $this->request->input('group_id');
				$data['employee_number'] 		= $this->request->input('employee_number');
				$data['bank'] 					= $this->request->input('bank');
				$data['key_bank'] 				= $this->request->input('key_bank');
				$data['identification_paper'] 	= $this->request->input('identification_paper');
				$data['curp_paper'] 			= $this->request->input('curp_paper');
				$data['born_paper'] 			= $this->request->input('born_paper');
				$data['photografy'] 			= $this->request->input('photografy');
				$data['home_paper'] 			= $this->request->input('home_paper');
				$data['nss_paper'] 				= $this->request->input('nss_paper');
				$data['study_paper'] 			= $this->request->input('study_paper');
			
				
				$validator = Validator::make($data, [
		        			'name' 					=> 'required',
		        			'first_name' 			=> 'required',
		        			'last_name' 			=> 'required',
		        			'email' 				=> 'required',	

		        			'nss'					=> 'required',
		        			'curp'					=> 'required',
		        			'rfc'					=> 'required',
		        			'country'				=> 'required',
		        			'state'					=> 'required',
		        			'city'					=> 'required',
		        			'colony'				=> 'required',
		        			'street'				=> 'required',
		        			'number'				=> 'required',
		        			'engagement_date'		=> 'required',
		        			'period_pay'			=> 'required',
		        			'sdi'					=> 'required',
		        			'contract_type'			=> 'required',
		        			'working_day_type'		=> 'required',
		        			'area'					=> 'required',
		        			'group_id'				=> 'required',
		        			'employee_number'		=> 'required',
		        			'bank'					=> 'required',
		        			'key_bank'				=> 'required',
		        			'identification_paper'	=> 'required',
		        			'curp_paper'			=> 'required',
		        			'born_paper'			=> 'required',
		        			'photografy'			=> 'required',
		        			'home_paper'			=> 'required',
		        			'nss_paper'				=> 'required',
		        			'study_paper'			=> 'required'
		    	]);
		 		if ( !$validator->fails() ){
					try{
					    $user = \Sentry::createUser(array(
					    	'name'				=> $data['name'],
					    	'first_name'		=> $data['first_name'],
					        'last_name'     	=> $data['last_name'],
					        'email'     		=> $data['email'],
					        'img_profile'		=> 'profile_male.png',
					        'creator_id'		=> \Sentry::getUser()->id,
					        'password'  		=> 'Iga2016',
					        'activated' 		=> true
					    ));

					    DB::table('user_data')
					    		->insert(
					    			array(
					    				'user_id'				=> $user->id,
										'nss'					=> $data['nss'],
			        					'curp'					=> $data['curp'],
					        			'rfc'					=> $data['rfc'],
					        			'country'				=> $data['country'],
					        			'state'					=> $data['state'],
					        			'city'					=> $data['city'],
					        			'colony'				=> $data['colony'],
					        			'street'				=> $data['street'],
					        			'number'				=> $data['number'],
					        			'engagement_date'		=> $data['engagement_date'],
					        			'period_pay'			=> $data['period_pay'],
					        			'sdi'					=> $data['sdi'],
					        			'contract_type'			=> $data['contract_type'],
					        			'working_day_type'		=> $data['working_day_type'],
					        			'area'					=> $data['area'],
					        			'group_id'				=> $data['group_id'],
					        			'employee_number'		=> $data['employee_number'],
					        			'bank'					=> $data['bank'],
					        			'key_bank'				=> $data['key_bank'],
					        			'identification_paper'	=> $data['identification_paper'],
					        			'curp_paper'			=> $data['curp_paper'],
					        			'born_paper'			=> $data['born_paper'],
					        			'photografy'			=> $data['photografy'],
					        			'home_paper'			=> $data['home_paper'],
					        			'nss_paper'				=> $data['nss_paper'],
					        			'study_paper'			=> $data['study_paper']
					        		)
					    		);
					    $r = $data['required_person'] - 1;
					    DB::table('requisition_user')
					    			->where('id', '=', $data['req_id'])
					    			->update( array('required_person' => $r) );

					    $adminGroup = \Sentry::findGroupById( $data['group_id'] );
					    $user->addGroup($adminGroup);
					    $this->res['msg'] = 'Usuario Creado Correctamente.';
					    $this->res['status'] = true;

					} catch (\Cartalyst\Sentry\Users\LoginRequiredException $e) {
						$this->res['msg'] = 'El Correo Electronico es Requerido.';
					} catch (\Cartalyst\Sentry\Users\PasswordRequiredException $e) {
						$this->res['msg'] = 'La Contraseña es Requerida.';
					} catch (\Cartalyst\Sentry\Users\UserExistsException $e)  {
						$this->res['msg'] = 'El Correo Electronico ya Existe en el Sistema.';
					} catch (\Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
						$this->res['msg'] = 'El Grupo No Esta Disponible.';
					}
		 		} else {
					$this->res['msg'] = 'Todos los campos son obligatorios.';
		 		}
			} else {
				$this->res['msg'] = 'Esta requisición fue Finalizada.';
			}
		} else {
			$this->res['msg'] = 'Por favor, elija una Requisición.';
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
