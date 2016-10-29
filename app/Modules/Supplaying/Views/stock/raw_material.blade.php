@extends('layouts.master')

@section('page_name')
  <h1>Control de Personal<small>Crear Trabajador</small></h1>
  <ol class="breadcrumb">
    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    <li class = "active">Control de Personal</a></li>
    <li class = "active">Crear Trabajador</a></li>
  </ol>
@stop

@section('js')
  {!! HTML::script('statics/js/customs/stock/row_material.js') !!}
@stop

@section('content')

@stop