@extends('layouts.master')

@section('page_name')
  	<h1>Producción<small>Orden de Compra</small></h1>
  	<ol class="breadcrumb">
	    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
	    <li>Producción</a></li>
    	<li class = "active">Orden de Compra</a></li>
  	</ol>
@stop

@section('js')
  	{!! HTML::script('statics/js/lib/jquery.inputmask.js') !!}
  	{!! HTML::script('statics/js/lib/jquery.inputmask.date.extensions.js') !!}
  	{!! HTML::script('statics/js/customs/order_buy.js') !!}
@stop

@section('content')
<div class="box box-default">
	<div class="box-header with-border">
		<h3 class="box-title">Orden de Compra</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
		</div><!--/box-tools-->
	</div><!--/box-header-->
	<div class="box-body" ng-init = "vm.OrdersBuyList();">
  		<div id = "orders_buy_list_msg"></div><!--/order_produciton_msg-->

  		<table class = "table">
  			<thead>
  				<th>Folio</th>
  				<th>Departamento</th>
  				<th>Fecha</th>
  				<th>Condición de Pago</th>
  				<th>Total</th>
          <th>Acción</th>
  			</thead>
  			<tbody ng-repeat = "elem in vm.orders_buy_list" ng-init = "cont = $index">
  				<tr>
  					<td>#@{{ elem.id }}</td>
  					<td>@{{ elem.group_name }}</td>
  					<td>@{{ elem.date }}</td>
  					<td>@{{ elem.pay_conditions }}</td>
  					<td>@{{ elem.total | currency }}</td>
            <td>
            <button type = "button" class = "btn btn-success btn-xs" ng-click = "vm.SeeRequisition($index);" data-toggle="tooltip" data-placement = "top" title = "Ver Orden de Compra"><i class = "fa fa-eye"></i></button>
            <button ng-if = "elem.pre_order == 1  && elem.finances_validate == 1" type = "button" class = "btn btn-default btn-xs" ng-click = "vm.ViewPayTicket($index);" data-toggle="tooltip" data-placement = "top" title = "Ver Boucher de Pago"><i class = "fa fa-money"></i></button>
            </td>
  				</tr>
  			</tbody>
  		</table>
  		<i  id = "orders_buy_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
	</div><!--/box-body-->
</div><!--/box-->

<div class="modal fade" id = "view_pay_modal" tabindex = "-1" role="dialog" aria-labelledby="view_pay_label" aria-hidden="true" role = "dialog" data-backdrop = "static" data-keyboard = "false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Ver Boucher de Pago</h4>
      </div>
      <div class="modal-body">
      <table class = "table">
        <thead>
          <th>Nombre</th>
          <th>Correo Electronico</th>
          <th>Cargo</th>
        </thead>
        <tbody ng-repeat = "elem in vm.provier_emails">
            <tr>
              <td>@{{ elem.name }}</td>
              <td>@{{ elem.email }}</td>
              <td>@{{ elem.cargo }}</td>
            </tr>
        </tbody>
      </table>
      <img src = "#" id = "view_pay" name = "view_pay" width="400" height="250" />
      </div><!--/modal-body-->
      <div id = "view_pay_msg"></div><!-- requisition_list_msg-->
      <div class = "clearfix"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id = "cancel_order_buy_view_btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
      </div>
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