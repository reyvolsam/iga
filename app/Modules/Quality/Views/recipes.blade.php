@extends('layouts.master')

@section('page_name')
  	<h1>Calidad<small>Recetas</small></h1>
  	<ol class="breadcrumb">
	    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    	<li>Calidad</a></li>
    	<li class = "active">Recetas</a></li>
  	</ol>
@stop

@section('js')
  	{!! HTML::script('statics/js/customs/quality/recipes.js') !!}
@stop

@section('content')

<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Recetas</h3>
    <div class="box-tools pull-right">
		<button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#create_recipes_modal"><i class="fa fa-plus"></i> Crear Recetas</button>
      	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      	<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
    </div><!--/box-tools-->
  </div><!--/box-header-->
  <div class="box-body">
  <div id = "recipes_msg"></div><!--/feature_msg-->
	<table class = "table table-bordered table-hover" ng-init = "vm.RecipesList();">
		<thead>
			<tr>
				<th>Tipo de Producto</th>
				<th>Nombre</th>
				<th>Descripción</th>
				<th>Fecha de Actualización</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody ng-repeat="elem in vm.recipes_list">
			<tr>
				<td>@{{elem.product_type_name}}</td>
				<td>@{{elem.product_name}}</td>
				<td>@{{elem.product_description}}</td>
				<td>@{{elem.updated_at}}</td>
				<td> <button type = "button" class = "btn btn-info btn-xs" ng-click = "vm.EditRecipe($index);" data-toggle="tooltip" data-placement="top" title="Editar Receta"><i class = "fa fa-edit"></i></button> <button type = "button" class = "btn btn-danger btn-xs" id = "rec_del_@{{elem.id}}" ng-click = "vm.DeleteRecipe($index);" data-toggle="tooltip" data-placement="top" title="Eliminar Receta"><i class = "fa fa-trash"></i></button> </td>
			</tr>
		</tbody>
	</table>
	<i  id = "recipes_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
  </div><!--/box-body-->
</div><!--/box-->

<div class="modal fade" id = "create_recipes_modal" tabindex = "-1" role="dialog" aria-labelledby="create_recipes_label" aria-hidden="true" role = "dialog" data-backdrop = "static" data-keyboard = "false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Recetas</h4>
			</div>
			<form method = "post" ng-submit = "vm.SaveRecipe();">
				<div class="modal-body" ng-init = "vm.LoadDataRecipe();">
					<div class="panel panel-default">
						<div class="panel-heading">Seleccion del Producto</div>
					 	<div class="panel-body">
							<div class="col-md-4">
								<label for = "product_type" class = "control-label">Tipo del Producto</label>
								<select class="form-control" id = "product_type" ng-model = "vm.recipe.product_type" ng-change = "vm.ChangeProductType();">	
									<option value = "">Seleccione una Opción...</option>
									<option value = "semifinished_product">Producto Semiterminado</option>
									<option value = "finished_product">Producto Terminado</option>
								</select>
							</div><!--/col-md-4-->
							<div class="col-md-4" id = "semifinished_product_type_div">
								<label for = "semifinished_product_type" class = "control-label">Tipo del Producto Semiterminado</label>
								<select class = "form-control" id = "semifinished_product_type" ng-model = "vm.recipe.semifinished_product_id" ng-options = "sp_list.id as sp_list.name for sp_list in vm.semifinished_products_list">	
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-md-4-->
							<div class="col-md-4" id = "finished_product_type_div">
								<label for = "finished_product_type" class = "control-label">Tipo del Producto de PT</label>
								<select class="form-control" id = "finished_product_type" ng-model = "vm.recipe.finished_product_type_id" ng-change = "vm.ChangeFinishedProductType();"  ng-options = "fp_list.id as fp_list.name for fp_list in vm.finished_products_types_list">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-md-4-->
					 	</div><!--panel-body-->
					</div><!--/panel-->

					<div class="panel panel-default">
						<div class="panel-heading">Caracteristicas de Producto Terminado</div><!--/panel-heading-->
						<div class="panel-body">
							<div class="col-lg-6" id ="adjust_div">
								<label for = "adjust" class="control-label">Ajuste</label>
								<select class = "form-control" id = "adjust" name = "adjust" ng-model = "vm.recipe.adjust_id" ng-change = "vm.ChangeFeatureProduct();" ng-options = "a_list.id as a_list.name for a_list in vm.adjust">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="class_div">
								<label for = "class" class="control-label">Clase</label>
								<select id = "class" name = "class" id = "class" class = "form-control" ng-model = "vm.recipe.class_id" ng-change = "vm.ChangeFeatureProduct();" ng-options = "c_list.id as c_list.name for c_list in vm.class">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="model_div">
								<label for = "model" class="control-label">Modelo</label>
								<select id = "model" name = "model" id = "model" class = "form-control" ng-model = "vm.recipe.model_id" ng-change = "vm.ChangeFeatureProduct();" ng-options = "m_list.id as m_list.name for m_list in vm.model">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="color_div">
								<label for = "color" class="control-label">Color</label>
								<select class = "form-control" id = "color" name = "color" ng-model = "vm.recipe.color_id" ng-change = "vm.ChangeFeatureProduct();" ng-options = "cl_list.id as cl_list.name for cl_list in vm.color">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="feets_div">
								<label for = "feets" class="control-label">Color de Armazon de Lente</label>
								<select class = "form-control" id = "feets" name = "feets" ng-model = "vm.recipe.feets_id" ng-change = "vm.ChangeFeatureProduct();" ng-options = "f_list.id as f_list.name for f_list in vm.feets">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-lg-6-->
						</div><!-- termina panel-body -->
					</div><!-- termina panel -->

					<div class="panel panel-default">
						<div class="panel-heading">Lista de Productos</div><!--/panel-heading-->
						<div class="panel-body">
							<div class="col-md-4">
								<label for = "product_type_recipe" class = "control-label">Tipo de Producto</label>
								<select class="form-control" id = "product_type_recipe" naem = "product_type_recipe" ng-model = "vm.product.type" ng-change = "vm.ChangeProductTypeRecipe();">	
									<option value = "">Seleccione una Opción...</option>
									<option value = "raw_material">Materia Prima</option>
									<option value = "semifinished_products">Producto Semiterminado</option>
									<option value = "finished_products">Producto Terminado</option>
								</select>
							</div><!--/col-md-4-->
							<div class="col-md-4">
								<label for = "product_id_recipe" class = "control-label">Producto</label>
								<i id = "select_product" class = "fa fa-spinner fa-spin fa-1x"></i>
								<select class="form-control" name = "product_id_recipe" id = "product_id_recipe" ng-model = "vm.product.id" ng-change = "vm.ChangeProductRecipe();" ng-options = "p_list.id as p_list.name for p_list in vm.product_list_select">
									<option value = "">Seleccione una Opción...</option>
								</select>
							</div><!--/col-md-4-->
							<div class="col-md-2">
								<label for = "quantity_product_recipe" class = "control-label">Cantidad</label>
								<input type="text" class = "form-control" name = "quantity_recipe" id = "quantity_recipe" ng-model = "vm.product.quantity" />
							</div><!--/col-md-4-->
							<div class="col-md-2">
								<label for = "unit_product_recipe" class = "control-label">Unidad</label>
								<input type="text" class = "form-control" name = "unit_product_recipe" id = "unit_product_recipe" ng-model = "vm.product.unit" readonly />
							</div><!--/col-md-4-->
							<div class = "clearfix"></div><!--/clearfix-->
							<br />
							<div class = "col-md-4">
								<button type = "button" class = "btn btn-default" ng-click = "vm.AddProduct();"><i class = "fa fa-plus"></i> Agregar</button>
							</div><!--/col-md-4-->
						</div><!--/panel-body-->
					</div><!--/panel-->

					<table class = "table">
						<thead>
							<th>Partida</th>
							<th>Producto</th>
							<th>Descripción</th>
							<th>Cantidad</th>
							<th>Unidad</th>
							<th>Acción</th>
						</thead>
						<tbody ng-repeat = "elem in vm.products_recipe_list" ng-init = "cont = $index">
							<tr>
								<td>#@{{ cont+1 }}</td>
								<td>@{{ elem.name }}</td>
								<td>@{{ elem.description }}</td>
								<td>@{{ elem.quantity }}</td>
								<td>@{{ elem.unit }}</td>
								<td> <button type = "button" class = "btn btn-danger btn-xs" id = "req_del_@{{elem.id}}" ng-click = "vm.DeleteRequirement($index);" data-toggle="tooltip" data-placement="top" title="Eliminar Requisición"><i class = "fa fa-trash"></i></button> </td>
							</tr>
						</tbody>
					</table>

				</div><!--/modal-body-->
				<div id = "recipes_save_msg"></div><!--/feature_save_msg-->
				<div class="modal-footer">			
					<button type="button" class="btn btn-danger" ng-click = "vm.CancelRecipe();" id = "cancel_recipe_btn" data-dismiss="modal">Cancelar</button>
					<button type = "submit" class = "btn btn-success" id = "save_recipe_btn">Guardar Receta</button>
				</div><!--/footer-->
			</form>
		</div><!--/modal-content-->
	</div><!--modal-dialog-->
</div><!--/modal-->
@stop