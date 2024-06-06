<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t53">
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
<div class="row mt-2">
<div class="col-md-12"><h4>Libro Mayor Custodia</h4></div>
</div>

<div class="row mt-2">
<div class="col-md-3" id="h_fechas">
<label>Periodo</label>
<input type="text" class="form-control" id="fechadc">
<input type="hidden" name="filter[start_date]" id="finicio" value=<?=date("Y-m-d");?>>
<input type="hidden" name="filter[end_date]" id="ffin" value=<?=date("Y-m-d");?>>
</div>
<div class="col-md-2">
<label>Bulto</label>
<?=htmlselect('bulto','bulto','bultos','bul_id','bul_nombre','','','','bul_id','','','si','no','no');?>
</div>
<div class="col-md-3">
<label>Ubicación</label>
<?=htmlselect('ubicacion','ubicacion','ubicaciones','ubi_id','ubi_nombre','','','','ubi_id','','','si','no','no');?>
</div>
<div class="col-md-2">
<label>Estado</label>
<select name="estado" id="estado" class="form-control">
<option value=0>Todos</option>
<option value=1>En Custodia</option>
<option value=2>Entregado</option>
</select>
</div>
<div class="col-md-2 mt27">
<button type="button" class="btn btn-success btn-block" id="btnbuscarmov" onclick="BuscarMovimientos()">Buscar</button>
</div>
</div>
<div class="row mt-3">
<div class="col-md-3">
<a href="#" class="btn btn-success" id="btnexcel"><?=$i_descarga;?> Descargar a Excel</a>
</div>
</div>

<div class="row mt-1">
<div class="col-md-12">
<div class="card">
<!--<div class="card-header">
<h3 class="card-title"></h3>
</div>-->
<div class="card-body">
<table class="table table-bordered table-striped" id="tb53_movimientos">
<thead>
<th>#</th>
<th>Código</th>
<th>F.Ingreso</th>
<th>TIempo en Custodia</th>
<th>Valor</th>
<th>Estado</th>
<th>F.Entrega</th>
<th>Valor Adicional</th>
<th>Consultas Retiro</th>
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
</div>
<script>
$('#fechadc').daterangepicker({
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


let movimientos;
let tb53_movimientos = new DataTable('#tb53_movimientos');
function BuscarMovimientos(){
datos = {};
datos["fdesde"]=$("#finicio").val();
datos["fhasta"]=$("#ffin").val();
datos["bulto"]=$("#bulto").val();
datos["ubicacion"]=$("#ubicacion").val();
datos["estado"]=$("#estado").val();
datos["total"] = $("input[name='total']").val();
json = JSON.stringify(datos);
// console.log(json);

// return;

var randomNo = Math.floor(Math.random()*9999999);
$("#btnbuscarmov").html("buscando "+i_cargando+"...").attr("disabled",true);
$.get("operaciones.php",{operacion:'getMovcustodia',numero:''+randomNo+'',filtros:json,retornar:0} ,function(data){
// console.log(data);
// return;
datos=$.parseJSON(data);
$("#btnexcel").attr("href",datos["informe"]);
movimientos=datos["movimientos"];
tbmcu="";
$.each(movimientos, function(index,valor){
if(parseInt(valor.cuentaconsultas) > 0 && parseInt(valor.estado)== 1){
cuentaconsultas="<span class='label label-important'>"+valor.cuentaconsultas+"</span>";
colorfila="error";
}else{
cuentaconsultas="<span class='label'>"+valor.cuentaconsultas+"</span>";
if(parseInt(valor["tiempotrans"]["HH"]) > 24 && parseInt(valor.estado)== 1){
colorfila="error";	
}else{
colorfila="";	
}
}
tbmcu+="<tr class="+colorfila+" id='filamcu"+valor.id+"'><td>"+valor.numero+"</td><td>"+valor.codigo+"</td><td>"+valor.fechaingreso+"</td><td>"+valor["tiempotrans"]["tiempo"]+"</td><td>$"+enpesos(valor.valor)+"</td><td>"+valor.estadoticket+"<td>"+valor.fecharetiro+"</td><td>$"+enpesos(valor.valoradicional)+"</td><td class='text-center'>"+cuentaconsultas+"</td><td><button type='button' class='btn btn-outline-primary' onclick='detalleMov("+index+")'>"+i_ver+"</td><td><button type='button' class='btn btn-outline-danger' onclick='quitarMov("+index+")'>"+i_borrar+"</button></tr>";
});


$("#tb53_movimientos tbody").html("");
tb53_movimientos.destroy();
$("#tb53_movimientos tbody").html(tbmcu);
tb53_movimientos=$('#tb53_movimientos').DataTable({
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

/* $("#tbmcu").dataTable().fnDestroy();
$("#tbmcu tbody").html(tbmcu);
$('#tbmcu').DataTable({
"language":{ url: 'dtspanish.json'},
"paging": true,
"order": [[0, "desc" ]],
"lengthChange": true,
"lengthMenu": [[20,-1], [20,"Todos"]],
"pageLength":20,
"searching": true,
//"ordering": true,
"info": true,
"autoWidth": false
}); */

$("#btnbuscarmov").html("Buscar").attr("disabled",false);
});
}

function detalleMov(index){
bulto=movimientos[index];
detalle="<h4>Bultos</h4>";
detalle+="<table class='table table-bordered table-striped' id='detallebulto'>";
detalle+="<thead><th>Cantidad</th><th>Bulto</th><th>Ubicaci&oacute;n</th><th>Tarifa</th><th>Total</th></thead><tbody>";
vhh=0;
totalbultos=0;
totalingresos=0;
hhformateado="";
$.each(bulto.bultos, function(index,valor){
totalbultos = totalbultos + parseInt(valor.cantidad);
totalingresos = totalingresos + parseInt(valor.total);
detalle+="<tr><td>"+valor.cantidad+"</td><td>"+valor.bulto+"</td><td class='text-center'>"+valor.ubicacion+"</td><td>$"+enpesos(valor.tarifa)+"</td><td>$"+enpesos(valor.total)+"</td></tr>";
});
hh = (parseInt(bulto["tiempotrans"]["HH"]) - 24);
if(parseInt(bulto.estado) == 2){
if(hh > 0){
had=hh;
vhh=bulto.valoradicional;	
}else{
had=0;
vhh=0;
}

}else{
if(hh > 0 ){
had = hh;// horas adicionales 
tad= parseInt($("#valortad").val());// valor tarifa adicional
vhh = tad * (totalbultos * had);
// txhh= parseInt(parseInt(valor.tarifa) / 24);
// vhh = vhh + (hh * txhh);
}else{
had=0;
vhh=0;
}
}
// hh = (parseInt(bulto["tiempotrans"]["HH"]) - 24);
// if(hh > 0 ){
// had=hh;
// txhh= parseInt(parseInt(valor.tarifa) / 24);
// vhh = vhh + (hh * txhh);
// }else{
// had=0;
// vhh=0;
// }
hhformateado=bulto["tiempotrans"]["tiempo"];
detalle+="<tr><td><b>"+totalbultos+"</b></td><td colspan=3></td><td><b>$"+enpesos(totalingresos)+"</b></td></tr>";
detalle+="</tbody></table>";
//detalle+="<IMG SRC='cds_codigobarras.php?claveunica="+bulto.codigo+"' id=cu></div>";

/****************** actualizado el 21-02-2020 ****************/
if(parseInt(bulto.estado) !=1){
fdev=bulto.fecharetiro;
usuaux=bulto.auxiliar;
}else{
fdev="--";	
usuaux="--";
}

detalle+="<table class='table table-bordered'><tr><td>Numero</td><td><b>"+bulto.numero+"</b></td></tr><tr><td>Fecha Ingreso</td><td>"+bulto.fechaingreso+"</td></tr><tr><td>Fecha Devoluci&oacute;n</td><td><input type='hidden' name='fechadevolucion' value='"+fdev+"'>"+fdev+"</td></tr><tr><td>Horas Adicionales</td><td>"+had+"</td></tr><tr><td>Tiempo transcurrido</td><td>"+hhformateado+"</td></tr><tr><td>Cobro Adicional</td><td>$"+enpesos(vhh)+"</td><input type='hidden' name='valoradicional' value="+vhh+"></tr><tr><td>Usuario Ingreso</td><td>"+bulto.usuario+"</td></tr><tr><td>Usuario Entrega</td><td>"+usuaux+"</td></tr></table>";
if(parseInt(bulto["cuentaconsultas"]) > 0){
detalle+="<h4>Consulta de Ticket</h4><table class='table table-bordered'>";
historial = bulto["historialconsultas"];
$.each(historial,function(ihmc,vhmc){
detalle+="<tr><td>Fecha Hora consulta</td><td>"+vhmc.fechaconsulta+"</td></tr>";
detalle+="<tr><td>Usuario Consulta</td><td>"+vhmc.auxiliar+"</td></tr>";
});
detalle+="</table>";
}


$("#m_t53 .modal-header").removeClass("header-rojo").addClass("header-verde");
$("#m_t53 .modal-title").html("Detalle de Movimiento");
$("#m_t53 .modal-body").html(detalle);
$("#m_t53 .modal-footer").html("<button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Cancelar</button>");
$("#m_t53").modal("toggle");

// $("#modalUbicacion .modal-header h3").html("Detalle de Movimiento");
// $("#modalUbicacion .modal-body").html(detalle);
// $("#modalUbicacion").modal('show')

}
function quitarMov(index){
mov =movimientos[index];
detalle="<table class='table table-bordered'><tr><td>Numero</td><td><b>"+mov.numero+"</b></td></tr><tr><td>Fecha Ingreso</td><td>"+mov.fechaingreso+"</td></tr></table>";

if(parseInt(mov.arendido)==1  || parseInt(mov.rendido) == 1){
if(parseInt(mov.estado)==2){
detalle+="<h5>El movimiento a eliminar se encuentra rendido y los bultos fueron entregados, ¿ desea continuar ? </h5>";
}else{
detalle+="<h5>El movimiento a eliminar se encuentra rendido, ¿ desea continuar ? </h5>";
}
detalle+="<button type='button' onclick='eliminarMov("+mov.id+")' class='btn btn-success'>Si, eliminar</button>";	
}else{
detalle+="<h5>El movimiento no ha sido rendido, ¿desea continuar ?</h5><button type='button' onclick='eliminarMov("+mov.id+",\""+mov.codigo+"\")' class='btn btn-success'>Si, eliminar</button>";		
}
//detalle+="<IMG SRC='cds_codigobarras.php?claveunica="+bulto.codigo+"' id=cu></div>";


$("#m_t53 .modal-header").removeClass("header-verde").addClass("header-rojo");
$("#m_t53 .modal-title").html("Eliminar de Movimiento");
$("#m_t53 .modal-body").html(detalle);
$("#m_t53 .modal-footer").html("<button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Cancelar</button>");
$("#m_t53").modal("toggle");

// $("#modalUbicacion .modal-header h3").html("Eliminar de Movimiento");
// $("#modalUbicacion .modal-body").html(detalle);
// $("#modalUbicacion").modal('show')
}

function cancelar(){
("#m_t51").modal("hide");
$("#codigo").focus();	
}
function eliminarMov(idmov,code){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php",{operacion:'eliminarMovCustodia',numero:''+randomNo+'',id:idmov,codigo:code,retornar:0} ,function(data){
$("#filamcu"+idmov+"").remove();

});

}


</script>