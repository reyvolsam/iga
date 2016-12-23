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
  				<th>Acciones</th>
  			</thead>
  			<tbody ng-repeat = "elem in vm.orders_buy_list" ng-init = "cont = $index">
  				<tr>
  					<td>#@{{ elem.id }}</td>
  					<td>@{{ elem.group_name }}</td>
  					<td>@{{ elem.order_buy_date }}</td>
  					<td>@{{ elem.pay_conditions }}</td>
  					<td>@{{ elem.total | currency }}</td>
  					<td>  </td>
  				</tr>
  			</tbody>
  		</table>
  		<i  id = "orders_buy_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
	</div><!--/box-body-->
</div><!--/box-->


<div class="modal fade" id = "save_requisition_modal" tabindex = "-1" role="dialog" aria-labelledby="save_requisition_label" aria-hidden="true" role = "dialog" data-backdrop = "static" data-keyboard = "false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Ordenes de Compra</h4>
			</div>
			<form method = "post" ng-submit = "vm.SaveOrdersBuy();">
				<div class="modal-body">

				</div><!--/modal-body-->
				<div id = "save_orders_buy_msg"></div><!-- requisition_list_msg-->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" id = "cancel_orders_buy_btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
					<button type = "submit" class = "btn btn-success" id = "submit_orders_buy_btn">Guardar Orden de Compra</button>
				</div>
			</form>
		</div><!--/modal-content-->
	</div><!--/modal-dialog-->
</div><!--/modal-->
@stop