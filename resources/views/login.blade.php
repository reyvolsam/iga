<!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Inicio de Sesión - Plasticos del Golfo</title>
      <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      {!! HTML::style('statics/css/lib/bootstrap.min.css') !!}
      {!! HTML::style('statics/css/lib/font-awesome.css') !!}
      {!! HTML::style('statics/css/lib/ionicons.min.css') !!}
      {!! HTML::style('statics/css/AdminLTE.min.css') !!}

      {!! HTML::style('statics/css/login.css') !!}

</head>
<body ng-app = "app" ng-controller = "appCtrl as vm">
  <div class = "login-box">
    <div class = "login-box-body">
      {!! HTML::image('statics/images/logo.png', 'Iga prodcutos') !!}
      <hr />
      <div id = "msg_login"></div><!--/msg_login-->
      <form id = "login_form" ng-submit = "vm.SubmitLogin()">
        <div class = "form-group has-feedback">
          <input type = "email" id = "email" name = "email" class="form-control" placeholder="Correo Electronico" ng-model = "vm.login_data.email" />
          <span class = "glyphicon glyphicon-envelope form-control-feedback"></span>
        </div><!--/form-group-->
        <div class = "form-group has-feedback">
          <input type = "password" id = "password" name = "password" class="form-control" placeholder = "Contraseña" ng-model = "vm.login_data.passwd" />
          <span class = "glyphicon glyphicon-lock form-control-feedback"></span>
        </div><!--/form-group-->
        <div class = "row">
          <div class = "col-xs-5">
            <button type = "submit" id = "submit_login_button" class = "btn btn-primary btn-block btn-flat">Iniciar Sesión</button>
          </div><!--/col-xs-4-->
        </div><!--/row-->
      </form>

  {!! HTML::script('statics/js/lib/jquery-2.2.3.min.js') !!}
  {!! HTML::script('statics/js/lib/bootstrap.min.js') !!}
  {!! HTML::script('statics/js/lib/angular.min.js') !!}
  
  {!! HTML::script('statics/js/customs/login.js') !!}
</body>
</html>
