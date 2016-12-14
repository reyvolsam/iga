@extends('layouts.master')

@section('page_name')
  <h1>Productos<small>Stock</small></h1>
  <ol class="breadcrumb">
    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    <li class = "active">Stock de Materia Prima</a></li>
  </ol>
@stop

@section('js')
  {!! HTML::script('statics/js/customs/stock/row_material.js') !!}
@stop

@section('content')

<div class="box box-default" ng-init = "vm.LoadDataRawMaterialEntry();">
  <div class="box-header with-border">
    <h3 class="box-title">Materia Prima - Entradas</h3>
    <div class="box-tools pull-right">
		<button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#raw_material_entry_modal"><i class="fa fa-plus"></i> Entrada Materia Prima</button>
      	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      	<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
    </div><!--/box-tools-->
  </div><!--/box-header-->
  <div class="box-body" ng-init = "vm.GetMoveList();">
  	<div id = "move_list_msg"></div><!--/product_msg-->
	<table class = "table">
		<thead>
			<th>Ingreso</th>
			<th>Fecha Movimiento</th>
			<th>Movimiento</th>
			<th>Codigo/Lote</th>
			<th>Producto</th>
			<th>Unidad</th>
			<th>Cantidad</th>
		</thead>
		<tbody ng-repeat="elem in vm.stock_move_list">
			<tr>
				<td>@{{ elem.move_id }}</td>
				<td>@{{ elem.move_date }}</td>
				<td>@{{ elem.move_entry_name }}</td>
				<td>@{{ elem.code }}/@{{ elem.lote }}</td>
				<td>@{{ elem.product_description }}</td>
				<td>@{{ elem.unit }}</td>
				<td>@{{ elem.quantity }}</td>
			</tr>
		</tbody>
	</table>
	<i  id = "move_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
  </div><!--/box-body-->
</div><!--/box-->

<div class="modal fade modal-xl" id="raw_material_entry_modal" tabindex="-1" role="dialog" aria-labelledby="raw_material_entry_label" aria-hidden="true" data-backdrop = "static" data-keyboard = "false">
	<div class = "modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Movimientos Materia Prima</h4>
			</div><!--/modal-header-->
			<div class="modal-body">
				<form method = "post" ng-submit = "vm.SubmitRawMaterialEntry()" class="form-horizontal" role="form">
					<div class="form-group">
						<div class = "col-lg-4">
							<label for = "move_date" class = "control-label">Fecha Movimiento</label>
							<input type = "text" class="form-control" id = "move_date" name = "move_date" ng-model = "vm.move.date" readonly />
						</div><!--/col-lg-4-->
						<div class = "col-lg-4">
							<label for = "move_type" class = "control-label">Tipo Movimiento</label>
							<input type="text" id = "move_type" name="move_type" class = "form-control" ng-model = "vm.move.type" value = "Movimiento de Entrada de MP" readonly />
							</select>
						</div><!--/col-lg-4-->
						<div class = "col-lg-4">
							<label for = "move_id" class = "control-label">Movimiento</label>
							<i id = "move_id_loader" class = "fa fa-spinner fa-spin fa-1x"></i>
							<select id = "move_id" name = "move_id" class = "form-control" ng-model = "vm.move.id" ng-change = "vm.ChangeMove();" ng-options = "m_list.id as m_list.name for m_list in vm.move_list">
								<option value = "">Seleccione una Opción...</option>
							</select>
						</div><!--/col-lg-4-->				
						<div class = "col-lg-4" id ="provider_div">
							<label for = "provider_id" class = "control-label">Proveedor</label>
							<select id = "provider_id" name = "provider_id" ng-model = "vm.move.provider_id" class = "form-control" ng-options = "p_list.id as p_list.name for p_list in vm.provider_list">
								<option value = "">Seleccione una Opción...</option>
							</select>
						</div><!--/col-lg-4-->				
						<div class = "col-lg-4" id ="invoice_div">
							<label for = "invoice" class = "control-label"> No. de Factura</label>
							<input type = "text" id = "invoice" name = "invoice" ng-model = "vm.move.invoice" class="form-control" placeholder = "No. de Factura" />
						</div><!--/col-lg-4-->
						<div class = "col-lg-4" id ="invoice_date_div">
							<label for = "invoice_date" class = "control-label">Fecha de Factura</label>
							<input type = "date" id = "invoice_date" name = "invoice_date" ng-model = "vm.move.invoice_date" class="form-control" />
						</div><!--/col-lg-4-->
						<div class = "col-lg-4" id ="order_production_number_div">
							<label for = "order_production_number" class = "control-label"> No. de Orden de Produccion</label>
							<select id = "order_production_number" name = "order_production_number" ng-model = "vm.move.order_production_number" class = "form-control">
								<option value = "">Seleccione una Opción...</option>
							</select>
						</div><!--/col-lg-4-->
						<div class = "col-lg-4" id ="factory_product_div">
							<label for = "factory_product" class = "control-label">Producto a Fabricar</label>
							<select id = "factory_product" name = "factory_product" ng-model = "vm.move.factory_product" class = "form-control">
								<option value = "">Seleccione una Opción...</option>
							</select>
						</div><!--/col-lg-4-->
						<div class = "col-lg-4" id ="factory_pieces_div">
							<label for = "factory_pieces" class = "control-label"> Pzas. a Fabricar</label>
							<input type = "text" id = "factory_pieces" name = "factory_pieces" ng-model = "vm.move.factory_pieces" class = "form-control" placeholder = "Pzas. a Fabricar" readonly/>
						</div><!--/col-lg-4-->
						<div class = "col-lg-4" id ="pack_send_div">
							<label for = "pack_send_id" class = "control-label"> Transporte</label>
							<div class="input-group">
								<select id = "pack_send_id" name = "pack_send_id" ng-model = "vm.move.pack_send_id" class = "form-control" ng-options = "pl_list.id as pl_list.name for pl_list in vm.packs_list">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div>
						</div><!--/col-lg-4-->
						<div class = "col-lg-4" id ="pack_send_invoice_div">
							<label for = "pack_send_invoice" class = "control-label">Factura de Transporte</label>
							<input type = "text" class = "form-control" id = "pack_send_invoice" name = "pack_send_invoice" ng-model = "vm.move.pack_send_invoice" placeholder = "Factura de Transporte"/>
						</div><!--/col-lg-4-->
						<div class = "col-lg-4" id ="pack_send_cost_div">
							<label for = "pack_send_cost" class = "control-label">Costo del Traslado</label>
							<div class="input-group">
							  	<span class="input-group-addon" id="basic-addon3">$</span>
								<input type = "text" class="form-control" id = "pack_send_cost" name = "pack_send_cost" ng-model = "vm.move.pack_send_cost" placeholder = "Costo del Traslado"/>
							</div><!--/input-group-->
						</div><!--/col-lg-4-->					
					</div><!--/form-group-->

					<div class = "panel panel-default" id = "add_product_div">
						<div class="panel-heading">Agregar Producto</div>
						<div class="panel-body">
							<div class="col-lg-4">
								<label for = "product_code" class="control-label">Codigo de MP</label>
								<input type = "text" id = "product_code" ng-model = "vm.product.code" class = "form-control" placeholder ="Codigo" readonly />
							</div><!--/col-lg-4-->
							<div class = "col-lg-8">
								<label for = "product_id" class = "control-label">Producto</label>
								<select class = "form-control" id = "product_id" name = "product_id" ng-model = "vm.product.id" ng-change = "vm.ChangeProduct();" ng-options = "p_list.id as p_list.name for p_list in vm.product_list">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-4-->						
							<div class = "col-lg-4">
								<label for = "product_unit" class = "control-label">Unidad</label>
								<input type = "text" class = "form-control" id = "product_unit" name = "product_unit" ng-model = "vm.product.unit" placeholder ="Unidad" readonly />
							</div><!--/col-lg-4-->
							<div class = "col-lg-8">
								<label for = "product_description" class = "control-label">Descripcion</label>
								<input type = "text" class="form-control" id = "product_description" name = "product_description" ng-model = "vm.product.description" placeholder ="Descripcion" readonly />
							</div><!--/col-lg-4-->
							<div class = "col-lg-4">
								<label for = "product_quantity" class = "control-label">Cantidad</label>
								<input type = "number" class = "form-control" id = "product_quantity" name = "product_quantity" ng-model = "vm.product.quantity" placeholder ="Cantidad" />
							</div><!--/col-lg-4-->
							<div class = "col-lg-4">
								<label for = "product_lote" class = "control-label">Lote</label>
								<input type = "text" class = "form-control" id = "product_lote" name = "product_lote" ng-model = "vm.product.lote" placeholder ="Lote"/>
							</div><!--/col-lg-4-->
							<div class="col-md-4">
								<br />
								<button type = "button" class = "form-control btn btn-primary" id = "add_product_btn" ng-click = "vm.AddProductMove();"><i class="fa fa-plus"></i> Agregar Producto </button>
							</div><!--/col-lg-4-->
						</div><!-- termina panel-body -->
					</div><!-- termina panel -->

					<div class = "panel panel-default" id = "add_product_div">
						<div class="panel-heading">Partidas de Productos</div><!--/panel-heading-->
						<div class="panel-body">
							<div id = "item_product"></div><!--item_product-->
							<table class = "table">
								<thead>
									<th>Codigo</th>
									<th>No. Lote</th>									
									<th>Producto</th>
									<th>Unidad</th>
									<th>Cantidad</th>
									<th>Acciones</th>
								</thead>
								<tbody ng-repeat="elem in vm.item_product_list" ng-init="cont = $index;">
									<tr>
										<td>@{{elem.code}}</td>
										<td>@{{elem.lote}}</td>
										<td>@{{elem.name}}</td>
										<td>@{{elem.unit}}</td>
										<td>@{{elem.quantity}}</td>
										<td> <button class = "btn btn-danger btn-xs" ng-click = "vm.DeleteProductItem(cont)"><i class = "fa fa-trash"></i></button></td>
									</tr>
								</tbody>
							</table>
						</div><!--/panel-body-->
					</div><!--/panel-->

					<div id = "move_msg"></div><!-- msg_form -->
					<div class = "modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type = "submit" id = "submit_move_btn" class = "btn btn-success">Guardar Movimiento</button>
					</div><!-- termina footer -->
				</form>
			</div><!-- termina modal_body -->
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop