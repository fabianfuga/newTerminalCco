<?
setlocale(LC_ALL,"es_ES");
$idcajero=0;
$sql="select id,nombre from usuarios where activo=1 && perfil=3 && enturno=1";
$res=$link->query($sql);
if(mysqli_num_rows($res) == 0 ){
// $mensaje="<div class='infoerror'>".$btnvolver."&nbsp;&nbsp;No existe ning&uacute;n cajero  con sesi&oacute;n iniciada</div>";
}elseif(mysqli_num_rows($res) >  1 ){
// $mensaje="<div class='infoerror'>".$btnvolver."&nbsp;&nbsp;Existe m&aacute;s de un cajero con sesi&oacute;n iniciada</div>";
}else{
$fila=mysqli_fetch_array($res);
// $mensaje="<div class='infosuccess'>".$btnvolver."&nbsp;&nbsp;Cajero activo : ".$fila["nombre"]."</div>";
$idcajero=$fila["id"];
}
// buscar ultima tarifa por hora adicional
$sql0="select tad_valor from tarifacobroadicional where date(tad_fecha) <='".date("Y-m-d")."' order by tad_fecha desc limit 0,1";
// echo $sql0;
$res0=$link->query($sql0);
$fila0=mysqli_fetch_array($res0);
$valortad=$fila0["tad_valor"];
?>
<input type="hidden" name="idcajero" value=<?=$idcajero;?>>
<input type="hidden" id="valortad" value=<?=$valortad;?>>
<div class="container">
<div class="row mt20 c_36" id="v36_listado">
<div class="col-12">
<h3>Tickets de custodia por entregar</h3>
<!--<div class="callout callout-warning">

<p class='text-muted'><span class='text-danger'><?=$i_info;?></span>&nbsp;&nbsp;Los datos modificados aquí afectan completamente el resultado de las operaciones sobre la plataforma</p>
</div>

<small></small>-->
<div class="card mt20">
<!--<div class="card-header">
<h3 class="card-title"></h3>
</div>-->
<div class="card-body">
<div class="row">
<div class="col-md-12">
<table class="table table-bordered table-striped" id="v52_tbtickets">
<thead>
<th>Numero</th>
<th>Tipo</th>
<th>F.Ingreso</th>
<th>F.Retiro</th>
<th>N° Bultos</th>
<th>Tarifa</th>
<th>H.Adicional</th>
<th>Cobro Adicional</th>
<th></th>
<th></th>
</thead>
<tbody>
</tbody>
</table>
</div>
</div>

<div class="row" id="detalleticket">
<div class="col-md-6" id="detalle"></div>
<div class="col-md-6" id="acciones"></div>
</div>

<?if(isset($_REQUEST["imprimir"])){
?>
<iframe src="formulariocustodia.php?id=<?=$_REQUEST["imprimir"];?>" style="width:1px;height:1px;border:0px;"></iframe>
<?}?>


</div>
</div>
</div>
</div>
</div>

<script>
$(function(){
getTicketsCustodia();
});
window.tickets;
function getTicketsCustodia(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php",{operacion:'getTicketsCustodia',numero:''+randomNo+'',retornar:0} ,function(data){
tickets=$.parseJSON(data);
console.log(tickets);
fbultos="";
$.each(tickets, function(index,valor){
totalbultos=0;
totalingresos=0;
had=0;
vhh=0;
$.each(valor.bultos, function(index1,valor1){
totalbultos = totalbultos + parseInt(valor1.cantidad);
totalingresos = totalingresos + parseInt(valor1.total);
});

hh = (parseInt(valor["tiempotrans"]["HH"]) - 24);
// console.log("valor hh : "+hh);
if(hh > 0 ){
had = hh;// horas adicionales 
tad= parseInt($("#valortad").val());// valor tarifa adicional
console.log(tad+" * ("+totalbultos+" * "+had+")");
vhh = tad * (totalbultos * had);
// txhh= parseInt(parseInt(valor.tarifa) / 24);
// vhh = vhh + (hh * txhh);
}else{
had=0;
vhh=0;
}


fbultos+="<tr><td>"+valor.numero+"</td><td>"+valor.estadoticket+"</td><td>"+valor.fechaingreso+"</td><td>"+valor.fechadevolucion+"</td><td>"+totalbultos+"</td><td>$"+enpesos(totalingresos)+"</td><td>"+had+"</td><td>$"+enpesos(vhh)+"</td><td class='text-center'><button type='button' class='btn btn-outline-primary btn-xs' onclick='detalleTicket("+index+")'>Detalle</button></td><td class='text-center'><button type='button' class='btn btn-success btn-xs' onclick='revertirEstado("+valor.id+")'>Enviar a Custodia</button></td></tr>";
});
$("#v52_tbtickets tbody").html(fbultos);

});
}

function revertirEstado(i){
$.post("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'devACustodia',id:i,retornar:0},function(data){
location.reload();
});	
}


function detalleTicket(index){
ticket = tickets[index];
iduser=$("#userid").val();
detalle="";
accion="";
detalle+="<h2>Detalle de Ticket</h2><IMG SRC='cds_codigobarras.php?claveunica="+ticket.codigo+"' id=cu><br><table class='table table-bordered table-striped' id='detallebulto'>";
detalle+="<thead><th>Cantidad</th><th>Bulto</th><th>Ubicaci&oacute;n</th><th>Tarifa</th><th>Total</th></thead><tbody>";
vhh=0;
totalbultos=0;
totalingresos=0;
hhformateado="";
$.each(ticket.bultos, function(index,valor){
hhformateado=ticket["tiempotrans"]["tiempo"];
totalbultos = totalbultos + parseInt(valor.cantidad);
totalingresos = totalingresos + parseInt(valor.total);
detalle+="<tr><td>"+valor.cantidad+"</td><td>"+valor.bulto+"</td><td>"+valor.ubicacion+"</td><td>$"+enpesos(valor.tarifa)+"</td><td>$"+enpesos(valor.total)+"</td></tr>";
});

hh = (parseInt(ticket["tiempotrans"]["HH"]) - 24);
// console.log("valor hh : "+hh);
if(hh > 0 ){
had = hh;// horas adicionales 
tad= parseInt($("#valortad").val());// valor tarifa adicional
// console.log(tad+" * ("+totalbultos+" * "+had+")");
vhh = tad * (totalbultos * had);
// txhh= parseInt(parseInt(valor.tarifa) / 24);
// vhh = vhh + (hh * txhh);
}else{
had=0;
vhh=0;
}
detalle+="<tr><td><b>"+totalbultos+"</b></td><td colspan=3></td><td><b>$"+enpesos(totalingresos)+"</b></td></tr>";
detalle+="</tbody></table>";

if(parseInt(ticket.estado)==4){
// ticket perdido
detalle+="<div class='row'><div class='col-md-6'><label>Nombre</label><input type='text' name='retiranombre' id='retiranombre' class='form-control' autocomplete='off'></div><div class='col-md-6'><label>Rut</label><INPUT TYPE='text' name='retirarut'  id='retirarut' class='form-control' autocomplete='off'></div></div>";
detalle+="<div class='row mt-2'><div class='col-md-12'><label>Direccion</label><input type='text' name='retiradireccion' id='retiradireccion' class='form-control' autocomplete='off'></div></div>";
detalle+="<div class='row mt-2'><div class='col-md-6'><label>Telefono</label><INPUT TYPE='text' name='retiratelefono'  id='retiratelefono' class='form-control' autocomplete='off'></div><div class='col-md-6'><label>Descripcion de Bultos</label><textarea name='retiradescripcion' rows=5 class='rznone form-control' autocomplete='off'></textarea></div></div>";
}
//accion+="<IMG SRC='cds_codigobarras.php?claveunica="+ticket.codigo+"' id=cu></div>";
accion+="<table class='table table-bordered'><tr><td>Numero</td><td><b>"+ticket.numero+"</b></td></tr><tr><td>Fecha Ingreso</td><td>"+ticket.fechaingreso+"</td></tr><tr><td>Fecha Devoluci&oacute;n</td><td><input type='hidden' name='fechadevolucion' value='"+ticket.fechadevolucion+"'>"+ticket.fechadevolucion+"</td></tr><tr><td>Horas Adicionales</td><td>"+had+"</td></tr><tr><td>Tiempo transcurrido</td><td>"+hhformateado+"</td></tr><tr><td>Cobro Adicional</td><td><input type='text' class='form-control' name='montoadicional' value="+vhh+"></td><input type='hidden' name='valoradicional' value="+vhh+"></tr><tr><td>Observaciones</td><td><textarea name='observacionesentrega' class='form-control rznone' rows=5></textarea></td></tr></table>";
accion+="<div class='row'><div class='col-md-6'><button type='button' class='btn btn-success  btn-block' onclick='liberarBulto("+ticket.id+","+ticket.estado+")'>Liberar</button></div></div>";

$("#detalle").html(detalle);
$("#acciones").html(accion);
$("#listadotickets").hide();
$("#detalleticket").show();

}
/*************** funcion actualizada el 13-03-2020 *************/
function liberarBulto(index,estado){
usercaja =$("input[name='idcajero']").val();
useraxiliar = $("#userid").val();
adicional =$("input[name='montoadicional']").val();
fechadev =$("input[name='fechadevolucion']").val();
observaciones =$("textarea[name='observacionesentrega']").val();
nombre=$("input[name='retiranombre']").val();
rut=$("input[name='retirarut']").val();
direccion=$("input[name='retiradireccion']").val();
telefono=$("input[name='retiratelefono']").val();
descripcion =$("textarea[name='retiradescripcion']").val();

// console.log(fechadev);
// return;
$.post("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'liberarBulto',id:index,cajero:usercaja,auxiliar:useraxiliar,valoradicional:adicional,fecharetiro:fechadev,mcu_observaciones:observaciones,retiranombre:nombre,retirarut:rut,retiradireccion:direccion,retiratelefono:telefono,retiradescripcion:descripcion,estadoticket:estado,retornar:0},function(data){
if(parseInt(estado)==4){
var url      = window.location.href; 
// console.log(url+"&imprimir="+index+"");
window.location.assign(url+"&imprimir="+index+"");	
}else{
location.reload();
}

});

}

</script>