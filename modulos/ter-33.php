<div class="row mt-4">
<div class="col-md-5">
<div class="row"><div class="col-md-12"><h4>Usuarios empresa desactivados</h4></div></div>
<div class="row">
<div class="col-md-12">

<div class="card">
<div class="card-body">
<table class="table table-bordered table-striped" id="tb33_uempresa">
<thead><th>Empresa</th><th>Usuario</th><th>Correo</th><th>Ultimo Acceso</th><th>Estado</th>
</thead>
<tbody>
</tbody>
</table>
</div>
</div>

</div>

</div>
</div>

<div class="col-md-7">
<div class="row"><div class="col-md-12"><h4>Usuarios de sistema desactivados</h4></div></div>
<div class="row">
<div class="col-md-12">

<div class="card">
<div class="card-body">
<table class="table table-bordered table-striped" id="tb33_usistema">
<thead>
<th>N°</th>
<!--<th>Razón Social</th>-->
<th>Rut</th>
<th>Nombre</th>
<th>Correo</th>
<th>Teléfono</th>
<th>Ultimo Acceso</th>
<th>Estado</th>
</thead>
<tbody>
</tbody>
</table>
</div>
</div>

</div>

</div>
</div>
</div>

<script>
$(function(){
getUsuariosDesactivados();
});
window.uempresa;
window.usistema;
let tb33_usistema = new DataTable('#tb33_usistema');
let tb33_uempresa = new DataTable('#tb33_uempresa');
function getUsuariosDesactivados(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getUsuariosDesactivados',retornar:0},function(data){
// console.log(data);
res=$.parseJSON(data);
uempresa = res.empresa;
usistema = res.sistema;
listarUsuariosEmpresa();
listarUsuariosSistema();

});
}

function listarUsuariosEmpresa(){
fila = "";
x=0;
$.each(uempresa,function(index,valor){
x++;
idestado = parseInt(valor.idestado);
if(idestado === 1){estado ="<span class='text-green pointer' onclick='cambiarestadoUE(\""+index+"\",0)'><i class='fa fa-lg fa-toggle-on' aria-hidden='true'></i></span>"; retro="";}else{estado="<span class='text-muted pointer' onclick='cambiarestadoUE(\""+index+"\",1)'><i class='fa fa-lg fa-toggle-off' aria-hidden='true'></i></span>";retro="retro";}
//<td class='text-center' width=50><button class='btn btn-success btn-circle-s'>"+i_lista+"</button></td><td>"+valor.razonsocial+"</td><td>"+valor.telefono+"</td>
fila+="<tr id='ue"+index+"'><td><span style='display:none;'>"+valor.ultimodt+"</span>"+valor.empresa+"</td><td>"+valor.usuario+"</td><td>"+valor.correo+"</td><td>"+valor.ultimo+"</td><td class='text-center' id='eue"+index+"'>"+estado+"</td></tr>";

});
$("#tb33_uempresa tbody").html("");
tb33_uempresa.destroy();
$("#tb33_uempresa tbody").html(fila);
tb_tarjetas=$('#tb33_uempresa').DataTable({
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

}
function listarUsuariosSistema(){
fila = "";
x=0;
$.each(usistema,function(index,valor){
x++;
idestado = parseInt(valor.idestado);
if(idestado === 1){estado ="<span class='text-green pointer' onclick='cambiarestadoUS(\""+index+"\",0)'><i class='fa fa-lg fa-toggle-on' aria-hidden='true'></i></span>"; retro="";}else{estado="<span class='text-muted pointer' onclick='cambiarestadoUS(\""+index+"\",1)'><i class='fa fa-lg fa-toggle-off' aria-hidden='true'></i></span>";retro="retro";}
fila+="<tr id='usuario"+index+"'><td>"+x+"</td><td>"+valor.rut+"</td><td>"+valor.nombre+"</td><td>"+valor.correo+"</td><td>"+valor.fono+"</td><td>"+valor.ultimo+"</td><td class='text-center' id='eus"+index+"'>"+estado+"</td></tr>";

});
// $("#tbusuarios tbody").html(fila);

$("#tb33_usistema tbody").html("");
tb33_usistema.destroy();
$("#tb33_usistema tbody").html(fila);
tb_tarjetas=$('#tb33_usistema').DataTable({
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

}

function cambiarestadoUE(usuario,estado){
$.get("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'cambiarestadoUE',id:usuario,idestado:estado,retornar:0},function(data){
// console.log(data);
if(estado == 1){estadouser ="<span class='text-green pointer' onclick='cambiarestadoUE(\""+usuario+"\",\"0\")'><i class='fa fa-lg fa-toggle-on' aria-hidden='true'></i></span>";}else{estadouser="<span class='text-muted pointer' onclick='cambiarestadoUE(\""+usuario+"\",\"1\")'><i class='fa fa-lg fa-toggle-off' aria-hidden='true'></i></span>";}

$("#eue"+usuario+"").html(estadouser);
});	
}


function cambiarestadoUS(usuario,estado){
$.get("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'cambiarestadoUsuario',id:usuario,idestado:estado,retornar:0},function(data){
// console.log(data);
if(estado == 1){estadouser ="<span class='text-green pointer' onclick='cambiarestadoUS(\""+usuario+"\",\"0\")'><i class='fa fa-lg fa-toggle-on' aria-hidden='true'></i></span>";}else{estadouser="<span class='text-muted pointer' onclick='cambiarestadoUS(\""+usuario+"\",\"1\")'><i class='fa fa-lg fa-toggle-off' aria-hidden='true'></i></span>";}

$("#eus"+usuario+"").html(estadouser);
});	
}
</script>