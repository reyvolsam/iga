@extends('layouts.master')

@section('page_name')
  	<h1>Ventas<small>Clientes</small></h1>
  	<ol class="breadcrumb">
	    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
	    <li>Ventas</a></li>
    	<li class = "active">Clientes</a></li>
  	</ol>
@stop

@section('js')
  	{!! HTML::script('statics/js/customs/selling/clients.js') !!}
@stop

@section('content')
<div class="box box-default">
	<div class="box-header with-border">
		<h3 class="box-title">Clientes</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#create_client_modal"><i class="fa fa-plus"></i> Crear Ciente</button>
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
		</div><!--/box-tools-->
	</div><!--/box-header-->
	<div class="box-body" ng-init = "vm.GetClientsList();">
  		<div id = "clients_list_msg"></div><!--/order_produciton_msg-->

  		<table class = "table">
  			<thead>
  				<th>RFC</th>
  				<th>Razon Social</th>
  				<th>Nombre Comercial</th>
  				<th>Tipo de Empresa</th>
  				<th>Tipo de Pago</th>
  				<th>Metodo de Pago</th>
  				<th>Acciones</th>
  			</thead>
  			<tbody ng-repeat = "elem in vm.clients_list" ng-init = "cont = $index">
  				<tr>
  					<td>@{{ elem.rfc }}</td>
  					<td>@{{ elem.razon_social }}</td>
  					<td>@{{ elem.tradename }}</td>
  					<td>@{{ elem.type_company }}</td>
  					<td>@{{ elem.pay_type }}</td>
  					<td>@{{ elem.pay_method }}</td>
  					<td> <button type = "button" class = "btn btn-info btn-xs" ng-click = "vm.EditClient($index);" data-toggle="tooltip" data-placement="top" title="Editar Cliente"><i class = "fa fa-edit"></i></button> <button type = "button" class = "btn btn-danger btn-xs" id = "del_@{{ elem.id }}" ng-click = "vm.DeleteClient($index);" data-toggle="tooltip" data-placement="top" title="Eliminar Cliente"><i class = "fa fa-trash"></i></button> </td>
  				</tr>
  			</tbody>
  		</table>
  		<i  id = "clients_list_loader" class = "fa fa-spinner fa-spin fa-2x col-md-offset-5"></i>
	</div><!--/box-body-->
	<div class = "box-footer">
		<ul class = "pagination pull-right"></ul>
	</div><!--/box-footer-->
</div><!--/box-->


<div class="modal fade" id = "create_client_modal" tabindex = "-1" role="dialog" aria-labelledby="create_client_label" aria-hidden="true" role = "dialog" data-backdrop = "static" data-keyboard = "false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Guardar Cliente</h4>
			</div>
			<form method = "post" ng-submit = "vm.SubmitCreateClient();">
				<div class="modal-body">

					<div class = "row">
						<div class="form-group">
							<div class="col-md-4">
								<label for="type_company" class="control-label">Tipo de Empresa</label>
								<select class = "form-control" id="type_company" name = "type_company" ng-model = "vm.client.type_company">
									<option value = "">Selecciona una Opción...</option>
									<option value = "Fisica">Persona Fisica</option>
									<option value = "Moral">Persona Moral</option>
								</select>
							</div><!--/col-md-4-->
							<div class="col-md-4">
								<label for="pay_type" class="control-label">Tipo de Pago</label>
								<input type = "text" class = "form-control" id = "pay_type" name = "pay_type" ng-model = "vm.client.pay_type" placeholder="Tipo de Pago" readonly />
							</div><!--/col-md-4-->
							<div class="col-md-4">
								<label for="rfc" class="control-label">RFC</label>
								<input type="text" class="form-control" id = "rfc" name = "rfc" ng-model = "vm.client.rfc"  placeholder="RFC" />
							</div><!--/col-md-4-->
						</div><!-- termina form-group -->
					</div><!-- termina row -->
					<br />
					<div class = "row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="razon_social" class="control-label">Razón Social</label>
								<input type="text" class="form-control" id="razon_social" name = "razon_social" ng-model = "vm.client.razon_social" placeholder = "Razón Social" />
							</div><!--/col-md-5-->
							<div class="col-md-5">
								<label for = "tradename" class="control-label">Nombre Comercial</label>
								<input type="text" class="form-control" id="tradename" name = "tradename" ng-model = "vm.client.tradename" placeholder="Nombre de la Compañia" />
							</div><!--/col-md-5-->
						</div><!-- termina form-group -->
					</div><!-- termina row -->	
					<br />
					<div class = "row">
						<div class="form-group">
							<div class="col-md-3">
								<label for="web" class="control-label">Web</label>
								<input type="text" class="form-control" id = "web" name = "web" ng-model = "vm.client.web" placeholder="Pagína Web" />
							</div><!--/col-md-3-->	
							<div class="col-md-3">
								<label for = "pay_method" class = "control-label">Metodo de Pago</label>
								<select class="form-control" id = "pay_method" name = "pay_method" ng-model = "vm.client.pay_method">
									<option value = "">Seleccione una Opción...</option>
									<option value = "Cheque">Cheque</option>
									<option value = "Transferencia">Transferencia</option>
									<option value = "Deposito">Deposito</option>
								</select>
							</div><!--/col-md-3-->
						</div><!--/ form-group -->															
					</div><!-- termina row -->	
					<br />
					@if( Sentry::getUser()->inGroup( Sentry::findGroupByName('root') ) )
						<div class = "row" ng-init = "vm.GetUsersList();">
							<div class="form-group">
								<div class="col-md-3">
									<label for = "user_id" class = "control-label">Ejecutivo de Ventas</label>
									<i id = "select_users" class = "fa fa-spinner fa-spin fa-1x"></i>
									<select class="form-control" id = "user_id" name = "user_id" ng-model = "vm.client.user_id" ng-options = "u_list.id as u_list.name for u_list in vm.users_list">
										<option value = "">Seleccione una Opción...</option>
									</select>
								</div><!--/col-md-3-->
							</div><!--/ form-group -->										
						</div><!--/row-->
					@endif
					<br />
					<div class="panel panel-default">
						<div class="panel-heading">Datos del Contacto</div>
					 	<div class="panel-body">
							<div class="col-md-6">
								<label for = "contact_name" class = "control-label">Nombre</label>
								<input type = "text" class = "form-control" id = "contact_name" name = "contact_name" ng-model = "vm.contact.name" placeholder="Nombre" />
							</div>
							<div class="col-md-6">
								<label for = "contact_cargo" class = "control-label">Cargo</label>
								<input type = "text" class = "form-control" id = "contact_cargo" name = "contact_cargo" ng-model = "vm.contact.cargo" placeholder="Cargo" />
							</div>
							<br />
							<div class="col-md-5">
								<label for = "contact_email" class = "control-label">Email</label>
								<input type = "text" class = "form-control" id = "contact_email" name = "contact_email" ng-model = "vm.contact.email" placeholder="Email" />
							</div>
							<div class="col-md-4">
								<label for = "contact_phone" class = "control-label">Telefono</label>
								<input type = "text" class = "form-control" id = "contact_phone" name = "contact_phone" ng-model = "vm.contact.phone" placeholder="Telefono" />
							</div>
							<div class="col-md-3">
								<label for = "contact_ext" class = "control-label">Extension</label>
								<input type = "text" class = "form-control" id = "contact_ext" name = "contact_ext" ng-model = "vm.contact.ext" placeholder="Ext" />
							</div>
							<div class="col-md-3">
								<br />
								<button type = "button" id = "add_contact_btn" ng-click = "vm.AddContact();" class="btn btn-default"><i class="fa fa-plus"></i>&nbsp;  Agregar Datos</button>
							</div>
							<table class = "table">
								<thead>
									<th>Nombre</th>
									<th>Cargo</th>
									<th>Email</th>
									<th>Telefono</th>
									<th>Ext</th>
									<th>Acciones</th>
								</thead>
								<tbody ng-repeat = "elem in vm.contacts_list" ng-init="cont = $index;">
									<tr>
										<td>@{{ elem.name }}</td>
										<td>@{{ elem.cargo }}</td>
										<td>@{{ elem.email }}</td>
										<td>@{{ elem.phone }}</td>
										<td>@{{ elem.ext }}</td>
										<td> <button type = "button" class = "btn btn-danger btn-xs" ng-click = "vm.DeleteContact($index);" data-toggle="tooltip" data-placement="top" title="Eliminar Contacto"><i class = "fa fa-trash"></i></button> </td>
									</tr>
								</tbody>
							</table>
						</div><!--/panel-box-->
					</div><!--/panel-->

					<div class="panel panel-default">
						<div class="panel-heading">Cuentas Bancarias del Cliente</div>
						<div class="panel-body">
							<div class="col-md-4" ng-init = "vm.GetBanksList();">
								<label for = "bank_name" class="control-label">Banco</label>										
								<i id = "select_banks" class = "fa fa-spinner fa-spin fa-1x"></i>
								<div class="input-group">
									<select class = "form-control" id = "bank_name" name = "bank_name" ng-model = "vm.bank.id" ng-options = "b_list.id as b_list.name for b_list in vm.banks_list_select">
										<option value = "">Seleccione una Opción...</option>
									</select>
								</div><!--/input-group-->
							</div><!--/col-md-4-->
							<div class="col-md-4">
								<label for = "bank_no_account" class="control-label">No. de Cuenta</label>
								<input type="text" class="form-control" id = "bank_no_account" name = "bank_no_account" ng-model = "vm.bank.no_account" placeholder="No. de Cuenta" />
							</div><!--/col-md-4-->
							<div class="col-md-4">
								<br />
								<button type = "button" id = "add_bank_btn" ng-click = "vm.AddBank();" class="btn btn-default"><i class="fa fa-plus"></i>&nbsp;  Agregar Cuenta de Banco</button>
							</div><!--/col-md-4-->
							<table class = "table">
								<thead>
									<th>Banco</th>
									<th>No. de Cuenta</th>
									<th>Acción</th>
								</thead>
								<tbody ng-repeat = "elem in vm.banks_list" ng-init = "cont = $index">
									<tr>
										<td>@{{ elem.name}}</td>
										<td>@{{ elem.no_account}}</td>
										<td> <button type = "button" class = "btn btn-danger btn-xs" ng-click = "vm.DeleteBank($index);" data-toggle="tooltip" data-placement="top" title="Eliminar Banco"><i class = "fa fa-trash"></i></button> </td>
									</tr>
								</tbody>
							</table>
						</div><!--/panel-body-->
					</div><!--/panel-->

					<div class="panel panel-default">
						<div class="panel-heading">Domicilio Fiscal</div>
						<div class="panel-body">
							<div class="form-group">
								<div class="col-md-3">
									<label for="country_fiscal" class="control-label">País</label>
									<select  class = "form-control" id = "country_fiscal" name = "country_fiscal" ng-model = "vm.fiscal.country">
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
												<option value="MX" selected = "selected">México</option>
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
								</div><!--/col-md-3-->
							</div><!--/form-group-->
							<div class="form-group">
								<div class="col-md-3">
									<label for="street_fiscal" class="control-label">Calle</label>
									<input type="text" class="form-control" id = "street_fiscal" name = "street_fiscal" ng-model = "vm.fiscal.street" placeholder="Calle" />
								</div><!--/form-group-->
								<div class="col-md-3">
									<label for="no_ext_fiscal" class="control-label"># Ext</label>
									<input type="text" class="form-control" id = "no_ext_fiscal" name = "no_ext_fiscal" ng-model = "vm.fiscal.no_ext" placeholder="No. Ext" />
								</div><!--/col-md-3-->
								<div class="col-md-3">
									<label for="no_int_fiscal" class="control-label"># Int</label>
									<input type="text" class="form-control" id = "no_int_fiscal" name = "no_int_fiscal" ng-model = "vm.fiscal.no_int" placeholder="No. Int" />
								</div><!--/col-md-3-->
								<div class="col-md-3">
									<label for="colony_fiscal" class="control-label">Colonia</label>
									<input type="text" class="form-control" id = "colony_fiscal" name = "colony_fiscal" ng-model = "vm.fiscal.colony" placeholder="Colonia" />
								</div><!--/col-md-3-->				
								<div class="col-md-3">
									<label for="city_fiscal" class="control-label">Ciudad</label>
									<input type="text" class="form-control" id = "city_fiscal" name = "city_fiscal" ng-model = "vm.fiscal.city" placeholder="Ciudad" />
								</div><!--/col-md-3--> 									
								<div class="col-md-3">
									<label for="state_fiscal" class="control-label">Estado</label>
									<input type="text" class="form-control" id = "state_fiscal" name = "state_fiscal" ng-model = "vm.fiscal.state" placeholder="Estado" />											
								</div><!--/col-md-3-->					
								<div class="col-md-3">
									<label for="cp_fiscal" class="control-label">C.P.</label>
									<input type="text" class="form-control" id = "cp_fiscal" name = "cp_fiscal" ng-model = "vm.fiscal.cp" placeholder = "Código Postal" />
								</div><!--/col-md-3-->
								<div class="col-md-4">
									<label for="contact" class="control-label">Contacto</label>
									<input type="text" class="form-control" id = "contact" name = "contact" ng-model = "vm.fiscal.contact" placeholder="Contacto" />
								</div><!--/col-md-4-->
							</div><!-- termina form-group-->
							<div class="form-group">	
								<div class="col-md-10">
									<br />
									<button type = "button" id = "add_fiscal_btn" ng-click = "vm.AddFiscal();" class="btn btn-default"><i class="fa fa-plus"></i>&nbsp;  Agregar Domicilio Fiscal</button>
								</div><!--/col-md-10-->
							</div><!--/form-group-->
							<table class = "table">
								<thead>
									<th>Calle</th>
									<th># Int</th>
									<th># Ext</th>
									<th>Colonia</th>
									<th>Ciudad</th>
									<th>Estado</th>
									<th>Pais</th>
									<th>C.P.</th>
									<th>Contacto</th>
								</thead>
								<tbody ng-repeat = "elem in vm.fiscal_list" ng-init = "cont = $index">
									<tr>
										<td>@{{ elem.street }}</td>
										<td>@{{ elem.no_int }}</td>
										<td>@{{ elem.no_ext }}</td>
										<td>@{{ elem.colony }}</td>
										<td>@{{ elem.city }}</td>
										<td>@{{ elem.state }}</td>
										<td>@{{ elem.country }}</td>
										<td>@{{ elem.cp }}</td>
										<td>@{{ elem.contact }}</td>
										<td><button type = "button" class = "btn btn-danger btn-xs" ng-click = "vm.DeleteFiscal($index);" data-toggle="tooltip" data-placement="top" title="Eliminar Datos Fiscales"><i class = "fa fa-trash"></i></button></td>
									</tr>
								</tbody>
							</table>
						</div><!--/panel-body-->
					</div><!--/panel-->

					<div class="panel panel-default">
						<div class="panel-heading">Domicilio de Envío</div>
						<div class="panel-body">
							<div class="form-group">
								<div class="col-md-12">
									<label>&nbsp;</label><br>
									<button type ="button" ng-click = "vm.CopyFromFiscal();" class="btn btn-default"><i class="fa fa-copy"></i>&nbsp;  Copiar Direccion Fiscal</button>
								</div><!--/col-md-12-->
								<div class="col-md-3">
									<label for="country_send" class="control-label">País</label>
									<select  class = "form-control"  id = "country_send" name = "country_send" >
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
												<option value="MX" selected = "selected">México</option>
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
								</div><!--/col-md-3-->
								<div class="form-group">
									<div class="col-md-3">
										<label for="street_send" class="control-label">Calle</label>
										<input type="text" class="form-control" id = "street_send" ng-model = "vm.send.street" placeholder="Calle" />
									</div><!--/col-md-3-->
									<div class="col-md-3">
										<label for="street_r1_send" class="control-label">Calle de Referencia 1</label>
										<input type="text" class="form-control" id = "street_r1_send" ng-model = "vm.send.street_r1" placeholder="Calle de Referencia 1" />
									</div><!--/col-md-3-->
									<div class="col-md-3">
										<label for="street_r2_send" class="control-label">Calle de Referencia 2</label>
										<input type="text" class="form-control" id = "street_r2_send" ng-model = "vm.send.street_r2" placeholder="Calle de Referencia 2" />
									</div><!--/col-md-3-->
									<div class="col-md-3">
										<label for="no_ext_send" class="control-label"># Ext</label>
										<input type="text" class="form-control" id = "no_ext_send" ng-model = "vm.send.no_ext" placeholder="No. Ext" />
									</div><!--/col-md-3-->						
									<div class="col-md-3">
										<label for="no_int_send" class="control-label"># Int</label>
										<input type="text" class="form-control" id = "no_int_send" ng-model = "vm.send.no_int" placeholder="No. Int" />
									</div><!--/col-md-3-->					
									<div class="col-md-3">
										<label for="colony_send" class="control-label">Colonia</label>
										<input type="text" class="form-control" id = "colony_send" ng-model = "vm.send.colony" placeholder="Colonia" />
									</div><!--/col-md-3-->					
									<div class="col-md-3">
										<label for="city_send" class="control-label">Ciudad</label>
										<input type="text" class="form-control" id = "city_send" ng-model = "vm.send.city" placeholder="Ciudad" />
									</div><!--/col-md-3-->
									<div class="col-md-3">
										<label for="state_send" class="control-label">Estado</label>
										<input type="text" class="form-control" id = "state_send" ng-model = "vm.send.state" placeholder="Estado" />											
									</div><!--/col-md-3-->
									<div class="col-lg-3 col-md-3">
										<label for="cp_send" class="control-label">C.P.</label>
										<input type="text" class="form-control" id = "cp_send" ng-model = "vm.send.cp" placeholder="Código Postal" />
									</div><!--/col-md-3-->
									<div class="col-lg-3 col-md-3">
										<label for="flete_type_send" class="control-label">Tipo de Flete</label>
										<select class = "form-control" id="flete_type_send" ng-model = "vm.send.flete_type">
											<option value = "">Seleccione una opción...</option>
											<option value = "Facturado">Facturado</option>
											<option value = "Por Cobrar">Por Cobrar</option>
											<option value = "Por Cuenta del Cliente">Por Cuenta del Cliente</option>
											<option value = "Local">Local</option>
										</select>
									</div><!--/col-md-3-->
									<div class="col-lg-3 col-md-3" ng-init = "vm.GetSendlist();">
										<label for = "pack_send" class="control-label">Paquetería</label>
										<i id = "select_send" class = "fa fa-spinner fa-spin fa-1x"></i>
										<div class="input-group">
											<select class = "form-control" id = "pack_send" ng-model = "vm.send.pack" ng-options = "s_list.name as s_list.name for s_list in vm.send_list_select">
												<option value = "">Seleccione una opción...</option>
											</select>
										</div>
									</div><!--/col-md-3-->
									<div class="col-md-3">
										<label for="contact_send" class="control-label">Autorizado Recibir</label>
										<input type="text" class = "form-control" id = "contact_send" name="contact_send" ng-model = "vm.send.contact" />
									</div><!--/col-md-3-->
								</div><!--/form-group -->
								<div class="form-group">
									<div class="col-md-10">
										<br />
										<button type = "button" id = "add_send_btn" ng-click = "vm.AddSend();" class="btn btn-default"><i class="fa fa-plus"></i>&nbsp;  Agregar Domicilio de Envio</button>
									</div><!--/col-md-10-->
								</div><!--/form-group-->
								<table class = "table">
									<thead>
										<th>Calle</th>
										<th>Calle R1</th>
										<th>Calle R2</th>
										<th># Int</th>
										<th># Ext</th>
										<th>Colonia</th>
										<th>Ciudad</th>
										<th>Estado</th>
										<th>Pais</th>
										<th>C.P.</th>
										<th>Tipo de Flete</th>
										<th>Paqueteria</th>
										<th>Autorizado Recibir</th>
										<th>Acciones</th>
									</thead>
									<tbody ng-repeat = "elem in vm.send_list" ng-init = "cont = $index">
										<tr>
											<td>@{{ elem.street }}</td>
											<td>@{{ elem.street_r1 }}</td>
											<td>@{{ elem.street_r2 }}</td>
											<td>@{{ elem.no_int }}</td>
											<td>@{{ elem.no_ext }}</td>
											<td>@{{ elem.colony }}</td>
											<td>@{{ elem.city }}</td>
											<td>@{{ elem.state }}</td>
											<td>@{{ elem.country }}</td>
											<td>@{{ elem.cp }}</td>
											<td>@{{ elem.flete_type }}</td>
											<td>@{{ elem.pack }}</td>
											<td>@{{ elem.contact }}</td>
											<td><button type = "button" class = "btn btn-danger btn-xs" ng-click = "vm.DeleteSend($index);" data-toggle="tooltip" data-placement="top" title="Eliminar Datos de Envio"><i class = "fa fa-trash"></i></button></td>
										</tr>
									</tbody>
								</table>
							</div><!--/form-group-->
						</div><!--/panel-body-->
					</div><!--/panel-->

				</div><!--/modal-body-->
				<div id = "save_client_msg"></div><!-- order_produciton_list_msg-->
				<div class="modal-footer">
					<button type = "button" class="btn btn-danger" id = "cancel_client_btn" ng-click = "vm.CancelClient();" data-dismiss="modal" aria-hidden="true">Cancelar</button>
					<button type = "submit" class = "btn btn-success" id = "submit_client_btn">Guardar Cliente</button>
				</div>
			</form>
		</div><!--/modal-content-->
	</div><!--/modal-dialog-->
</div><!--/modal-->
@stop