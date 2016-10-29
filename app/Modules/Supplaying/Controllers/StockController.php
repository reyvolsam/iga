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


	public function RawMaterialIndex()
	{
		if( \Sentry::getUser()->inGroup( \Sentry::findGroupByName('root') ) 
			|| \Sentry::getUser()->inGroup( \Sentry::findGroupByName('supplaying') ) ){
				return view("Supplaying::stock.raw_material", ['request' => $this->request, 'ru' => $this->ru]);	
		}
	}//RawMaterialIndex

}//StockController
