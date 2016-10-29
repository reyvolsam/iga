@extends('layouts.master')

@section('page_name')
  <h1>Requisición de Personal<small>Formato de Requisición de Personal</small></h1>
  <ol class="breadcrumb">
    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    <li class = "active">Control de Personal</a></li>
    <li class = "active">Requisición de Personal</a></li>
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
      <h3 class="box-title">Formato de Requisición de Personal</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
      </div><!--/box-tools-->
    </div><!--/box-header-->
  
    <form id = "create_requisition_form" ng-submit = "vm.SubmitRequisitionUser()" ng-init = "vm.GetGroupList();">
      <div class="box-body">
      <div id = "requisition_msg"></div><!--/resuisition_msg-->
        <div class = "row">
          <div class = "col-md-4">
            <div class="form-group">
              <label for = "date">Fecha:</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div><!--/input-group-addon-->
                <input type="text" class="form-control" name = "date" id = "date" ng-model = "vm.requisition_data.date" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask />
              </div><!--/input-group-->
            </div><!--/form-group-->
          </div><!-- termina col-md-4 -->
        </div><!--/row-->
        
        <div class = "col-md-4">
          <h4>1. Información sobre la Vacante</h4>
          <div class="form-group">
            <label>
              <input type = "radio" name = "vacant_information" ng-model = "vm.requisition_data.vacant_information" value = "Creación de Cargo" />
              Creación de Cargo
            </label>
            <br />
            <label>
              <input type = "radio" name = "vacant_information" ng-model = "vm.requisition_data.vacant_information" value = "Reemplazo Definitivo" />
              Reemplazo Definitivo
            </label>
            <br />
            <label>
              <input type = "radio" name = "vacant_information" ng-model = "vm.requisition_data.vacant_information" value = "Reemplazo Temporal" />
              Reemplazo Temporal
            </label>
            <br />
            <label>
              <input type = "radio" name = "vacant_information" ng-model = "vm.requisition_data.vacant_information" value = "Reestructuración del Cargo" />
              Reestructuración del Cargo
            </label>
          </div><!--/form-group-->
        </div><!--/col-md-4-->
        
        <div class = "col-md-4">
          <h4>2. La Vacante se Produjo Por</h4>
          <div class="form-group">
            <label>
              <input type = "radio" name = "vacant_for" ng-model = "vm.requisition_data.vacant_for" value = "Renuncia del Titutlar" />
              Renuncia del Titular
            </label>
            <br />
            <label>
              <input type = "radio" name = "vacant_for" ng-model = "vm.requisition_data.vacant_for" value = "Termino de Contrato" />
              Termino de Contrato
            </label>
            <br />
            <label>
              <input type = "radio" name = "vacant_for" ng-model = "vm.requisition_data.vacant_for" value = "Vacaciones" />
              Vacaciones
            </label>
            <br />
            <label>
              <input type = "radio" name = "vacant_for" ng-model = "vm.requisition_data.vacant_for" value = "Incapacidad" />
              Incapacidad
            </label>
            <br />
            <label>
              <input type = "radio" name = "vacant_for" ng-model = "vm.requisition_data.vacant_for" value = "Incapacidad Maternidad" />
              Incapacidad Maternidad
            </label>
            <br />
            <label>
              <input type = "radio" name = "vacant_for" ng-model = "vm.requisition_data.vacant_for" value = "Incremento de Labores" />
              Incremento de Labores
            </label>
          </div><!--/form-group-->
        </div><!--/col-md-4-->
        <h4>3. Información sobre el cargo</h4>
        <div class = "row">
          <div class = "col-md-4">
            <div class="form-group">
              <label for = "date">Fecha en que debe quedar cubierta la Vacante:</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div><!--/input-group-addon-->
                <input type="text" class="form-control" name = "vacant_begin_date" id = "vacant_begin_date" ng-model = "vm.requisition_data.vacant_begin_date" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask />
              </div><!--/input-group-->
            </div><!--/form-group-->
          </div><!-- termina col-md-4 -->
          <div class = "col-md-4">
            <div class="form-group">
              <label>Nombre del Puesto:</label>
              <i id = "select_loader_list" class = "fa fa-spinner fa-spin fa-1x"></i>
                <select class = "form-control" ng-model = "vm.requisition_data.group_id" ng-options="p_list.id as p_list.slug for p_list in vm.group_list">
                  <option value = "">Seleccione una Opción...</option>
                </select>
            </div><!--/form-group-->
            <div class="form-group">
              <label>4. No. Personas Requeridas:</label>
              <input type="number" class = "form-control" ng-model = "vm.requisition_data.required_person" />
            </div><!--/form-group-->
          </div><!-- termina col-md-4 -->
        </div><!--/row-->

        <div class="modal-footer">
          <button type = "submit" id = "btn_create_requisition" class = "btn btn-default">Crear Rquisición</button>
        </div><!-- termina modal-footer -->

      </div><!--/box-body-->
    </form>
  </div><!--/box-->
@stop