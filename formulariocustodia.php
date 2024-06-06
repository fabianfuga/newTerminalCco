<?
include("conexion.php");
$sql="select * from formularioscustodia where fcu_ticket =".$_REQUEST["id"]." order by fcu_id desc limit 0,1";
$res=$link->query($sql);
$fila=mysqli_fetch_array($res);
?>
<!doctype html>
<html lang="es">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link href="includes/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<title></title>
</head>
<body onload="window.print()">
<!--<body>-->
<div class="container">
<div class=" text-center">
<img class="d-block mx-auto mb-4" src="images/logo_nuevo.png" alt="" width="250" height="100">
<h2>Formulario de Entrega de Custodia</h2>
<p class="lead" style='text-align:justify;border:1px solid #000;padding:10px;font-size: 1rem;'>Por medio del presente documento, la persona que suscribe sus datos a continuación declara ante Plaza Terminal Curicó la pérdida del ticket de ingreso de bulto a custodia, instrumento utilizado para el retiro de las especies cuya validez es al potador. Para realizar validación de pertenencia de se solicita la declaración de características y/o contenidos de el o los bultos en cuestión. El interesando mediante la firma de este documento acepta las condiciones antes descritas para la entrega de el o los bultos, no teniendo reclamo u objeción posterior.</p>
</div>

<div class="row">
<div class="col-md-12 order-md-1">
<h4 class="mb-3">Antecedentes de quien retira:</h4>
<form class="needs-validation" novalidate="">
<div class="row">
<div class="col-md-6 mb-3" style="float:left;width:600px;">
<label for="firstName">Nombre</label>
<div style='border-bottom:1px solid #000;'><?=$fila["fcu_nombre"];?></div>
</div>
<div class="col-md-6 mb-3" style="float:left;width:300px;">
<label for="lastName">Rut</label>
<div style='border-bottom:1px solid #000;'><?=$fila["fcu_rut"];?></div>
</div>
</div>
<div class="row">
<div class="col-md-6 mb-3" style="float:left;width:600px;">
<label for="firstName">Dirección</label>
<div style='border-bottom:1px solid #000;'><?=$fila["fcu_direccion"];?></div>
</div>
<div class="col-md-6 mb-3" style="float:left;width:300px;">
<label for="lastName">Teléfono</label>
<div style='border-bottom:1px solid #000;'><?=$fila["fcu_telefono"];?></div>
</div>
</div>

<div class="row">
<div class="col-md-12 mb-3" style="float:left;width:600px;">
<label for="firstName">Fecha y hora de Retiro:</label>
<div style='border-bottom:1px solid #000;'><?=date("d-m-Y H:m");?></div>
</div>
</div>



<div class="row">
<div class="col-md-12 mb-3" style="float:left;width:900px;">
<label for="firstName">Descripción de Bultos</label>
<textarea rows=3 class="form-control" style="resize:none;"><?=$fila["fcu_descripcion"];?></textarea>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-md-12 text-center text-small" style='margin-top:50px;margin-bottom:50px;'>
<div style="float:left;width:300px;border-top:2px solid #989897;height:40px; text-align:center;padding-top:10px;">Firma Custodia</div><div style="float:left;margin-left:300px;width:300px;border-top:2px solid #989897;height:40px; text-align:center;padding-top:10px;">Firma Persona que retira</div>
</div>
</div>
<!--<div class="row" style='width:100%;height:200px;border:1px solid #000'>
</div>-->
</div>
<script src="includes/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
