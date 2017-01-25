@extends('layouts.master')

@section('page_name')
  <h1>Productos<small>Stock</small></h1>
  <ol class="breadcrumb">
    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    <li class = "active">Entradas de Producto Semiterminado</a></li>
  </ol>
@stop

@section('js')
  {!! HTML::script('statics/js/customs/stock/semifinished_product.js') !!}
@stop

@section('content')

<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Producto Semiterminado - Entradas</h3>
  </div><!--/box-header-->
  <div class="box-body" ng-init = "">
  	<div id = "stock_list_msg"></div><!--/product_msg-->
	<!--<i  id = "stock_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>-->
  </div></div><!--/box-body-->
</div><!--/box-->

@stop