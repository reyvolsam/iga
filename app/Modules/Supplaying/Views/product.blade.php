@extends('layouts.master')

@section('page_name')
	@if($product_type == 'raw_material')
  	<h1>Productos<small>Materia Prima</small></h1>
  	@elseif($product_type == 'finished_product')
  	<h1>Productos<small>Producto Terminado</small></h1>
  	@elseif($product_type == 'semifinished_product')
  	<h1>Productos<small>Producto Semiterminado</small></h1>
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
	{!! HTML::script('statics/js/lib/angular-file-upload.min.js') !!}
  	{!! HTML::script('statics/js/customs/product.js') !!}
@stop

@section('content')

<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Lista de Productos</h3>
    <div class="box-tools pull-right">
    @if($product_type == 'raw_material')
		<button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#raw_material_modal"><i class="fa fa-plus"></i> Materia Prima</button>
	@elseif($product_type == 'semifinished_product')
		<button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#semifinished_product_modal"><i class="fa fa-plus"></i> Producto Semiterminado</button>
	@elseif($product_type == 'finished_product')
		<button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#finished_product_modal"><i class="fa fa-plus"></i> Producto Terminado</button>
	@endif
      	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      	<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
    </div><!--/box-tools-->
  </div><!--/box-header-->
  <div class="box-body" ng-init = "vm.GetProductList();">
  <div id = "product_msg"></div><!--/product_msg-->
	<ul class = "pagination pull-right"></ul><!--/pagination-->
  	@if($product_type == 'raw_material')
	<table class = "table table-bordered table-hover">
		<thead>
			<tr>
				<th>Codigo</th>
				<th>Producto</th>
				<th>Proveedor</th>
				<th>Max</th>
				<th>Min</th>
				<th>Unidad</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody ng-repeat="elem in vm.product_list" ng-init = "cont = $index">
			<tr>
				<td>#@{{elem.code}}</td>
				<td>@{{elem.name}}</td>
				<td>@{{elem.provider_name}}</td>
				<td>@{{elem.max}}</td>
				<td>@{{elem.min}}</td>
				<td>@{{elem.unit}}</td>
				<td> 
					<button class = "btn btn-info btn-xs" ng-click = "vm.EditProduct($index)" ><i class = "fa fa-edit"></i></button>  
					<button class = "btn btn-danger btn-xs" ng-click = "vm.DeleteProduct($index)" ><i class = "fa fa-trash"></i></button>  
				</td>
			</tr>
		</tbody>
	</table>
	<i  id = "product_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
	@endif
	@if($product_type == 'semifinished_product')
	<table class = "table table-bordered table-hover">
		<thead>
			<tr>
				<th>Producto</th>
				<th>Descripción</th>
				<th>Proveedor</th>
				<th>Max</th>
				<th>Min</th>
				<th>Peso en gr.</th>
				<th>Unidad</th>
				<th>Total</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody ng-repeat="elem in vm.product_list">
			<tr>
				<td>@{{elem.name}}</td>
				<td>@{{elem.description}}</td>
				<td>@{{elem.provider_name}}</td>
				<td>@{{elem.max}}</td>
				<td>@{{elem.min}}</td>
				<td>@{{elem.weight}}</td>
				<td>@{{elem.unit}}</td>
				<td>@{{elem.total}}</td>
				<td>  </td>
			</tr>
		</tbody>
	</table>
	<i  id = "product_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
	@endif
	@if($product_type == 'finished_product')
	<table class = "table table-bordered table-striped">
		<thead>
			<tr>
				<th>Marca</th>
				<th>Descripción</th>
				<th>Unidad</th>
				<th>Proveedor</th>
				<th>Uso</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody ng-repeat="elem in vm.product_list">
			<tr>
				<td>@{{elem.brand}}</td>
				<td>@{{elem.description}}</td>
				<td>@{{elem.unit}}</td>
				<td>@{{elem.provider_name}}</td>
				<td>@{{elem.product_use}}</td>
				<td>  </td>
			</tr>
		</tbody>
	</table>
	@endif
	</div><!--/box-body-->
	<div class = "box-footer">
		<ul class = "pagination pull-right"></ul><!--/pagination-->
	</div><!--/box-footer-->
</div><!--/box-->

@if($product_type == 'raw_material')
<div class="modal fade" id = "raw_material_modal" tabindex = "-1" role="dialog" aria-labelledby="raw_material_label" aria-hidden="true" role = "dialog" data-backdrop = "static" data-keyboard = "false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Producto de Materia Prima</h4>
			</div>
			<form method = "post" ng-submit = "vm.SaveProduct();">
				<div class="modal-body">
					<div class="file-field input-field">
						<div class="form-group">
							<label for = "technical_file" class="control-label">Ficha Tecnica</label>
							<div id = "wrapper"> 
								<input  nv-file-select = "" uploader="uploader" class = "form-control" type = "file" id = "technical_file" name = "technical_file" size="1" /> 
							</div><!--/wrapper-->
						</div><!--/form-group-->
					</div><!--/file-field-->
					<div id = "technical_file_raw_material_div">
						<a href="#" target = "_blank" class = "label label-info" id = "product_technical_file"><i class = "fa fa-download"></i> Descargar Ficha Tecnica</a>
					</div><!--/technical_file_div_raw_material-->
					<br />
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
					<div class="form-group">
						<label for = "code" class="control-label">Codigo</label>
						<input type="text" class = "form-control" name = "code" id = "code" ng-model = "vm.product.code" />
					</div><!--/form-group-->
					<div class="form-group">
						<label for="name" class="control-label">Producto</label>
						<input type = "text" class = "form-control" id = "name" name = "name" ng-model = "vm.product.name" placeholder = "Nombre del Producto" />
					</div><!--/form-group-->
					<div class="form-group" ng-init = "vm.GetProviderList();">
						<label for = "provider" class="control-label">Proveedor</label>
						<i id = "select_provider" class = "fa fa-spinner fa-spin fa-1x"></i>
						<select id = "provider" name = "provider" ng-model = "vm.product.provider_id" class = "form-control" ng-options = "p_list.id as p_list.name for p_list in vm.provider_list">
							<option value = "">Seleccione una Opción...</option>
						</select>
					</div><!--/form-group-->
					<div class="form-group">
						<label for="max" class="control-label">Maximo</label>
						<input type = "number" class = "form-control" id = "max" name = "max" ng-model = "vm.product.max" placeholder = "Maximo" />
					</div><!--/form-group-->
					<div class="form-group">
						<label for="min" class="control-label">Minimo</label>
						<input type = "number" class = "form-control" id = "min" name = "min" ng-model = "vm.product.min" placeholder = "Minimo" />
					</div><!--/form-group-->
					<div class="form-group">
						<label for = "unidad" class="control-label">Unidad</label>
							<select id = "unidad" name = "unidad" class = "form-control" ng-model = "vm.product.unit">
								<option value = "">Seleccione una Opción</option>
								<option value = "Pieza">Pieza</option>
								<option value = "Cm.">Cm.</option>
								<option value = "Mtrs.">Mtrs.</option>
								<option value = "Kg.">Kg.</option>
								<option value = "Ln">Ln.</option>
							</select>
					</div><!--/form-group-->
					<div class="form-group">
						<label for = "descripcion" class="control-label">Descripción</label>
						<textarea class="form-control" rows="5" id = "descripcion" name = "descripcion" ng-model = "vm.product.description"></textarea>
					</div><!--/form-group-->
					<div id = "product_msg"></div><!--/product_msg-->
                  	<div class="progress xs">
                    	<div id = "progress_bar_file" class="progress-bar progress-bar-green"></div>
                  	</div><!---/progress-->
					<div class="modal-footer">			
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
						<button type = "submit" class = "btn btn-success" id = "save_product_btn">Guardar Producto</button>
					</div><!--/footer-->
				</div><!--/modal-body-->
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endif
@if($product_type == 'semifinished_product')
<div class="modal fade" id = "semifinished_product_modal" tabindex = "-1" role="dialog" aria-labelledby="semifinished_product_label" aria-hidden="true" role = "dialog" data-backdrop = "static" data-keyboard = "false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Producto Semiterminado</h4>
			</div>
			<form method = "post" ng-submit = "vm.SaveProduct();">
				<div class="modal-body">
					<div class="file-field input-field">
						<div class="form-group">
							<label for = "technical_file" class="control-label">Ficha Tecnica</label>
							<div id = "wrapper"> 
								<input  nv-file-select = "" uploader="uploader" class = "form-control" type = "file" id = "technical_file" name = "technical_file" size="1" /> 
							</div><!--/wrapper-->
						</div><!--/form-group-->
					</div><!--/file-field-->
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
					<div class="form-group">
						<label for="name" class="control-label">Producto</label>
						<input type = "text" class = "form-control" id = "name" name = "name" ng-model = "vm.product.name" placeholder = "Nombre del Producto" />
					</div><!--/form-group-->
					<div class="form-group">
						<label for = "provider" class="control-label">Proveedor</label>
						<input type = "text" name="provider" id = "provider" ng-model = "vm.product.provider_name" class = "form-control" disabled />
					</div><!--/form-group-->
					<div class="form-group">
						<label for="max" class="control-label">Maximo</label>
						<input type = "number" class = "form-control" id = "max" name = "max" ng-model = "vm.product.max" placeholder = "Maximo" />
					</div><!--/form-group-->
					<div class="form-group">
						<label for="min" class="control-label">Minimo</label>
						<input type = "number" class = "form-control" id = "min" name = "min" ng-model = "vm.product.min" placeholder = "Minimo" />
					</div><!--/form-group-->
					<div class="form-group">
						<label for = "unidad" class="control-label">Unidad</label>
							<select id = "unidad" name = "unidad" class = "form-control" ng-model = "vm.product.unit">
								<option value = " ">Seleccione una Opción</option>
								<option value = "pieza">Pieza</option>
								<option value = "Cm.">Cm.</option>
								<option value = "Kg.">Kg.</option>
								<option value = "Ln">Ln.</option>
							</select>
					</div><!--/form-group-->
					<div class="form-group">
						<label for="weigth" class="control-label">Peso en gr.</label>
						<div class = "input-group">
							<input type = "text" class = "form-control" id = "weigth" name = "weigth" ng-model = "vm.product.weigth" placeholder = "Peso en gr." />
							<div class = "input-group-addon">gr.</div>
						</div><!--/input-group-->
						
					</div><!--/form-group-->
					<div class="form-group">
						<label for = "descripcion" class="control-label">Descripción</label>
						<textarea class="form-control" rows="5" id = "descripcion" name = "descripcion" ng-model = "vm.product.description"></textarea>
					</div><!--/form-group-->
					<div id = "product_msg"></div><!--/product_msg-->
                  	<div class="progress xs">
                    	<div id = "progress_bar_file" class="progress-bar progress-bar-green"></div>
                  	</div><!---/progress-->
					<div class="modal-footer">			
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type = "submit" class = "btn btn-success" id = "save_product_btn">Guardar Producto</button>
					</div><!--/footer-->
				</div><!--/modal-body-->
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endif
@if($product_type == 'finished_product')
<div class="modal fade modal-xl" id = "finished_product_modal" tabindex = "-1" role="dialog" aria-labelledby="finished_product_label" aria-hidden="true" role = "dialog" data-backdrop = "static" data-keyboard = "false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Producto Terminado</h4>
			</div><!--/modal-header-->
			<form method = "post" ng-submit = "vm.SaveProductPT();">
				<div class="modal-body">

					<div class="col-lg-6">
						<div class="file-field input-field">
							<div class="form-group">
								<label for = "technical_file" class="control-label">Ficha Tecnica</label>
								<div id = "wrapper"> 
									<input  nv-file-select = "" uploader="uploader" class = "form-control" type = "file" id = "technical_file" name = "technical_file" size="1" multiple/> 
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


					<div class="col-lg-6">
						<div class="file-field input-field">
							<div class="form-group">
								<label for = "img_product" class="control-label">Imagen del Producto</label>
								<div id = "wrapper"> 
									<input  nv-file-select = "" uploader="uploader_img" class = "form-control" type = "file" id = "img_product" name = "img_product" size="1"/> 
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
						<tbody ng-repeat="item in uploader_img.queue">
							<tr>
								<td><strong>@{{ item.file.name }}</strong></td>
								<td> @{{ item.file.size/1024/1024|number:2 }} MB </td>
								<td><button type="button" class="btn btn-danger btn-xs" ng-click="item.remove()"><span class="glyphicon glyphicon-trash"></span></button></td>
							</tr>
						</tbody>
					</table>
					<div class="col-lg-6" ng-init = "vm.ProductTypeList();">
						<label for = "product_type_id" class="control-label">Tipo de Producto</label>
						<select class = "form-control" id = "product_type_id" name = "product_type_id" ng-model = "vm.product.product_type_id" ng-change = "vm.ChangeProductType();" ng-options="pt_list.id as pt_list.name for pt_list in vm.product_type_list">
							<option value = "">Seleccione una Opción...</option>
						</select>
					</div><!--/col-lg-6-->

					<div class = "clearfix"></div>
					<br />

					<div class="panel panel-default">
						<div class="panel-heading">Caracteristicas</div><!--/panel-heading-->
						<div class="panel-body">
							<div class="col-lg-6" id ="adjust_div">
								<label for = "adjust" class="control-label">Ajuste</label>
								<select class = "form-control" id = "adjust" name = "adjust" ng-model = "vm.product.adjust_id" ng-options = "a_list.id as a_list.name for a_list in vm.adjust">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="class_div">
								<label for = "class" class="control-label">Clase</label>
								<select id = "class" name = "class" class = "form-control" ng-model = "vm.product.class_id" ng-options = "c_list.id as c_list.name for c_list in vm.class">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="model_div">
								<label for = "model" class="control-label">Modelo</label>
								<select id = "model" name = "model_" class = "form-control" ng-model = "vm.product.model_id" ng-options = "m_list.id as m_list.name for m_list in vm.model">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="color_div">
								<label for = "color" class="control-label">Color</label>
								<select class = "form-control" id = "color" name = "color" ng-model = "vm.product.color_id" ng-options = "cl_list.id as cl_list.name for cl_list in vm.color">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="feets_div">
								<label for = "feets" class="control-label">Color de Armazon de Lente</label>
								<select class = "form-control" id = "feets" name = "feets" ng-model = "vm.product.feets_id" ng-options = "f_list.id as f_list.name for f_list in vm.feets">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-6-->
						</div><!-- termina panel-body -->
					</div><!-- termina panel -->

					<div class="panel panel-default">
						<div class="panel-heading">Almacen Coatza</div><!--/panel-heading-->
						<div class="panel-body">
							<div class = "col-lg-3">
								<label for = "coatza_min" class = "control-label">Min. en Almacen</label>
								<input type = "text" class = "form-control" id="coatza_min" name = "coatza_min" ng-model = "vm.product.coatza_min" placeholder = "Minimo" />
							</div><!--/col-lg-3-->
							<div class = "col-lg-3">
								<label for = "coatza_max" class = "control-label">Max. en Almacen</label>
								<input type = "text" class = "form-control" id="coatza_max" name = "coatza_max" ng-model = "vm.product.coatza_max" placeholder = "Maximo" />
							</div><!--/col-lg-3-->
							<div class = "col-lg-3">
								<label for = "coatza_max_ped" class = "control-label">Prod. max. por Pedido</label>
								<input type = "text" class = "form-control" id="coatza_max_ped" name = "coatza_max_ped" ng-model = "vm.product.coatza_max_ped" placeholder = "Maximo por Pedido" />
							</div><!--/col-lg-3-->
							<div class ="col-lg-3">
								<label for = "status_product_coatza" class = "control-label">Estado Prod.</label>
								<select class = "form-control" id = "status_product_coatza" name = "coatza_status_product" ng-model = "vm.product.coatza_status">
									<option value = "1">ACTIVO</option>
									<option value = "0">INACTIVO</option>
								</select>
							</div><!--/col-lg-3-->
						</div><!--panel body-->
					</div><!--panel-default-->

					<div class="panel panel-default">
						<div class="panel-heading">Almacen Guadalajara</div><!--/panel-heading-->
						<div class="panel-body">
							<div class = "col-lg-3">
								<label for = "guadalajara_min" class = "control-label">Min. en Almacen</label>
								<input type = "text" class = "form-control" id="guadalajara_min" name = "guadalajara_min" ng-model = "vm.product.guadalajara_min" placeholder = "Minimo" />
							</div><!--/col-lg-3-->
							<div class = "col-lg-3">
								<label for = "guadalajara_max" class = "control-label">Max. en Almacen</label>
								<input type = "text" class = "form-control" id="guadalajara_max" name = "guadalajara_max" ng-model = "vm.product.guadalajara_max" placeholder = "Maximo" />
							</div><!--/col-lg-3-->
							<div class = "col-lg-3">
								<label for = "guadalajara_max_ped" class = "control-label">Prod. max. por Pedido</label>
								<input type = "text" class = "form-control" id="guadalajara_max_ped" name = "guadalajara_max_ped" ng-model = "vm.product.guadalajara_max_ped" placeholder = "Maximo por Pedido" />
							</div><!--/col-lg-3-->
							<div class ="col-lg-3">
								<label for = "status_product_guadalajara" class = "control-label">Estado Prod.</label>
								<select class = "form-control" id = "status_product_guadalajara" name = "status_product_guadalajara" ng-model = "vm.product.guadalajara_status">
									<option value = "1">ACTIVO</option>
									<option value = "0">INACTIVO</option>
								</select>
							</div><!--/col-lg-3-->
						</div><!--panel body-->
					</div><!--panel-default-->

					<div class="panel panel-default">
						<div class="panel-heading">Informacion del Producto</div><!--/panel-heading-->
						<div class="panel-body">
							<div class="form-group" ng-init = "vm.GetProviderList();">
								<div class="col-lg-6">
									<label for = "provider_id" class="control-label">Proveedor</label>
									<select id = "provider_id" name = "provider_id" class = "form-control" ng-model = "vm.product.provider_id" ng-options = "p_list.id as p_list.name for p_list in vm.provider_list">
										<option value = "">Seleccione una Opción...</option>
									</select>
								</div><!--/col-lg-6-->
								<div class="col-lg-6">
									<label for = "unit" class="control-label">Unidad</label>
									<select id = "unit" name = "unit" class = "form-control" ng-model = "vm.product.unit">
										<option value = "">Seleccione una Opción...</option>
										<option value = "pieza">Pieza</option>
										<option value = "Cm.">Cm.</option>
										<option value = "Kg.">Kg.</option>
										<option value = "Ln">Ln.</option>
									</select>
								</div><!--/col-lg-6-->
							</div><!--/form-group-->
							<div class = "col-lg-4">
								<label for = "brand" class = "control-label">Marca</label>
								<input type = "text" class="form-control" id = "brand" name = "brand" ng-model = "vm.product.brand" placeholder = "Marca" />
							</div><!--/col-lg-4-->
							<div class="form-group">
								<div class="col-lg-9">
									<label for = "description" class="control-label">Descripción</label>
									<textarea class="form-control" rows="5" id = "description" name = "description" ng-model = "vm.product.description"></textarea>
								</div><!--/col-lg-9-->
								<div class="col-lg-3">
									<label>Uso del Producto</label>
									<div class="radio">
										<label>
											<input type="radio" id="selling_chkbx" name="product_use" ng-model = "vm.product.use" value = "Venta" checked />
											a)	Venta
										</label>
									</div><!--/radio-->
									<div class="radio">
										<label>
											<input type="radio" id="intern_chkbx" name="product_use" ng-model = "vm.product.use" value="Interno" />
											b)	Uso Interno
										</label>
									</div><!--/radio-->
								</div><!--/col-lg-3-->
							</div><!--/form-group-->
							<div class = "clearfix"></div><!--/clearfix-->
							<br />
							<div id = "product_msg"></div><!--/product_msg-->
		                  	<div class="progress xs">
		                    	<div id = "progress_bar_file" class="progress-bar progress-bar-green"></div>
		                  	</div><!---/progress-->
						</div><!--/panel-body-->
					</div><!--/panel-->

					<div class="modal-footer">			
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type = "submit" class = "btn btn-success" id = "save_product_btn">Guardar Producto</button>
					</div><!--/footer-->
				</div><!--/modal-body-->
			</form>
		</div><!--/modal-content-->
	</div><!--/modal-dialog-->
</div><!--/modal-->
@endif

@stop