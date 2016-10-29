@extends('layouts.master')

@section('page_name')
	@if($internal_code_type == 'raw_material')
  	<h1>Productos<small>Codigo Interno de Materia Prima</small></h1>
  	@elseif($internal_code_type == 'finished_product')
  	<h1>Productos<small>Codigo Interno de Producto Terminado</small></h1>
  	@endif
  	<ol class="breadcrumb">
	    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    	<li>Productos</a></li>
    	<li>Codigo Interno</a></li>
    	@if($internal_code_type == 'raw_material')
    	<li class = "active">Materia Prima</a></li>
  		@elseif($internal_code_type == 'finished_product')
  		<li class = "active">Producto Terminado</li>
  		@endif
  	</ol>
@stop

@section('js')
	<script type="text/javascript">
		var internal_code_type = "{{ $internal_code_type }}";
	</script>
  {!! HTML::script('statics/js/customs/internal_code.js') !!}
@stop

@section('content')

<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Codigos Internos</h3>
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
				<th>Codigo</th>
				<th>Producto</th>
				<th>Min.</th>
				<th>Max.</th>
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
				<h4 class="modal-title">Codigo Interno de Materia Prima</h4>
			</div>
			<form method = "post" ng-submit = "vm.SaveRawMaterial();">
				<div class="modal-body">
					<div class="form-group">
						<label for = "code" class = "col-lg-3 control-label">Codigo</label>
						<input type = "text" class = "form-control" id = "code" name = "code" ng-model = "vm.internal_code.code" placeholder = "Codigo Interno" />
					</div>
					<div class="form-group">
						<label for = "product" class = "col-lg-3 control-label">Producto</label>
						<input type = "text" class="form-control" id = "product" name = "product" placeholder = "Producto" />
					</div>
					<div class="form-group">
						<label for = "minimo" class = "col-lg-3 control-label">Minimo</label>
						<div class = "col-lg-9">
							<input type = "text" class = "form-control" id="minimo" name = "minimo" placeholder = "Minimo" />
						</div>
					</div>
					<div class="form-group">
						<label for = "maximo" class = "col-lg-3 control-label">Maximo</label>
						<div class = "col-lg-9">
							<input type = "text" class = "form-control" id="maximo" name = "maximo" placeholder = "Maximo" />
						</div>
					</div>

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