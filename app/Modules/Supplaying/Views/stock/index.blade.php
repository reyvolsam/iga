@extends('layouts.master')

@section('page_name')
  <h1>Productos<small>Stock</small></h1>
  <ol class="breadcrumb">
    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    <li class = "active">Stock de Materia Prima</a></li>
  </ol>
@stop

@section('js')
  {!! HTML::script('statics/js/customs/stock/index.js') !!}
@stop

@section('content')

<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Materia Prima - Stock</h3>
  </div><!--/box-header-->
  <div class="box-body" ng-init = "vm.GetStockList();">
  	<div id = "stock_list_msg"></div><!--/product_msg-->
	<table class = "table">
		<thead>
			<th>Codigo</th>
			<th>Producto</th>
			<th>Unidad</th>
			<th>Total Saldo</th>
		</thead>
		<tbody ng-repeat="elem in vm.stock_list">
			<tr>
				<td>@{{ elem.code }}</td>
				<td>@{{ elem.name }}</td>
				<td>@{{ elem.unit }}</td>
				<td>@{{ elem.total }}</td>
			</tr>
		</tbody>
	</table>
	<i  id = "stock_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
  </div></div><!--/box-body-->
</div><!--/box-->

@stop