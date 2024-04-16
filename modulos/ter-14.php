<div class="container">
<div class="row mt20 c_ter14" id="v14_listado">
<div class="col-12">
<h3>Listado Buses en Sistema</h3>
<div class="card mt20">
<!--<div class="card-header">
<h3 class="card-title"></h3>
</div>-->
<div class="card-body">
<table class="table table-bordered table-striped" id="v14_tbbuses">
<thead>
<th>Patente</th>
<th>Número</th>
<th>Empresa</th>
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

<div class="row mt20 oculto c_ter14" id="v_recorridos">
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
getAllBuses();
});
let buses;
let tb_buses = new DataTable('#v14_tbbuses');
function getAllBuses(){
$("#v14_tbbuses tbody").html("<tr><td colspan='5' class='text-center'><p class='text-green negrita'>Cargando informacón ..."+i_cargando+"</p></td></tr>");
// return;
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getAllBuses',retornar:0},function(data){
buses = $.parseJSON(data);
listarBuses();

});
}

function listarBuses(){
fb="";
$.each(buses,function(i,v){
fb+="<tr><td>"+v.patente+"</td><td>"+v.numero+"</td><td>"+v.empresa+"</td><td class='text-center'><button type='button' class='btn btn-outline-primary btn-sm' onclick='verRecorrido("+i+","+v.id+")'>"+i_ver+" Ver Recorrido</button></td><td class='text-center'><button type='button' class='btn btn-outline-danger btn-sm'>"+i_borrar+"</button></td></tr>";
	
});

$("#v14_tbbuses tbody").html("");
tb_buses.destroy();
$("#v14_tbbuses tbody").html(fb);
tb_buses=$('#v14_tbbuses').DataTable({
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

let tb_rec = new DataTable('#v_listarecorridos');
function verRecorrido(i,id){
$(".c_ter14").hide();
bus=buses[i];
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
$(".c_ter14").hide();
$("#v14_listado").show();
}


</script>