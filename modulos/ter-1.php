<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t1">
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
<!-- fin modal -->
<div class="container">
<div class="row mt20">
<div class="col-12">
<h3>Usuarios Activos</h3>
</div>
</div>


<div class="row mt-2">
<div class="col-md-12 top20 cuser" id="listadousuarios">
<div class="card">
<div class="card-body">
<table class="table table-bordered table-striped" id="tb1_usuarios">
<thead>
<th>N°</th>
<!--<th>Razón Social</th>-->
<th>Rut</th>
<th>Nombre</th>
<th>Correo</th>
<th>Teléfono</th>
<th>Ultimo Acceso</th>
<th>Estado</th>
<th class="text-center"></th>
<th class="text-center"></th>
<th class="text-center"></th>
<th class="text-center"></th>
</thead>
<tbody>
</tbody>
</table>
</div>
</div>
</div>

<!-- AGREGAR USUARIO  --->
<div class="col-md-8 cuser top20 oculto" id="f_adduser">
<div class="card">
<div class="card-header bg-black">
<h3 class="card-title">Agregar Usuario</h3>
<div class="card-tools">
<button type="button" class="btn btn-tool" onclick="indexuser()"><i class="fas fa-times"></i></button>
</div>
</div>
<div class="card-body">
<form class="form-horizontal" action="o_genericas.php" method="post" enctype="multipart/form-data" onsubmit="return validanuevousuario()">
<input type="hidden" name="operacion" value="agregarusuario"/>
<input type="hidden" name="retornar" value="index.php?mod=9&subid=ter-1"/>


<div class="row top10">
<label class="col-md-2 control-label txtleft">Nombre</label>
<div class="col-md-6">
<input type="text"  name="nombre" class="form-control">
</div>
</div>

<div class="row top10">
<label class="col-md-2 control-label txtleft">Rut</label>
<div class="col-md-4">
<input type="text"  name="rut" class="form-control validarut">
</div>
</div>
<div class="row top10">
<label class="col-sm-2 control-label txtleft">Teléfono</label>
<div class="col-sm-4">
<input type="text"  name="telefono" class="form-control">
</div>
</div>
<div class="row top10">
<label class="col-sm-2 control-label txtleft">Correo</label>
<div class="col-sm-6">
<input type="text"  name="correo" class="form-control">
</div>
</div>
<div class="row top10">
<label class="col-sm-2 control-label txtleft">Bodega(s)</label>
<div class="col-md-6"><select name="bod[]" id="bod" class="form-control chosen-select" multiple data-placeholder="..."></select></div>
</div>
<div class="row top10">
<label class="col-sm-2 control-label txtleft">Usuario</label>
<div class="col-sm-6">
<input type="text"  name="usuario" class="form-control">
</div>
<div class="col-sm-1 txtcolor-azulmenu padtop7 oculto" id="validandouser"><i class="fa fa-cog fa-lg fa-spin fa-fw"></i></div>
</div>

<div class="row top10">
<label class="col-sm-2 control-label txtleft">Clave</label>
<div class="col-sm-6">
<input type="password"  name="clave" class="form-control">
</div>
</div>
<div class="row top10">
<label class="col-sm-2 control-label txtleft">Foto</label>
<div class="col-sm-6">
<input type="file" class='form-control' name="foto" >
</div>
</div>


<div class="row top10">
<div class="offset-md-2 col-sm-4">
<button type="button" class=" btn btn-success btn-rounded btn-block" onclick="registrarUsuario()">Registrar</button>
</div>
</div>
</form>
</div>
</div>
</div>


<!-- EDITAR USUARIO -->
<div class="col-md-12 cuser top20 oculto " id="f_editaruser">
<div class="card">
<div class="card-header ">
<h3 class="card-title"></h3>
<div class="card-tools">
<button type="button" class="btn btn-tool" onclick="indexuser()"><i class="fas fa-times"></i></button>
</div>
</div>
<div class="card-body">
<div class="row">
<div class="col-md-4">
<div class="text-center">
<img src="" class="avatarperfil img-circle img-thumbnail" id="avataruser" style="max-width:50%;">
<h6>Cambiar foto</h6>
<div class="input-group mb-3">
<input type="file" class='form-control gruporight' name="e_foto" >
<div class="input-group-text igaright iconlogin" id="iconfoto"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i></div>
</div>
</div>
</div>

<div class="col-md-8">
<div id="formeditarusuario">
<input type="hidden" name="user_id">

<div class="row mt-1">
<label class="col-md-4 control-label txtleft">Rut</label>
<div class="col-md-6">
<input type="text"  name="rut" class="form-control">
</div>
</div>

<div class="row mt-1">
<label class="col-md-4 control-label txtleft">Nombre</label>
<div class="col-md-6">
<input type="text"  name="nombre" class="form-control">
</div>
</div>

<div class="row  mt-1">
<label class="col-md-4 control-label txtleft">Email</label>
<div class="col-md-6">
<input type="text"  name="email" class="form-control">
</div>
</div>
<div class="row  mt-1">
<label class="col-sm-4 control-label txtleft">Teléfono</label>
<div class="col-sm-4">
<input type="text"  name="telefono" class="form-control">
</div>
</div>
<div class="row  mt-1">
<label class="col-sm-4 control-label txtleft">Perfil</label>
<div class="col-sm-6">
<? htmlselect('perfil','perfil','perfiles','id','nombre','','','','nombre','','','si','no','no');?>
</div>
</div>

<div class="row  mt-1">
<label class="col-sm-4 control-label txtleft">Codigo softland</label>
<div class="col-sm-4">
<input type="text"  name="cod_softland" class="form-control" >
</div>
</div>

<div class="row  mt-1">
<label class="col-sm-4 control-label txtleft">Usuario</label>
<div class="col-sm-4">
<input type="text"  name="usuario" class="form-control"  disabled >
</div>
</div>

<div class="row  mt-1">
<label class="col-sm-4 control-label txtleft">Clave</label>
<div class="col-sm-4">
<input type="password"  name="clave" class="form-control" value="******" disabled >
</div>
<div class="col-sm-1" id="iconclave"><span class="pointer btn btn-primary btn-circle" onclick="actualizapass();"><?=$i_lock;?></span></div>
</div>

<div class="row  mt-2">
<label class="col-sm-4 control-label txtleft">Código único</label>
<div class="col-sm-4"><IMG SRC="" id="cu"></div>

</div>


<div class="row  mt-1">
<div class="offset-md-4 col-sm-4">
<button type="button" class="btn btn-success btn-block" onclick="editarUser()">Editar</button></div>
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

<div class="col-md-12 cuser top20 oculto" id="permisosusuario">
<div class="card">
<div class="card-header">
<h3 class="card-title"></h3>
<div class="card-tools">
<button type="button" class="btn btn-tool" onclick="indexuser()"><i class="fas fa-times"></i></button>
</div>
</div>
<div class="card-body">
</div>
</div>
</div>



</div>


</div>
<iframe height="0" width="0" name="imprimir" src=""></iframe>
<script>
$(function(){
getTabUsuarios();
});
window.usuarios;
let tb1_usuarios = new DataTable('#tb1_usuarios');
function getTabUsuarios(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getUsuariosActivos',retornar:0},function(data){
// console.log(data);
usuarios = $.parseJSON(data);
fila = "";
x=0;
$.each(usuarios,function(index,valor){
x++;
idestado = parseInt(valor.idestado);
if(idestado === 1){estado ="<span class='text-green pointer' onclick='cambiarestadoUsuario(\""+index+"\",0)'><i class='fa fa-lg fa-toggle-on' aria-hidden='true'></i></span>"; retro="";}else{estado="<span class='text-muted pointer' onclick='cambiarestadoUsuario(\""+index+"\",1)'><i class='fa fa-lg fa-toggle-off' aria-hidden='true'></i></span>";retro="retro";}
//<td class='text-center' width=50><button class='btn btn-success btn-circle-s'>"+i_lista+"</button></td><td>"+valor.razonsocial+"</td><td>"+valor.telefono+"</td>
fila+="<tr id='usuario"+index+"'><td>"+x+"</td><td>"+valor.rut+"</td><td>"+valor.nombre+"</td><td>"+valor.correo+"</td><td>"+valor.fono+"</td><td>"+valor.ultimo+"</td><td class='text-center' id='estadouser"+index+"'>"+estado+"</td><td class='text-center' width=50><button class='btn btn-outline-primary' onclick='verPermisos("+index+")'>"+i_unlock+"</button></td><td class='text-center' width=50><button class='btn btn-warning' onclick='editarUsuario("+index+")'>"+i_edit+"</button></td><td class='text-center' width=50><button class='pointer btn btn-outline-danger' onclick='eliusuario("+index+")'>"+i_borrar+"</button></td><td class='text-center'><a href='index.php?mod=8&subid=ter-1-imprimir&id="+index+"' class='btn btn-outline-primary btn-sm' target='imprimir'>"+i_print+"</a></td></tr>";

});
// $("#tbusuarios tbody").html(fila);

$("#tb1_usuarios tbody").html("");
tb1_usuarios.destroy();
$("#tb1_usuarios tbody").html(fila);
tb_tarjetas=$('#tb1_usuarios').DataTable({
"language":{ url: 'dtspanish.json'},
"paging": true,
"autoWidth": false,
"lengthChange": false,
"lengthMenu": [[20,-1], [20,"Todos"]],
"pageLength":20,
"searching": true,
"ordering": false,
// "order": [[ 0, "desc" ]],
"info": true
});	

});
}

function indexuser(){
$(".cuser").hide();
$("#listadousuarios").show();
}

function nuevoUsuario(){
$(".cuser").hide();
$("#f_adduser").show();
// cargar sucursales

optbod="";
$.each(bodegas,function(i,v){
optbod+="<option value="+i+">"+v.nombre+"</option>";
});
$("#bod").html(optbod);	
$("#bod").chosen({no_results_text: "sin resultados !!!"});

}




function editarUsuario(i){
usu= usuarios[i];
$("input[name='user_id']").val(i);
$("#f_editaruser .card-title").html("Editando usuario : <b>"+usu.nombre+"</b>");
$("#f_editaruser #avataruser").attr("src","images/usuarios/"+usu.foto+"");
//$("#f_editaruser #razonsocial").val(usu.idrazonsocial);
$("#f_editaruser input[name='rut']").val(usu.rut);
$("#f_editaruser input[name='nombre']").val(usu.nombre);
$("#f_editaruser input[name='email']").val(usu.correo);
$("#f_editaruser input[name='telefono']").val(usu.fono);
$("#f_editaruser #perfil").val(usu.idperfil);
$("#f_editaruser input[name='cod_softland']").val(usu.codsoftland);
$("#f_editaruser input[name='usuario']").val(usu.usuario);
$("#cu").attr("src","cds_codigobarras.php?claveunica="+usu.codigo+"");
$(".cuser").hide();
$("#f_editaruser").show();
}

function actualizapass(){
$("#formeditarusuario").hide();
$("#cambiarclave").show();
}

function volveralform(){
$("#cambiarclave").hide();
$("#formeditarusuario").show();
$(".errorpass").hide();
$("#lbl_errorPass").hide();
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
$.get('operaciones.php',{bncnr:''+Math.floor(Math.random()*9999999)+'',operacion:'changePassword',id:user,clave:nueva,retornar:0}, function(data){
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


$("input[name='e_foto']").change(function(){
image=$(this).prop('files')[0];
if(image !=""){
// console.log("subir");
var form_data = new FormData();
form_data.append('operacion','actualizarFotoUser');
form_data.append('id',$("input[name='user_id']").val());
form_data.append('foto', image);
form_data.append('retornar',0);

$.ajax({
url: 'operaciones.php', //ruta archivo operaciones
dataType: 'text',  // tipo de datos
cache: false,
contentType: false,
processData: false,
data: form_data,
type: 'post',
success: function(respuesta){
// console.log(respuesta);
$("#f_editaruser #avataruser").attr("src","images/usuarios/"+respuesta+"");
$(this).val("");
}
});
}else{
console.log("nada");
}
});


function editarUser(){
id = $("#f_editaruser input[name='user_id']").val();
//razonsocial = $("#f_editaruser #razonsocial").val();
rut = $("#f_editaruser input[name='rut']").val();
nombre= $("#f_editaruser input[name='nombre']").val();
correo = $("#f_editaruser input[name='email']").val();
fono = $("#f_editaruser input[name='telefono']").val();
idperfil = $("#f_editaruser #perfil").val();
codsoftland=$("#f_editaruser input[name='cod_softland']").val();

var form_data = new FormData();
form_data.append('operacion','editarusuario');
form_data.append('id',id);
// form_data.append('razonsocial',razonsocial);
form_data.append('rut',rut);
form_data.append('nombre',nombre);
form_data.append('correo',correo);
form_data.append('fono', fono);
form_data.append('perfil', idperfil);
form_data.append('codsoftland', codsoftland);	
form_data.append('retornar',0);


$.ajax({
url: 'operaciones.php', //ruta archivo operaciones
dataType: 'text',  // tipo de datos
cache: false,
contentType: false,
processData: false,
data: form_data,
type: 'post',
success: function(respuesta){
location.reload();
// console.log(respuesta);

}
});
}


function verPermisos(i){
usu= usuarios[i];
$("#permisosusuario .card-title").html("Administrar permisos para el usuario : <b>"+usu.nombre+"</b>");	
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'permisosxusuario',user:i,retornar:0}
,function(data){
datos=$.parseJSON(data);
mods=datos["modulos"];
permisos=datos["permisos"];
modmenu="<div class='row row-cols-1 row-cols-md-3 g-4'>";
$.each(mods,function(ii,vv){
modmenu+="<div class='col'><div class='card' id='menu'>";
modmenu+="<div class='card-header'><h6 class='card-title'>"+ii+"</h6></div>";
modmenu+="<div class='card-body'>";
$.each(vv,function(im,vm){
if(permisos[im]){checked="checked";}else{checked="";}
modmenu+="<div class='input-group mb-3'><input type='text' value='"+vm.modulo+" ' class='form-control gruporight' disabled ><div class='input-group-text igaright'><input type='checkbox' id='check"+im+"' class='pointer' onclick='asignarpermiso(\""+im+"\","+i+","+vm.idmod+");' "+checked+"/></div></div>";	
});

modmenu+="</div></div></div>";

});
modmenu+="</div>";
$("#permisosusuario .card-body").html(modmenu);

});


$(".cuser").hide();
$("#permisosusuario").show();


}

function eliusuario(id){
usu= usuarios[id];
info="Realmente desea eliminar este usuario : <b>"+usu.nombre+"</b>";
$("#m_t1 .modal-dialog").css({'width':'30%'});
$("#m_t1 .modal-header").removeClass("header-verde").addClass("header-rojo");
$("#m_t1 .modal-title").html("Eliminar Usuario");
$("#m_t1 .modal-body").html(info);
// $("#vehiculo .modal-footer").css({display:"none"})
$("#m_t1 .modal-footer").html("<button type='button' class='btn btn-danger pull-left' data-dismiss='modal'>Cancelar</button><button type='button' class='btn btn-success ' onclick='borrarUsuario(\""+id+"\")'>Confirmar</button>")
$("#m_t1").modal("toggle");


}
function borrarUsuario(id){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'eliminarusuario',idusuario:id,retornar:0},function(data){
$("#usuario"+id+"").remove();
$("#m_t1").modal("hide");
});
}

function asignarpermiso(a,b,c){
tipo=0;
check=$("#check"+a+"").val();
if($("#check"+a+"").is(':checked')){tipo=1;}
// console.log(tipo);
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'permisousuario',idmod:''+a+'',accion:tipo,idusuario:''+b+'',idmenu:''+c+'',retornar:0}
,function(data){
console.log(data);
});

}


function cambiarestadoUsuario(usuario,estado){
$.get("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'cambiarestadoUsuario',id:usuario,idestado:estado,retornar:0},function(data){
// console.log(data);
if(estado == 1){estadouser ="<span class='text-green pointer' onclick='cambiarestadoUsuario(\""+usuario+"\",\"0\")'><i class='fa fa-lg fa-toggle-on' aria-hidden='true'></i></span>";}else{estadouser="<span class='text-muted pointer' onclick='cambiarestadoUsuario(\""+usuario+"\",\"1\")'><i class='fa fa-lg fa-toggle-off' aria-hidden='true'></i></span>";}

$("#estadouser"+usuario+"").html(estadouser);
});	
}

</script>

