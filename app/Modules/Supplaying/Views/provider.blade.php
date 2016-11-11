@extends('layouts.master')

@section('page_name')
	@if($provider_type == 'raw_material')
  	<h1>Proveedores<small>Materia Prima</small></h1>
  	@elseif($provider_type == 'finished_provider')
  	<h1>Proveedores<small>Producto Terminado</small></h1>
  	@endif
  	<ol class="breadcrumb">
	    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    	<li class = "active">Proveedores</a></li>
  	</ol>
@stop

@section('js')
	<script type="text/javascript">
		var provider_type = "{{ $provider_type }}";
	</script>
  	{!! HTML::script('statics/js/customs/provider.js') !!}
@stop

@section('content')

<div class="box box-default" ng-init = "vm.GetProviderList();">
  <div class="box-header with-border">
    <h3 class="box-title">Proveedores</h3>
    <div class="box-tools pull-right">
		<button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#provider_modal"><i class="fa fa-plus"></i> Proveedor</button>
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
    </div><!--/box-tools-->
  </div><!--/box-header-->
  <div class="box-body">
  <div id = "provider_list_msg"></div><!--/provider_msg-->
	<ul class = "pagination pull-right">
	</ul>
	<table class = "table table-bordered table-hover">
		<thead>
			<tr>
				<th>No.</th>
				<th>Proveedor</th>
				<th>RFC</th>
				<th>Nombre Comercial</th>
				<th>Domicilio</th>
				<th>Días de Credito</th>
				<th>Lim Crédito</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody ng-repeat="elem in vm.provider_list" ng-init="cont = $index;">
			<tr>
				<td>#@{{elem.id}}</td>
				<td>@{{elem.name}}</td>
				<td>@{{elem.rfc}}</td>
				<td>@{{elem.comercial}}</td>
				<td>@{{elem.street}} #@{{elem.number}} @{{elem.colony}}</td>
				<td>@{{elem.credit_days}}</td>
				<td>@{{elem.credit_limit}}</td>
				<td> <button class = "btn btn-info btn-xs" ng-click = "vm.EditProvider(cont);"><i class = "fa fa-edit"></i></button> <button id = "del_@{{elem.id}}" class = "btn btn-danger btn-xs" ng-click = "vm.DeleteProvider(cont);"><i class = "fa fa-trash"></i></button> </td>
			</tr>
		</tbody>
	</table>
	<i  id = "provider_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
  </div><!--/box-body-->
  <div class = "box-footer">
	<ul class = "pagination pull-right">
	</ul>
  </div><!--/box-footer-->
</div><!--/box-->


<div class="modal fade modal-xl" id = "provider_modal" tabindex = "-1" role="dialog" aria-labelledby="provider_label" aria-hidden="true" role = "dialog" data-backdrop = "static" data-keyboard = "false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Proveedor</h4>
			</div>
			<form method = "post" ng-submit = "vm.SaveProvider();">
				<div class="modal-body">

					<div class = "row">
						<div class="col-md-4">							
							<label for = "proveedor_name" class = "control-label">Razon Social</label>
							<input type = "text" class = "form-control" name = "proveedor_name" id = "proveedor_name" ng-model = "vm.provider.name" placeholder="Razon Social" />
						</div><!--/col-md-4-->
						<div class="col-md-4">
							<label for = "no_proveedor" class = "control-label">Nombre Comercial</label>
							<input type = "text" class="form-control" name = "no_proveedor" id="no_proveedor" ng-model = "vm.provider.comercial" placeholder="Nombre Comercial" />
						</div><!--/col-md-4-->		
						<div class="col-md-4">
							<label for = "rfc" class = "control-label">RFC</label>
							<input type = "text" class="form-control" name = "rfc" id = "rfc" ng-model = "vm.provider.rfc" placeholder = "RFC" />
						</div><!--/col-md-4-->
					</div><!-- termina row -->

						<br />
						<div class="panel panel-default">
							<div class="panel-heading">Domicilio</div>
							<div class="panel-body">
								<div class="col-md-4">
									<label for = "street" class = "control-label">Calle</label>
									<input type = "text" class = "form-control" name = "street" id = "street" ng-model = "vm.provider.street" placeholder = "Calle" />
								</div><!--/col-md-4-->
								<div class="col-md-4">
									<label for = "number" class = "control-label">Número</label>
									<input type = "text" class = "form-control" name = "number" id = "number" ng-model = "vm.provider.number" placeholder = "Número" />
								</div><!--/col-md-4-->
								<div class="col-md-4">
									<label for = "colony" class = "control-label">Colonia</label>
									<input type = "text" class = "form-control" name = "colony" id = "colony" ng-model = "vm.provider.colony" placeholder = "Colonia" />
								</div><!--/col-md-4-->
								<div class="col-md-4">
									<label for = "city" class = "control-label">Ciudad</label>
									<input type = "text" class = "form-control" name = "city" id = "city" ng-model = "vm.provider.city" placeholder = "Ciudad" />
								</div><!--/col-md-4-->
								<div class="col-md-4">
									<label for = "state" class = "control-label">Estado</label>
									<select class = "form-control" name = "state" id = "state" ng-model = "vm.provider.state">
                                        <option value="">Elige una opción</option>
                                        <option value="AGUASCALIENTES">AGUASCALIENTES</option>
                                        <option value="BAJA CALIFORNIA">BAJA CALIFORNIA</option>
                                        <option value="BAJA CALIFORNIA SUR">BAJA CALIFORNIA SUR</option> 
                                        <option value="CAMPECHE">CAMPECHE</option>
                                        <option value="CHIAPAS">CHIAPAS</option>
                                        <option value="CHIHUAHUA">CHIHUAHUA</option>
                                        <option value="COAHUILA DE ZARAGOZA">COAHUILA DE ZARAGOZA</option>
                                        <option value="COLIMA">COLIMA</option>
                                        <option value="DISTRITO FEDERAL">DISTRITO FEDERAL</option>
                                        <option value="DURANGO">DURANGO</option>
                                        <option value="GUANAJUATO">GUANAJUATO</option>
                                        <option value="GUERRERO">GUERRERO</option>
                                        <option value="HIDALGO">HIDALGO</option>
                                        <option value="JALISCO">JALISCO</option>
                                        <option value="MÉXICO">MÉXICO</option>
                                        <option value="MICHOACÁN DE OCAMPO">MICHOACÁN DE OCAMPO</option>
                                        <option value="MORELOS">MORELOS</option>
                                        <option value="NAYARIT">NAYARIT</option>
                                        <option value="NUEVO LEÓN">NUEVO LEÓN</option>
                                        <option value="OAXACA">OAXACA</option>
                                        <option value="PUEBLA">PUEBLA</option>
                                        <option value="QUERÉTARO">QUERÉTARO</option>
                                        <option value="QUINTANA ROO">QUINTANA ROO</option>
                                        <option value="SAN LUIS POTOSÍ">SAN LUIS POTOSÍ</option>
                                        <option value="SINALOA">SINALOA</option>
                                        <option value="SONORA">SONORA</option>
                                        <option value="TABASCO">TABASCO</option>
                                        <option value="TAMAULIPAS">TAMAULIPAS</option>
                                        <option value="TLAXCALA">TLAXCALA</option>
                                        <option value="VERACRUZ DE IGNACIO DE LA LLAVE">VERACRUZ DE IGNACIO DE LA LLAVE</option>
                                        <option value="YUCATÁN">YUCATÁN</option>
                                        <option value="ZACATECAS">ZACATECAS</option>
									</select>
								</div><!--/col-md-4-->
								<div class="col-md-4">
									<label for = "country" class = "control-label">País</label>
									<select  class = "form-control" name = "country" id = "country" ng-model = "vm.provider.country">
										<option value="">Selecciona una Opción</option>
										<option value="AF">Afganistán</option>
										<option value="AL">Albania</option>
										<option value="DE">Alemania</option>
										<option value="AD">Andorra</option>
										<option value="AO">Angola</option>
										<option value="AI">Anguilla</option>
										<option value="AQ">Antártida</option>
										<option value="AG">Antigua y Barbuda</option>
										<option value="AN">Antillas Holandesas</option>
										<option value="SA">Arabia Saudí</option>
										<option value="DZ">Argelia</option>
										<option value="AR">Argentina</option>
										<option value="AM">Armenia</option>
										<option value="AW">Aruba</option>
										<option value="AU">Australia</option>
										<option value="AT">Austria</option>
										<option value="AZ">Azerbaiyán</option>
										<option value="BS">Bahamas</option>
										<option value="BH">Bahrein</option>
										<option value="BD">Bangladesh</option>
										<option value="BB">Barbados</option>
										<option value="BE">Bélgica</option>
										<option value="BZ">Belice</option>
										<option value="BJ">Benin</option>
										<option value="BM">Bermudas</option>
										<option value="BY">Bielorrusia</option>
										<option value="MM">Birmania</option>
										<option value="BO">Bolivia</option>
										<option value="BA">Bosnia y Herzegovina</option>
										<option value="BW">Botswana</option>
										<option value="BR">Brasil</option>
										<option value="BN">Brunei</option>
										<option value="BG">Bulgaria</option>
										<option value="BF">Burkina Faso</option>
										<option value="BI">Burundi</option>
										<option value="BT">Bután</option>
										<option value="CV">Cabo Verde</option>
										<option value="KH">Camboya</option>
										<option value="CM">Camerún</option>
										<option value="CA">Canadá</option>
										<option value="TD">Chad</option>
										<option value="CL">Chile</option>
										<option value="CN">China</option>
										<option value="CY">Chipre</option>
										<option value="VA">Ciudad del Vaticano (Santa Sede)</option>
										<option value="CO">Colombia</option>
										<option value="KM">Comores</option>
										<option value="CG">Congo</option>
										<option value="CD">Congo, República Democrática del</option>
										<option value="KR">Corea</option>
										<option value="KP">Corea del Norte</option>
										<option value="CI">Costa de Marfíl</option>
										<option value="CR">Costa Rica</option>
										<option value="HR">Croacia (Hrvatska)</option>
										<option value="CU">Cuba</option>
										<option value="DK">Dinamarca</option>
										<option value="DJ">Djibouti</option>
										<option value="DM">Dominica</option>
										<option value="EC">Ecuador</option>
										<option value="EG">Egipto</option>
										<option value="SV">El Salvador</option>
										<option value="AE">Emiratos Árabes Unidos</option>
										<option value="ER">Eritrea</option>
										<option value="SI">Eslovenia</option>
										<option value="ES">España</option>
										<option value="US">Estados Unidos</option>
										<option value="EE">Estonia</option>
										<option value="ET">Etiopía</option>
										<option value="FJ">Fiji</option>
										<option value="PH">Filipinas</option>
										<option value="FI">Finlandia</option>
										<option value="FR">Francia</option>
										<option value="GA">Gabón</option>
										<option value="GM">Gambia</option>
										<option value="GE">Georgia</option>
										<option value="GH">Ghana</option>
										<option value="GI">Gibraltar</option>
										<option value="GD">Granada</option>
										<option value="GR">Grecia</option>
										<option value="GL">Groenlandia</option>
										<option value="GP">Guadalupe</option>
										<option value="GU">Guam</option>
										<option value="GT">Guatemala</option>
										<option value="GY">Guayana</option>
										<option value="GF">Guayana Francesa</option>
										<option value="GN">Guinea</option>
										<option value="GQ">Guinea Ecuatorial</option>
										<option value="GW">Guinea-Bissau</option>
										<option value="HT">Haití</option>
										<option value="HN">Honduras</option>
										<option value="HU">Hungría</option>
										<option value="IN">India</option>
										<option value="ID">Indonesia</option>
										<option value="IQ">Irak</option>
										<option value="IR">Irán</option>
										<option value="IE">Irlanda</option>
										<option value="BV">Isla Bouvet</option>
										<option value="CX">Isla de Christmas</option>
										<option value="IS">Islandia</option>
										<option value="KY">Islas Caimán</option>
										<option value="CK">Islas Cook</option>
										<option value="CC">Islas de Cocos o Keeling</option>
										<option value="FO">Islas Faroe</option>
										<option value="HM">Islas Heard y McDonald</option>
										<option value="FK">Islas Malvinas</option>
										<option value="MP">Islas Marianas del Norte</option>
										<option value="MH">Islas Marshall</option>
										<option value="UM">Islas menores de Estados Unidos</option>
										<option value="PW">Islas Palau</option>
										<option value="SB">Islas Salomón</option>
										<option value="SJ">Islas Svalbard y Jan Mayen</option>
										<option value="TK">Islas Tokelau</option>
										<option value="TC">Islas Turks y Caicos</option>
										<option value="VI">Islas Vírgenes (EEUU)</option>
										<option value="VG">Islas Vírgenes (Reino Unido)</option>
										<option value="WF">Islas Wallis y Futuna</option>
										<option value="IL">Israel</option>
										<option value="IT">Italia</option>
										<option value="JM">Jamaica</option>
										<option value="JP">Japón</option>
										<option value="JO">Jordania</option>
										<option value="KZ">Kazajistán</option>
										<option value="KE">Kenia</option>
										<option value="KG">Kirguizistán</option>
										<option value="KI">Kiribati</option>
										<option value="KW">Kuwait</option>
										<option value="LA">Laos</option>
										<option value="LS">Lesotho</option>
										<option value="LV">Letonia</option>
										<option value="LB">Líbano</option>
										<option value="LR">Liberia</option>
										<option value="LY">Libia</option>
										<option value="LI">Liechtenstein</option>
										<option value="LT">Lituania</option>
										<option value="LU">Luxemburgo</option>
										<option value="MK">Macedonia, Ex-República Yugoslava de</option>
										<option value="MG">Madagascar</option>
										<option value="MY">Malasia</option>
										<option value="MW">Malawi</option>
										<option value="MV">Maldivas</option>
										<option value="ML">Malí</option>
										<option value="MT">Malta</option>
										<option value="MA">Marruecos</option>
										<option value="MQ">Martinica</option>
										<option value="MU">Mauricio</option>
										<option value="MR">Mauritania</option>
										<option value="YT">Mayotte</option>
										<option value="MX" selected>México</option>
										<option value="FM">Micronesia</option>
										<option value="MD">Moldavia</option>
										<option value="MC">Mónaco</option>
										<option value="MN">Mongolia</option>
										<option value="MS">Montserrat</option>
										<option value="MZ">Mozambique</option>
										<option value="NA">Namibia</option>
										<option value="NR">Nauru</option>
										<option value="NP">Nepal</option>
										<option value="NI">Nicaragua</option>
										<option value="NE">Níger</option>
										<option value="NG">Nigeria</option>
										<option value="NU">Niue</option>
										<option value="NF">Norfolk</option>
										<option value="NO">Noruega</option>
										<option value="NC">Nueva Caledonia</option>
										<option value="NZ">Nueva Zelanda</option>
										<option value="OM">Omán</option>
										<option value="NL">Países Bajos</option>
										<option value="PA">Panamá</option>
										<option value="PG">Papúa Nueva Guinea</option>
										<option value="PK">Paquistán</option>
										<option value="PY">Paraguay</option>
										<option value="PE">Perú</option>
										<option value="PN">Pitcairn</option>
										<option value="PF">Polinesia Francesa</option>
										<option value="PL">Polonia</option>
										<option value="PT">Portugal</option>
										<option value="PR">Puerto Rico</option>
										<option value="QA">Qatar</option>
										<option value="UK">Reino Unido</option>
										<option value="CF">República Centroafricana</option>
										<option value="CZ">República Checa</option>
										<option value="ZA">República de Sudáfrica</option>
										<option value="DO">República Dominicana</option>
										<option value="SK">República Eslovaca</option>
										<option value="RE">Reunión</option>
										<option value="RW">Ruanda</option>
										<option value="RO">Rumania</option>
										<option value="RU">Rusia</option>
										<option value="EH">Sahara Occidental</option>
										<option value="KN">Saint Kitts y Nevis</option>
										<option value="WS">Samoa</option>
										<option value="AS">Samoa Americana</option>
										<option value="SM">San Marino</option>
										<option value="VC">San Vicente y Granadinas</option>
										<option value="SH">Santa Helena</option>
										<option value="LC">Santa Lucía</option>
										<option value="ST">Santo Tomé y Príncipe</option>
										<option value="SN">Senegal</option>
										<option value="SC">Seychelles</option>
										<option value="SL">Sierra Leona</option>
										<option value="SG">Singapur</option>
										<option value="SY">Siria</option>
										<option value="SO">Somalia</option>
										<option value="LK">Sri Lanka</option>
										<option value="PM">St Pierre y Miquelon</option>
										<option value="SZ">Suazilandia</option>
										<option value="SD">Sudán</option>
										<option value="SE">Suecia</option>
										<option value="CH">Suiza</option>
										<option value="SR">Surinam</option>
										<option value="TH">Tailandia</option>
										<option value="TW">Taiwán</option>
										<option value="TZ">Tanzania</option>
										<option value="TJ">Tayikistán</option>
										<option value="TF">Territorios franceses del Sur</option>
										<option value="TP">Timor Oriental</option>
										<option value="TG">Togo</option>
										<option value="TO">Tonga</option>
										<option value="TT">Trinidad y Tobago</option>
										<option value="TN">Túnez</option>
										<option value="TM">Turkmenistán</option>
										<option value="TR">Turquía</option>
										<option value="TV">Tuvalu</option>
										<option value="UA">Ucrania</option>
										<option value="UG">Uganda</option>
										<option value="UY">Uruguay</option>
										<option value="UZ">Uzbekistán</option>
										<option value="VU">Vanuatu</option>
										<option value="VE">Venezuela</option>
										<option value="VN">Vietnam</option>
										<option value="YE">Yemen</option>
										<option value="YU">Yugoslavia</option>
										<option value="ZM">Zambia</option>
										<option value="ZW">Zimbabue</option>
									</select>
								</div><!--/col-md-4-->
								<div class="col-md-4">
									<label for = "cp" class = "control-label">CP</label>
									<input type = "text" class = "form-control" name = "cp" id = "cp" ng-model = "vm.provider.cp" placeholder = "CP" />
								</div><!--/col-md-4-->
							</div><!-- termina panel-body -->
						</div><!-- termina panel -->

						<div class="panel panel-default">
							<div class="panel-heading">Telefonos</div>
						 	<div class="panel-body">
								<div class="col-md-4">
									<label for = "phone" class = "control-label">Teléfono</label>
									<input type = "text" class = "form-control" id = "phone" ng-model = "vm.phone.phone" placeholder = "Teléfono" />
								</div><!--/col-md-4-->
								<div class="col-md-4">
									<label for = "exts" class = "control-label">Extensión</label>
									<input type = "text" class = "form-control" id = "exts" ng-model = "vm.phone.exts" placeholder = "Extensión" />
								</div><!--/col-md-4-->
								<br />
								<div class="col-md-4">
									<button type = "button" class = "form-control btn btn-primary" ng-Click = "vm.AddPhoneContact();"><i class="icon-plus-sign"></i> Agregar Teléfono</button>
								</div><!--/col-md-4-->
								<table class="table table-bordered table-striped">
									<thead>
										<th>Teléfono</th>
										<th>Extensión</th>
										<th>Acciones</th>
									</thead>
									<tbody ng-repeat="elem in vm.provider.phones" ng-init = "cont = $index;">
										<tr>
											<td>@{{elem.phone}} </td>
											<td>@{{elem.exts}} </td>
											<td> <button id = "del_phone_@{{cont}}" class = "btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Eliminar Telefono" ng-click = "vm.DeletePhone(cont);"><i class = "fa fa-trash"></i></button> </td>
										</tr>
									</tbody>
								</table>
						 	</div><!--/panel-body-->
						</div><!--/panel-->


						<div class="panel panel-default">
							<div class="panel-heading">Contactos</div>
						 	<div class="panel-body">
								<div class="col-md-4">
									<label for = "contact_name" class = "control-label">Nombre contacto</label>
									<input type = "text" class = "form-control" id = "contact_name" ng-model = "vm.contact.name" placeholder = "Nombre Contacto" />
								</div><!--/col-md-4-->
								<div class="col-md-4">
									<label for = "contact_email" class = "control-label">Correo Electronico</label>
									<input type = "text" class = "form-control"  id = "contact_email" ng-model = "vm.contact.email" placeholder = "Correo Electronico del Contacto" />
								</div><!--/col-md-4-->
								<div class="col-md-4">
									<label for = "contact_phone" class = "control-label">Celular</label>
									<input type = "text" class = "form-control" id = "contact_phone" ng-model = "vm.contact.phone" placeholder = "Celular del Contacto" />
								</div><!--/col-md-4-->
								<div class="col-md-4">
									<label for = "contact_cargo" class = "control-label">Cargo</label>
									<input type = "text" class = "form-control" id = "contact_cargo" ng-model = "vm.contact.cargo" placeholder = "Cargo del Contacto" />
								</div><!--/col-md-4-->
								<div class="col-md-4">
									<br />
									<button type = "button" class = "form-control btn btn-primary" ng-click = "vm.AddContact();"><i class="icon-plus-sign"></i> Agregar Contacto </button>
								</div><!--/col-md-4-->
								<table class="table table-bordered table-striped">
									<thead>
										<th>Nombre del Contacto</th>
										<th>Correo Electronico</th>
										<th>Celular</th>
										<th>Cargo</th>
										<th>Acciones</th>
									</thead>
									<tbody ng-repeat = "elem in vm.provider.contacts" ng-init = "cont = $index;">
										<tr>
											<td>@{{elem.name}}</td>
											<td>@{{elem.email}}</td>
											<td>@{{elem.phone}}</td>
											<td>@{{elem.cargo}}</td>
											<td> <button id = "del_contact_@{{cont}}" class = "btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Eliminar Contacto" ng-click = "vm.DeleteContact(cont);"><i class = "fa fa-trash"></i></button> </td>
										</tr>
									</tbody>
								</table>
							</div><!-- termina panel-body -->
						</div><!-- termina panel -->

						<div class="panel panel-default">
							<div class="panel-heading">Condiciones de Crédito</div>
						 	<div class="panel-body">
						 		<div class="col-md-4">
									<label for = "credit_type" class = "control-label">Tipo de Crédito</label>
									<select class = "form-control" name = "credit_type" id = "credit_type" ng-model = "vm.provider.credit_type">
										<option value = "">Selecciona una Opción...</option>
										<option value = "Contado">Contado</option>
										<option value = "Credito">Crédito</option>
									</select>
								</div><!--/col-md-4-->
								<div id ="div_credito">
									<div class="col-md-4">
										<label for = "credit_days" class = "control-label">Días de Crédito</label>
										<input type = "text" class = "form-control" name = "credit_days" id = "credit_days" ng-model = "vm.provider.credit_days" placeholder = "Días de Crédito" />
									</div><!--/col-md-4-->
									<div class="col-md-4">
										<label for = "credit_limit" class = "control-label">Limite de Crédito</label>
										<div class="input-group">
										  <span class="input-group-addon">$</span>
										  <input type = "text" class = "form-control" name = "credit_limit" id = "credit_limit" ng-model = "vm.provider.credit_limit" placeholder = "Limite de Crédito" currency />
										</div><!--/input-group-->
									</div><!--/col-md-4-->
								</div>									
								<div class="col-md-8">
									<label for = "notes" class = "control-label">Notas</label>
									<input type = "text" class = "form-control" name = "notes" id = "notes" ng-model = "vm.provider.notes" placeholder = "Notas" />
								</div><!--/col-md-8-->
							</div><!-- termina panel-body -->
						</div><!-- termina panel -->

						<br />
						<div class="panel panel-default" ng-init = "vm.BankList();">
							<div class="panel-heading">Bancos</div>
						 	<div class="panel-body">
								<div class="col-md-4">
									<label for="bank" class="control-label">Banco</label>
									<i id = "select_bank" class = "fa fa-spinner fa-spin fa-1x"></i>
									<select class = "form-control" id = "bank" name = "bank" ng-model = "vm.bank.id" ng-options="p_bank.id as p_bank.name for p_bank in vm.bank_list">
										<option value = "">Seleccione una Opción...</option>
									</select>
								</div><!--/col-md-4-->
								<div class="col-md-4">
									<label for = "no_count" class = "control-label">No. de Cuenta</label>
									<input type = "text" class = "form-control" id = "no_count" name = "no_count" ng-model = "vm.bank.no_count" placeholder = "No. de Cuenta" />
								</div><!--/col-md-4-->
								<div class="col-md-4">
									<label for = "inter_key" class = "control-label">Clave Interbancaria</label>
									<input type = "text" class = "form-control" id = "inter_key" name = "inter_key" ng-model = "vm.bank.inter_key" placeholder = "Clave Interbancaria" />
								</div><!--/col-md-4-->
								<div class="col-md-4">
									<label for = "branch_office" class = "control-label">Sucursal</label>
									<input type = "text" class = "form-control" id = "branch_office" name = "branch_office" ng-model = "vm.bank.branch_office" placeholder = "Sucursal" />
								</div><!--/col-md-4-->
								<div class="col-md-4">
									<label for = "type_coin" class = "control-label">Tipo Moneda</label>
									<select class = "form-control" id = "type_coin" name = "type_coin" ng-model = "vm.bank.type_coin">
										<option value = "">Selecciona una Opción...</option>
										<option value = "Moneda Nacional">Moneda nacional</option>
										<option value = "Dollar">Dolar</option>
									</select>
								</div><!--/col-md-4-->
								<div class="col-md-4">
									<br />
									<button type = "button" class = "form-control btn btn-primary" ng-Click = "vm.AddBank();"><i class="icon-plus-sign"></i> Agregar Banco al Proveedor </button>
								</div><!--/col-md-4-->
								<table class = "table">
									<thead>
										<th>Banco</th>
										<th>No. de Cuenta</th>
										<th>Clave Interbancaria</th>
										<th>Sucursal</th>
										<th>Tipo de Moneda</th>
										<th>Acciones</th>
									</thead>
									<tbody ng-repeat = "elem in vm.provider.banks.list" ng-init = "cont = $index;">
										<tr>
											<td>@{{elem.name}}</td>
											<td>@{{elem.no_count}}</td>
											<td>@{{elem.inter_key}}</td>
											<td>@{{elem.branch_office}}</td>
											<td>@{{elem.type_coin}}</td>
											<td> <button type = "button" id = "del_bank_@{{cont}}" class = "btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Eliminar Banco" ng-click = "vm.DeleteBank(cont);"><i class = "fa fa-trash"></i></button> </td>
										</tr>
									</tbody>
								</table>
							</div><!-- termina panel-body -->
						</div><!-- termina panel -->
					<div id = "provider_msg"></div>
					<div class="modal-footer">			
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>					
						<button type = "submit" class = "btn btn-success" id = "save_provider_btn">Guardar Proveedor</button>
					</div><!--/modal-footer-->
				</div><!--/modal-body-->

			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->							
@stop