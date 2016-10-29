<?php 
namespace App\Modules\Supplaying\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Modules\Provider\Models;

use App\Modules\Users\Controllers\UsersController;

use Validator;
use DB;

class ProviderController extends Controller {

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

	public function ProviderIndex($provider_type)
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('supplaying') ) ){
				return view("Supplaying::provider", ['request' => $this->request, 'ru' => $this->ru, 'provider_type' => $provider_type]);	
		}
	}//ProviderIndex

	public function ProviderSave()
	{
		try{
			$type_provider = null;
			$provider_id 			= $this->request->input('id');
			$data['name'] 			= strtoupper( $this->request->input('name') );
			$data['comercial'] 		= strtoupper( $this->request->input('comercial') );
			$data['rfc'] 			= strtoupper( $this->request->input('rfc') );
			$data['street'] 		= strtoupper( $this->request->input('street') );
			$data['number'] 		= strtoupper( $this->request->input('number') );
			$data['colony'] 		= strtoupper( $this->request->input('colony') );
			$data['city'] 			= strtoupper( $this->request->input('city') );
			$data['state'] 			= $this->request->input('state');
			$data['country'] 		= $this->request->input('country');
			$data['cp'] 			= $this->request->input('cp');
			$data['phones'] 		= $this->request->input('phones');
			$data['contacts'] 		= $this->request->input('contacts');
			$data['credit_type'] 	= $this->request->input('credit_type');
			$data['credit_days'] 	= $this->request->input('credit_days');
			$data['credit_limit'] 	= $this->request->input('credit_limit');
			$data['notes'] 			= $this->request->input('notes');
			$data['updated_at'] 	= date('Y-m-d h:m:i');
			
			$banks 					= $this->request->input('banks');
			$type_provider 			= $this->request->input('type');
			

		switch ($type_provider) {
			case 'raw_material':
					$data['provider_type_id'] = 1;
				break;
			case 'finished_provider':
					$data['provider_type_id'] = 3;
				break;
			default:
					$data['provider_type_id'] = '';
				break;
			}

			$validator = Validator::make($data, [
	        			'name' 			=> 'required',
	        			'comercial' 	=> 'required',
	        			'rfc' 			=> 'required',
	        			'street' 		=> 'required',
	        			'number' 		=> 'required',
	        			'colony' 		=> 'required',
	        			'city' 			=> 'required',
	        			'state' 		=> 'required',
	        			'country' 		=> 'required',
	        			'cp' 			=> 'required',
	        			'credit_type' 	=> 'required',
	        			'credit_days' 	=> 'required',
	        			'credit_limit' 	=> 'required',
	        			'notes' 		=> 'required'
	        		]);

			if ( !$validator->fails() ){
				//(count($data['banks']) <= 0) ? $data['banks'] = '[]' : $data['banks'] = json_encode($data['banks']);
				(count($data['phones']) <= 0) ? $data['phones'] = '[]' : $data['phones'] = json_encode($data['phones']);
				(count($data['contacts']) <= 0) ? $data['contacts'] = '[]' : $data['contacts'] = json_encode($data['contacts']);
				if($data['credit_limit'] == ''){$data['credit_limit'] = 0; }

				if($provider_id == null){

					$prfc = DB::table('providers')
								->where('rfc', '=', $data['rfc'])
								->get();

					if( count($prfc) == 0){
						$idp = DB::table('providers')
									->insertGetId($data);
						foreach ($banks as $kb => $vb) {
							DB::table('providers_banks')
									->insert(array(
											'bank_id' 		=> $vb['id'],
											'provider_id'	=> $idp
										));

						}
						$this->res['msg'] = 'Proveedor Guardado Correctamente.';
						$this->res['status'] = true;
					} else {
						$this->res['msg'] = 'Este RFC ya esta registrado.';
					}

				} else {
					$this->res['msg'] = 'Aqui se edita, pero no esta listo.';	
				}


			} else {
				$this->res['data'] = $data;
				$this->res['msg'] = 'Todos los Campos son obligatorios.';
			}

		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos'.$e;
		}
		return response()->json($this->res);
	}//ProviderSave

	public function ProviderList()
	{
		try{
			$type = $this->request->input('type');
			$tp = null;
			$pn = '';
			switch ($type) {
				case 'raw_material':
						$tp = 1;
						$pn = 'Materia Prima';
					break;
				case 'finished_provider':
						$tp = 3;
						$pn = 'Producto Terminado';
					break;
				case 'finished_product':
						$tp = 3;
						$pn = 'Producto Terminado';
					break;
				default:
						$tp = '';
						$pn = '-';
					break;
				}

				$lp = DB::table('providers')
							->where('provider_type_id', '=', $tp)
							->get();
				if( count($lp) > 0 ){
					$this->res['status'] = true;
					$this->res['data'] = $lp;
				} else {
					$this->res['msg'] = 'No hay Proveedores para '.$pn.'.';
				}

		} catch (\Exception $e) {
			$this->res['msg'] = 'Error en la Base de Datos'.$e;
		}
		return response()->json($this->res);
	}//ProviderList

}//ProviderController
