@extends('layouts.master')

@section('page_name')
  	<h1>Productos<small>Tipo de Materia</small></h1>
  	<ol class="breadcrumb">
	    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    	<li class = "active">Tipo de Materia</a></li>
  	</ol>
@stop

@section('js')
  {!! HTML::script('statics/js/customs/material_type.js') !!}
@stop

@section('content')

	<div class = "row" ng-init = "vm.GetMaterialTypeList();">

		<div class="col-md-6">
			<div class="box box-default">
				<div class="box-header">
					<h3 class="box-title">Crear Tipo de Materia</h3>
				</div>
				<form method = "post" ng-submit = "vm.SubmitMaterialType()">
					<div class="box-body">
	              		<div class="form-group">
    	            		<label for = "name">Tipo de Materia</label>
        	          		<input type="text" class="form-control" name = "name" id = "name" ng-model = "vm.material_type.name" />
            	  		</div><!--/form-group-->
              			<div class="form-group">
                			<label for = "description">Descripción</label>
                			<input type="text" class = "form-control" name = "description" id = "description" ng-model = "vm.material_type.description">
            	  		</div><!--/form-group-->
					</div><!--/box-body-->
					<div id = "material_type_msg"></div><!--/post_msg-->
        			<div class="box-footer">
        				<button type = "submit" id = "submit_material_type_form" class="btn btn-default pull-right">Guardar Tipo de Materia</button>
        			</div><!--/box-footer-->
				</form>
			</div><!--/box-->
		</div><!--/col-md-6-->

		<div class="col-md-6">
			<div class="box box-default">
				<div class="box-header">
					<h3 class="box-title">Lista de Tipos de Materia</h3>
				</div>
				<div class="box-body">
					<div id = "material_type_list_msg"></div><!--/post_msg-->
					<table class="table table-bordered table-striped">
						<thead>
							<th>Tipo de Materia</th>
							<th>Descripción</th>
						</thead>
						<tbody ng-repeat="elem in vm.material_type_list" ng-init="cont = $index;">
							<tr>
								<td>@{{elem.name}}</td>
								<td>@{{elem.description}}</td>
							</tr>
						</tbody>
					</table>
					<i  id = "material_type_loader_list" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
				</div><!--/box-body-->
			</div><!--/box-->
		</div><!--/col-md-6-->

	</div><!--/row-->

@stop