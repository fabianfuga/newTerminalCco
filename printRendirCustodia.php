<?
include("conexion.php");
include("funciones.php");
?>
<!DOCTYPE html>
<html>
<head>


<style type="text/css">
*{font-family: \'Source Sans Pro\',\'Helvetica Neue\',Helvetica,Arial,sans-serif;font-size:7px;}
@media all {div.saltopagina{display: none;}}
@media print{div.saltopagina{display:block;page-break-before:always;}}

table td{height:14px;padding:0;}
.logo{max-width:80%;height:80px;margin-left:10%;text-align:center;}
.logo img{max-height:90%;}
.iot{
	font-size:12px;
	font-weight: bold;
	text-align:center;
	color:#000;	
	padding:5px;
	border: 2px solid #000;
}
.iot label{display:block;width:100%;}
.iventa{width:100%; overflow:hidden; margin-top:10px;}
.col6{width:50%; float:left;overflow:hidden;font-size:12px } 
.icliente{width:100%; display:block;overflow:hidden;}
.col12{width:100%;display:block;overflow:hidden;}
.ititulo, .itotales{width:100%;margin-top:10px;overflow:hidden;}

.col3{width:25%; float:left;}
.itotales .col3{font-size:12px !important;}
.left6{margin-left:50%;}
.icodigo{width:100%; overflow:hidden; margin-top:10px; text-align:center;}

.tbreceta{font-size:10px;width:100%;color:#000;}
.tbreceta td{border:1px solid #000;padding:2px;}

.icomprobante {width:100%; overflow:hidden;}
.icomprobante label{display:block; font-size:12px !important;width:90%; margin-left:5%; overflow:hidden; margin-top:5px;}
.rayafirma{border-bottom:2px solid #000;}


.tbgarantia{width:100%;color:#000;}
.tbgarantia td{font-size:11px  !important;padding:1px;}
.raya{border-bottom:1px solid #000;}

.tbmontos{width:100%;color:#000;}
.tbmontos td{border:1px solid #000;font-size:11px  !important;padding:1px;}
</style>

</head>
<body onload="window.print();">

<?
$res=[];
$idcajero=$_REQUEST["idcajero"];
$cajero=obtenervalor("usuarios","nombre","where id=".$idcajero."");
/**** total recaudacion de cajero ******/
$suma = $link->query("select sum(monto) as total from movimientos where tipo=1 && estado=0 && usuario=".$idcajero."");
$ressuma=mysqli_fetch_array($suma);
$debe=$ressuma["total"];
if(empty($debe)){
$res["error"]=true;

}else{
$res["error"]=false;
$recaudacion=enpesos($debe);

$res["recaudacion"]=$debe;

/***** PRIMER REGISTRO DE CAJERO *************/
$sqlprimero="select * from movimientos where tipo=1 and usuario='".$idcajero."'&& estado=0 order by fecha asc limit 0,1";
$resprimero=$link->query($sqlprimero);
while($filprimero=mysqli_fetch_array($resprimero)){$primero=devfechal($filprimero["fecha"]);$fechaprimero=solofechag($filprimero["fecha"]);}

$res["primero"]=$primero;
$res["fechaprimero"]=$fechaprimero;

/****** ULTIMO REGISTRO DE CAJERO **************/
$sqlultimo="select * from movimientos where tipo=1 && usuario='".$idcajero."' && estado=0 order by fecha desc limit 0,1";
$resultimo=$link->query($sqlultimo);
while($filultimo=mysqli_fetch_array($resultimo)){$ultimo=devfechal($filultimo["fecha"]);$fechaultimo=solofechag($filultimo["fecha"]);}

$res["ultimo"]=$ultimo;
$res["fechaultimo"]=$fechaultimo;

/******* TABLA DETALLES DE RECARGAS CAJERO **********************/
	
}	

?>
<div class="saltopagina">
<div class="col12"><div class="col6">Cajero</div><div class="col6"><?=$cajero;?></div></div>
<div class="col12"><div class="col6">Primera Recarga</div><div class="col6"><?=$primero;?></div></div>
<div class="col12"><div class="col6">Ultima Recarga</div><div class="col6"><?=$ultimo;?></div></div>
<div class="col12"><div class="col6">Recaudación</div><div class="col6">$<?=$recaudacion;?></div></div>
<div class="col12" style="margin:5 0 5 0;"><hr></div>
<div class="col12"><div class="col6">Cliente</div><div class="col6">Total Recargas</div></div>
<div class="col12" style="margin-top:5px;">&nbsp;</div>
<?
$sqlmultiple="SELECT  cliente, sum(monto) as total from movimientos where  tipo=1 && estado=0 && usuario='".$idcajero."' group by cliente";
$resmultiple=mysqli_query($link,$sqlmultiple)or die(mysqli_error());
$totalfilas=mysqli_num_rows($resmultiple);
if($totalfilas > 0){
while($filmultiple=mysqli_fetch_array($resmultiple)){
$idclient=$filmultiple["cliente"];
$client=obtenervalor("clientes","nombre","where id='".$idclient."'");
$cash="$".enpesos($filmultiple["total"]);
?>
<div class="col12"><div class="col6"><?=$client;?></div><div class="col6"><?=$cash;?></div></div>
<?
}
}
else{
?>
<div class="col12">Cajero no registra recargas.</div>
<?	
}
?>

</div>

</body>
</html>