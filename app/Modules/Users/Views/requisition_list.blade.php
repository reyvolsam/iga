@extends('layouts.master')

@section('page_name')
  <h1>Requisición de Personal<small>Formato de Requisición de Personal</small></h1>
  <ol class="breadcrumb">
    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    <li class = "active">Control de Personal</a></li>
    <li class = "active">Lista de Requisiciones</a></li>
  </ol>
@stop

@section('css')
  {!! HTML::style('statics/css/iCheck/all.css') !!}
@stop

@section('js')
  {!! HTML::script('statics/js/lib/icheck.min.js') !!}
  {!! HTML::script('statics/js/lib/jquery.inputmask.js') !!}
  {!! HTML::script('statics/js/lib/jquery.inputmask.date.extensions.js') !!}
  {!! HTML::script('statics/js/customs/requisicion_user.js') !!}
@stop

@section('content')

  <div class="box box-default">
      <div class="box-header with-border">
          <h3 class="box-title">Lista de Requisiciones</h3>

      <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div><!--/box-tools-->
      </div><!--/box-header-->
      <div class="box-body">
      <div id = "users_msg"></div><!--/users_msg-->
        <table class="table table-bordered table-striped" ng-init = "vm.RequisitionList();">
          <thead>
            <tr>
              <th>ID</th>
              <th>Fecha</th>
              <th>Información sobre la Vacante</th>
              <th>La Vacante se Produjo por</th>
              <th>Nombre del Puesto</th>
              <th>Personas Requeridas</th>
              <th>Revisiones</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody ng-repeat="elem in vm.requisition_list" ng-init="cont = $index;">
            <tr>
              <td>#@{{elem.id}}</td>
              <td>@{{elem.date}}</td>
              <td>@{{elem.vacant_information}}</td>
              <td>@{{elem.vacant_for}}</td>
              <td>@{{elem.group_name}}</td>
              <td>@{{elem.required_person}}</td>
              <td> 
                <small class="label bg-red" ng-if = "elem.review_flag == '0'">No Revisado</small> 
                <small class="label bg-green" ng-if = "elem.review_flag == '1'">Revisado</small> 

                <small class="label bg-red" ng-if = "elem.autorize_flag == '0'" >No Autorizado</small>
                <small class="label bg-green" ng-if = "elem.autorize_flag == '1'" >Autorizado</small>
              </td>
              <td>
                <a class = "btn btn-default btn-xs" href = "{{ URL::to('users/create/')}}/@{{elem.id}}" ng-if = "elem.autorize_flag == '1' || elem.review_flag == '1' && elem.required_person > '1'" ><i class = "fa fa-user-plus"></i></a>
              </td>
            </tr>
          </tbody>
        </table>
        <i  id = "requisition_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
        <ul class = "pagination">
        </ul><!--/pagination-->

      </div><!--/box-body-->
    </div><!--/box-->

@stop