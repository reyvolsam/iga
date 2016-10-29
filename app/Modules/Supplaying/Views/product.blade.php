@extends('layouts.master')

@section('page_name')
	@if($product_type == 'raw_material')
  	<h1>Productos<small>Materia Prima</small></h1>
  	@elseif($product_type == 'finished_product')
  	<h1>Productos<small>Producto Terminado</small></h1>
  	@endif
  	<ol class="breadcrumb">
	    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    	<li class = "active">Productos</a></li>
  	</ol>
@stop

@section('js')
	<script type="text/javascript">
		var product_type = "{{ $product_type }}";
	</script>
  {!! HTML::script('statics/js/customs/product.js') !!}
@stop

@section('content')

<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Lista de Productos</h3>
    <div class="box-tools pull-right">
		<button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#raw_material_modal"><i class="fa fa-plus"></i> Materia Prima</button>
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
    </div><!--/box-tools-->
  </div><!--/box-header-->
  <div class="box-body">
	<table class = "table table-bordered table-striped">
		<thead>
			<tr>
				<th>No.</th>
				<th>Producto</th>
				<th>Proveedor</th>
				<th>Tipo de Materia</th>
				<th>Codigo</th>
				<th>Unidad</th>
				<th>Descripción</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody ng-repeat="elem in vm.product_list">
			<tr>
				<td>#@{{elem.id}}</td>
			</tr>
		</tbody>
	</table>
  </div><!--/box-body-->
</div><!--/box-->


<div class="modal fade" id = "raw_material_modal" tabindex = "-1" role="dialog" aria-labelledby="raw_material_label" aria-hidden="true" role = "dialog" data-backdrop = "static" data-keyboard = "false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Producto de Materia Prima</h4>
			</div>
			<form method = "post" ng-submit = "vm.SaveRawMaterial();" ng-init = "vm.GetProviderList();">
				<div class="modal-body">
					<div class="file-field input-field">
						<div class="form-group">
							<label for="ficha_tecnica" class="col-lg-2 control-label">Ficha Tecnica</label>
								<div id = "browse"></div>
								<div id = "wrapper"> 
									<input  nv-file-select="" uploader="uploader" class = "form-control" type = "file" id = "technical_" name = "img_product" size="1" /> 
								</div><!--/wrapper-->
						</div><!--/form-group-->
					</div>
					<div class="form-group">
						<label for="name" class="control-label">Producto</label>
						<input type = "text" class = "form-control" id = "name" name = "name" ng-model = "vm.product.name" placeholder = "Nombre del Producto" />
					</div><!--/form-group-->
					<div class="form-group">
						<label for = "provider" class="control-label">Proveedor</label>
						<i id = "select_provider" class = "fa fa-spinner fa-spin fa-1x"></i>
						<select id = "provider" name = "provider" ng-model = "vm.product.provider" class = "form-control" ng-options = "p_list.id as p_list.name for p_list in vm.provider_list">
							<option value = "">Seleccione una Opción...</option>
						</select>
					</div><!--/form-group-->
					<div class="form-group" ng-init = "vm.GetMaterialTypeList();">
						<label for = "material_type" class="control-label">Tipo de Materia</label>
						<i id = "select_material_type" class = "fa fa-spinner fa-spin fa-1x"></i>
						<select id = "material_type" name = "material_type" ng-model = "vm.product.material_type" class = "form-control" ng-options = "m_list.id as m_list.name for m_list in vm.material_type_list">
						<option value = "">Seleccione una Opción...</option>
						</select>
					</div><!--/form-group-->
					<div class="form-group">
						<label for = "codigo" class="control-label">Codigo</label>
						<select class = "form-control" id = "codigo" name = "codigo" ng-model = "vm.product.internal_code"></select>
					</div><!--/form-group-->
					<div class="form-group">
						<label for = "unidad" class="control-label">Unidad</label>
							<select id = "unidad" name = "unidad" class = "form-control" ng-model = "vm.product.unit">
								<option value = " ">Selecciona una Opción</option>
								<option value = "pieza">Pieza</option>
								<option value = "Cm.">Cm.</option>
								<option value = "Kg.">Kg.</option>
								<option value = "Ln">Ln.</option>
							</select>
					</div><!--/form-group-->
					<div class="form-group">
						<label for = "descripcion" class="control-label">Descripción</label>
						
							<textarea class="form-control" rows="5" id = "descripcion" name = "descripcion" ng-model = "vm.product.description"></textarea>
						
					</div><!--/form-group-->
					<div id = "product_msg"></div>
					<div class="modal-footer">			
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>					
						<button type = "submit" class = "btn btn-success" id = "save_product_btn">Guardar Producto</button>
					</div><!--/footer-->
				</div><!--/modal-body-->
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->							
@stop