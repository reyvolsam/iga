								<div class = "row">
									<div class="page-header"><h4> Teléfonos</h4></div>
									<div class="col-md-4">
										<label for = "phone" class = "control-label">Teléfono</label>
										<input type = "text" class = "form-control" id = "phone" ng-model = "vm.phones.phone" placeholder = "Teléfono" />
									</div>
									<div class="col-md-4">
										<label for = "exts" class = "control-label">Extensión</label>
										<input type = "text" class = "form-control" id = "exts" ng-model = "vm.phones.exts" placeholder = "Extensión" />
									</div>
									<br />
									<div class="col-md-4">
										<button class = "form-control btn btn-primary" ng-click = "vm.AddPhoneContact();"><i class="icon-plus-sign"></i> Agregar Teléfono</button>
									</div>
									<table class="table table-bordered table-striped">
										<thead>
											<th>Teléfono</th>
											<th>Extensión</th>
											<th>Acciones</th>
										</thead>
										<tbody ng-repeat="elem in vm.provider.phones" ng-init = "cont = $index;">
											<tr>
												<td>@{{vm..phones.phone}} </td>
												<td>@{{vm..phones.exts}} </td>
												<td> <button id = "del_phone_@{{cont}}" class = "btn btn-default btn-xs" data-toggle="tooltip" data-placement="top" title="Eliminar Telefono" ng-click = "vm.DeletePhones();"><i class = "fa fa-trash"></i></button> </td>
											</tr>
										</tbody>
									</table>
								</div><!--/row-->