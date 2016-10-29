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

	/*////////// METODOS DE TIPOS DE MATERIA DE MATERIA PRIMA//////////*/


	public function ProductMaterialTypeIndex()
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('supplaying') ) ){
				return view("Supplaying::material_type", ['request' => $this->request, 'ru' => $this->ru]);	
		}
	}//ProductMaterialType

	public function ProductMaterialTypeSave()
	{
		$data['name'] 			= $this->request->input('name');
		$data['description'] 	= $this->request->input('description');
		$data['updated_at']		= date('Y-m-d H:i:s');
		$validator = Validator::make($data, [
	        			'name' 			=> 'required',
	        			'description' 	=> 'required'
	        		]);
		try{
			if ( !$validator->fails() ){
				DB::table('material_type')
						->insert( $data );
				$mt = DB::table('material_type')
							->get();

				$this->res['data'] = $mt;
				$this->res['msg'] = 'Tipo de Materia Guardada Correctamente.';
				$this->res['status'] = true;
			} else {
				$this->res['msg'] = 'Todos los campos son obligatorios.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos'.$e;
		}
		return response()->json($this->res);
	}//ProductMaterialTypeSave

	public function ProductMaterialTypeList()
	{
		try{
			$c = DB::table('material_type')
					->get();
			if($c){
				$this->res['status'] = true;
				$this->res['data'] = $c;
			} else {
				$this->res['msg'] = 'No hay Tipos de Materia hasta el momento.';
			}
		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos'.$e;
		}
		return response()->json($this->res);
	}//ProductMaterialTypeList

	/*////////// METODOS DE CODIGO INTERNO DE MATERIA PRIMA Y PRODUCTO TERMINADO//////////*/

	public function InternalCodeIndex($internal_code_type)
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('supplaying') ) ){
				return view("Supplaying::internal_code", ['request' => $this->request, 'ru' => $this->ru, 'internal_code_type' => $internal_code_type]);	
		}
	}//InternalCodeIndex

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
