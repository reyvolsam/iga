@extends('layouts.master')

@section('page_name')
  	<h1>Producción<small>Requisición</small></h1>
  	<ol class="breadcrumb">
	    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
	    <li>Producción</a></li>
    	<li class = "active">Requisición</a></li>
  	</ol>
@stop

@section('js')
  	{!! HTML::script('statics/js/lib/jquery.inputmask.js') !!}
  	{!! HTML::script('statics/js/lib/jquery.inputmask.date.extensions.js') !!}
  	{!! HTML::script('statics/js/customs/requisition.js') !!}
@stop

@section('content')
<div class="box box-default">
	<div class="box-header with-border">
		<h3 class="box-title">Requisición</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#save_requisition_modal"><i class="fa fa-plus"></i> Crear Requisición</button>
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
		</div><!--/box-tools-->
	</div><!--/box-header-->
	<div class="box-body" ng-init = "vm.OrderProductionList();">
  		<div id = "requisition_list_msg"></div><!--/order_produciton_msg-->

  		<table class = "table">
  			<thead>
  				<th>Folio</th>
  				<th>Fecha Solicitada</th>
  				<th>Fecha Requerida</th>
  				<th>Total Requisitado</th>
  				<th>Uso</th>
  				<th>Acciones</th>
  			</thead>
  			<tbody ng-repeat = "elem in vm.requirement_list">
  				<tr>
  					<td>#@{{ elem.id }}</td>
  					<td>@{{ elem.requested_date }}</td>
  					<td>@{{ elem.required_date }}</td>
  					<td>@{{ elem.total | currency }}</td>
  					<td>@{{ elem.use }}</td>
  					<td> <button type = "button" class = "btn btn-info btn-xs" ng-click = "vm.EditRequirement($index);" data-toggle="tooltip" data-placement="top" title="Editar Requisición"><i class = "fa fa-edit"></i></button> <button type = "button" class = "btn btn-danger btn-xs" id = "req_del_@{{elem.id}}" ng-click = "vm.DeleteRequirement($index);" data-toggle="tooltip" data-placement="top" title="Eliminar Requisición"><i class = "fa fa-trash"></i></button> </td>
  				</tr>
  			</tbody>
  		</table>
  		<i  id = "requisition_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
	</div><!--/box-body-->
</div><!--/box-->


<div class="modal fade" id = "save_requisition_modal" tabindex = "-1" role="dialog" aria-labelledby="save_requisition_label" aria-hidden="true" role = "dialog" data-backdrop = "static" data-keyboard = "false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Requisición</h4>
			</div>
			<form method = "post" ng-submit = "vm.SubmitRequisition();">
				<div class="modal-body">
					<div class = "row">
						<div class="col-md-4" ng-init = "vm.GetDate();">
							<label for = "requested_date" class = "control-label">Fecha Solicitada</label>
							<input type = "text" class = "form-control" name = "requested_date" id = "requested_date" ng-model = "vm.requirement.requested_date" readonly/>
						</div><!--/col-md-4-->
						<div class="col-md-4">
							<label for = "required_date" class = "control-label">Fecha Requerida</label>
							<div class = "input-group">
								<div class="input-group-addon">
                  					<i class="fa fa-calendar"></i>
                				</div><!--/input-group-addon-->
                				<input type = "text" class="form-control" name = "required_date" id = "required_date" ng-model = "vm.requirement.required_date" required data-inputmask="'alias': 'yyyy-mm-dd'" data-mask/>
							</div><!--/input-group-->
						</div><!--/col-md-4-->	
						<div class="col-md-4">
							<label for = "product_type" class = "control-label">Producto</label>
							<select id = "product_type" name = "product_type" ng-model = "vm.requirement.product_type" ng-change = "vm.ChangeProductType();" class = "form-control">
								<option value = "">Selecciona una Opción</option>
								<option value = "catalog">Producto de Catalago</option>
								<option value = "no_catalog">Producto sin Registro</option>
							</select>
						</div><!--/col-md-4-->
					</div><!-- termina row -->
					<br />
					<br />
					<div id = "product_msg"></div><!-- requisition_list_msg-->
					<div class="panel panel-default" id = "catalog_product_div">
						<div class="panel-heading">Productos de Catalago</div>
					 	<div class="panel-body">
							<div class="col-md-4">
								<label for = "filter_product" class = "control-label">Tipo de Producto</label>
								<select id = "filter_product" name = "filter_product" ng-model = "vm.requirement.filter_product" ng-change = "vm.ChangeFilterProduct();" class = "form-control">
									<option value = "">Selecciona una Opción</option>
									<option value = "raw_material">Materia prima</option>
									<option value = "finished_product">Productos Terminados</option>
									<option value = "others">Varios</option>
								</select>
							</div><!--/col-md-4-->
							<div class="col-md-4">
								<label for = "catalog_product_name" class = "control-label">Producto</label>
								<i id = "select_product" class = "fa fa-spinner fa-spin fa-1x"></i>
								<select class = "form-control" id = "catalog_product_name" name = "catalog_product_name" ng-model = "vm.product.catalog.id" ng-change = "vm.ChangeProduct();" ng-options = "p_list.id as p_list.description for p_list in vm.product_list_select">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-md-4-->
							<div class="col-md-4">
								<label for = "catalog_product_unit" class = "control-label">Unidad</label>
								<input type = "text" class = "form-control" id = "catalog_product_unit" name = "catalog_product_unit" ng-model = "vm.product.catalog.unit" placeholder = "Unidad" readonly />
							</div><!--/col-md-4-->
							<div class="col-md-6">
								<label for = "catalog_product_description" class = "control-label">Descripción</label>
								<input type = "text" class = "form-control" name = "catalog_product_description" id = "catalog_product_description" ng-model = "vm.product.catalog.description" placeholder="Descripción del Producto" readonly/>
							</div><!--/col-md-4-->
							<div class="col-md-6">
								<label for = "catalog_product_use" class = "control-label">Uso Req.</label>
								<input type = "text" class = "form-control" name = "catalog_product_use" id = "catalog_product_use" ng-model = "vm.product.catalog.use" placeholder="Uso del Producto" />
							</div><!--/col-md-4-->
							<div class="col-md-4">
								<label for = "catalog_product_pieces" class = "control-label">Pzas. Requeridas</label>
								<input type = "number" class = "form-control" id = "catalog_product_pieces" name = "catalog_product_pieces" ng-model = "vm.product.catalog.pieces" ng-change = "vm.ChangePiecesCatalog();" placeholder = "Pzas. Requeridas" />
							</div><!--/col-mdd-4-->
						</div><!-- termina panel-body -->
					</div><!-- termina panel -->

					<div class="panel panel-default" id = "new_product_div">
						<div class="panel-heading">Producto sin Registro</div>
					 	<div class="panel-body">
							<div class="col-md-6">
								<label for = "new_producto_name" class = "control-label">Nombre Producto</label>
								<input type = "text" class = "form-control" name = "new_producto_name" id = "new_producto_name" ng-model = "vm.product.new.name" placeholder="Nombre Producto" />
							</div><!--/col-md-6-->
							<div class="col-md-6">
								<label for = "new_product_unit" class = "control-label">Unidad</label>
								<select id = "new_product_unit" name = "new_product_unit" class = "form-control" ng-model = "vm.product.new.unit" ng-change = "vm.ChangeUnit();">
									<option value = " ">Seleccione una Opción</option>
									<option value = "Pieza">Pieza</option>
									<option value = "Cm.">Cm.</option>
									<option value = "Mtrs.">Mtrs.</option>
									<option value = "Kg.">Kg.</option>
									<option value = "Ln">Ln.</option>
									<option value = "Other">Otra Unidad</option>
								</select>
							</div><!--/col-md-6-->
							<div class="col-md-6" id = "new_unit_div">
								<label for = "new_product_new_unit" class = "control-label">Unidad Especifica</label>
								<input type = "text" class = "form-control" name = "new_product_new_unit" id = "new_product_new_unit" ng-model = "vm.product.new.new_unit" placeholder="Unidad Especifica" />
							</div>
							<div class="col-md-6">
								<label for = "new_product_description" class = "control-label">Descripción</label>
								<input type = "text" class = "form-control" name = "new_product_description" id = "new_product_description" ng-model = "vm.product.new.description" placeholder="Descripción del Producto" />
							</div>
							<div class="col-md-6">
								<label for = "new_product_use" class = "control-label">Uso Req.</label>
								<input type = "text" class = "form-control" name = "new_product_use" id = "new_product_use" ng-model = "vm.product.new.use" placeholder="Uso del Producto" />
							</div>
							<div class="col-md-4">
								<label for = "catalog_product_pieces" class = "control-label">Pzas. Requeridas</label>
								<input type = "number" class = "form-control" id = "catalog_product_pieces" name = "catalog_product_pieces" ng-model = "vm.product.new.pieces" ng-change = "vm.ChangePiecesNew();" placeholder = "Pzas. Requeridas" />
							</div><!--/col-mdd-4-->
						</div><!-- termina panel-body -->
					</div><!-- termina panel -->

					<div class="panel panel-default">
						<div class="panel-heading">Información Financiera</div>
						 	<div class="panel-body">
						 		<div class="col-md-4">
									<label for = "money_type" class = "control-label">Tipo de moneda</label>
									<select class = "form-control" id = "money_type" name = "money_type" ng-model = "vm.finances.money_type" ng-change = "vm.ChangeMoneyType();">
										<option value = "">Selecciona una Opción...</option>
										<option value = "USD">USD</option>
										<option value = "MX">MX</option>
									</select>
								</div><!--/col-md-4-->
								<div class="col-md-4" id = "dollar_value_id">
									<label for = "dollar_value" class = "control-label">Tipo de Cambio</label>
									<div class="input-group">
										<div class="dolar_sign input-group-addon">$</div>
										<input type = "text" class = "form-control" id = "dollar_value" name = "dollar_value" ng-model = "vm.finances.dollar_value" ng-change = "vm.ChangeDollarValue();" placeholder = "Tipo de Cambio" />
									</div>
								</div>
								<div class="col-md-4" id = "dollar_price_div">
									<label for = "dollar_price" class = "control-label">Precio Unitario en Dolares</label>
									<div class="input-group">
										<div class="dolar_sign input-group-addon">$</div>
										<input type = "text" class = "form-control" id = "dollar_price" name = "dollar_price" ng-change = "vm.ChangeDollarPrice();" ng-model = "vm.finances.dollar_price" placeholder = "Precio Unitario en Dolar" />
									</div>
								</div>	
								<div class="col-md-4" id = "pesos_price_div">
									<label for = "pesos_price" class = "control-label">Precio Unitario en Pesos</label>
									<div class="input-group">
										<div class="input-group-addon">$</div>
										<input type = "text" class = "form-control" id = "pesos_price" name = "pesos_price" ng-model = "vm.finances.pesos_price" ng-change = "vm.ChangePesosPrice();" placeholder = "Precio en Pesos" />
									</div>
								</div>					
								<div class="col-md-4" id = "importe_div">
									<label for = "importe" class = "control-label">Importe (MX) </label>
									<div class="input-group">
										<div class="input-group-addon">$</div>
										<input type = "text" class = "form-control" id = "importe" name = "importe" ng-model = "vm.finances.importe" placeholder = "Importe" readonly />
									</div>
								</div>
									
							</div><!-- termina panel-body -->
						</div><!-- termina panel -->
						<div class="col-md-4">
							<button type = "button" id = "add_product_item_btn" class = "form-control btn btn-default" ng-click = "vm.AddProductItem();"><i class="fa fa-plus"></i> Agregar Partida </button>
						</div><!--/col-md-4-->
						<br />
						<table class = "table">
							<thead>
								<th>Part.</th>
								<th>Descripción</th>
								<th>Unidad</th>
								<th>Uso Req.</th>
								<th>Pzas. Req</th>
								<th>Moneda</th>
								<th>Precio Unitario(MX)</th>
								<th>Importe(MX)</th>
							</thead>
							<tbody ng-repeat = "elem in vm.products_list" ng-init = "cont = $index">
								<tr>
									<td>@{{ cont+1 }}</td>
									<td>@{{ elem.product_description }}</td>
									<td>@{{ elem.product_unit }}</td>
									<td>@{{ elem.product_use }}</td>
									<td>@{{ elem.product_pieces }}</td>
									<td>@{{ elem.money_type }}</td>
									<td>@{{ elem.pesos_price | currency }}</td>
									<td>@{{ elem.importe | currency }}</td>
								</tr>
							</tbody>
						</table>
						<br />
						<div class="col-md-4">
							<label for = "sub_total" class = "control-label">Sub-Total</label>
							<div class="input-group">
								<div class="input-group-addon">$</div>
									<input type = "text" class = "form-control" id = "sub_total" name = "sub_total" ng-model = "vm.requirement.subtotal" placeholder = "Sub-Total" readonly />
								</div><!--/input-group-addon-->
						</div><!--/col-md-4-->
						<div class="col-md-4">
							<label for = "iva" class = "control-label">IVA</label>
							<div class="input-group">
								<input type = "text" class = "form-control" id = "iva" name = "iva" ng-model = "vm.requirement.iva" ng-change = "vm.ChangeIVA();" placeholder = "IVA" />
								<div class="input-group-addon">%</div>
							</div><!--/input-group-->
						</div><!--/col-md-4-->
						<div class="col-md-4">
							<label for = "total" class = "control-label">Total</label>
							<div class="input-group">
								<div class="input-group-addon">$</div>
								<input type = "text" class = "form-control" id = "total" name = "total" ng-model = "vm.requirement.total" placeholder = "Total" readonly />
							</div><!--/input-group-->
						</div><!--/col-md-4-->
						<br />
						<br />
						<br />
						<br />
						<br />
						<br />
						<div class = "row">
							<div class="form-group">
								<label for = "use" class = "col-lg-3 control-label">Uso de lo Requerido</label>
								<div class="col-lg-9">
									<input type = "text" class = "form-control" name = "use" id = "use" ng-model = "vm.requirement.use" placeholder = "Uso de lo Requerido" required/>
								</div><!--/form-group-->
								<br />
								<br />
								<br />
								<label for = "observations" class="col-lg-2 control-label">Observaciones</label>
								<div class="col-lg-10">
									<textarea class="form-control" rows="4" id = "observations" name = "observations" ng-model = "vm.requirement.observations" required></textarea>
								</div><!--/col-lg-10-->
							</div><!--/form-group-->
						</div><!-- termina row -->

				</div><!--/modal-body-->
				<div id = "save_requisition_msg"></div><!-- requisition_list_msg-->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" id = "cancel_requirement_btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
					<button type = "submit" class = "btn btn-success" id = "submit_requisition_btn">Guardar Requisición</button>
				</div>
			</form>
		</div><!--/modal-content-->
	</div><!--/modal-dialog-->
</div><!--/modal-->
@stop