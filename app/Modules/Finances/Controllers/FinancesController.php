<?php 

namespace App\Modules\Finances\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Modules\Users\Controllers\UsersController;

use Validator;
use DB;

class FinancesController extends Controller {

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

	public function FinancesBankIndex()
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('finance') ) ){
				return view("Finances::bank", ['request' => $this->request, 'ru' => $this->ru]);	
		}
	}//FinanceIndex

	public function FinancesBankList()
	{
		try{
			$b = DB::table('banks')
					->orderBy('id', 'desc')
					->get();
			if($b){
				$this->res['status'] = true;
				$this->res['data'] = $b;
			} else {
				$this->res['msg'] = 'No hay Bancos resgistrados hasta el momento.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos'.$e;
		}
		return response()->json($this->res);
	}//FinancesBankList

	public function FinancesBankSave()
	{
		$name = $this->request->input('name');

		$data = array(
					'name' 			=> $name,
				);

		$validator = Validator::make($data, [
	        			'name' 			=> 'required',
	        		]);
		try{
			if ( !$validator->fails() ){
				DB::table('banks')
						->insert( $data );

				$b = DB::table('banks')
						->get();

				$this->res['data'] = $b;
				$this->res['msg'] = 'Banco Guardado Correctamente.';
				$this->res['status'] = true;
			} else {
				$this->res['msg'] = 'Todos los campos son obligatorios.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos'.$e;
		}
		return response()->json($this->res);
	}//FinancesBankSave

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view("Finances::index");
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
