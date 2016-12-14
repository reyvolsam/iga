@extends('layouts.master')

@section('page_name')
  	<h1>Producción<small>Orden de Producción</small></h1>
  	<ol class="breadcrumb">
	    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
	    <li>Producción</a></li>
    	<li class = "active">Orden de Producción</a></li>
  	</ol>
@stop

@section('js')
  	{!! HTML::script('statics/js/customs/order_production.js') !!}
@stop

@section('content')
<div class="box box-default">
	<div class="box-header with-border">
		<h3 class="box-title">Orden de Producción</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#order_production_modal"><i class="fa fa-plus"></i> Orden de Producción</button>
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
		</div><!--/box-tools-->
	</div><!--/box-header-->
	<div class="box-body" ng-init = "vm.OrderProductionList();">
  		<div id = "order_production_list_msg"></div><!--/order_produciton_msg-->

  		<table class = "table">
  			<thead>
  				<th>Folio</th>
  				<th>Fecha Actual</th>
  				<th>Observaciones</th>
  				<th>Total Requisitado</th>
  			</thead>
  			<tbody ng-repeat = "elem in vm.order_products_list">
  				<tr>
  					<td>@{{ elem.id }}</td>
  					<td>@{{ elem.date }}</td>
  					<td>@{{ elem.observations }}</td>
  					<td>@{{ elem.total_pieces }}</td>
  				</tr>
  			</tbody>
  		</table>
  		<i  id = "order_production_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
	</div><!--/box-body-->
</div><!--/box-->


<div class="modal fade" id = "order_production_modal" tabindex = "-1" role="dialog" aria-labelledby="order_production_label" aria-hidden="true" role = "dialog" data-backdrop = "static" data-keyboard = "false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Orden de Producción</h4>
			</div>
			<form method = "post" ng-submit = "vm.SubmitOrderProduction();">
				<div class="modal-body">

					<div class="form-group">
						<div class = "col-lg-4">
							<label for = "date" class = "control-label">Fecha Actual</label>
							<input type = "text" class="form-control" id = "date" name = "date" ng-model = "vm.order_production.date" readonly />
						</div><!--/col-lg-4-->
						<div class = "col-lg-4">
							<label for = "total_pieces" class = "control-label">Total Requisitado</label>
							<input type = "text" class="form-control" id = "total_pieces" name = "total_pieces" ng-model = "vm.order_production.total_pieces" readonly />
						</div><!--/col-lg-4-->									
					</div><!---/form-grouop-->
					<div class = "clearfix"></div>
					<br />
					<div class="panel panel-default" id = "agregar_pt_div">
						<div class="panel-heading">Agregar Producto</div>
						<div class="panel-body" ng-init = "vm.LoadProductTypes();">
							<div class="col-lg-6">
								<label for = "product_type" class="control-label">Tipo de Producto</label>
								<i id = "product_type_select_loader_list" class = "fa fa-spinner fa-spin fa-1x"></i>
								<select class = "form-control" id = "product_type" name = "product_type" ng-model = "vm.order_production.type" ng-change = "vm.ChangeProductType();" ng-options="pt_list.id as pt_list.name for pt_list in vm.product_type_list">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="adjust_div">
								<label for = "adjust" class = "control-label">Ajuste</label>
								<select class = "form-control" id = "adjust" name = "product_type" ng-model = "vm.order_production.adjust" ng-options = "a_list.id as a_list.name for a_list in vm.adjust_list">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="class_div">
								<label for = "class" class="control-label">Clase</label>
								<select class = "form-control" id = "class" name = "product_type" ng-model = "vm.order_production.class" ng-options="c_list.id as c_list.name for c_list in vm.class_list">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="model_div">
								<label for = "model" class = "control-label">Modelo</label>
								<select class = "form-control" id = "model" name = "product_type" ng-model = "vm.order_production.model" ng-options="m_list.id as m_list.name for m_list in vm.model_list">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="color_div">
								<label for = "color" class = "control-label">Color</label>
								<select class = "form-control" id = "color" name = "product_type" ng-model = "vm.order_production.color" ng-options="c_list.id as c_list.name for c_list in vm.color_list">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="feets_div">
								<label for = "feets" class="control-label">Color de Armazon de Lente</label>
								<select class = "form-control" id = "feets" name = "product_type" ng-model = "vm.order_production.feets" ng-options="f_list.id as f_list.name for f_list in vm.feets_list">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-6-->
							<div class = "col-lg-4">
								<label for = "quantity" class = "control-label">Cantidad</label>
								<input type = "text" class="form-control" id = "quantity" name = "quantity" ng-model = "vm.order_production.quantity" placeholder ="Cantidad"/>
							</div><!--/col-lg-6-->
							<div class = "clearfix"></div><!--/clearfix-->
							<br />
							<div class="col-md-4">
								<button type = "button" class = "btn btn-primary" id = "add_product_btn" ng-click = "vm.AddProduct();"><i class="icon-plus-sign"></i> Agregar Producto </button>
							</div><!--/col-md-4-->
		
							<table class = "table">
								<thead>
									<th>No.</th>
									<th>Unidad</th>
									<th>Descripcion</th>
									<th>Cantidad</th>
								</thead>
								<tbody ng-repeat = "elem in vm.products" ng-init = "cont = $index+1">
								<tr>
									<td>@{{ cont }}</td>
									<td>@{{ elem.product_unit }}</td>
									<td>@{{ elem.product_description }}</td>
									<td>@{{ elem.quantity }}</td>
								</tr>
								</tbody>
							</table>
						</div><!--/panel-body-->
					</div><!--/panel-->

					<div class="col-md-12">
						<label for = "observations" class="control-label">Observaciones</label>							
						<textarea class="form-control" rows="4" id = "observations" name = "observations" ng-model = "vm.order_production.observations" ></textarea>
					</div><!--/col-md-12-->
					<br />
					<br /><br /><br /><br /><br />
				</div><!--/modal-body-->
				<div id = "save_order_production_msg"></div><!-- order_produciton_list_msg-->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancelar</button>
					<button type = "submit" class = "btn btn-success" id = "submit_order_production_btn">Guardar Orden de Producción</button>
				</div>
			</form>
		</div><!--/modal-content-->
	</div><!--/modal-dialog-->
</div><!--/modal-->
@stop