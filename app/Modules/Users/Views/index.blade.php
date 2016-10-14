@extends('layouts.master')

@section('page_name')
  <h1>Control de Personal<small>Lista de Personal</small></h1>
  <ol class="breadcrumb">
    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    <li class = "active">Control de Personal</a></li>
    <li class = "active">Lista de Personal</a></li>
  </ol>
@stop

@section('js')
  {!! HTML::script('statics/js/customs/users.js') !!}
@stop

@section('content')

	<div class="box box-default">
    	<div class="box-header with-border">
      		<h3 class="box-title">Personal</h3>

			<div class="box-tools pull-right">
        		<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        		<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
      		</div><!--/box-tools-->
    	</div><!--/box-header-->
    	<div class="box-body">
      <div id = "users_msg"></div><!--/users_msg-->
				<table class="table table-bordered table-striped" ng-init = "vm.usersList();">
					<thead>
						<tr>
              <th>Nombre</th>
              <th>Puesto</th>
              <th>Correo Electronico</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody ng-repeat="elem in vm.users_list" ng-init="cont = $index;">
            <tr>
              <td>@{{elem.name}} @{{elem.first_name}} @{{elem.last_name}}</td>
              <td>@{{elem.group_name}}</td>
              <td>@{{elem.email}}</td>
              <td></td>
            </tr>
          </tbody>
        </table>
        <i  id = "users_loader_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>

        <ul class = "pagination">
        </ul><!--/pagination-->

      </div><!--/box-body-->
    </div><!--/box-->

@stop