<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t27">
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
<div class="row mt20 c_27">
<div class="col-12">
<h3>Auditar Recarga</h3>
<div class="row">
<div class="col-3" id="c27_filtros">
<div class="card mt20">
<div class="card-body">
<div class="row">
<div class="col-12 top10" id="c27_fechas">
<input type="text" class="form-control" id="fechas_c27">
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
<? htmlselect('movimiento','movimiento','tipo_de_movimientos','id','tipo','','','where id IN (1,3,5,11)','id','','','si','no','no');?>
</div>
</div>


<div class="row mt-5">
<div class="col-6 offset-md-6 text-end"><button type="button" class="btn btn-success btn-block" onclick="btnBuscar27()"><?=$i_buscar;?> Buscar</button></div>
</div>
</div>
</div>
</div>

<div class="col-9">
<div class="card mt20">
<div class="card-body">
<div class="row">
<div class="col-12">
<table class="table table-bordered table-striped" id="tb27_movimientos">
<thead>
<th>Fecha</th><th>Tarjeta</th><th>Patente</th><th>Empresa</th><th>Tipo</th><th>Monto</th><th>Saldo</th><th>Rendido</th><th>Usuario</th><th>Comentarios</th><th>&nbsp;</th>
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

$('#fechas_c27').daterangepicker({
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
function btnBuscar27(){
let fdesde=$("#finicio").val();
let fhasta=$("#ffin").val();
let empresa = $("#cliente").val();
let patente = $("#patente").val();
let movimiento = $("#movimiento").val();

$("#tb27_movimientos tbody").html("<tr><td colspan='11' class='text-center'>buscando información..."+i_cargando+"</td></tr>");

var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getMovimientosTer27',desde:fdesde,hasta:fhasta,idempresa:empresa, idpatente:patente, idmovimiento:movimiento,retornar:0},function(data){
console.log(data);
resBusqueda=$.parseJSON(data);
listarResultados();
});

}




function listarResultados(){
f="";
$.each(resBusqueda,function(i,v){
if(parseInt(v.idestado)===2){
accion="<span class='badge bg-danger'></span>";
spancomentario="<span class='badge bg-danger'>"+v.comentario+"</span>";
}else{accion="<button type='button' class='btn btn-danger btn-xs' onclick='eliminarRecarga("+i+")'>"+i_borrar+" ELIMINAR</button>";spancomentario=v.comentario;}


f+="<tr><td>"+v.fecha+"</td><td>"+v.tarjeta+"</td><td>"+v.patente+"</td><td>"+v.empresa+"</td><td>"+v.tipo+"</td><td>"+v.monto+"</td><td>"+v.saldo+"</td><td>"+v.estado+"</td><td>"+v.usuario+"</td><td>"+spancomentario+"</td><td class='text-center'>"+accion+"</td></tr>";
});
$("#tb27_movimientos tbody").html("");
let tb_mov27 = new DataTable('#tb27_movimientos');
$("#tb27_movimientos tbody").html("");
tb_mov27.destroy();
$("#tb27_movimientos tbody").html(f);
tb_mov27=$('#tb27_movimientos').DataTable({
"language":{ url: 'dtspanish.json'},
"paging": true,
"autoWidth": false,
"lengthChange": false,
"lengthMenu": [[20,-1], [20,"Todos"]],
"pageLength":20,
"searching": true,
"ordering": true,
"order": [[ 0, "asc" ]], 
"info": true
});	

}

function eliminarRecarga(i){
mov = resBusqueda[i];	
formview="<div class='row'><div class='col-12'><table class='table table-bordered'><tr><td>Fecha</td><td>"+mov.fecha+"</td></tr><tr><td>Empresa</td><td>"+mov.empresa+"</td></tr><tr><td>Patente</td><td>"+mov.patente+"</td></tr><tr><td>Movimiento</td><td>"+mov.tipo+"</td></tr><tr><td>Monto</td><td>$"+enpesos(mov.monto)+"</td></tr><tr><td>Saldo</td><td>$"+enpesos(mov.saldo)+"</td></tr></table></div></div><div class='row'><div class='col-12'><label>Comentarios</label><textarea name='comentarios_et27' id='comentarios_et27' class='form-control rznone' rows='5'></textarea></div></div>";
$("#m_t27 .modal-header").removeClass("header-rojo").addClass("header-verde");
$("#m_t27 .modal-title").html("Eliminar movimiento");
$("#m_t27 .modal-body").html(formview);
$("#m_t27 .modal-footer").html("<button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Cancelar</button><button type='button' class='btn btn-success' onclick='confirmarEliminación(this,"+i+","+mov.monto+","+mov.idcuenta+",\""+mov.codigo+"\","+mov.idcliente+","+mov.fechaunix+")'>Confirmar</button>");
$("#m_t27").modal("toggle")




}

function confirmarEliminación(e,id,monto,cuenta,codigo,cliente,fecha){
$(e).html("espera por favor..."+i_cargando+"");
$(e).attr("disabled",true);
let iduser=$("#userid").val();
let username=$("#username").val();
let comentarios = $("#comentarios_et27").val();
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
$("#m_t27").modal("hide");

}
// location.reload();
}); 

}


</script>