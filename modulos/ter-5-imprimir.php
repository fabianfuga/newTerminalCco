
<html>
<head>
	<title>Tarjetas</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>
<script type="text/javascript">
$(document).ready(function() { 
$(".navbar").css("display","none");
$("footer").css("display","none");
}); 
</script>
<style>
body{width:207px;height:325px;margin:0;padding:0;}
img{border:0;}
.tarjeta{width:171px;height:307px;margin-top:18px;margin-left:18px;}
.titulo-tarjeta{width:170px;text-align:center;font-weight:bold;font-size:12px;}
.glosa{width:170px;text-align:center;font-size:7px;}
.codigo-barras{width:71px;height:181px;margin-top:18px;margin-left:49px;}
.bus{width:170px;margin-top:30px;font-weight:bold;font-size:13px;text-transform:uppercase; text-align:center;}
.cliente{width:170px;margin-top:0;font-size:11px;text-align:center;font-weight:bold;}
</style>
<body onload="window.print();">
<?
	$sql="select * from tarjetas where idtarjeta='".$_REQUEST["id"]."'";
	$resulta=$link->query($sql);
	while($fila=mysqli_fetch_array($resulta)){
		
		$codigotarjeta=$fila["codigo"];
		$codigo=substr ($codigotarjeta, 0, strlen($codigotarjeta) - 1);
		$cliente=obtenervalor("clientes","nombre","where id='".$fila['idcliente']."'");
		$tipo=obtenervalor("tipotransaccion","ntipotransaccion","where idtipotransaccion='".$fila['tipotransaccion']."'");
		$bus=obtenervalor("buses","patentebus","where idbus='".$fila['idpatente']."'");
		
		
	}
?>	
<div class="tarjeta">
<div class="titulo-tarjeta">PLAZA TERMINAL CURIC&Oacute;</div>
<div class="glosa">CREDENCIAL DE INDENTIFICACION DE BUSES</div>
<div class="codigo-barras"><IMG SRC="cds_codigobarras-vertical.php?claveunica=<?=$codigo;?>"/></div>
<div class="bus"><?=$bus;?></div>
<div class="cliente"><?=$cliente;?></div>
</div>
</body>
</html>