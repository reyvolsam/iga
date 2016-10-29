@extends('layouts.master')

@section('page_name')
  <h1>Control de Personal<small>Puestos</small></h1>
  <ol class="breadcrumb">
    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    <li class = "active">Control de Personal</a></li>
    <li class = "active">Puestos</a></li>
  </ol>
@stop

@section('js')
  {!! HTML::script('statics/js/customs/post.js') !!}
@stop

@section('content')

	<div class = "row" ng-init = "vm.GetPostList();">

		<div class="col-md-6">
			<div class="box box-default">
				<div class="box-header">
					<h3 class="box-title">Crear Puesto</h3>
				</div>
				<form id = "save_post_form" ng-submit = "vm.SubmitPost()">
					<div class="box-body">
	              		<div class="form-group">
    	            		<label>Nombre del Puesto</label>
        	          		<input type="text" class="form-control" ng-model = "vm.post_data.post_name" />
            	  		</div><!--/form-group-->
              			<div class="form-group">
                			<label>Puesto Superior</label>
                			<i id = "select_loader_list" class = "fa fa-spinner fa-spin fa-1x"></i>
	                  		<select class = "form-control" ng-model = "vm.post_data.chief_id" ng-options="p_list.id as p_list.name for p_list in vm.post_list_select">
    	              			<option value = "Selecciona una Opción..."></option>
        	          		</select>
            	  		</div><!--/form-group-->
					</div><!--/box-body-->
					<div id = "save_post_msg"></div><!--/post_msg-->
        			<div class="box-footer">
        			<button type = "submit" ng-click = "vm.CancelEditPost();" id = "cancel_edit_post_form" class="btn btn-danger pull-right">Cancelar</button>
        				<button type = "submit" id = "submit_post_form" class="btn btn-default pull-right">Crear Puesto</button>
        			</div><!--/box-footer-->
				</form>
			</div><!--/box-->
		</div><!--/col-md-6-->

		<div class="col-md-6">
			<div class="box box-default">
				<div class="box-header">
					<h3 class="box-title">Lista de Puestos</h3>
				</div>
				<div class="box-body">
					<div id = "post_list_msg"></div><!--/post_msg-->
					<table class="table table-bordered table-striped">
						<thead>
							<th>Nombre</th>
							<th>Fecha de Creación</th>
							<th>Acción</th>
						</thead>
						<tbody ng-repeat="elem in vm.post_list" ng-init="cont = $index;">
							<tr>
								<td>@{{elem.name}}</td>
								<td>@{{elem.created_at}}</td>
								<td><button ng-click="vm.EditPost($index);" class = "btn_delete btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fa fa-edit"></i></button> <button ng-click="vm.DeletePost($index);" id = "del_@{{cont}}" class = "btn_delete btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></button></td>
							</tr>
						</tbody>
					</table>
					<i  id = "posts_loader_list" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
				</div><!--/box-body-->
			</div><!--/box-->
		</div><!--/col-md-6-->

	</div><!--/row-->

@stop