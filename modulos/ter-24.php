<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t24">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title"></h5>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
</div>
<div class="modal-footer">
</div>
</div>
</div>
</div>

<div class="row mt-4">
<div class="col-md-4">
<div class="row">
<div class="col-md-12"><h4>Nuevo Usuario</h4></div>
</div>
<div class="row mt-2">
<div class="col-md-8">
<div class="card">
<div class="card-body">
<div class="form-group row">
<div class="col-12">
<label>Para Cliente</label>
<? htmlselect('n_cliente','n_cliente','clientes','id','nombre','','','','nombre','getPatentes(this)','','si','no','no');?>
</div>
</div>
<div class="form-group row">
<div class="col-12">
<label>Usuario</label>
<input type="text" name="n_usuario" class="form-control" autocomplete="off">
</div>
</div>

<div class="form-group row">
<div class="col-12">
<label>Password</label>
<input type="password" name="n_password" class="form-control" autocomplete="off">
</div>
</div>

<div class="form-group row">
<div class="col-12">
<label>Email</label>
<input type="text" name="n_correo" class="form-control" autocomplete="off">
</div>
</div>

<div class="form-group row">
<div class="col-12">
<button type="button" class="btn btn-success btn-block" onclick="nuevoUsuario()">Registrar</button>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<div class="col-md-8 mt-5">
<div class="row" id="c24_tbusuarios">
<div class="col-md-12">
<div class="card">
<div class="card-body">
<table class="table table-bordered table-striped" id="tb24_usuarios">
<thead><th>Empresa</th><th>Usuario</th><th>Correo</th><th>Ultimo Acceso</th><th>Estado</th>
<th class="text-center"></th>
<th class="text-center"></th>
</thead>
<tbody>
</tbody>
</table>
</div>
</div>
</div>
</div>

<div class="row oculto" id="c24_editarusuario">
<div class="col-md-12">
<div class="card">
<div class="card-header ">
<h3 class="card-title"></h3>
<div class="card-tools">
<button type="button" class="btn btn-tool" onclick="indexuser()"><i class="fas fa-times"></i></button>
</div>
</div>
<div class="card-body">
<div id="f24_editaruser">
<input type="hidden" name="user_id">
<!--<div class="row mt-1">
<label class="col-md-4 control-label txtleft">Usuario</label>
<div class="col-md-6">
<input type="text"  name="usuario" class="form-control">
</div>
</div>-->

<div class="row  mt-1">
<label class="col-md-4 control-label txtleft">Email</label>
<div class="col-md-6">
<input type="text"  name="correo" class="form-control">
</div>
</div>

<div class="row  mt-1">
<label class="col-sm-4 control-label txtleft">Clave</label>
<div class="col-sm-4">
<input type="password"  name="clave" class="form-control" value="******" disabled >
</div>
<div class="col-sm-1" id="iconclave"><span class="pointer btn btn-primary btn-circle" onclick="actualizapass();"><?=$i_lock;?></span></div>
</div>

<div class="row  mt-1">
<div class="col-sm-4 offset-md-4">
<button type="button" class="btn btn-warning btn-block" onclick="updateUser()">Actualizar Usuario</button>
</div>
</div>

</div>

<div id="cambiarclave" class="oculto">
<div class="row">
<div class="col-md-12"><h4>Actualizar Contraseña</h4></div>
</div>
<div class="row  mt-2" id="contentRP">
<div class="col-md-12">
<div class="row">
<label class="col-md-3 control-label txtleft">Nueva Contraseña</label>
<div class="col-md-4"><input type="password" name="nuevapass" class="form-control"/></div>
<div class="col-sm-1 text-red padtop7 oculto errorpass"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></div>
</div>
<div class="row mt-1">
<label class="col-md-3 control-label txtleft">Repetir Contraseña</label>
<div class="col-md-4"><input type="password" name="nuevapass1" class="form-control"/></div>
<div class="col-sm-1 text-red padtop7 oculto errorpass"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></div>
<label class="col-md-12 txtnormal top10 text-red oculto" id="lbl_errorPass">¡Error las contraseñas no coinciden !</label>
</div>

<div class="row mt-2">
<div class="offset-md-3 col-md-2"><button type="button" class="btn btn-danger btn-block" onclick="volveralform()">Cancelar</button></div>
<div class="col-md-2"><button type="button" class="btn btn-success  btn-block" onclick="e_actualizarpass();">Cambiar</button></div>
<div class="col-md-1 txtcolor-azulmenu padtop7 oculto" id="recuperando"><i class="fa fa-cog fa-lg fa-spin fa-fw"></i></div>
</div>
</div>
</div>

<div class="col-md-12 oculto" id="exitoRP">
<div class="col-sm-12" id="titulo_ERP">Contraseña actualizada <i class="fa fa-check-circle" aria-hidden="true"></i></div>
<div class="col-sm-12 top20">
<p>La contraseña ha sido actualizada, para volver al formulario haz clic en el siguiente boton.</p>
</div>
<div class="col-sm-5"><button type="button" class="btn btn-success" onclick="volveralform()">Volver al formulario</button></div>
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
getUserEmpresas();
$("#n_cliente").chosen();
});
window.uempresa;
let tb24_usuarios = new DataTable('#tb24_usuarios');
function getUserEmpresas(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getUserEmpresas',retornar:0},function(data){
// console.log(data);
uempresa = $.parseJSON(data);
fila = "";
x=0;
$.each(uempresa,function(index,valor){
x++;
idestado = parseInt(valor.idestado);
if(idestado === 1){estado ="<span class='text-green pointer' onclick='cambiarestadoUsuario(\""+index+"\",0)'><i class='fa fa-lg fa-toggle-on' aria-hidden='true'></i></span>"; retro="";}else{estado="<span class='text-muted pointer' onclick='cambiarestadoUsuario(\""+index+"\",1)'><i class='fa fa-lg fa-toggle-off' aria-hidden='true'></i></span>";retro="retro";}
//<td class='text-center' width=50><button class='btn btn-success btn-circle-s'>"+i_lista+"</button></td><td>"+valor.razonsocial+"</td><td>"+valor.telefono+"</td>
fila+="<tr id='ue"+index+"'><td><span style='display:none;'>"+valor.ultimodt+"</span>"+valor.empresa+"</td><td>"+valor.usuario+"</td><td>"+valor.correo+"</td><td>"+valor.ultimo+"</td><td class='text-center' id='estadouser"+index+"'>"+estado+"</td><td class='text-center' width=50><button class='btn btn-warning' onclick='editarUsuario("+index+")'>"+i_edit+"</button></td><td class='text-center' width=50><button class='pointer btn btn-danger' onclick='eliusuario("+index+")'>"+i_borrar+"</button></td></tr>";

});
// $("#tbusuarios tbody").html(fila);

$("#tb24_usuarios tbody").html("");
tb24_usuarios.destroy();
$("#tb24_usuarios tbody").html(fila);
tb_tarjetas=$('#tb24_usuarios').DataTable({
"language":{ url: 'dtspanish.json'},
"paging": true,
"autoWidth": false,
"lengthChange": false,
"lengthMenu": [[20,-1], [20,"Todos"]],
"pageLength":20,
"searching": true,
"ordering": true,
"order": [[0, "desc" ]],
"info": true
});	

});
}

function indexuser(){
$("#c24_editarusuario").hide();
$("#c24_tbusuarios").show();
}

function editarUsuario(i){
usu= uempresa[i];
$("input[name='user_id']").val(i);
$("#c24_editarusuario .card-title").html("Editando usuario : <b>"+usu.usuario+"</b>");
// $("#f24_editaruser input[name='usuario']").val(usu.usuario);
$("#f24_editaruser input[name='correo']").val(usu.correo);
$("#c24_tbusuarios, #cambiarclave").hide();
$("#c24_editarusuario, #f24_editaruser").show();
}

function actualizapass(){
$("#f24_editaruser").hide();
$("input[name='nuevapass'],input[name='nuevapass1']").val("");
$("#cambiarclave").show();
}

function volveralform(){
$("#cambiarclave").hide();
$("#f24_editaruser").show();
$(".errorpass").hide();
$("#lbl_errorPass").hide();
$("#exitoRP").hide();
$("#contentRP").show();

}


function e_actualizarpass(){
if($("input[name='nuevapass']").val().length > 0 && $("input[name='nuevapass1']").val().length > 0 ){
if($("input[name='nuevapass']").val() == $("input[name='nuevapass1']").val()){
user=$("input[name='user_id']").val();
nueva=$("input[name='nuevapass']").val();
// console.log(user);
// console.log(nueva);
// return;

$("#recuperando").show();
$.get('operaciones.php',{bncnr:''+Math.floor(Math.random()*9999999)+'',operacion:'changePasswordUE',id:user,clave:nueva,retornar:0}, function(data){
$("#recuperando").hide();
$("#contentRP").hide();
$("#exitoRP").show();
});
}else{
$(".errorpass").show();
$("#lbl_errorPass").show();
}
}else{
$(".errorpass").show();
$("#lbl_errorPass").show();
}

}

function updateUser(){
id = $("#f24_editaruser input[name='user_id']").val();
// usuario = $("#f24_editaruser input[name='usuario']").val();
correo= $("#f24_editaruser input[name='correo']").val();
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'editarUE',user:id,e_correo:correo,retornar:0}
,function(data){
res= $.parseJSON(data);
if(res.error){
alert(res.mensaje);
}else{
location.reload();
}

});


}

function nuevoUsuario(){
cliente = $("#n_cliente").val();
usuario = $("input[name='n_usuario']").val();
password = $("input[name='n_password']").val();
correo= $("input[name='n_correo']").val();
if(cliente && usuario && password && correo){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'nuevoUE',n_cliente:cliente,n_usuario:usuario,n_password:password,n_correo:correo,retornar:0}
,function(data){
res= $.parseJSON(data);
if(res.error){
alert(res.mensaje);
}else{
location.reload();
}

});	
}else{
alert("Para registrar un nuevo usuario empresa, todos los campos son obligatorios");
return;
}


}


function cambiarestadoUsuario(usuario,estado){
$.get("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'cambiarestadoUE',id:usuario,idestado:estado,retornar:0},function(data){
// console.log(data);
if(estado == 1){estadouser ="<span class='text-green pointer' onclick='cambiarestadoUsuario(\""+usuario+"\",\"0\")'><i class='fa fa-lg fa-toggle-on' aria-hidden='true'></i></span>";}else{estadouser="<span class='text-muted pointer' onclick='cambiarestadoUsuario(\""+usuario+"\",\"1\")'><i class='fa fa-lg fa-toggle-off' aria-hidden='true'></i></span>";}

$("#estadouser"+usuario+"").html(estadouser);
});	
}

function eliusuario(id){
usu= uempresa[id];
info="Realmente desea eliminar este usuario : <b>"+usu.usuario+"</b>";
$("#m_t24 .modal-dialog").css({'width':'30%'});
$("#m_t24 .modal-header").removeClass("header-verde").addClass("header-rojo");
$("#m_t24 .modal-title").html("Eliminar Usuario Empresa");
$("#m_t24 .modal-body").html(info);
// $("#vehiculo .modal-footer").css({display:"none"})
$("#m_t24 .modal-footer").html("<button type='button' class='btn btn-danger pull-left' data-dismiss='modal'>Cancelar</button><button type='button' class='btn btn-success ' onclick='borrarUsuario(\""+id+"\")'>Confirmar</button>")
$("#m_t24").modal("toggle");


}
function borrarUsuario(id){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'eliminarUE',idusuario:id,retornar:0},function(data){
$("#ue"+id+"").remove();
$("#m_t24").modal("hide");
});
}

</script>