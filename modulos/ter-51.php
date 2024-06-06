<!-- nuevo formulario de salidas -->
<?
if ($_SESSION["perfilusuario"] == 7){
$btnvolver="";
}else{
$btnvolver = "<a href ='index.php?mod=2&subid=recaudacion' class='btn btn-primary'><span>Volver al Menu</span></a>";
}
// para mostrar fecha en español
setlocale(LC_TIME,"es_CL.UTF-8");
$idcajero=0;
$sql="select id,nombre from usuarios where activo=1 && perfil=3 && enturno=1";
$res=$link->query($sql);
if(mysqli_num_rows($res) == 0 ){
$mensaje="<div class='alert alert-warning'>".$btnvolver."&nbsp;&nbsp;No existe ning&uacute;n cajero  con sesi&oacute;n iniciada</div>";
}elseif(mysqli_num_rows($res) >  1 ){
$mensaje="<div class='alert alert-danger'>".$btnvolver."&nbsp;&nbsp;Existe m&aacute;s de un cajero con sesi&oacute;n iniciada</div>";
}else{
$fila=mysqli_fetch_array($res);
$mensaje="<div class='alert alert-success'>".$btnvolver."&nbsp;&nbsp;Cajero activo : ".$fila["nombre"]."</div>";
$idcajero=$fila["id"];
}

// buscar ultima tarifa por hora adicional
$sql0="select tad_valor from tarifacobroadicional where date(tad_fecha) <='".date("Y-m-d")."' order by tad_fecha desc limit 0,1";
// echo $sql0;
$res0=$link->query($sql0);
$fila0=mysqli_fetch_array($res0);
$valortad=$fila0["tad_valor"];

?>
<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t51">
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

<input type="hidden" name="idcajero" value="<?=$idcajero;?>">
<input type="hidden" id="fecha" value="<?=date("d/m/Y");?>">
<input type="hidden" name="idcajero" value=<?=$idcajero;?>>
<input type="hidden" id="valortad" value=<?=$valortad;?>>

<div class="row mt-2">
<div class="col-md-12"><?=$mensaje;?></div>
</div>
<div class="row mt-3">
<div class="col-md-12">
<h4>Registro de Entrega</h4>
<div class="card">
<div class="card-body">
<div class="row">
<div class="col-md-6">
<div class="row">
<div class="col-md-6">
<?
echo $_SESSION["ip_pos"]."<br>";
echo $_SESSION["ip_pc"]."<br>";
?>
</div>
</div>

<div class="row mt-2">
<div class="col-md-6">
<label>Ticket Custodia</label>
<input type="text" name="ticketcustodia" id="ticketcustodia" class="form-control" autofocus autocomplete="off">
</div>
<div class="col-md-3 mt27">
<button type="button" class="btn btn-success" onclick="consultarTicketCustodia()">Consultar</button>
</div>
</div>

<div class="row mt-2" id="alerta"></div>
<div class="row mt-2" id="detalle"></div>
<div id="ubicaciones"></div>

</div>
<div class="col-md-6">
<h2 id="liveclock"></h2>
<p><?=ucwords(strftime("%A , %d de %B del %Y"))?></p>
<div id="acciones">
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
iduser=$("#userid").val();
$("#ticketcustodia").focus();
$('#ticketcustodia').keypress(function(e){ if(e.which == 13){consultarTicketCustodia(); } });
getUbicaciones();
});


let ubicaciones;
function getUbicaciones(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php",{operacion:'getUbicacionesIngreso',numero:''+randomNo+'',retornar:0} ,function(data){
ubicaciones=$.parseJSON(data);
});
}


let bulto;
function consultarTicketCustodia(){
// limpiar div alerta
$("#alerta").html("");
iduser=$("#userid").val();
codigotiket=$("input[name='ticketcustodia']").val();
if(codigotiket !=""){
$.get("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'consultaTicketCustodia',ticket:''+codigotiket+'',auxiliar:iduser,retornar:0},function(data){
console.log(data);
bulto=$.parseJSON(data);
if(bulto.id==null){
$("#ticketcustodia").val("").focus();
alert("codigo de ticket no encontrado !!!");
return;
}
detalle="<div class='col-md-8'>";
accion="";
switch(parseInt(bulto.estado)){
case 1:
detalle+="<table class='table table-bordered table-striped' id='detallebulto'>";
detalle+="<thead><th>Cantidad</th><th>Bulto</th><th>Ubicaci&oacute;n</th><th>Tarifa</th><th>Total</th></thead><tbody>";
vhh=0;
totalbultos=0;
totalingresos=0;
hhformateado="";
$.each(bulto.bultos, function(index,valor){
if(valor.ubicacion=="A" || valor.ubicacion=="B" || valor.ubicacion=="C" || valor.ubicacion=="D"){color="btn-primary";}else{color="btn";}
if(valor.ubicacion =="Bodega de administracion"){clase="btnbodega";color="btn-inverse";}else{clase="btnubicacion";}

hhformateado=bulto["tiempotrans"]["tiempo"];
totalbultos = totalbultos + parseInt(valor.cantidad);
totalingresos = totalingresos + parseInt(valor.total);
detalle+="<tr><td>"+valor.cantidad+"</td><td>"+valor.bulto+"</td><td class='text-center'><button type='button' class='btn btn-default btnmedio' onclick='cambiarUbicacion("+bulto.id+","+index+");'>"+valor.ubicacion+"</button></td><td>$"+enpesos(valor.tarifa)+"</td><td>$"+enpesos(valor.total)+"</td></tr>";
});


hh = (parseInt(bulto["tiempotrans"]["HH"]) - 24);
console.log("valor hh : "+hh);
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

detalle+="<tr><td><b>"+totalbultos+"</b></td><td colspan=3></td><td><b>$"+enpesos(totalingresos)+"</b></td></tr>";
detalle+="</tbody></table>";

if(vhh > 0){
// tiene cobro adicional
alerta="<div class='col-md-12'><div class='alert alert-danger'><h4>Ticket con cobro adicional : $"+enpesos(vhh)+"</b></h4></div></div>";
$("#alerta").html(alerta);
}

accion+="<input type='hidden' id='codigoconsulta' value="+codigotiket+"><IMG SRC='cds_codigobarras.php?claveunica="+codigotiket+"' id=cu></div>";



if(parseInt(bulto.liberado)==1){
vhh=bulto.valoradicional;
detalle+="<p><b>Observaciones Administraci&oacute;n :</b><br>"+bulto.observaciones+"</p>";
}
accion+="<table class='table table-bordered'><tr><td>Numero</td><td><b>"+bulto.numero+"</b></td></tr><tr><td>Fecha Ingreso</td><td>"+bulto.fechaingreso+"</td></tr><tr><td>Fecha Devoluci&oacute;n</td><td><input type='hidden' name='fechadevolucion' value='"+bulto.fechadevolucion+"'>"+bulto.fechadevolucion+"</td></tr><tr><td>Horas Adicionales</td><td>"+had+"</td></tr><tr><td>Tiempo transcurrido</td><td>"+hhformateado+"</td></tr><tr><td>Cobro Adicional</td><td>$"+enpesos(vhh)+"</td><input type='hidden' name='valoradicional' value="+vhh+"></tr></table>";
accion+="<div class='row btnentrega'><div class='col-md-6'><button type='button' class='btn btn-success btn-block' onclick='entregarBulto("+bulto.id+",1,this)'>Entregar</button></div><div class='col-md-6'><button type='button' class='btn  btn-default  btn-block'  onclick='entregarBulto("+bulto.id+",2,this)'>Entrega Administraci&oacute;n</button></div></div>";
/******** ACTUALIZADO EL 20-02-2020 *****************/
accion+="<div class='row btnentrega mt-2'><div class='col-md-6'><button type='button' class='btn btn-default btn-block' onclick='entregarBulto("+bulto.id+",3,this)'>P&eacute;rdida de Ticket</button></div><div class='col-md-6'><button type='button' class='btn btn-danger  btn-block'  onclick='cancelarRetiro()'>Cancelar</button></div></div>";
$("#ticketcustodia").attr("disabled",true);
break;
case 2:
//<button type='button' class='close' data-dismiss='alert'>&times;</button>
alerta="<div class='col-md-12'><div class='alert alert-warning'><h4>El bulto asociado a este ticket (<b>"+codigotiket+"</b>) ya fue entregado !</h4>Fecha de entrega <b>"+bulto.fecharetiro+"</b></div></div>";
$("#alerta").html(alerta);
/* setTimeout(function(){
$("#alerta").fadeOut(1000);}, 5000); */
break;

case 3:
//<button type='button' class='close' data-dismiss='alert'>&times;</button>
alerta="<div class='col-md-12'><div class='alert alert-warning'><h4>El bulto asociado a este ticket (<b>"+codigotiket+"</b>) debe ser entregado por administracion !</h4>Fecha de Igreso <b>"+bulto.fechaingreso+"</b></div></div>";
$("#alerta").html(alerta);
/* setTimeout(function(){
$("#alerta").fadeOut(1000);}, 5000); */
break;

}
detalle+="</div>";
$("#detalle").html(detalle);
$("#acciones").html(accion);
$("input[name='ticketcustodia']").val("").focus();
});
}else{
$("input[name='ticketcustodia']").val("").focus();
}
}

/******** FUNCIONA AGREGADA EL 20-02-2020 *****************/
function cancelarRetiro(){
$("#detalle").html("");
$("#acciones").html("");
$("#alerta").html("");
$("#ticketcustodia").attr("disabled",false).val("").focus();
}

function cambiarUbicacion(idbulto,indiceubicacion){
btnubicaciones="";
$.each(ubicaciones,function(index1,valor1){
if(valor1.nombre=="A" || valor1.nombre=="B" || valor1.nombre=="C" || valor1.nombre=="D"){
color="btn-primary";
}else{
color="btn default";
}
if(valor1.nombre =="Bodega de administracion"){clase="btnbodega";color="btn-inverse";}else{clase="btnubicacion";}
if(bulto["bultos"][indiceubicacion].ubicacion == valor1.nombre){activo="disabled";}else{activo="";}
btnubicaciones+="<button "+activo+" type='button' class='btn "+color+" "+clase+"' onclick='selectUbicacion(\""+index1+"\",\""+valor1.nombre+"\",\""+indiceubicacion+"\",\""+bulto["bultos"][indiceubicacion].id+"\",this)'><b>"+valor1.nombre+"</b></button>";
});

$("#m_t51 .modal-header").removeClass("header-rojo").addClass("header-verde");
$("#m_t51 .modal-title").html("Cambiar Ubicación");
$("#m_t51 .modal-body").html(btnubicaciones);
$("#m_t51 .modal-footer").html("<button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Cancelar</button>");
$("#m_t51").modal("toggle")

// $("#modalUbicacion .modal-body").html(btnubicaciones);
// $("#modalUbicacion").modal('show')
}

function entregarBulto(index,accion,e){
accion = parseInt(accion);
usercaja =$("input[name='idcajero']").val();
useraxiliar = $("#userid").val();
adicional =$("input[name='valoradicional']").val();
fechadev =$("input[name='fechadevolucion']").val();

// controlar con validacion casos con cobros adicionales
switch(accion){
case 1:
if(parseInt(adicional) > 0){
$("#m_t51 .modal-header").removeClass("header-rojo").addClass("header-verde");
$("#m_t51 .modal-title").html("Confirmar Operación de Entrega");
$("#m_t51 .modal-body").html("<div class='row'><div class='col-md-12'>¿Enviar cobro para que sea gestionado por cajero en Turno?</div></div>");
$("#m_t51 .modal-footer").html("<button type='button' class='btn btn-success' data-bs-dismiss='modal' onclick='enviarCobroaCaja()'>Confirmar</button>");
$("#m_t51").modal("toggle");	
}else{
confirmarRetiro(accion,index);	
}
break;
case 2:
$("#m_t51 .modal-header").removeClass("header-rojo").addClass("header-verde");
$("#m_t51 .modal-title").html("Confirmar Operación de Entrega");
$("#m_t51 .modal-body").html("<div class='row'><div class='col-md-12'>¿Enviar cobro para que sea gestionado por Administración?</div></div>");
$("#m_t51 .modal-footer").html("<button type='button' class='btn btn-success' data-bs-dismiss='modal' onclick='confirmarRetiro("+accion+","+index+")'>Confirmar</button>");
$("#m_t51").modal("toggle");
break;
case 3:
$("#m_t51 .modal-header").removeClass("header-rojo").addClass("header-verde");
$("#m_t51 .modal-title").html("Confirmar Operación de Entrega");
$("#m_t51 .modal-body").html("<div class='row'><div class='col-md-12'>¿Confirma perdida de ticket?</div></div>");
$("#m_t51 .modal-footer").html("<button type='button' class='btn btn-success' data-bs-dismiss='modal' onclick='confirmarRetiro("+accion+","+index+")'>Confirmar</button>");
$("#m_t51").modal("toggle");
break;

}





// return;



/* if(parseInt(adicional) > 0 && parseInt(accion) == 1){
// existe diferencia a cobrar la cual debe ser validada por el cajero
btnantes=$(e).html();
$(e).html("Enviando cobro a caja ...<i class='fa fa-refresh fa-spin fa-fw'></i>").attr("disabled",true);
$.get("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'posthistorialmcu',cajero:usercaja,auxiliar:useraxiliar,tipo:'cobroadicional',codigo:$("#codigoconsulta").val(),detalle:'ticket custodia registra un cobro adicional por $'+enpesos(adicional)+'',monto:adicional,retornar:0},function(data){
// console.log(data);
$("#detalle").html("");
$("#acciones").html("");
alerta="<div class='col-md-12'><div class='alert alert-danger'><h4>El bulto asociado a este ticket (<b>"+codigotiket+"</b>) registra un cobro adicional y debe ser entregado por el cajero de turno !</h4>Fecha de Igreso <b>"+bulto.fechaingreso+"</b></div></div>";
$("#alerta").html(alerta);
// location.reload();
});
return;
}else{
// console.log(fechadev);
// return;
$.get("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'entregarBulto',idaccion:accion,id:index,cajero:usercaja,auxiliar:useraxiliar,valoradicional:adicional,fecharetiro:fechadev,retornar:'no'},function(data){
// console.log(data);
location.reload();
});	
}
 */

}

function enviarCobroaCaja(){
// $(e).html("Enviando cobro a caja ...<i class='fa fa-refresh fa-spin fa-fw'></i>").attr("disabled",true);
// return;
usercaja =$("input[name='idcajero']").val();
useraxiliar = $("#userid").val();
adicional =$("input[name='valoradicional']").val();
fechadev =$("input[name='fechadevolucion']").val();
$.get("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'posthistorialmcu',cajero:usercaja,auxiliar:useraxiliar,tipo:'cobroadicional',codigo:$("#codigoconsulta").val(),detalle:'ticket custodia registra un cobro adicional por $'+enpesos(adicional)+'',monto:adicional,retornar:0},function(data){
// console.log(data);
$("#detalle").html("");
$("#acciones").html("");
alerta="<div class='col-md-12'><div class='alert alert-danger'><h4>El bulto asociado a este ticket (<b>"+codigotiket+"</b>) registra un cobro adicional y debe ser entregado por el cajero de turno !</h4>Fecha de Igreso <b>"+bulto.fechaingreso+"</b></div></div>";
$("#alerta").html(alerta);
// location.reload();
});
}

function confirmarRetiro(accion,index){
usercaja =$("input[name='idcajero']").val();
useraxiliar = $("#userid").val();
adicional =$("input[name='valoradicional']").val();
fechadev =$("input[name='fechadevolucion']").val();
$.get("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'entregarBulto',idaccion:accion,id:index,cajero:usercaja,auxiliar:useraxiliar,valoradicional:adicional,fecharetiro:fechadev,retornar:0},function(data){
// console.log(data);
location.reload();
});	
}

function selectUbicacion(idubicacion,ubicacion,indice,idbulto,e){
	
	
$.get("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'actualizarUbicacion',mcu_ubicacion:idubicacion,mcu_id:idbulto,retornar:0},function(data){
// location.reload()
bulto["bultos"][indice]["idubicacion"]=idubicacion;
bulto["bultos"][indice]["ubicacion"]=ubicacion;
refrescarsinfoegreso();
cancelar();
});

}

function refrescarsinfoegreso(){
totalbultos=0;
totalingresos=0;
detalle="<div class='col-md-8'><table class='table table-bordered table-striped' id='detallebulto'>";
detalle+="<thead><th>Cantidad</th><th>Bulto</th><th>Ubicaci&oacute;n</th><th>Tarifa</th><th>Total</th></thead><tbody>";
$.each(bulto.bultos, function(index,valor){
totalbultos = totalbultos + parseInt(valor.cantidad);
totalingresos = totalingresos + parseInt(valor.total);
detalle+="<tr><td>"+valor.cantidad+"</td><td>"+valor.bulto+"</td><td class='text-center'><button type='button' class='btn btn-default btnmedio' onclick='cambiarUbicacion("+bulto.id+","+index+");'>"+valor.ubicacion+"</button></td><td>$"+enpesos(valor.tarifa)+"</td><td>$"+enpesos(valor.total)+"</td></tr>";

});
detalle+="<tr><td><b>"+totalbultos+"</b></td><td colspan=3></td><td><b>$"+enpesos(totalingresos)+"</b></td></tr>";
detalle+="</tbody></table></div>";
$("#detalle").html(detalle);
}



function show5(){
if (!document.layers&&!document.all&&!document.getElementById)
return
var Digital=new Date();

// Digital.setHours(Digital.getHours() + 10);
// console.log(Digital);
var hours=Digital.getHours();
var minutes=Digital.getMinutes();
var seconds=Digital.getSeconds();
if (hours <=9){hours="0"+hours;}
if (minutes<=9){minutes="0"+minutes;}
if (seconds<=9){seconds="0"+seconds;}
myclock=""+hours+":"+minutes+":"+seconds+"";
// console.log(myclock);
if(hours == 00 && minutes== 00 && seconds == 00){
location.reload();
}

if (document.layers){
document.layers.liveclock.document.write(myclock)
document.layers.liveclock.document.close()
}
else if (document.all)
liveclock.innerHTML=myclock
else if (document.getElementById)
document.getElementById("liveclock").innerHTML=myclock
setTimeout("show5()",1000)
}
window.onload=show5


function cancelar(){
$("#m_t51").modal("hide")
$("#codigo").focus();	
}

</script>