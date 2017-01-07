<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>REQUISICION DE MATERIALES Y/O SERVICIOS</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<style>
	body {
		color: #222;
    
		/*margin: 0 auto;*/
    margin: -10px 0 0 0;
    /*padding: -50px;*/
  }
	.table {
		/*padding:0.2em 1.2em;*/
		border-collapse:collapse;
    border:  1px solid #0058A0;
	}
	th {
		/*padding: 0.2em 0.3em;*/
    border-collapse:collapse;
    border: 1px solid #0058A0;
	}

	.encabezado{
		font-size: 12px;
		color: #00182D;
		text-align: left;
		font-weight: bold;
		/*padding:0.2em  0.6em 0.2em 0.6em;*/
	}
  .list_article{
    font-size: 15px;
  }

	.folio {
		color: #888;
		font-size: 15px;
	}

  .table_list{
    font-size: 13px;
  }
	.franja {
		background: #0058A0;
		color: #fff;
		/*padding: 1.4em 0.4em;*/
	}
	.left {
		text-align: left;
	}
	.right {
		text-align: right;
	}
	.center {
		text-align: center;
	}
  .datos{
    font-size: 14px;
  }
</style>
</head>
<body>

<table class ="table" width ="100 %">
  <tr>
	   <th width="30" scope="col" class="left">
        <img src="{{ asset('statics/images/logo_plasticos.png') }}" alt="Plasticos del Golfo Sur" width="210" height="100">
      </th>
      <th colspan="16" scope="col" class ="right folio">
	       REQUISICION DE MATERIALES Y/O SERVICIOS
         <img src="{{ asset('statics/images/logo.png') }}" alt="Plasticos del Golfo Sur" width="120" height="100">
		  </th>
  </tr>
</table>
<table width ="100 %">
  <tr class="encabezado">
    <td colspan="6" scope="col" class ="left">REV 1. JUNIO /2014</td>
    <td colspan="6" scope="col" class ="right">FO-ABA-07.00</td>
  </tr>
  <tr class="center encabezado">
    <td colspan="1" scope="col">FECHA<br>SOLICITADA:</td>
    <td colspan="1" scope="col">FECHA<br>REQUERIDA:</td>
    <td colspan="8" scope="col">DEPARTAMENTO</td>
    <td colspan="2" scope="col">No. REQ.:</td>
  </tr> 
  <tr class="center encabezado">
    <td colspan="1" scope="col" class = "datos">{{$data->requested_date}}</td>
    <td colspan="1" scope="col" class = "datos">{{$data->required_date}}</td>
    <td colspan="8" scope="col" class = "datos">{{$data->group_name}}</td>
    <td colspan="2" scope="col" class = "datos">#{{$data->id}}</td>
  </tr>
</table>
<br>
<table class ="table" width ="100 %" >
  <tr class="center encabezado franja">
    <th width="12" scope="col">PART.</th>
    <th width="25" scope="col">PZAS.<br> REQUERIDAS</th>
    <th colspan="1" scope="col">UNIDAD</th>
    <th colspan="8" scope="col">DESCRIPCION</th>
    <th colspan="1" scope="col">PRECIO UNITARIO</th>
    <th colspan="1" scope="col">IMPORTE</th> 
  </tr>
  @foreach($data->products AS $pk => $vp)
  <tr class="center list_article">
    <th width="12" scope="col"><p>#{{$pk+1}}</p></th>
    <th width="25" scope="col">{{$vp->product_pieces}}</th>
    <th colspan="1" scope="col">{{$vp->product_unit}}</th>
    <th colspan="8" scope="col">{{$vp->product_name}}&nbsp;{{$vp->product_description}}</th>
    <th colspan="1" scope="col">${{$vp->pesos_price}}</th>
    <th colspan="1" scope="col">${{$vp->importe}}</th>
  </tr>
  @endforeach
  <!--<tr class="center list_article">
    <th width="12" scope="col"> <p>2</p></th>
    <th width="25" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="8" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
  </tr>
  <tr class="center list_article">
    <th width="12" scope="col"><p>3</p> </th>
    <th width="25" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="8" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
  </tr>
  <tr class="center list_article">
    <th width="12" scope="col"><p>4</p></th>
    <th width="25" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="8" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
  </tr>
  <tr class="center list_article">
    <th width="12" scope="col"><p>5</p></th>
    <th width="25" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="8" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
  </tr>
  <tr class="center list_article">
    <th width="12" scope="col"><p>6</p></th>
    <th width="25" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="8" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
  </tr>
  <tr class="center list_article">
    <th width="12" scope="col"><p>7</p></th>
    <th width="25" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="8" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
  </tr>
<tr class="center list_article">
    <th width="12" scope="col"><p>8</p></th>
    <th width="25" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="8" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
  </tr>
<tr class="center list_article">
    <th width="12" scope="col"><p>9</p></th>
    <th width="25" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="8" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
  </tr>
<tr class="center list_article">
    <th width="12" scope="col"><p>10</p></th>
    <th width="25" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="8" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
    <th colspan="1" scope="col">&nbsp;</th>
  </tr>-->

  <tr class="center list_article">
    <td colspan="11" scope="col">&nbsp;</td>
    <th colspan="1" scope="col" class = "right">SUB-TOTAL</th>
    <th colspan="1" scope="col">${{$data->subtotal}}</th>
  </tr>
  <tr class="center list_article">
    <td colspan="11" scope="col">&nbsp;</td>
    <th colspan="1" scope="col" class = "right">IVA</th>
    <th colspan="1" scope="col">${{$data->iva}}</th>
  </tr>
  <tr class="center list_article">
    <td colspan="11" scope="col">&nbsp;</td>
    <th colspan="1" scope="col" class="right">TOTAL</th>
    <th colspan="1" scope="col">${{$data->total}}</th>
  </tr>
  <tr class="center encabezado" >
    <th colspan="13" scope="col">USO DE LO REQUERIDO</th>
  </tr>
  <tr class="center list_article">
    <th colspan="13" scope="col" class="left "><p>{{$data->use}}</p></th>
  </tr>
  <tr class="center encabezado">
    <th colspan="13" scope="col">OBSERVACIONES</th>
  </tr>
  <tr class="center list_article">
    <th colspan="13" scope="col" class="left "><p>{{$data->observations}}</p></th>
  </tr> 
</table>
<br>
<table width ="100 %">
  <tr class="center encabezado">
    <td colspan="4" scope="col">SOLICITA<br> {{$data->name}} {{$data->first_name}} {{$data->last_name}}  </td>
    <td colspan="4" scope="col">DEPARTAMENTO<br>Claudia Y. Rodriguez Hernandez</td>
    <td colspan="4" scope="col">AUTORIZA<br> Ma. DELA PAZ TORRES</td>
  </tr>
  <tr class="center encabezado">
    <td colspan="12" scope="col">&nbsp;</td>
  </tr> 
  <tr class="center encabezado">
    <td colspan="12" scope="col">ACEPTA SERVICIO Y/O INSUMO</td>
  </tr> 
  <tr class="center encabezado">
    <td colspan="12" scope="col"><hr width="30%" /></td>
  </tr> 
  <tr class="center encabezado">
    <td colspan="12" scope="col">NOMBRE Y FIRMA</td>
  </tr> 
</table>

</body>
</html>
