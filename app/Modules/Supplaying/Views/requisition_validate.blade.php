@extends('layouts.master')

@section('page_name')
  	<h1>Producción<small>Validar Ordenes de Compra</small></h1>
  	<ol class="breadcrumb">
	    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
	    <li>Producción</a></li>
    	<li class = "active">Validar Ordenes de Compra</a></li>
  	</ol>
@stop

@section('js')
  	{!! HTML::script('statics/js/lib/angular-file-upload.min.js') !!}
  	{!! HTML::script('statics/js/lib/checklist-model.js') !!}
  	{!! HTML::script('statics/js/customs/requisitions/validate.js') !!}
@stop

@section('content')
<div class="box box-default">
	<div class="box-header with-border">
		<h3 class="box-title">Ordenes de Compra</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
		</div><!--/box-tools-->
	</div><!--/box-header-->
	<div class="box-body" ng-init = "vm.RequisitionList();">		
  		<div id = "requisition_list_msg"></div><!--/order_produciton_msg-->
  		<div class = "col-md-12">
  			@if( Sentry::getUser()->inGroup( Sentry::findGroupByName('root') ) || Sentry::getUser()->inGroup( Sentry::findGroupByName('supplaying') ) || \Sentry::getUser()->inGroup( \Sentry::findGroupByName('finance') ) )
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
  				<th>Total Requisitado</th>
  				<th>Uso</th>
  				<th>Acciones</th>
  			</thead>
  			<tbody ng-repeat = "elem in vm.requisition_list" ng-init = "cont = $index">
  				<tr>
  					<td>#@{{ elem.id }}</td>
  					<td>@{{ elem.group_name }}</td>
  					<td>@{{ elem.requested_date }}</td>
  					<td>@{{ elem.required_date }}</td>
  					<td>@{{ elem.total | currency }}</td>
  					<td>@{{ elem.use }}</td>
  					<td>
  						<button type = "button" class = "btn btn-success btn-xs" ng-click = "vm.SeeRequisition($index);" data-toggle="tooltip" data-placement = "top" title = "Ver Orden de Compra"><i class = "fa fa-eye"></i></button>
  						@if( Sentry::getUser()->inGroup( Sentry::findGroupByName('root') )  || Sentry::getUser()->inGroup( Sentry::findGroupByName('finance') ) ) 
							<button ng-if = "elem.pre_order == 1  && elem.finances_validate == 0" type = "button" class = "btn btn-default btn-xs" ng-click = "vm.ValidatePayRequisition($index);" data-toggle="tooltip" data-placement = "top" title = "Validar Pago de Requisición"><i class = "fa fa-check"></i></button> 
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

<div class="modal fade" id = "validate_pay_modal" tabindex = "-1" role="dialog" aria-labelledby="validate_pay_label" aria-hidden="true" role = "dialog" data-backdrop = "static" data-keyboard = "false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Validar Pago</h4>
			</div>
			<form method = "post" ng-submit = "vm.SubmitValidatePayRequsition();">
				<div class="modal-body">
					<div class = "col-md-8">
						<label ng-repeat="elem in vm.order_buy.providers">
	  						<input type="checkbox" checklist-model="vm.order_buy.providers_selected" data-checklist-value="elem"> @{{elem.name}} | @{{elem.email}}
						</label>
						<table class = "table">
							<thead>
								<th>Cargo</th>
								<th>Nombre</th>
								<th>Correo Electronico</th>
								<th>Telefono</th>
							</thead>
							<tbody ng-repeat = "elem in vm.order_buy.providers_selected">
								<tr>
									<td>@{{ elem.cargo }}</td>
									<td>@{{ elem.name }}</td>
									<td>@{{ elem.email }}</td>
									<td>@{{ elem.phone }}</td>
								</tr>
							</tbody>
						</table>
					</div><!--/col-md-6-->
					<div class = "clearfix"></div>

					<div class="col-lg-6">
						<div class="file-field input-field">
							<div class="form-group">
								<label for = "ticket_file" class="control-label">Baucher de Pago</label>
								<div id = "wrapper"> 
									<input  nv-file-select = "" uploader="uploader" class = "form-control" type = "file" id = "ticket_file" name = "ticket_file" size="1" multiple/> 
								</div><!--/wrapper-->
							</div><!--/form-group-->
						</div><!--/file-field-->
					</div><!--/col-lg-6-->

					<table class = "table table-bordered table-striped">
						<thead>
							<th>Nombre del Archivo</th>
							<th>Tamaño</th>
							<th>Acción</th>
						</thead>
						<tbody ng-repeat="item in uploader.queue">
							<tr>
								<td><strong>@{{ item.file.name }}</strong></td>
								<td> @{{ item.file.size/1024/1024|number:2 }} MB </td>
								<td><button type="button" class="btn btn-danger btn-xs" ng-click="item.remove()"><span class="glyphicon glyphicon-trash"></span></button></td>
							</tr>
						</tbody>
					</table>

                  	<div class="progress xs">
                    	<div id = "progress_bar_file" class="progress-bar progress-bar-green"></div>
                  	</div><!---/progress-->

				</div><!--/modal-body-->
				<div id = "save_validate_pay_msg"></div><!-- requisition_list_msg-->
				<div class = "clearfix"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" ng-click = "vm.CancelValidatePay();" id = "cancel_validate_pay_btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
					<button type = "submit" class = "btn btn-success" id = "submit_validate_pay_btn">Validar Pago de Requisición</button>
				</div>
			</form>
		</div><!--/modal-content-->
	</div><!--/modal-dialog-->
</div><!--/modal-->

<div class="modal fade" id = "order_buy_modal" tabindex = "-1" role="dialog" aria-labelledby="order_buy_label" aria-hidden="true" role = "dialog" data-backdrop = "static" data-keyboard = "false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id = "modal_title_msg">Ver Orden Compra</h4>
      </div>
      <form method = "post">
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
            <tbody ng-repeat = "elem in vm.order_buy_products_list">
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

          <div class="col-md-4">
            <label for = "sub_total" class = "control-label">Sub-Total</label>
            <div class="input-group">
              <div class="input-group-addon">$</div>
                <input type = "text" class = "form-control" id = "sub_total" name = "sub_total" ng-model = "vm.order_buy.subtotal" placeholder = "Sub-Total" readonly />
              </div><!--/input-group-addon-->
          </div><!--/col-md-4-->
          <div class="col-md-4">
            <label for = "iva" class = "control-label">IVA</label>
            <div class="input-group">
              <input type = "text" class = "form-control" id = "iva" name = "iva" ng-model = "vm.order_buy.iva" ng-change = "vm.ChangeIVA();" placeholder = "IVA" readonly />
              <div class="input-group-addon">%</div>
            </div><!--/input-group-->
          </div><!--/col-md-4-->
          <div class="col-md-4">
            <label for = "total" class = "control-label">Total</label>
            <div class="input-group">
              <div class="input-group-addon">$</div>
              <input type = "text" class = "form-control" id = "total" name = "total" ng-model = "vm.order_buy.total" placeholder = "Total" readonly />
            </div><!--/input-group-->
          </div><!--/col-md-4-->
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
        </div>
      </form>
    </div><!--/modal-content-->
  </div><!--/modal-dialog-->
</div><!--/modal-->

@stop