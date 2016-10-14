<?php
use App\Http\Requests;
use Illuminate\Http\RedirectResponse;
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use DB;
use Mail;

class LoginController extends Controller
{

  	private $res = array();
  	private $modules = array();
  	private $request;

	public function __construct(Request $request){
		$this->request = $request;
		$this->res['status'] = false;
	}

    public function LoginUser()
    {
		try{
		    $credentials = array(
		        'email'    => $this->request->input('email'),
		        'password' => $this->request->input('passwd'),
		    );

		    $user = \Sentry::authenticate($credentials, false);
		    $this->res['status'] = true;
		} catch (\Cartalyst\Sentry\Users\LoginRequiredException $e) {
			$this->res['msg'] = 'El Correo Electronico es Requerido.';
		} catch (\Cartalyst\Sentry\Users\PasswordRequiredException $e) {
			$this->res['msg'] = 'Introduce una Contraseña.';
		} catch (\Cartalyst\Sentry\Users\WrongPasswordException $e) {
			$this->res['msg'] = 'Contraseña Incorrecta, intenta de nuevo.';
		} catch (\Cartalyst\Sentry\Users\UserNotFoundException $e) {
			$this->res['msg'] = 'El Usuario no existe.';
		} catch (\Cartalyst\Sentry\Users\UserNotActivatedException $e) {
			$this->res['msg'] = 'El Usuario no esta activado.';
		} catch (\Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
			$this->res['msg'] = 'El Usuario esta suspendido.';
		} catch (\Cartalyst\Sentry\Throttling\UserBannedException $e) {
			$this->res['msg'] = 'Tu cuenta esta bloqueda. Contacta al Administrador.';
		}
		return response()->json($this->res);
    }//end LoginUser

}//end LoginController