<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Acceso a Plaza Terminal Curic&oacute;</title>
<link href="includes/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link href="css/chosen.css" rel="stylesheet">
<link href="includes/fontawesome/css/all.css" rel="stylesheet">
<link href="css/terminal.css?<?=date("U");?>" rel="stylesheet">
<link href="css/login.css?<?=date("U");?>" rel="stylesheet">

<script src="includes/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="includes/jquery-3.7.1.min.js"></script>
<script src="includes/moment.min.js" type="text/javascript"></script>
<script src="includes/daterangepicker.js" type="text/javascript"></script>
<script src="includes/chosen.jquery.js" type="text/javascript"></script>
<script src="includes/fontawesome/js/all.js" type="text/javascript"></script>

</head>
<body>
<div class="container">
<div class="modal" id="mlogin">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h6 class="modal-title"></h6>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
<!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>-->
</div>
<div class="modal-body"></div>
<div class="modal-footer">
</div>
</div>
</div>
</div>

<div class="row justify-content-start">
<div class="col-4 offset-md-4 mt50">
<main class="form-signin w-100 m-auto">
<form id="logologin">
<img class="mb-4" src="images/logo.png" >
<div class="row">
<div class="col-6"><h1 class="h3 mb-3 fw-normal">Identificaci&oacute;n</h1></div>
<div class="col-6"><button type="button" class="btn btn-primary float-end" onclick="cambiaracceso(2,this);">Formulario</button></div>

</div>

<input type="hidden" id="tipoacceso" value=1>

<div id="contarjeta">
<div class="form-floating">
<input type="text" class="form-control" id="tarjeta" placeholder="Código Tarjeta">
<label for="usuario">Código Tarjeta</label>
</div>
</div>

<div id="sintarjeta" class="oculto">
<div class="form-floating">
<input type="text" class="form-control" id="rut" placeholder="Rut">
<label for="usuario">Rut</label>
</div>
<div class="form-floating mt10">
<input type="password" class="form-control" id="clave" placeholder="Password">
<label for="clave">Password</label>
</div>
</div>

<!--<div class="form-check text-start my-3">
<input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
<label class="form-check-label" for="flexCheckDefault">
Remember me
</label>
</div>-->
<button class="btn btn-secondary w-100 my-3 btn-lg" type="button" onclick="login(this);">Ingresar</button>
<!--<p class="mt-5 mb-3 text-body-secondary">© 2017–2023</p>-->
</form>

</main> 
</div>
</div>
</div> 

<script src="includes/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="includes/jquery-3.7.1.min.js"></script>
<script>
let tipoingreso=1;

$(function(){
$("#tarjeta").focus();
$("#tipoacceso").val(tipoingreso);
});

$('#mlogin').on('hidden.bs.modal', function () {
if(tipoingreso==1){
$("#tarjeta").val("").focus();	
}else{
$("#rut").val("").focus();
$("#clave").val("");
	
}
});

function cambiaracceso(tipo,e){
tipoingreso=parseInt(tipo);
if(parseInt(tipo)==2){
$("#contarjeta").hide();
$("#sintarjeta").show();
$(e).html("Tarjeta").attr("onclick","cambiaracceso(1,this)");
$("#rut").focus();
}else{
$("#sintarjeta").hide();
$("#contarjeta").show();
$(e).html("Formulario").attr("onclick","cambiaracceso(2,this)");
$("#tarjeta").focus();
}
$("#tipoacceso").val(tipo);
}


function login(e){
idtipo=$("#tipoacceso").val();
codigo=$("#tarjeta").val();
rut=$("#rut").val();
pass=$("#clave").val();

if(!rut && !pass && idtipo==2){
alert("Rut y clave son requeridos");
return;	
}else if(!codigo && idtipo==1){
alert("El código de la tarjeta es requerido");
return;		
}

// return;

$.get("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'loginterminal',rutuser:rut,clave:pass,tipo:idtipo,tarjeta:codigo,retornar:0},function(data){
res=$.parseJSON(data);
console.log(res);

if(res.error){
$("#mlogin .modal-dialog").css({'width':'40%'});
$("#mlogin .modal-header").removeClass("header-verde").addClass("header-rojo");
$("#mlogin .modal-title").html("Error al ingresar ");
$("#mlogin .modal-body").html("<div class='row'><div class='col-md-12'>"+res.mensaje+"</div></div>");
$("#mlogin .modal-footer").hide();
$("#mlogin").modal("toggle");	



}else{
window.location=res.ir

}

});



}

</script>
</body>
</html>