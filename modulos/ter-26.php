<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t26">
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

<div class="container-fluid">
<div class="row mt20 c_26">
<div class="col-12">
<h3>Auditar Salida</h3>
<div class="row">
<div class="col-3" id="c26_filtros">
<div class="card mt20">
<div class="card-body">
<div class="row">
<div class="col-12 top10" id="c26_fechas">
<input type="text" class="form-control" id="fechas_c26">
<input type="hidden" name="filter[start_date]" id="finicio" value=<?=date("Y-m-d");?>>
<input type="hidden" name="filter[end_date]" id="ffin" value=<?=date("Y-m-d");?>>
</div>
</div>

<div class="row mt-3">
<div class="col-12">
<label>Empresa</label>
<? htmlselect('cliente','cliente','clientes','id','nombre','','','','nombre','getPatentes(this)','','si','no','no');?>
</div>
</div>

<div class="row mt-3">
<div class="col-12">
<label>Patente</label>
<select name="patente" id="patente" class="form-control"><option value=0>--</option></select>
</div>
</div>

<div class="row mt-3">
<div class="col-12">
<label>Movimiento</label>
<? htmlselect('movimiento','movimiento','tipo_de_movimientos','id','tipo','','','where id NOT IN (1,3,5,11)','id','','','si','no','no');?>
</div>
</div>


<div class="row mt-5">
<div class="col-6 offset-md-6 text-end"><button type="button" class="btn btn-success btn-block" onclick="btnBuscar26()"><?=$i_buscar;?> Buscar</button></div>
</div>
</div>
</div>
</div>

<div class="col-9">
<div class="card mt20">
<div class="card-body">
<div class="row">
<div class="col-12">
<table class="table table-bordered table-striped" id="tb26_movimientos">
<thead>
<th>Fecha</th><th>Tarjeta</th><th>Patente</th><th>Empresa</th><th>Tipo</th><th>Tarifa</th><th>Saldo</th><th>Destino</th><th>Rendido</th><th>Usuario</th><th>Comentarios</th><th>&nbsp;</th>
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


</div>
</div>
</div>
<script>
$(function(){
$("#cliente,#patente,#movimiento").chosen();
});

$('#fechas_c26').daterangepicker({
timePicker: false,
timePickerIncrement: 10,
dateLimit: {
days: 365
},
linkedCalendars: false,
autoApply: true,
applyClass: 'hide',
cancelClass: 'hide',
"alwaysShowCalendars": true,
locale: {
format: 'DD-MM-YYYY',
customRangeLabel: 'Rango personalizado',
daysOfWeek: [
"Dom",
"Lun",
"Mar",
"Mie",
"Jue",
"Vie",
"Sab"
],
monthNames: [
"Enero",
"Febrero",
"Marzo",
"Abril",
"Mayo",
"Junio",
"Julio",
"Agusto",
"Septiembre",
"Octubre",
"Noviembre",
"Diciembre"
]
},ranges: {
'Hoy': [moment().startOf('day'), moment().endOf('day')],
'Ayer': [moment().startOf('day').subtract(1, 'days'), moment().endOf('day').subtract(1, 'days')],
'Últimos 7 días': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
'Últimos 30 días': [moment().subtract(29, 'days').startOf('day'), moment().endOf('day')],
'Este mes': [moment().startOf('month'), moment().endOf('month')]
}
}, function(start, end, label) {
$('#finicio').val(start.format('YYYY-MM-DD'));
$('#ffin').val(end.format('YYYY-MM-DD'));
// getResumenDeCargas();
});

$('#date_range_picker').on('apply.daterangepicker', function(_event, picker) {
if (!$('#finicio').val()) {
$('#finicio').val(picker.startDate.format('YYYY-MM-DD'));
}

if (!$('#ffin').val()) {
$('#ffin').val(picker.endDate.format('YYYY-MM-DD'));
}


});


function getPatentes(e){
id=$(e).val();
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getBusesxEmpresa',idcliente:id,retornar:0},function(data){

busesxempresa = $.parseJSON(data);
s_bus="<option value=0>--</option>";
$.each(busesxempresa, function(i,v){
s_bus+="<option value="+v.id+">"+v.patente+"</option>";	
});
$("#patente").html(s_bus);
$("#patente").trigger("chosen:updated");


});

}


let resBusqueda;
function btnBuscar26(){
let fdesde=$("#finicio").val();
let fhasta=$("#ffin").val();
let empresa = $("#cliente").val();
let patente = $("#patente").val();
let movimiento = $("#movimiento").val();

$("#tb26_movimientos tbody").html("<tr><td colspan='12' class='text-center'>buscando información..."+i_cargando+"</td></tr>");

var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getMovimientosTer26',desde:fdesde,hasta:fhasta,idempresa:empresa, idpatente:patente, idmovimiento:movimiento,retornar:0},function(data){
// console.log(data);
resBusqueda=$.parseJSON(data);
listarResultados();
});

}




function listarResultados(){
f="";
$.each(resBusqueda,function(i,v){
if(parseInt(v.idestado)===2){
accion="<span class='badge bg-warning'></span>";
spancomentario="<span class='badge bg-warning'>"+v.comentario+"</span>";
}else{accion="<button type='button' class='btn btn-warning btn-xs' onclick='salida("+i+")'>"+i_edit+" EDITAR</button>";spancomentario=v.comentario;}


f+="<tr><td>"+v.fecha+"</td><td>"+v.tarjeta+"</td><td>"+v.patente+"</td><td>"+v.empresa+"</td><td>"+v.tipo+"</td><td>"+v.monto+"</td><td>"+v.saldo+"</td><td>"+v.recorrido+"</td><td>"+v.estado+"</td><td>"+v.usuario+"</td><td>"+spancomentario+"</td><td class='text-center'>"+accion+"</td></tr>";
});
$("#tb26_movimientos tbody").html("");
let tb_mov26 = new DataTable('#tb26_movimientos');
$("#tb26_movimientos tbody").html("");
tb_mov26.destroy();
$("#tb26_movimientos tbody").html(f);
tb_mov26=$('#tb26_movimientos').DataTable({
"language":{ url: 'dtspanish.json'},
"paging": true,
"autoWidth": false,
"lengthChange": false,
"lengthMenu": [[20,-1], [20,"Todos"]],
"pageLength":20,
"searching": true,
"ordering": true,
"order": [[ 0, "desc" ]],
"info": true
});	

}

function salida(i){
mov = resBusqueda[i];	


var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getRecorridosxBusT26',idpatente:mov.idpatente,idrecorrido:mov.idrecorrido,retornar:0},function(data){

r_rec=$.parseJSON(data);
fnrec="<option value=0>SELECCIONAR</option>";
$.each(r_rec, function(i,v){
fnrec+="<option value="+i+">"+v.recorrido+"</option>";
});

formview="<div class='row'><div class='col-12'><table class='table table-bordered'><tr><td>Fecha</td><td>"+mov.fecha+"</td></tr><tr><td>Patente</td><td>"+mov.patente+"</td></tr><tr><td>Movimiento</td><td>"+mov.tipo+"</td></tr><tr><td>Recorrido</td><td>"+mov.recorrido+"</td></tr><tr><td>Monto</td><td>$"+enpesos(mov.monto)+"</td></tr><tr><td>Saldo</td><td>$"+enpesos(mov.saldo)+"</td></tr></table></div></div><div class='row mt20'><div class='col-12'><label>Editar Recorrido</label><select name='newrecorrido_es26' id='newrecorrido_es26' class='form-control'>"+fnrec+"</select></div></div><div class='row mt20'><div class='col-12'><label>Comentarios</label><textarea name='comentarios_et26' id='comentarios_et26' class='form-control rznone' rows='5'></textarea></div></div>";
$("#m_t26 .modal-header").removeClass("header-rojo").addClass("header-verde");
$("#m_t26 .modal-title").html("Auditar Salida");
$("#m_t26 .modal-body").html(formview);
$("#m_t26 .modal-footer").html("<button type='button' class='btn btn-warning' onclick='editarSalida(this,"+i+")'>Editar</button><button type='button' class='btn btn-danger' onclick='eliminarSalida(this,"+i+")'>Eliminar</button><button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Cancelar</button>");
$("#m_t26").modal("toggle")
	
});
}

function editarSalida(e,i){
$(e).html("espera por favor..."+i_cargando+"");
$(e).attr("disabled",true);
let s_idnew= parseInt($("#newrecorrido_es26").val());
let s_newrec= $("#newrecorrido_es26 option:selected").text();
let s_comentarios = $("#comentarios_et26").val();
let s_user=$("#username").val();
let s_iduser=$("#userid").val();
if(!s_idnew){alert("Es necesario seleccionar un nuevo recorrido para editar la salida"); return;}

mov = resBusqueda[i];	
// console.log(mov);
var randomNo = Math.floor(Math.random()*9999999);
$.post("operaciones.php", {numero:''+randomNo+'',operacion:'editarsalida',id:i,usuario:s_iduser,idnew:s_idnew,comentarios:s_comentarios,retornar:0},function(data){

res =$.parseJSON(data);
mov["idrecorrido"]=s_idnew;
mov["recorrido"]=s_newrec;
mov["editadopor"]=s_user;
mov["comentario"]=res.comentario;
mov["saldo"]=res.saldo;
mov["monto"]=res.tarifa;
resBusqueda[i]=mov;
listarResultados();
$("#m_t26").modal("hide");
});

// console.log(s_idnews)
// console.log(s_comentarios)



}

function eliminarSalida(e,i){
$(e).html("espera por favor..."+i_cargando+"");
$(e).attr("disabled",true);
let s_iduser=$("#userid").val();
let s_user=$("#username").val();
mov = resBusqueda[i];
var randomNo = Math.floor(Math.random()*9999999);
$.post("operaciones.php", {numero:''+randomNo+'',operacion:'eliminarsalida',id:i,usuario:s_iduser,retornar:0},function(data){

res =$.parseJSON(data);
mov["editadopor"]=s_user;
mov["comentario"]=res.comentario;
mov["saldo"]=res.saldo;
mov["monto"]=res.tarifa;
mov["idestado"]=res.idestado;
resBusqueda[i]=mov;
listarResultados();
$("#m_t26").modal("hide");
});

// console.log(s_idnews)
// console.log(s_comentarios)
}




function confirmarEliminación(e,id,monto,cuenta,codigo,cliente,fecha){
$(e).html("espera por favor..."+i_cargando+"");
$(e).attr("disabled",true);
let iduser=$("#userid").val();
let username=$("#username").val();
let comentarios = $("#comentarios_et26").val();
var randomNo = Math.floor(Math.random()*9999999);
$.post("operaciones.php", {numero:''+randomNo+'',operacion:'eliminarrecarga',m_id:id,m_monto:monto,m_cuenta:cuenta,m_codigo:codigo,m_cliente:cliente,m_fecha:fecha,m_usuario:iduser,m_comentarios:comentarios,m_username:username,retornar:0},function(data){
res = $.parseJSON(data);
if(!res.error){
resBusqueda[id]["comentario"]="Eliminada por : "+username+","+comentarios;
resBusqueda[id]["editadopor"]=username;
resBusqueda[id]["monto"]=0;
resBusqueda[id]["saldo"]=res.saldoanterior;
resBusqueda[id]["idestado"]=2;
resBusqueda[id]["estado"]="ELIMINADA";
listarResultados();
$("#m_t26").modal("hide");

}
// location.reload();
}); 

}


</script>