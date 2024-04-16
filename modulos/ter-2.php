<div class="container">
<div class="row mt20 c_ter2" id="v_listado">
<div class="col-12">
<h3>Listado de Empresas</h3>
<div class="card mt50">
<!--<div class="card-header">
<h3 class="card-title">Listado de Empresas</h3>
</div>-->
<div class="card-body">

<table class="table table-bordered table-striped" id="v_listaempresas">
<thead>
<th>Nombre</th>
<th>Rut</th>
<th>Télefono</th>
<th>Contacto</th>
<th>Boleta</th>
<th>Saldo</th>
<th></th>
<th></th>
</thead>
<tbody>
</tbody>
</table>
</div>
</div>
</div>
</div>

<div class="row mt20 oculto c_ter2" id="v_busesxempresa">
<div class="col-md-12">
<button type="button" class="btn btn-black btn-xs btn-rounded" onclick="verListado();"><?=$i_back;?> Volver al Listado</button>
</div>

<div class="col-12 mt50">
<h3 id="t_busesxempresa">Listado de Buses </h3>
<div class="card mt50">
<!--<div class="card-header">
<h3 class="card-title">Listado de Empresas</h3>
</div>-->
<div class="card-body">

<table class="table table-bordered table-striped" id="v_listabxe">
<thead>
<th>Patente</th>
<th>Número</th>
<th></th>
<th></th>
</thead>
<tbody>
</tbody>
</table>
</div>
</div>
</div>
</div>


<div class="row mt20 oculto c_ter2" id="v_recorridos">
<div class="col-md-12">
<button type="button" class="btn btn-black btn-xs btn-rounded" onclick="verListadoBuses();"><?=$i_back;?> Volver al Listado</button>
</div>

<div class="col-12 mt50">
<h3 id="t_recorridos">Listado de Buses </h3>
<div class="card mt50">
<!--<div class="card-header">
<h3 class="card-title">Listado de Empresas</h3>
</div>-->
<div class="card-body">

<table class="table table-bordered table-striped" id="v_listarecorridos">
<thead>
<th>Recorrido</th>
<th>Tipo Recorrido</th>
<th></th>
</thead>
<tbody>
</tbody>
</table>
</div>
</div>
</div>
</div>


</div>

<script>
$(function(){
getEmpresas();
});

let empresas;
let busesxempresa;
let tb = new DataTable('#v_listaempresas');
let tb_be = new DataTable('#v_listabxe');
let tb_rec = new DataTable('#v_listarecorridos');
function getEmpresas(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getEmpresas',retornar:0},function(data){
empresas= $.parseJSON(data);
console.log(empresas);
listarempresas();
});
}


function listarempresas(){
fe="";
$.each(empresas,function(i,v){
if(parseInt(v.boleta)){boleta="SI";}else{boleta="NO";}
if(parseInt(v.saldo) < 0){bgsaldo="danger";}else{bgsaldo="success";}
fe+="<tr><td>"+v.nombre+"</td><td>"+v.rut+"</td><td>"+v.telefono+"</td><td>"+v.contacto+"</td><td>"+boleta+"</td><td><span class='badge bg-"+bgsaldo+"'>$"+enpesos(v.saldo)+"</span></td><td class='text-center'><button type='button' class='btn btn-outline-primary btn-sm' onclick='verBuses("+i+","+v.id+")'>"+i_ver+" Ver Buses</button></td><td class='text-center'><button type='button' class='btn btn-outline-danger btn-sm'>"+i_borrar+"</button></td></tr>";
	
});

$("#v_listaempresas tbody").html("");
//$("#v_listaempresas").dataTable().fnDestroy();


tb.destroy();
// $('#v_listaempresas').empty();
$("#v_listaempresas tbody").html(fe);
tb=$('#v_listaempresas').DataTable({
"language":{ url: 'dtspanish.json'},
"paging": true,
"autoWidth": false,
"lengthChange": false,
"lengthMenu": [[20,-1], [20,"Todos"]],
"pageLength":20,
"searching": true,
"ordering": false,
// "order": [[ 0, "desc" ]],
"info": true,
// "columnDefs": [
// {"width": "5%","targets": 0},
// {"width": "85%","targets": 1},
// {"width": "5%","targets": 2},
// {"width": "5%","targets": 3}
// ]
});


}


function verBuses(i,id){
$(".c_ter2").hide();
empresa=empresas[i];
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getBusesxEmpresa',idcliente:id,retornar:0},function(data){
busesxempresa = $.parseJSON(data);
$("#t_busesxempresa").html("Listado buses "+empresa.nombre+"");
listarBuses();

$("#v_busesxempresa").show();

});


}

function listarBuses(){
fbe="";
$.each(busesxempresa,function(i,v){
fbe+="<tr><td>"+v.patente+"</td><td>"+v.numero+"</td><td class='text-center'><button type='button' class='btn btn-outline-primary btn-sm' onclick='verRecorrido("+i+","+v.id+")'>"+i_ver+" Ver Recorrido</button></td><td class='text-center'><button type='button' class='btn btn-outline-danger btn-sm'>"+i_borrar+"</button></td></tr>";
	
});

$("#v_listabxe tbody").html("");
tb_be.destroy();
$("#v_listabxe tbody").html(fbe);
tb_be=$('#v_listabxe').DataTable({
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
function verListado(){
$(".c_ter2").hide();
$("#v_listado").show();
}

function verRecorrido(i,id){
$(".c_ter2").hide();
bus=busesxempresa[i];
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getRecorridosxBus',idbus:id,retornar:0},function(data){
res = $.parseJSON(data);

recorridos = res.recorridos;
rxbus = res.rxbus;

// crear tabla con recorridos
fr="";
$.each(recorridos,function(i,v){
if(rxbus.includes(v.id)){check="checked";}else{check="";}
fr+="<tr><td>"+v.nombre+"</td><td>"+v.tipo+"</td><td class='text-center'><input type='checkbox' value="+v.id+" "+check+" onclick='asignarRecorrido(this,"+v.id+","+id+")'></td></tr>";
	
});

$("#v_listarecorridos tbody").html("");
tb_rec.destroy();
$("#v_listarecorridos tbody").html(fr);
tb_rec=$('#v_listarecorridos').DataTable({
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

//console.log();
$("#t_recorridos").html("Recorrido de Bus "+bus.patente+"");
$("#v_recorridos").show();

});

}

function asignarRecorrido(e,idrec,idbus){
let randomNo = Math.floor(Math.random()*9999999);
carga=0;
if($(e).is(':checked')){
carga=1
}
user=$("#userid").val();
$.post("operaciones.php", {numero:''+randomNo+'',operacion:'asignarecorrido',asignar:carga,irecorrido:idrec,ibus:idbus,usuario:user,retornar:0},function(data){

});



}

function verListadoBuses(){
$(".c_ter2").hide();
$("#v_busesxempresa").show();
}


</script>