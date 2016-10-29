@extends('layouts.master')

@section('page_name')
  <h1>Finanzas<small>Bancos</small></h1>
  <ol class="breadcrumb">
    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    <li class = "active">Bancos</a></li>
  </ol>
@stop

@section('js')
  {!! HTML::script('statics/js/customs/finances/bank.js') !!}
@stop

@section('content')

	<div class = "row" ng-init = "vm.GetBankList();">

		<div class="col-md-6">
			<div class="box box-default">
				<div class="box-header">
					<h3 class="box-title">Crear Banco</h3>
				</div>
				<form id = "save_bank_form" ng-submit = "vm.SubmitBank()">
					<div class="box-body">
	              		<div class="form-group">
    	            		<label>Nombre</label>
        	          		<input type="text" class="form-control" ng-model = "vm.bank.name" />
            	  		</div><!--/form-group-->
					</div><!--/box-body-->
					<div id = "bank_msg"></div><!--/post_msg-->
        			<div class="box-footer">
        				<button type = "submit" id = "submit_bank_form" class="btn btn-default pull-right">Crear Banco</button>
        			</div><!--/box-footer-->
				</form>
			</div><!--/box-->
		</div><!--/col-md-6-->

		<div class="col-md-6">
			<div class="box box-default">
				<div class="box-header">
					<h3 class="box-title">Lista de Bancos</h3>
				</div>
				<div class="box-body">
					<div id = "bank_list_msg"></div><!--/post_msg-->
					<table class="table table-bordered table-striped">
						<thead>
							<th>Nombre</th>
						</thead>
						<tbody ng-repeat="elem in vm.bank_list" ng-init="cont = $index;">
							<tr>
								<td>@{{elem.name}}</td>
							</tr>
						</tbody>
					</table>
					<i  id = "bank_loader_list" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
				</div><!--/box-body-->
			</div><!--/box-->
		</div><!--/col-md-6-->

	</div><!--/row-->

@stop