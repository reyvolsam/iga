					<div class="col-lg-6">
						<div class="file-field input-field">
							<div class="form-group">
								<label for = "technical_file" class="control-label">Ficha Tecnica</label>
								<div id = "wrapper"> 
									<input  nv-file-select = "" uploader="uploader" class = "form-control" type = "file" id = "technical_file" name = "technical_file" size="1" /> 
								</div><!--/wrapper-->
							</div><!--/form-group-->
						</div><!--/file-field-->
					</div><!--/col-lg-6-->


					<div class="panel panel-default">
						<div class="panel-heading">Caracteristicas</div><!--/panel-heading-->
						<div class="panel-body">
							<div class="col-lg-6" id ="ajuste_div">
								<label for = "ajuste" class="control-label">Ajuste</label>
								<select class = "form-control" id = "ajuste" name = "ajuste"></select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="clase_div">
								<label for = "clase" class="control-label">Clase</label>
								<select id = "clase" name = "clase" class = "form-control"></select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="modelo_div">
								<label for = "modelo" class="control-label">Modelo</label>
								<select id = "modelo" name = "modelo" class = "form-control"></select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="color_div">
								<label for = "color" class="control-label">Color</label>
								<select class = "form-control" id = "color" name = "color"></select>
							</div><!--/col-lg-6-->
							<div class="col-lg-6" id ="patitas_div">
								<label for = "patitas" class="control-label">Color de Armazon de Lente</label>
								<select class = "form-control" id = "patitas" name = "patitas"></select>
							</div><!--/col-lg-6-->
						</div><!-- termina panel-body -->
					</div><!-- termina panel -->

							<div class="panel panel-default">
								<div class="panel-heading">Almacen Coatza</div><!--/panel-heading-->
								<div class="panel-body">
									<div class = "col-lg-3">
										<label for = "minimo1" class = "control-label">Min. en Almacen</label>
										<input type = "text" class = "form-control" id="minimo1" name = "minimo1" placeholder = "Minimo" />
									</div><!--/col-lg-3-->
									<div class = "col-lg-3">
										<label for = "maximo1" class = "control-label">Max. en Almacen</label>
										<input type = "text" class = "form-control" id="maximo1" name = "maximo1" placeholder = "Maximo" />
									</div><!--/col-lg-3-->
									<div class = "col-lg-3">
										<label for = "max_pedido1" class = "control-label">Prod. max. por Pedido</label>
										<input type = "text" class = "form-control" id="max_pedido1" name = "max_pedido1" placeholder = "Maximo por Pedido" />
									</div><!--/col-lg-3-->
									<div class ="col-lg-3">
										<label for = "status_producto1" class = "control-label">Estado Prod.</label>
										<select class = "form-control" id = "status_producto1" name = "status_producto1">
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
										<label for = "minimo2" class = "control-label">Min. en Almacen</label>
										<input type = "text" class = "form-control" id="minimo2" name = "minimo2" placeholder = "Minimo" />
									</div><!--/col-lg-3-->
									<div class = "col-lg-3">
										<label for = "maximo2" class = "control-label">Max. en Almacen</label>
										<input type = "text" class = "form-control" id="maximo2" name = "maximo2" placeholder = "Maximo" />
									</div><!--/col-lg-3-->
									<div class = "col-lg-3">
										<label for = "max_pedido2" class = "control-label">Prod. max. por Pedido</label>
										<input type = "text" class = "form-control" id="max_pedido2" name = "max_pedido2" placeholder = "Maximo por Pedido" />
									</div><!--/col-lg-3-->
									<div class ="col-lg-3">
										<label for = "status_producto2" class = "control-label">Estado Prod.</label>
										<select class = "form-control" id = "status_producto2" name = "status_producto2">
											<option value = "activo">ACTIVO</option>
											<option value = "inactivo">INACTIVO</option>
										</select>
									</div><!--/col-lg-3-->
								</div><!--panel body-->
							</div><!--panel-default-->

							<div class="panel panel-default">
								<div class="panel-heading">Informacion del Producto</div><!--/panel-heading-->
								<div class="panel-body">
									<div class="form-group">
										<div class="col-lg-6">
											<label for = "proveedor" class="control-label">Proveedor</label>
											<select id = "proveedor_mp" name = "proveedor_mp" class = "form-control"></select>
										</div><!--/col-lg-6-->
										<div class="col-lg-6">
											<label for = "unidad" class="control-label">Unidad</label>
											<select id = "unidad" name = "unidad" class = "form-control"></select>
										</div><!--/col-lg-6-->
									</div><!--/form-group-->
									<div class = "col-lg-4">
										<label for = "marca" class = "control-label">Marca</label>
										<input type = "text" class="form-control" id = "marca" name = "marca" placeholder = "Marca" />
									</div><!--/col-lg-4-->
									<div class="form-group">
										<div class="col-lg-9">
											<label for = "descripcion" class="control-label">Descripción</label>
											<textarea class="form-control" rows="5" id = "descripcion" name = "descripcion"></textarea>
										</div><!--/col-lg-9-->
										<div class="col-lg-3">
											<label>Uso del Producto</label>
											<div class="radio">
												<label>
													<input type="radio" id="venta_check" name="uso_producto" value="venta" checked>
													a)	Venta
												</label>
											</div><!--/radio-->
											<div class="radio">
												<label>
													<input type="radio" id="interno_check"name="uso_producto"  value="interno">
													b)	Uso Interno
												</label>
											</div><!--/radio-->
										</div><!--/col-lg-3-->
										<div class="col-lg-6">
											<label for="img_producto" class="control-label">Imagen del Producto</label>
											<input type = "file" class = "form-control" id = "img_producto" name = "img_producto" placeholder = "Imagen Producto" />
										</div><!--/col-lg-6-->
									</div><!--/form-group-->
								</div><!--/panel-body-->
							</div><!--/panel-->
