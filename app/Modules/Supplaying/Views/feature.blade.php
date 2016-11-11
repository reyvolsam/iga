@extends('layouts.master')

@section('page_name')
	@if($feature == 'class')
  	<h1>Caracteristica de Producto<small>Clase</small></h1>
  	@elseif($feature == 'model')
  	<h1>Caracteristica de Producto<small>Modelo</small></h1>
  	@elseif($feature == 'adjust')
  	<h1>Caracteristica de Producto<small>Ajuste</small></h1>
  	@elseif($feature == 'color')
  	<h1>Caracteristica de Producto<small>Color</small></h1>
  	@elseif($feature == 'feets')
  	<h1>Caracteristica de Producto<small>Patitas</small></h1>
  	@endif
  	<ol class="breadcrumb">
	    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    	<li class = "active">Caracteristicas de Producto</a></li>
  	</ol>
@stop

@section('js')
	<script type="text/javascript">
		var feature = "{{ $feature }}";
	</script>
  	{!! HTML::script('statics/js/customs/feature.js') !!}
@stop

@section('content')

<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Lista de Caracteristica de Producto</h3>
    <div class="box-tools pull-right">
    @if($feature == 'class')
		<button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#feature_modal"><i class="fa fa-plus"></i> Clase</button>
	@elseif($feature == 'model')
		<button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#feature_modal"><i class="fa fa-plus"></i> Modelo</button>
	@elseif($feature == 'adjust')
		<button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#feature_modal"><i class="fa fa-plus"></i> Ajuste</button>
	@elseif($feature == 'color')
		<button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#feature_modal"><i class="fa fa-plus"></i> Color</button>
	@elseif($feature == 'feets')
		<button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#feature_modal"><i class="fa fa-plus"></i> Patitas</button>
	@endif
      	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      	<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
    </div><!--/box-tools-->
  </div><!--/box-header-->
  <div class="box-body">
  <div id = "feature_msg"></div><!--/feature_msg-->
	<table class = "table table-bordered table-hover" ng-init = "vm.FeatureList();">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Descripción</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody ng-repeat="elem in vm.feature_list">
			<tr>
				<td>@{{elem.name}}</td>
				<td>@{{elem.description}}</td>
				<td>  </td>
			</tr>
		</tbody>
	</table>
	<i  id = "feature_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
  </div><!--/box-body-->
</div><!--/box-->

<div class="modal fade" id = "feature_modal" tabindex = "-1" role="dialog" aria-labelledby="feature_label" aria-hidden="true" role = "dialog" data-backdrop = "static" data-keyboard = "false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Caracteristica de Producto</h4>
			</div>
			<form method = "post" ng-submit = "vm.SaveFeature();">
				<div class="modal-body">
					<div class="form-group">
						<label for="name" class="control-label">Nombre</label>
						<input type = "text" class = "form-control" id = "name" name = "name" ng-model = "vm.feature.name" placeholder = "Nombre" />
					</div><!--/form-group-->
					<div class="form-group">
						<label for = "description" class="control-label">Descripción</label>
						<input type = "text" name="description" id = "description" ng-model = "vm.feature.description" class = "form-control"/>
					</div><!--/form-group-->
				</div><!--/modal-body-->
				<div id = "feature_save_msg"></div><!--/feature_save_msg-->
				<div class="modal-footer">			
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type = "submit" class = "btn btn-success" id = "save_feature_btn">Guardar</button>
				</div><!--/footer-->
			</form>
		</div><!--/modal-content-->
	</div><!--modal-dialog-->
</div><!--/modal-->
@stop