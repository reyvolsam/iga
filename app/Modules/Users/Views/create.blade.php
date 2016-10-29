@extends('layouts.master')

@section('page_name')
  <h1>Control de Personal<small>Crear Trabajador</small></h1>
  <ol class="breadcrumb">
    <li><a href="{{URL::to('/')}}"><i class="fa fa-dashboard"></i> Principal</a></li>
    <li class = "active">Control de Personal</a></li>
    <li class = "active">Crear Trabajador</a></li>
  </ol>
@stop

@section('js')
  {!! HTML::script('statics/js/lib/jquery.inputmask.js') !!}
  {!! HTML::script('statics/js/lib/jquery.inputmask.date.extensions.js') !!}
  {!! HTML::script('statics/js/customs/create_user.js') !!}
@stop

@section('content')

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title"> Datos de la Requisición</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div><!--/box-tools-->
          </div><!--/box-header-->
          <div class="box-body" ng-init = "vm.LoadDataRequisition();">
            <div id = "req_msg"></div><!--/req_msg-->
            <div class = "col-md-2">
              <label for = "req_id" class = "control-label">ID</label>
              <div class = "input-group has-success">
                <div class="input-group-addon">#</div>
                <input type = "text" name = "req_id" id = "req_id" class = "form-control" ng-model = "vm.requisition_data.id" placeholder = "ID" />
              </div><!--/input-group-->
            </div><!-- termina col-md-4 -->
            <div class = "clearfix"></div>
            <div class = "col-md-4">
              <label for = "date" class = "control-label">Fecha</label>
              <input type = "text" name = "date" id = "date" class = "form-control" ng-model = "vm.requisition_data.date" placeholder = "Fecha" disabled/>
            </div><!-- termina col-md-4 -->
            <div class = "col-md-4">
              <label for = "vacant_information" class = "control-label">Información sobre la Vacante</label>
              <input type = "text" name = "vacant_information" id = "vacant_information" class = "form-control" ng-model = "vm.requisition_data.vacant_information" placeholder = "Información sobre la Vacante" disabled />
            </div><!-- termina col-md-4 -->
            <div class = "col-md-4">
              <label for = "vacant_for" class = "control-label">La Vacante se Produjo Por</label>
              <input type = "text" name = "vacant_for" id = "vacant_for" class = "form-control" ng-model = "vm.requisition_data.vacant_for" placeholder = "La Vacante se Produjo Por" disabled />
            </div><!-- termina col-md-4 -->
            <div class = "col-md-4">
              <label for = "vacant_begin_date" class = "control-label">Fecha en que debe quedar cubierta la Vacante</label>
              <input type = "text" name = "vacant_begin_date" id = "vacant_begin_date" class = "form-control" ng-model = "vm.requisition_data.vacant_begin_date" placeholder = "Fecha en que debe quedar cubierta la Vacante" disabled />
            </div><!-- termina col-md-4 -->
            <div class = "col-md-4">
              <label for = "group_name" class = "control-label">Nombre del Puesto</label>
              <input type = "text" name = "post_name" id = "group_name" class = "form-control" ng-model = "vm.requisition_data.group_name" placeholder = "Nombre del Puesto"  disabled/>
            </div><!-- termina col-md-4 -->
            <div class = "col-md-4">
              <label for = "required_person" class = "control-label">No. Personas Requeridas</label>
              <input type = "text" name = "required_person" id = "required_person" class = "form-control" ng-model = "vm.requisition_data.required_person" placeholder = "No. Personas Requeridas" disabled />
            </div><!-- termina col-md-4 -->
          </div><!--/box-body-->
          <div class="modal-footer">
            <input type = "submit" id = "btn_requisition_data" ng-click = "vm.GetRequisitionData();" class = "btn btn-success" value = "Cargar Datos de   Requisición" />
          </div><!-- termina modal-footer -->
        </div><!--/box-->

        <form method = "post" ng-submit = "vm.CreateUser()">
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Información Personal</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div><!--/box-tools-->
            </div><!--/box-header-->
          
            <div class="box-body">

              <div class = "row">
                <div class = "col-md-4">
                  <label for = "name" class = "control-label">Nombre</label>
                  <input type = "text" name = "name" id = "name" class = "form-control" ng-model = "vm.user.name" placeholder = "Nombre" />
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "first_name" class = "control-label">Apellido Paterno</label>
                  <input type = "text" name = "first_name" id = "first_name" class = "form-control" ng-model = "vm.user.first_name" placeholder = "Apellido Paterno" />
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "last_name" class = "control-label">Apellido Materno</label>
                  <input type = "text" name = "last_name" id = "last_name" class = "form-control" ng-model = "vm.user.last_name" placeholder = " Apellido Materno" />
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "email" class = "control-label">Correo Electronico</label>
                  <input type = "email" name = "email" id = "email" class = "form-control" ng-model = "vm.user.email" placeholder = "Correo Electronico" />
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "nss" class = "control-label">NSS</label>
                  <input type = "text" name = "nss" id = "nss" class = "form-control" ng-model = "vm.user.nss" placeholder = "NSS" />
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "curp" class = "control-label">CURP</label>
                  <input type = "text" name = "curp" id = "curp" class = "form-control" ng-model = "vm.user.curp" placeholder = "CURP" />
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "rfc" class = "control-label">RFC</label>
                  <input type = "text" name = "rfc" id = "rfc" class = "form-control" ng-model = "vm.user.rfc" placeholder = "RFC" />
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "country" class = "control-label">Pais</label>
                    <select  class = "form-control" name = "country" id = "country" ng-model = "vm.user.country">
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
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "state" class = "control-label">Estado</label>
                  <select class = "form-control" name = "state" id = "state" ng-model = "vm.user.state">
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
                  <option value="VERACRUZ DE IGNACIO DE LA LLAVE" selected>VERACRUZ DE IGNACIO DE LA LLAVE</option>
                  <option value="YUCATÁN">YUCATÁN</option>
                  <option value="ZACATECAS">ZACATECAS</option>
                  </select>
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "city" class = "control-label">Ciudad</label>
                  <input type = "text" name = "city" id = "city" class = "form-control" ng-model = "vm.user.city" placeholder = "Ciudad" />
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "colony" class = "control-label">Colonia</label>
                  <input type = "text" name = "colony" id = "colony" class = "form-control" ng-model = "vm.user.colony" placeholder = "Colonia" />
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "street" class = "control-label">Calle</label>
                  <input type = "text" name = "street" id = "street" class = "form-control" ng-model = "vm.user.street" placeholder = "Calle" />
                </div><!-- termina col-md-4 -->
                <div class = "col-md-2">
                  <label for = "number" class = "control-label">Número</label>
                  <div class="input-group">
                    <div class="input-group-addon">#</div>
                    <input type = "text" name = "number" id = "number" class = "form-control" ng-model = "vm.user.number" placeholder = "Número" />
                  </div><!-- termina input-group -->
                </div><!-- termina col-md-2 -->
              </div><!--/row-->

            </div><!-- /.box-body -->
          </div><!-- /.box -->

          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Informacion Laboral</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div><!--/box-tools-->
            </div><!--/box-header-->
          
            <div class="box-body">

              <div class = "row">
                <div class = "col-md-4">
                  <label for = "engagement_date" class = "control-label">Fecha Contratación</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div><!--/input-group-addon-->
                    <input type = "text" name = "engagement_date" id = "engagement_date" class = "form-control" ng-model = "vm.user.engagement_date" placeholder = "Fecha Contratación" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask />
                  </div><!--/input-group-->
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "period_pay" class = "control-label">Periodicidad de Pago</label>
                  <select name = "period_pay" id = "period_pay" class = "form-control" ng-model = "vm.user.period_pay">
                    <option value = "">Selecciona una Opción...</option>
                    <option value = "Semanal">Semanal</option>
                    <option value = "Quincenal">Quincenal</option>
                    <option value = "Catorcenal">Catorcenal</option>
                    <option value = "Mensual">Mensual</option>
                  </select>
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "sdi" class = "control-label">SDI</label>
                    <div class="input-group">
                      <div class="input-group-addon">$</div>
                      <input type = "text" name = "sdi" id = "sdi" class = "form-control" ng-model = "vm.user.sdi" placeholder = "SDI"/>
                  </div><!-- termina input-group -->
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "contract_type" class = "control-label">Tipo de Contrato</label>
                  <select name = "contract_type" id = "contract_type" class = "form-control" ng-model = "vm.user.contract_type">
                    <option value = "">Selecciona una Opción...</option>
                    <option value = "Base">Base</option>
                    <option value = "Temporal">Temporal</option>
                  </select>
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "working_day_type" class = "control-label">Tipo de Jornada</label>
                  <select name = "working_day_type" id = "working_day_type" class = "form-control" ng-model = "vm.user.working_day_type">
                    <option value = "">Selecciona una Opción...</option>
                    <option value = "Diurna">Diurna</option>
                    <option value = "Diario Dia">Diario Dia</option>
                  </select>
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "area" class = "control-label">Area</label>
                  <select name = "area" id = "area" class = "form-control" ng-model = "vm.user.area">
                    <option value = "">Selecciona una Opción...</option>
                    <option value = "Area 1">Area 1</option>
                  </select>
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "group_id" class = "control-label">Puesto</label>
                  <i id = "select_loader_list" class = "fa fa-spinner fa-spin fa-1x"></i>
                  <select name = "group_id" id = "group_id" class = "form-control" ng-model = "vm.user.group_id" ng-options="p_list.id as p_list.slug for p_list in vm.group_list">
                    <option value = "">Selecciona una Opción...</option>
                  </select>

                </div><!-- termina col-md-4 -->
                <div class = "col-md-2">
                  <label for = "employee_number" class = "control-label">No. Empleado</label>
                  <input type = "number" name = "employee_number" id = "employee_number" class = "form-control" ng-model = "vm.user.employee_number" />
                </div><!-- termina col-md-2 -->
              </div><!-- termina row -->

            </div><!-- /.box-body -->
          </div><!-- /.box -->

          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Informacion Bancaria</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div><!--/box-tools-->
            </div><!--/box-header-->
          
            <div class="box-body">

              <div class = "row">
                <div class = "col-md-4">
                  <label for = "bank" class = "control-label">Banco</label>
                  <select name = "bank" id = "bank" class = "form-control" ng-model = "vm.user.bank">
                    <option value = "">Seleccione una opción...</option>
                    <option value = "Banamex">Banamex</option>
                    <option value = "Banorte">Banorte</option>
                    <option value = "Bancomer">Bancomer</option>
                    <option value = "HSBC">HSBC</option>
                    <option value = "Santander">Santander</option>
                    <option value = "Scotiabank inverlat">Scotiabank inverlat</option>
                    <option value = "Inbursa">Inbursa</option>
                    <option value = "Bajío">Bajío</option>
                    <option value = "Banco Azteca">Banco Azteca</option>
                    <option value = "Banjercito">Banjercito</option>
                  </select>
                </div><!-- termina col-md-4 -->
                <div class = "col-md-4">
                  <label for = "key_bank" class = "control-label">Clave</label>
                  <input type = "text" name = "key_bank" id = "key_bank" class = "form-control" ng-model = "vm.user.key_bank" />
                </div><!-- termina col-md-4 -->
              </div><!--/row-->

            </div><!-- /.box-body -->
          </div><!-- /.box -->

          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Documentos Presentados</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div><!--/box-tools-->
            </div><!--/box-header-->
          
            <div class="box-body">

              <div class = "row">
                <div class = "col-md-2">
                  <label for = "identification_paper" class = "control-label">Credencial de Elector</label>
                  <select name = "identification_paper" id = "identification_paper" class = "form-control" ng-model = "vm.user.identification_paper">
                    <option value = "">Seleccione una opción...</option>
                    <option value = "SI">SI</option>
                    <option value = "NO">NO</option>
                  </select>
                </div><!-- termina col-md-2 -->
                <div class = "col-md-2">
                  <label for = "curp_paper" class = "control-label">CURP</label>
                  <select name = "curp_paper" id = "curp_paper" class = "form-control" ng-model = "vm.user.curp_paper">
                    <option value = "">Seleccione una opción...</option>
                    <option value = "SI">SI</option>
                    <option value = "NO">NO</option>
                  </select>
                </div><!-- termina col-md-2 -->
                <div class = "col-md-2">
                  <label for = "born_paper" class = "control-label">Acta de Nacimiento</label>
                  <select name = "born_paper" id = "born_paper" class = "form-control" ng-model = "vm.user.born_paper">
                    <option value = "">Seleccione una opción...</option>
                    <option value = "SI">SI</option>
                    <option value = "NO">NO</option>
                  </select>
                </div><!-- termina col-md-2 -->
                <div class = "col-md-3">
                  <label for = "photografy" class = "control-label">2 Fotografias a color tamaño infantil</label>
                  <select name = "photografy" id = "photografy" class = "form-control" ng-model = "vm.user.photografy">
                    <option value = "">Seleccione una opción...</option>
                    <option value = "SI">SI</option>
                    <option value = "NO">NO</option>
                  </select>
                </div><!-- termina col-md-4 -->
                <div class = "col-md-3">
                  <label for = "home_paper" class = "control-label">Comprobante de Domicilio</label>
                  <select name = "home_paper" id = "home_paper" class = "form-control" ng-model = "vm.user.home_paper">
                    <option value = "">Seleccione una opción...</option>
                    <option value = "SI">SI</option>
                    <option value = "NO">NO</option>
                  </select>
                </div><!-- termina col-md-4 -->
                <div class = "col-md-2">
                  <label for = "nss_paper" class = "control-label">NSS</label>
                  <select name = "nss_paper" id = "nss_paper" class = "form-control" ng-model = "vm.user.nss_paper">
                    <option value = "">Seleccione una opción...</option>
                    <option value = "SI">SI</option>
                    <option value = "NO">NO</option>
                  </select>
                </div><!-- termina col-md-2 -->
                <div class = "col-md-3">
                  <label for = "study_paper" class = "control-label">Certificado de Estudios</label>
                  <select name = "study_paper" id = "study_paper" class = "form-control" ng-model = "vm.user.study_paper">
                    <option value = "">Seleccione una opción...</option>
                    <option value = "SI">SI</option>
                    <option value = "NO">NO</option>
                  </select>
                </div><!-- termina col-md-2 -->
              </div><!--/row-->

            </div><!-- /.box-body -->
          </div><!-- /.box -->

          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Datos de acceso al Sistema</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div><!--/box-tools-->
            </div><!--/box-header-->
          
            <div class="box-body">
            <p><b>Contraseña de Usuario: </b>Iga2016</p>
            <span class="label label-danger">Indicar al Trabajador que tiene que hacer cambio de contraseña por una personal, para fines de seguridad.</span>
            </div><!--/box-body-->
            <div id = "user_msg"></div><!--/user_msg-->
            <div class="modal-footer">
              <button type = "submit"  id = "btn_create_employee" class = "btn btn-success">Guardar Empleado</button>
            </div><!-- termina modal-footer -->
          </div><!--/box-->
        </form>

@stop