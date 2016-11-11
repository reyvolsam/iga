@extends('layouts.master')

@section('page_name')
  	<h1>Productos<small>Tipo de Producto</small></h1>
  	<ol class="breadcrumb">
	    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    	<li class = "active">Tipo de Producto</a></li>
  	</ol>
@stop

@section('js')
  {!! HTML::script('statics/js/customs/product_type.js') !!}
@stop

@section('content')

	<div class = "row">

		<div class="col-md-5">
			<div class="box box-default">
				<div class="box-header">
					<h3 class="box-title">Crear Tipo de Producto</h3>
				</div>
				<form method = "post" ng-submit = "vm.SubmitProductType()">
					<div class="box-body">
	              		<div class="form-group">
    	            		<label for = "name">Nombre</label>
        	          		<input type="text" class="form-control" name = "name" id = "name" ng-model = "vm.product_type.name" />
            	  		</div><!--/form-group-->
              			<div class="form-group">
                			<label for = "description">Descripción</label>
                			<input type="text" class = "form-control" name = "description" id = "description" ng-model = "vm.product_type.description">
            	  		</div><!--/form-group-->
						<div class="panel panel-default">
							<div class="panel-heading">Seleccion de Caracteristicas </div>
								<div class="panel-body">
									<div class="col-lg-4">
										<input type="checkbox" ng-model = "vm.product_type.adjust" id = "adjust"> <label for = "adjust">Ajuste</label>
									</div>
									<div class="col-lg-4">
										<input type="checkbox" ng-model = "vm.product_type.class" id = "class"> <label for = "class">Clase</label>
									</div>
									<div class="col-lg-4">
										<input type="checkbox" ng-model = "vm.product_type.model" id = "model"> <label for = "model">Modelo</label>
									</div>
									<div class="col-lg-4">
										<input type="checkbox" ng-model = "vm.product_type.color" id = "color"> <label for = "color">Color</label>
									</div>
									<div class="col-lg-4">
										<input type="checkbox" ng-model = "vm.product_type.feets" id = "feets"> <label for = "feets">Patitas</label>
									</div>			
								</div><!-- termina panel-body -->
						</div><!-- termina panel -->
					</div><!--/box-body-->
					<div id = "product_type_msg"></div><!--/product_type_msg-->        			
					<div class="box-footer">
        				<button type = "submit" id = "product_type_btn" class="btn btn-default pull-right">Guardar Tipo de Producto</button>
        			</div><!--/box-footer-->
				</form>
			</div><!--/box-->
		</div><!--/col-md-6-->

		<div class="col-md-7" ng-init = "vm.ProductTypeList();">
			<div class="box box-default">
				<div class="box-header">
					<h3 class="box-title">Lista de Tipos de Productos</h3>
				</div>
				<div class="box-body">
					<div id = "producto_type_list_msg"></div><!--/post_msg-->
					<table class="table table-bordered table-striped">
						<thead>
							<th>Nombre</th>
							<th>Descripción</th>
							<th>Ajuste</th>
							<th>Clase</th>
							<th>Modelo</th>
							<th>Color</th>
							<th>Patitas</th>
							<th>Acción</th>
						</thead>
						<tbody ng-repeat="elem in vm.product_type_list" ng-init="cont = $index;">
							<tr>
								<td>@{{elem.name}}</td>
								<td>@{{elem.description}}</td>
								<td>@{{elem.adjust}}</td>
								<td>@{{elem.class}}</td>
								<td>@{{elem.model}}</td>
								<td>@{{elem.color}}</td>
								<td>@{{elem.feets}}</td>
								<td> <button type = "button" class = "btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title = "Eliminar Tipo de Producto"><i class = "fa fa-trash"></i></button> </td>
							</tr>
						</tbody>
					</table>
					<i  id = "producto_type_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
				</div><!--/box-body-->
			</div><!--/box-->
		</div><!--/col-md-6-->

	</div><!--/row-->

@stop