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
@media all {div.saltopagina{display: none;}}
@media print{div.saltopagina{display:block;page-break-before:always;}}
body{width:207px;height:325px;margin:0;padding:0;}
img{border:0;}
.tarjeta{width:171px;height:307px;margin-top:18px;margin-left:18px;}
.titulo-tarjeta{width:170px;text-align:center;font-weight:bold;font-size:12px;}
.glosa{width:170px;text-align:center;font-size:7px;}
.codigo-barras{width:71px;height:181px;margin-top:18px;margin-left:49px;}
.usuariotarjeta{width:170px;margin-top:20px;font-weight:bold;font-size:13px;}
</style>
<body onload="window.print();">
<?

		$nombre=obtenervalor("usuarios","nombre","where id='".$_REQUEST["id"]."'");
		$codigo=obtenervalor("usuarios","codigo","where id='".$_REQUEST["id"]."'");
?>	
<div class="tarjeta saltopagina">
<div class="titulo-tarjeta">PLAZA TERMINAL CURIC&Oacute;</div>
<div class="glosa">IDENTIFICACION DE USUARIO</div>
<div class="codigo-barras"><IMG SRC="cds_codigobarras-vertical.php?claveunica=<?=$codigo;?>"/></div>
<div class="usuariotarjeta"><?=$nombre;?></div>
</div>
</body>
</html>