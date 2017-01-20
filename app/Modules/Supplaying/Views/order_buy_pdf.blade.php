<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Orden de Compra</title>
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
    /*border-collapse:collapse;*/
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
		color: #0058A0;
		font-size: 15px;
	}
  .datos{
    font-size: 14px;
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
  
  .borde{
    /*posicion borde esq sup izq, esq sup der, esq inf der ,borde esq inf izq */
  border-radius: 7px 7px 7px 7px !important;
  -moz-border-radius: 7px 7px 7px 7px !important;
  -webkit-border-radius: 7px 7px 7px 7px !important;
  /*border-collapse-radius:collapse;*/
  border: 1px solid #0058A0;
  /*background: #0058A0;
  color: #fff;*/
  }
  .borde_super{
    /*posicion borde esq sup izq, esq sup der, esq inf der ,borde esq inf izq */
    border-radius: 7px 7px 0px 0px;
    -moz-border-radius: 7px 7px 0px 0px;
    -webkit-border-radius: 7px 7px 0px 0px;
    border: 1px solid #0058A0;
  }
  .borde_inf{
    border-radius: 0px 0px 7px 7px;
    -moz-border-radius: 0px 0px 7px 7px;
    -webkit-border-radius: 0px 0px 7px 7px;
    border: 1px solid #0058A0;
  }
  .borde_lateral{
    border-left-color:#0058A0; border-left-width:1px;border-left-style:solid;
    /*border-right-color:#0058A0; border-right-width:1px;border-right-style:solid;*/
  }
  .borde_frontal{
    border-bottom-color:#0058A0;border-bottom-width:1px;border-bottom-style:solid;
    border-top-color:#0058A0;border-top-style:solid;border-top-width:1px; 
  }
  .abajo{
    border-bottom-color:#0058A0;border-bottom-width:1px;border-bottom-style:solid;
  }
</style>
</head>
<body>

<table class ="table" width ="100 %">
  <tr>
	   <th width="30" scope="col" class="left">
        <img src="{{ asset('statics/images/logo_plasticos.png') }}" alt="Plasticos del Golfo Sur" width="210" height="100">
      </th>
      <th colspan="12" scope="col" class ="center folio">
	       ORDEN DE COMPRA
		  </th>
  </tr>
</table>
<table width ="100 %">
  <tr class="encabezado">
    <td colspan="1" rowspan="5" scope="col" class ="center">
      <img src="{{ asset('statics/images/logo.png') }}" alt="Iga">
    </td>
    <td colspan="2" scope="col" class ="left">
      CARRETERA COATZA-MINA KM 7.5 
    </td>
    <td colspan="7" rowspan="3" scope="col" class ="center"> REV 1. JULIO /2014 FO-ABA-08.00</td>
  </tr>
  <tr class="encabezado">
    <td colspan="2" scope="col" class ="left">COL. TIERRA NUEVA CP. 96496</td>
  </tr>
  <tr class="encabezado">
    <td colspan="3" scope="col" class ="left">COATZACOALCOS, VER TEL/FAX : 01(921)21-583-00/01</td>
  </tr>
  <tr class="encabezado">
    <td colspan="2" scope="col" class ="left">EMAIL: compras@igaproductos.com.mx</td>
    <td colspan="7" rowspan="2" scope="col"><p class="borde center datos">ORDEN DE COMPRA<br>No.&nbsp;{{$data->id}}</p></td>
  </tr>
  <tr class="encabezado">
    <td colspan="2" scope="col" class ="left">RFC: PGS990308443</td>

  </tr>
</table>
<!-- inicio
<table class ="borde_super" width ="100 %">
  <tr class="center encabezado list_article">
    <td width ="15%" scope="col" class ="left">SOLICITADO A:</td>
    <td colspan="9" scope="col">&nbsp;</td>
    <th colspan="2" scope="col" >FECHA:</th>
  </tr>
  <tr class="center list_article">
    <td width ="15%" scope="col" class ="left"><p>ENTREGAR EN :</p></td>
    <td colspan="9" scope="col">&nbsp;textoaaaa</td>
    <td colspan="2" scope="col" class = "table_list">09/09/2014</td>
  </tr> 
  <tr class="center list_article">
    <td colspan="10" scope="col" class="left">TEL:</td>
    <th colspan="2" scope="col" class = "franja">CONDICIONES DE PAGO</th>
  </tr>
  <tr class="center list_article">
    <td colspan="10" scope="col" class = "left">No. REQ</td>
    <td colspan="2" scope="col" class = "borde_lateral">CREDITO</td>
  </tr> 
  <tr class="center list_article">
    <table class ="borde_inf" width ="100 %" cellpadding="0" >
      <tr class="center datos franja" >
        <th width="6%" scope="col">PART.</th>
        <th width="11%" scope="col">CANTIDAD</th>
        <th width="10%" scope="col">UNIDAD</th>
        <th colspan="7" scope="col">DESCRIPCION</th>
        <th colspan="1" scope="col">PRECIO U.</th>
        <th colspan="1" scope="col">IMPORTE</th> 
      </tr>  
      <tr class="center table_list" >
        <td width="6%" scope="col">PART.</td>
        <td width="11%" scope="col">CANTIDAD</td>
        <td width="10%" scope="col">UNIDAD</td>
        <td colspan="7" scope="col">DESCRIPCION</td>
        <td colspan="1" scope="col">PRECIO U.</td>
        <td colspan="1" scope="col" class = "borde_lateral">IMPORTE</td> 
      </tr>
      <tr class="center table_list" >
        <td colspan="11" scope="col">&nbsp;</td>
        <td colspan="1" scope="col" class = "borde_lateral">&nbsp;</td>
      </tr>
      <tr class="center encabezado list_article" >
        <td colspan="11" scope="col"  class ="left ">DATOS BANCARIOS</td>
        <td colspan="1" scope="col" class = "borde_lateral table_list">&nbsp; -</td>
      </tr>
      <tr class="center table_list" >
        <td colspan="3" scope="col"  class ="left ">BANCO</td>
        <td colspan="8" scope="col"  class ="left ">BANCO</td>
        <td colspan="1" scope="col" class = "borde_lateral">&nbsp; -</td>
      </tr>
      <tr class="center table_list" >
        <td colspan="3" scope="col"  class ="left ">CUENTA</td>
        <td colspan="8" scope="col"  class ="left ">BANCO</td>
        <td colspan="1" scope="col" class = "borde_lateral">&nbsp; - </td>
      </tr>
      <tr class="center table_list" >
        <td colspan="3" scope="col"  class ="left ">CLAVE INTERBANCARIA</td>
        <td colspan="8" scope="col"  class ="left ">BANCO</td>
        <td colspan="1" scope="col" class = "borde_lateral">&nbsp; - </td>
      </tr>
      <tr class="center table_list" >
        <td colspan="11" scope="col">&nbsp;</td>
        <td colspan="1" scope="col" class = "borde_lateral">&nbsp;</td>
      </tr>
      <tr class="center table_list" >
        <td colspan="11" scope="col" class ="left borde_frontal list_article"><strong>OBSERVACIONES:</strong><br>
          <p class="table_list">PRUEBA TEXTO</p>
        </td>
        <td colspan="1" scope="col" class = "borde_lateral">&nbsp;-</td>
      </tr>
      <tr class="center table_list" >
        <td colspan="11" scope="col">&nbsp;</td>
        <td colspan="1" scope="col" class = "borde_lateral">&nbsp;</td>
      </tr>
      <tr class="center table_list" >
        <td colspan="3" scope="col" rowspan="3" >SELLO IMG</td>
        <td colspan="7" scope="col">AUTORIZO</td>
        <td colspan="1" scope="col" class = "right">&nbsp;SUBTOTAL </td>
        <td colspan="1" scope="col" class = "borde_lateral">&nbsp; - </td>
      </tr>
      <tr class="center table_list" >
        <td colspan="7" scope="col">JOVAN JUAREZ RAMIREZ</td>
        <td colspan="1" scope="col" class = "right">&nbsp;IVA </td>
        <td colspan="1" scope="col" class = "borde_lateral">&nbsp; - </td>
      </tr>
      <tr class="center table_list" >
        <td colspan="7" scope="col">DEPARTAMENTO DE COMPRAS</td>
        <td colspan="1" scope="col" class = "right">&nbsp;TOTAL </td>
        <td colspan="1" scope="col" class = "borde_lateral">&nbsp; - </td>
      </tr>
    </table>
  </tr> 
</table>
fin -->
<table class ="table" width ="100 %">
  <tr class="center encabezado list_article">
    <td width ="15%" scope="col" class ="left abajo">SOLICITADO A:</td>
    <td colspan="9" scope="col" class ="abajo">{{$data->provider_comercial}}</td>
    <th colspan="2" scope="col" >FECHA:</th>
  </tr>
  <tr class="center list_article">
    <td width ="15%" scope="col" class ="left abajo"><p>ENTREGAR EN :</p></td>
    <td colspan="9" scope="col" class ="abajo">&nbsp;{{$data->deliver_place}}</td>
    <td colspan="2" scope="col" class ="borde_lateral">{{$data->date}}</td>
  </tr> 
  <tr class="center list_article">
    <td width ="15%" scope="col" class="left">TEL:</td>
    <td colspan="2" scope="col" class="left">
      @foreach($data->phones AS $t)
      {{$t->phone}}&nbsp;
      {{$t->exts}}&nbsp;
      @endforeach
    </td>
    <td colspan="4" scope="col" class="left">RFC: &nbsp;{{$data->provider_rfc}}</td>
    <td colspan="3" scope="col" class="left">No. Req: &nbsp; {{$data->id}}</td>
    <th colspan="2" scope="col">CONDICIONES DE PAGO</th>
  </tr>
  <tr class="center list_article">
    <td colspan="1" scope="col" class = "left">Domicilio: </td>
    <td colspan="9" scope="col" class = "left">
      {{$data->provider_street}}&nbsp;#{{$data->provider_number}}&nbsp;COL.&nbsp;{{$data->provider_colony}}
      &nbsp;{{$data->provider_city}}&nbsp;{{$data->provider_state}}
    </td>
    <td colspan="2" scope="col" class = "borde_lateral abajo">{{$data->pay_conditions}}</td>
  </tr> 
  <tr class="center encabezado list_article" >
    <td colspan="12" scope="col"  class ="left ">&nbsp;DATOS BANCARIOS</td>
  </tr>
  @foreach($data->provider_banks AS $b) 
  <tr class="center table_list" >
    <td colspan="3" scope="col"  class ="left ">&nbsp;BANCO</td>
    <td colspan="9" scope="col"  class ="left ">{{$b->bank_name}}</td>
  </tr>
  <tr class="center table_list" >
    <td colspan="3" scope="col"  class ="left ">&nbsp;CUENTA</td>
    <td colspan="9" scope="col"  class ="left ">{{$b->no_count}}</td>
  </tr>
  <tr class="center table_list" >
    <td colspan="3" scope="col"  class ="left ">&nbsp;CLAVE INTERBANCARIA</td>
    <td colspan="9" scope="col"  class ="left ">{{$b->inter_key}}</td>
  </tr>
  <tr class="center encabezado list_article" >
    <td colspan="12" scope="col"  class ="left">&nbsp;</td>
  </tr>
  @endforeach
  <tr class="center list_article">
    <table class ="table" width ="100 %" cellpadding="0" >
      <tr class="center datos franja" >
        <th width="6%" scope="col">PART.</th>
        <th width="11%" scope="col">CANTIDAD</th>
        <th width="10%" scope="col">UNIDAD</th>
        <th colspan="7" scope="col">DESCRIPCION</th>
        <th colspan="1" scope="col">PRECIO U.</th>
        <th colspan="1" scope="col">IMPORTE</th> 
      </tr>
      @foreach($data->products AS $pk => $vp)  
      <tr class="center table_list" >
        <td width="6%" scope="col">{{$pk+1}}</td>
        <td width="11%" scope="col">{{$vp->product_pieces}}</td>
        <td width="10%" scope="col">{{$vp->product_unit}}</td>
        <td colspan="7" scope="col">{{$vp->product_name}}&nbsp;{{$vp->product_description}}</th>
        <td colspan="1" scope="col">${{$vp->pesos_price}}</td>
        <td colspan="1" scope="col" class = "borde_lateral">${{$vp->importe}}</td> 
      </tr>
      @endforeach
      <tr class="center table_list" >
        <td colspan="11" scope="col">&nbsp;</td>
        <td colspan="1" scope="col" class = "borde_lateral">&nbsp;</td>
      </tr>
      <!--inicio
        <tr class="center encabezado list_article" >
          <td colspan="11" scope="col"  class ="left ">&nbsp;DATOS BANCARIOS</td>
          <td colspan="1" scope="col" class = "borde_lateral table_list">&nbsp; -</td>
        </tr>
        <tr class="center table_list" >
          <td colspan="3" scope="col"  class ="left ">&nbsp;BANCO</td>
          <td colspan="8" scope="col"  class ="left ">BANCO</td>
          <td colspan="1" scope="col" class = "borde_lateral">&nbsp; -</td>
        </tr>
        <tr class="center table_list" >
          <td colspan="3" scope="col"  class ="left ">&nbsp;CUENTA</td>
          <td colspan="8" scope="col"  class ="left ">BANCO</td>
          <td colspan="1" scope="col" class = "borde_lateral">&nbsp; - </td>
        </tr>
        <tr class="center table_list" >
          <td colspan="3" scope="col"  class ="left ">&nbsp;CLAVE INTERBANCARIA</td>
          <td colspan="8" scope="col"  class ="left ">BANCO</td>
          <td colspan="1" scope="col" class = "borde_lateral">&nbsp; - </td>
        </tr>
      fin-->
      <tr class="center table_list" >
        <td colspan="11" scope="col" class ="left borde_frontal list_article"><strong>&nbsp;OBSERVACIONES:</strong><br>
          <p class="table_list">&nbsp;{{$data->observations}}</p>
        </td>
        <td colspan="1" scope="col" class = "borde_lateral">&nbsp;-</td>
      </tr>
      <tr class="center table_list" >
        <td colspan="11" scope="col">&nbsp;</td>
        <td colspan="1" scope="col" class = "borde_lateral">&nbsp;</td>
      </tr>
      <tr class="center table_list" >
        <td colspan="3" scope="col" rowspan="3" >SELLO IMG</td>
        <td colspan="7" scope="col">AUTORIZO</td>
        <td colspan="1" scope="col" class = "right">SUBTOTAL&nbsp;</td>
        <td colspan="1" scope="col" class = "borde_lateral">${{$data->subtotal}} </td>
      </tr>
      <tr class="center table_list" >
        <td colspan="7" scope="col">Claudia Y. Rodriguez Hernandez</td>
        <td colspan="1" scope="col" class = "right">IVA &nbsp;</td>
        <td colspan="1" scope="col" class = "borde_lateral">${{$data->iva}}</td>
      </tr>
      <tr class="center table_list" >
        <td colspan="7" scope="col">DEPARTAMENTO DE COMPRAS</td>
        <td colspan="1" scope="col" class = "right">TOTAL&nbsp;</td>
        <td colspan="1" scope="col" class = "borde_lateral abajo">${{$data->total}}</td>
      </tr>
      <tr class="center table_list" >
        <td colspan="11" scope="col">&nbsp;</td>
        <td colspan="1" scope="col">
       </td>
      </tr>
    </table>
  </tr> 
</table>

</body>
</html>
