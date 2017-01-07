@extends('layouts.master')

@section('page_name')
  	<h1>Producción<small>Requisiciones</small></h1>
  	<ol class="breadcrumb">
	    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
	    <li>Producción</a></li>
    	<li class = "active">Requisiciones</a></li>
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
	<div class="box-body" ng-init = "vm.RequisitionList();">		
  		<div id = "requisition_list_msg"></div><!--/order_produciton_msg-->
  		<div class = "col-md-12">
  			@if( Sentry::getUser()->inGroup( Sentry::findGroupByName('root') ) || Sentry::getUser()->inGroup( Sentry::findGroupByName('supplaying') ) )
  			<div class = "col-md-4">
  				<label>Filtrar por Departamento</label><br />
  					<select class = "form-control pull-left" id = "filter_user" name = "filter_user" ng-model = "vm.filter_user" ng-change = "vm.ChangeFilterUser();"> 
  						<option value = "all" selected>Todos</option>
  						<option value = "1">Administrador</option>
  						<option value = "4">Finanzas</option>
  						<option value = "5">Compras</option>
						<option value = "6">Ventas</option>
  						<option value = "7">Calidad</option>

  					</select>
  			</div><!--/col-md-4-->
  			@endif
  			<ul class = "pagination pull-right"></ul><!--/pagination-->	
  		</div><!--/col-md-12-->
		
  		<table class = "table">
  			<thead>
  				<th>Folio</th>
  				<th>Departamento</th>
  				<th>Fecha Solicitada</th>
  				<th>Fecha Requerida</th>
  				<th>Dias de Vencimiento</th>
  				<th>Total Requisitado</th>
  				<th>Uso</th>
  				<th>Estado</th>
  				<th>Acciones</th>
  			</thead>
  			<tbody ng-repeat = "elem in vm.requisition_list" ng-init = "cont = $index">
  				<tr>
  					<td>#@{{ elem.id }}</td>
  					<td>@{{ elem.group_name }}</td>
  					<td>@{{ elem.requested_date }}</td>
  					<td>@{{ elem.required_date }}</td>
  					<td ng-if = "elem.left_days <= 0" class="danger">@{{ elem.left_days }}</td>
  					<td ng-if = "elem.left_days > 0">@{{ elem.left_days }}</td>
  					<td>@{{ elem.total | currency }}</td>
  					<td>@{{ elem.use }}</td>
  					<td>
  						<span ng-if = "elem.pre_order == 0" class="label label-default">En espera de Validación...</span>
  						<span ng-if = "elem.pre_order == 1" class="label label-primary">En espera de Orden de Compra...</span>
  					</td>
  					<td>
						<button ng-if = "elem.pre_order == 0" type = "button" class = "btn btn-info btn-xs" ng-click = "vm.EditRequisition($index);" data-toggle="tooltip" data-placement="top" title="Editar Requisición"><i class = "fa fa-edit"></i></button> 
						<button ng-if = "elem.pre_order == 0" type = "button" class = "btn btn-danger btn-xs" id = "req_del_@{{elem.id}}" ng-click = "vm.DeleteRequisition($index);" data-toggle="tooltip" data-placement="top" title="Eliminar Requisición"><i class = "fa fa-trash"></i></button>
						<button ng-if = "elem.pre_order == 1" type = "button" class = "btn btn-info btn-xs" ng-click = "vm.EditRequisition($index);" data-toggle="tooltip" data-placement="top" title="Ver Requisición"><i class = "fa fa-eye"></i></button> 
						<a href = "{{ URL::to('supplaying/requisition/pdf') }}/@{{elem.id}}" ng-if = "elem.pre_order == 1" title ="Descargar" class = "download btn btn-default btn-xs"  data-toggle="tooltip" data-placement = "top" title = "Descargar PDF"><i class="fa fa-cloud-download"></i></a>
						@if( Sentry::getUser()->inGroup( Sentry::findGroupByName('root') )  || Sentry::getUser()->inGroup( Sentry::findGroupByName('supplaying') ) ) 
							<button ng-if = "elem.pre_order == 0" type = "button" class = "btn btn-success btn-xs" ng-click = "vm.ConvertRequisition($index);" data-toggle="tooltip" data-placement = "top" title = "Convertir a Orden de Compra"><i class = "fa fa-check"></i></button>
						@endif
  					</td>
  				</tr>
  			</tbody>
  		</table>
  		<i  id = "requisition_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
	</div><!--/box-body-->
	<div class = "box-footer">
		<ul class = "pagination pull-right"></ul><!--/pagination-->
	</div><!--/box-footer-->
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
							<input type = "text" class = "form-control" name = "requested_date" id = "requested_date" ng-model = "vm.requisition.requested_date" readonly/>
						</div><!--/col-md-4-->
						<div class="col-md-4">
							<label for = "required_date" class = "control-label">Fecha Requerida</label>
							<div class = "input-group">
								<div class="input-group-addon">
                  					<i class="fa fa-calendar"></i>
                				</div><!--/input-group-addon-->
                				<input type = "text" class="form-control" name = "required_date" id = "required_date" ng-model = "vm.requisition.required_date" required data-inputmask="'alias': 'yyyy-mm-dd'" data-mask/>
							</div><!--/input-group-->
						</div><!--/col-md-4-->	
						<div class="col-md-4" id = "product_type_div">
							<label for = "product_type" class = "control-label">Producto</label>
							<select id = "product_type" name = "product_type" ng-model = "vm.requisition.product_type" ng-change = "vm.ChangeProductType();" class = "form-control">
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
								<select id = "filter_product" name = "filter_product" ng-model = "vm.requisition.filter_product" ng-change = "vm.ChangeFilterProduct();" class = "form-control">
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

					<div class="panel panel-default" id = "finances_info">
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
							<th>Acción</th>
						</thead>
						<tbody ng-repeat = "elem in vm.products_list" ng-init = "cont = $index">
							<tr>
								<td>#@{{ cont+1 }}</td>
								<td>@{{ elem.product_description }}</td>
								<td>@{{ elem.product_unit }}</td>
								<td>@{{ elem.product_use }}</td>
								<td>@{{ elem.product_pieces }}</td>
								<td>@{{ elem.money_type }}</td>
								<td>@{{ elem.pesos_price | currency }}</td>
								<td>@{{ elem.importe | currency }}</td>
								<td>
								<button type = "button" class = "btn_edit_pieces btn btn-info btn-xs" ng-click = "vm.EditProductPieces($index);"><i class = "fa fa-edit"></i></button> 
								<button type = "button" class = "btn_delete_pieces btn btn-danger btn-xs" ng-click = "vm.DeleteProductPieces($index);"><i class = "fa fa-trash"></i></button>
								</td>
							</tr>
						</tbody>
					</table>
					<div id = "edit_product_msg"></div><!--/edit_product_msg-->
					<br />
					<div class="col-md-4">
						<label for = "sub_total" class = "control-label">Sub-Total</label>
						<div class="input-group">
							<div class="input-group-addon">$</div>
								<input type = "text" class = "form-control" id = "sub_total" name = "sub_total" ng-model = "vm.requisition.subtotal" placeholder = "Sub-Total" readonly />
							</div><!--/input-group-addon-->
					</div><!--/col-md-4-->
					<div class="col-md-4">
						<label for = "iva" class = "control-label">IVA</label>
						<div class="input-group">
							<input type = "text" class = "form-control" id = "iva" name = "iva" ng-model = "vm.requisition.iva" ng-change = "vm.ChangeIVA();" placeholder = "IVA" />
							<div class="input-group-addon">%</div>
						</div><!--/input-group-->
					</div><!--/col-md-4-->
					<div class="col-md-4">
						<label for = "total" class = "control-label">Total</label>
						<div class="input-group">
							<div class="input-group-addon">$</div>
							<input type = "text" class = "form-control" id = "total" name = "total" ng-model = "vm.requisition.total" placeholder = "Total" readonly />
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
								<input type = "text" class = "form-control" name = "use" id = "use" ng-model = "vm.requisition.use" placeholder = "Uso de lo Requerido" required/>
							</div><!--/form-group-->
							<br />
							<br />
							<br />
						</div><!--/form-group-->
						<div class = "form-group">
							<label for = "observations" class="col-lg-2 control-label">Observaciones</label>
							<div class="col-lg-10">
								<textarea class="form-control" rows="4" id = "observations" name = "observations" ng-model = "vm.requisition.observations" required></textarea>
							</div><!--/col-lg-10-->
						</div><!--/form-group-->
					</div><!-- termina row -->
				</div><!--/modal-body-->
				<div id = "save_requisition_msg"></div><!-- requisition_list_msg-->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" id = "cancel_requistion_btn" data-dismiss="modal" aria-hidden="true" ng-click = "vm.CancelRequisition();">Cerrar</button>
					<button type = "submit" class = "btn btn-success" id = "submit_requisition_btn">Guardar Requisición</button>
				</div><!--/modal-footer-->
			</form>
			@if( Sentry::getUser()->inGroup( Sentry::findGroupByName('root') )  || Sentry::getUser()->inGroup( Sentry::findGroupByName('supplaying') ) ) 
			<br />
			<br />
			<div class="modal-body">
				<div class="panel panel-default">
					<div class="panel-heading">Notificaciones</div>
					 <div class="panel-body">
					 	<div class = "form-group">
					 		<label for = "user_notification">Contenido de Notifiación</label>
					 		<textarea class = "form-control" id = "user_notification" ng-model = "vm.notification" name = "user_notification"></textarea>
					 	</div><!--/form-group-->
					 	<div id = "notification_msg"></div><!--/notification_msg-->
					 	<button class = "btn btn-success pull-right" id = "save_notification_btn" ng-click = "vm.SaveNotification();">Agregar Notificación</button>	
						<div class = "clearfix"></div><!--/clearfix-->
						<br />
						<div class="list-group">
							<div ng-repeat = "elem in vm.notification_list" ng-init = "cont = $index">
								<a href="#" class="list-group-item active" ng-if = "elem.seen == 0">
									<button type="button" class="close" aria-label="Cerrar" ng-click = "vm.DeleteNotification($index);" id = "deln_@{{$index}}"><span aria-hidden="true">&times;</span></button>
							    	@{{ elem.notification }}
							  	</a>
								<a href="#" class="list-group-item" ng-if = "elem.seen == 1">
									<button type="button" class="close" aria-label="Cerrar" ng-click = "vm.DeleteNotification($index);"><span aria-hidden="true">&times;</span></button>
							    	@{{ elem.notification }}
							  	</a>
							</div>
						</div><!--/list-group-->
					</div><!--/panel-body-->
				</div><!--/panel-->
			</div><!--/modal-body-->
			@else
			<div class="modal-body">
				<div id = "notification_msg"></div><!--/notification_msg-->
				<div class="panel panel-default">
					<div class="panel-heading">Notificaciones</div>
					 <div class="panel-body">
						<div class="list-group">
							<div ng-repeat = "elem in vm.notification_list" ng-init = "cont = $index">
								<a href="" class="list-group-item active" ng-if = "elem.seen == 0" ng-click = "vm.UpdateNotification($index);">
							    	@{{ elem.notification }}
							  	</a>
								<a href="" class="list-group-item" ng-if = "elem.seen == 1">
							    	@{{ elem.notification }}
							  	</a>
							</div>
						</div><!--/list-group-->
					</div><!--/panel-body-->
				</div><!--/panel-->
			</div><!--/modal-body-->
			@endif
		</div><!--/modal-content-->		
	</div><!--/modal-dialog-->
</div><!--/modal-->

<div class="modal fade" id = "convert_requisition_modal" tabindex = "-1" role="dialog" aria-labelledby="convert_requisition_label" aria-hidden="true" role = "dialog" data-backdrop = "static" data-keyboard = "false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id = "modal_title_msg">Convertir a Orden Compra</h4>
			</div>
			<form method = "post" ng-submit = "vm.SubmitConvertRequisition();">
				<div class="modal-body">
					<div class = "row">
						<div class="col-md-4">
							<label for = "order_buy_date" class = "control-label">Fecha</label>
							<div class = "input-group">
								<div class="input-group-addon">
                  					<i class="fa fa-calendar"></i>
                				</div><!--/input-group-addon-->
                				<input type = "text" class="form-control" name = "order_buy_date" id = "order_buy_date" ng-model = "vm.order_buy.date" required data-inputmask="'alias': 'yyyy-mm-dd'" data-mask/>
							</div><!--/input-group-->
						</div><!--/col-md-4-->	
						<div class="col-md-4">
							<label for = "order_buy_pay_conditions" class = "control-label">Condiciones de Pago</label>
							<input type = "text" class = "form-control" id = "order_buy_pay_conditions" name = "order_buy_pay_conditions" ng-model = "vm.order_buy.pay_conditions" placeholder = "Condiciones de Pago" />
						</div><!--/col-md-4-->
					</div><!-- termina row -->

					<div class="col-md-6">
						<label for = "order_buy_provider_id" class = "control-label">Proveedor</label>
						<i id = "select_providers" class = "fa fa-spinner fa-spin fa-1x"></i>
						<select id = "order_buy_provider_id" name = "order_buy_provider_id" ng-model = "vm.order_buy.provider_id" class = "form-control" ng-options = "p_list.id as p_list.name for p_list in vm.providers_list_select">
							<option value = "">Seleccione una Opción...</option>
						</select>
					</div><!--/col-md-4-->

					<br />
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
						<tbody ng-repeat = "elem in vm.order_buy_list" ng-init = "cont = $index">
							<tr>
								<td>#@{{ cont+1 }}</td>
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
					<br />
					<br />
					<br />
					<br />
					<br />
					<div class = "row">
						<div class="form-group">
							<label for = "order_buy_deliver_place" class = "col-lg-3 control-label">Entregar en:</label>
							<div class="col-lg-9">
								<select class = "form-control" name = "order_buy_deliver_place" id = "order_buy_deliver_place" ng-model = "vm.order_buy.deliver_place" ng-change = "vm.ChangeDeliverPlace();">
									<option value = "">Seleccione una Opción...</option>
									<option value = "PLASTICOS DEL GOLFO SUR">PLASTICOS DEL GOLFO SUR</option>
									<option value = "other">Nueva Opción de Entega...</option>
								</select>
							</div><!--/form-group-->
						</div><!--/form-group-->
						<br />
						<br />
						<div class = "form-group" id = "new_place_div">
							<label for = "order_buy_new_place" class="col-lg-2 control-label">Nuevo Lugar:</label>
							<div class="col-lg-10">
								<input type = "text" class="form-control"  id = "order_buy_new_place" name = "order_buy_new_place" ng-model = "vm.order_buy.new_place" />
							</div><!--/col-lg-10-->
						</div><!--/form-group-->
						<br />
						<br />
						<div class = "form-group">
							<label for = "order_buy_observations" class="col-lg-2 control-label">Observaciones</label>
							<div class="col-lg-10">
								<textarea class="form-control" rows="4" id = "order_buy_observations" name = "order_buy_observations" ng-model = "vm.order_buy.order_observations" required></textarea>
							</div><!--/col-lg-10-->
						</div><!--/form-group-->
					</div><!-- termina row -->

				</div><!--/modal-body-->
				<div id = "save_order_buy_msg"></div><!-- requisition_list_msg-->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" ng-click = "vm.CancelOrderBuy();" id = "cancel_order_buy_btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
					<button type = "submit" class = "btn btn-success" id = "submit_order_buy_btn">Convertir a Orden de Compra</button>
				</div>
			</form>
		</div><!--/modal-content-->
	</div><!--/modal-dialog-->
</div><!--/modal-->

@stop