<div class="container">
<div class="row mt20">
<div class="col-12">
<h3>Nuevo Usuario</h3>
</div>
</div>
<div class="row mt-2">
<div class="col-md-12 top20">
<div class="card">
<div class="card-body">
<div class="row mt-3">

<div class="col-md-12" id="f_adduser">

<div class="row mt-2">
<label class="col-md-4 control-label txtleft">Rut</label>
<div class="col-md-4">
<input type="text"  name="rut" class="form-control">
</div>
</div>

<div class="row mt-2">
<label class="col-md-4 control-label txtleft">Nombre</label>
<div class="col-md-6">
<input type="text"  name="nombre" class="form-control">
</div>
</div>

<div class="row  mt-2">
<label class="col-md-4 control-label txtleft">Email</label>
<div class="col-md-6">
<input type="text"  name="email" class="form-control">
</div>
</div>
<div class="row  mt-2">
<label class="col-sm-4 control-label txtleft">Teléfono</label>
<div class="col-sm-4">
<input type="text"  name="telefono" class="form-control">
</div>
</div>
<div class="row  mt-2">
<label class="col-sm-4 control-label txtleft">Perfil</label>
<div class="col-sm-4">
<? htmlselect('perfil','perfil','perfiles','id','nombre','','','','nombre','','','si','no','no');?>
</div>
</div>

<div class="row  mt-2">
<label class="col-sm-4 control-label txtleft">Codigo softland</label>
<div class="col-sm-4">
<input type="text"  name="cod_softland" class="form-control" >
</div>
</div>

<div class="row  mt-2">
<label class="col-sm-4 control-label txtleft">Usuario</label>
<div class="col-sm-4">
<input type="text"  name="usuario" class="form-control" >
</div>
</div>

<div class="row  mt-2">
<label class="col-sm-4 control-label txtleft">Clave</label>
<div class="col-sm-4">
<input type="password"  name="clave" class="form-control">
</div>

</div>

<div class="row mt-2">
<label class="col-sm-4 control-label txtleft">Foto</label>
<div class="col-sm-6">
<input type="file" class='form-control' name="foto" >
</div>
</div>

<div class="row  mt-3">
<label class="col-sm-4 control-label txtleft">Código único</label>
<div class="col-sm-4"><IMG SRC="" id="cu"></div>

</div>


<div class="row  mt-3">
<div class="offset-md-4 col-sm-4">
<button type="button" class=" btn btn-success  btn-block" onclick="registrarUsuario()">Registrar</button>
</div>
</div>


</div>

</div>

</div>
</div>
</div>
</div>
</div>

<script>

$(function(){
getGeneraCodigoTarjeta();	
// getLastTarjetas();
// $("#cliente,#patente").chosen();
});

let codigo;
function getGeneraCodigoTarjeta(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getGeneraCodigoTarjeta',retornar:0},function(data){
// console.log(data);
codigo= data;
$("input[name='codigo']").val(codigo);
$("#cu").attr("src","cds_codigobarras.php?claveunica="+codigo+"");
});

}

function registrarUsuario(){
//razonsocial = $("#f_adduser #razonsocial").val();
rut = $("#f_adduser input[name='rut']").val();
nombre= $("#f_adduser input[name='nombre']").val();
correo = $("#f_adduser input[name='email']").val();
fono = $("#f_adduser input[name='telefono']").val();
idperfil = $("#f_adduser #perfil").val();
codsoftland=$("#f_adduser input[name='cod_softland']").val();
usuario = $("#f_adduser input[name='usuario']").val();
clave  = $("#f_adduser input[name='clave']").val();
foto = $("#f_adduser input[name='foto']").prop('files')[0];

var form_data = new FormData();
form_data.append('operacion','nuevousuario');
// form_data.append('razonsocial',razonsocial);
form_data.append('rut',rut);
form_data.append('nombre',nombre);
form_data.append('correo',correo);
form_data.append('fono', fono);
form_data.append('perfil', idperfil);
form_data.append('codsoftland', codsoftland);	
form_data.append('usuario', usuario);
form_data.append('clave', clave);
form_data.append('foto', foto);
form_data.append('codigo', codigo);
form_data.append('retornar',0);

$.ajax({
url: 'operaciones.php', //ruta archivo operaciones
dataType: 'text',  // tipo de datos
cache: false,
contentType: false,
processData: false,
data: form_data,
type: 'post',
// xhr: function () {
/* var xhr = $.ajaxSettings.xhr();
xhr.upload.onprogress = function (e) {
if (e.lengthComputable) {
porcentaje=parseInt((e.loaded / e.total)*100);
if(porcentaje < 100){
$("#cargandoDetalle .progress-bar").css({width:""+porcentaje+"%"});
$("#cargandoDetalle .progress-bar").html(porcentaje+"%");
$("#cargandoDetalle .sr-only").html(porcentaje+"%");	
}else{
$("#cargandoDetalle").hide();
$("#btnCargaDetalle").html("Procesando archivo, por favor espera...<i class='fa fa-cog fa-spin'></i>").removeClass("btn-warning").addClass("btn-black").attr("disabled",true);
}

}};
return xhr; */
// },
success: function(respuesta){
location.reload();
// console.log(respuesta);
// return;
/* data = $.parseJSON(respuesta);
if(data["status"]=="success"){
$("#fddc input[name='fechacarga']").val("");
$("#fddc input[name='archivoguias'], input[name='archivocartones']").val("");
$("#fddc #mandante").val();
$("#fddc").hide();
$("#exitoddc").show();
}else{
$("#mddc .modal-dialog").css({'width':'40%'});
$("#mddc .modal-header").removeClass("header-azul").addClass("header-rojo");
$("#mddc .modal-title").html("Error al cargar archivo");
$("#mddc .modal-body").html("Error al importar archivo: <b>"+data["mensaje"]+"</b>");
$("#mddc .modal-footer").html("<button type='button' class='btn btn-danger btn-rounded' data-dismiss='modal'>ENTENDIDO</button>");
$("#mddc").modal("toggle");	
$("#cargandoDetalle").hide();
$("#btnCargaDetalle").html("Cargar Detalle").removeClass("btn-warning").addClass("btn-outline-success");
}

  */
}
});





}
</script>