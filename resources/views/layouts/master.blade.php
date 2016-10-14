<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Iga | Plasticos del Golfo</title>
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  {!! HTML::style('statics/css/lib/bootstrap.min.css') !!}
  {!! HTML::style('statics/css/lib/font-awesome.css') !!}
  {!! HTML::style('statics/css/lib/ionicons.min.css') !!}

  {!! HTML::style('statics/css/AdminLTE.min.css') !!}
  {!! HTML::style('statics/css/skins/skin-blue.min.css') !!}
  @yield('css')
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini" ng-app="app" ng-controller="appCtrl as vm">
  <div class="wrapper">

    <header class="main-header">
      <a href="{{ URL::to('/') }}" class="logo">
        <span class="logo-mini"><b>I</b>ga</span>
        <span class="logo-lg"><b>P</b>lasticos</span>
      </a><!--/logo-->

      <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a><!--/sidebar-toggle-->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="{{ asset('statics/images/img_profiles/'.Sentry::getUser()->img_profile) }}" class="user-image" alt="User Image">
                <span class="hidden-xs">{{Sentry::getUser()->name}} {{Sentry::getUser()->first_name}} {{Sentry::getUser()->last_name}}</span>
              </a><!--/dropdown-toggle-->
              <ul class="dropdown-menu">
                <li class="user-header">
                  <img src="{{ asset('statics/images/img_profiles/'.Sentry::getUser()['img_profile']) }}" class="img-circle" alt="User Image">
                  <p>
                    {{Sentry::getUser()->name}} {{Sentry::getUser()->first_name}} {{Sentry::getUser()->last_name}}
                    <small>{{$ru->group_slug}}</small>
                  </p>

                </li>
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="{{URL::to('profile')}}" class="btn btn-default btn-flat">Perfil</a>
                  </div><!--/pull-left-->
                  <div class="pull-right">
                    <a href="{{URL::to('logout')}}" class="btn btn-default btn-flat">Cerrar Sesión</a>
                  </div><!--/pull-right-->
                </li><!--/user-footer-->
              </ul><!--/dropdown-menu-->
            </li><!--user-menu-->
          </ul><!--nav-bar-->
        </div><!--/nav-bar-custom-menu-->
      </nav>
    </header>
    
    <aside class="main-sidebar">
      
      <section class="sidebar">
        <div class="user-panel">
          <div class="pull-left image">
            <img src="{{ asset('statics/images/logo.png') }}" class="img-circle" alt="User Image">
          </div><!--image-->
          <div class="pull-left info">
            <p>{{Sentry::getUser()->name}} {{Sentry::getUser()->first_name}} {{Sentry::getUser()->last_name}}</p>
          </div><!--/info-->
        </div><!--user-panel-->

        <ul class="sidebar-menu">
          @if ($request->is('/')) 
          <li class="active"> @else <li> @endif
            <a href="{{URL::to('/')}}"><i class="fa fa-home"></i> <span>Principal</span></a>
          </li>

          <li class="header">Modulos Principales</li>
          @if( Sentry::getUser()->inGroup( Sentry::findGroupByName('root') ) || Sentry::getUser()->inGroup( Sentry::findGroupByName('human_resources') ) )
            @if ($request->is('users') || $request->is('users/create') || $request->is('users/requisition') || $request->is('users/requisition/list') || $request->is('users/requisition/view/list')) 
            <li class = "active treeview"> @else <li class = "treeview"> @endif
              <a href="{{URL::to('users')}}"><i class="fa fa-users"></i> <span>Control de Personal</span>            
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span></a>
              <ul class="treeview-menu">
                <li><a href="{{URL::to('users')}}">Lista de Personal Activo</a></li>
                <li>
                  <a href="#">Requisición de Personal
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="{{URL::to('users/requisition/view/list')}}"> Lista de Requisiciones</a></li>
                    <li><a href="{{URL::to('users/requisition')}}"> Crear Requisición</a></li>
                  </ul>
                </li>
                <li><a href="{{URL::to('users/create')}}">Crear Trabajador</a></li>
              </ul>
            </li>
          @endif
          
          <li class="header">Modulos de Validación</li>
          @if( Sentry::getUser()->inGroup( Sentry::findGroupByName('root') ) || Sentry::getUser()->inGroup( Sentry::findGroupByName('management') ) || Sentry::getUser()->inGroup( Sentry::findGroupByName('finance') ))
            @if ($request->is('users/requisition/validate/view/list')) 
            <li class = "active"> @else <li> @endif
              <a href="{{URL::to('users/requisition/validate/view/list')}}"><i class="fa fa-file-text-o" aria-hidden="true"></i> <span>Validar Requisición</span></a>
            </li>
          @endif
        </ul><!--/sidebar-menu-->
      </section>
    </aside>

    <div class="content-wrapper">

      <section class="content-header">
        @yield('page_name')
      </section>

      <section class="content">
        <!-- Your Page Content Here -->
        @yield('content')
      </section>

    </div><!-- /content-wrapper -->

    <footer class="main-footer">
      <div class="pull-right hidden-xs">
        Powered By <a href="http://www.next-io.com">Next-IO</a>
      </div><!--/pull-right-->
      <strong>Copyright &copy; 2016 <a href="#">Iga | Plasticos</a>.</strong> All rights reserved.
    </footer>

  </div><!-- ./wrapper -->

  {!! HTML::script('statics/js/lib/jquery-2.2.3.min.js') !!}
  {!! HTML::script('statics/js/lib/bootstrap.min.js') !!}
  {!! HTML::script('statics/js/lib/angular.min.js') !!}
  {!! HTML::script('statics/js/app.js') !!}
  @yield('js')
</body>
</html>
