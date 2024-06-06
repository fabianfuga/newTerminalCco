<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t6">
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
<?
if($_SESSION["perfilusuario"]==3){
echo "<div class='row mt-2'><div class='col-md-12'><div class='alert alert-success'><a href ='index.php?mod=2&subid=recaudacion' class='btn btn-primary '><span>Volver al Menu</span></a></div></div></div>";
}
?>
<div id="validaciondetarjeta" class="toasts-top-right fixed oculto">
<div class="toast bg-danger fade show" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-header"><strong class="mr-auto">Error al registrar recarga</strong><button  type="button" class="ml-2 mb-1 close" aria-label="Close" onclick='cerrarAlerta()'><span aria-hidden="true">×</span></button></div><div class="toast-body"></div></div>
</div>

<div id="notify_recarga" class="toasts-top-right fixed oculto">
<div class="toast bg-danger fade show" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-header"><strong class="mr-auto">Error al registrar recarga</strong><button  type="button" class="ml-2 mb-1 close" aria-label="Close" onclick='cerrarAlerta()'><span aria-hidden="true">×</span></button></div><div class="toast-body"></div></div>
</div>


<div class="row mt20 c_6" id="v6">
<div class="col-12">
<h3>Rercargar Tarjeta</h3>
<div class="card mt20">
<!--<div class="card-header">
<h3 class="card-title"></h3>
</div>-->
<div class="card-body">
<div class="row mt-3">

<div class="col-4">
<div class="row mb-3">
<div class="col-4"><label class="col-form-label">Monto</label></div>
<div class="col-4"><input type="text" id="monto" class="form-control" name="monto"></div>
</div>

<div class="row mb-3">
<div class="col-4"><label class="col-form-label">Dinero Recibido</label></div>
<div class="col-4"><input type="text" id="dinero" class="form-control" name="dinero"></div>
</div>

<div class="row mb-3">
<div class="col-4"><label class="col-form-label">Vuelto</label></div>
<div class="col-4"><input type="text" id="vuelto" class="form-control" name="vuelto" disabled></div>
</div>

<div class="row mb-3">
<div class="col-4"><label class="col-form-label">Código Tarjeta</label></div>
<div class="col-8"><input type="text" id="codigo" class="form-control" name="codigo"></div>
</div>

<div class="row mb-3">
<div class="col-8 offset-md-4"><button type="button" class="btn btn-success" onclick="validar();">Recargar</button></div>
</div>
</div>

<div class="col-3" id="divbotonera">
<input type="hidden" id="inpcompletar">
<div class="row">
<button type="button" class="btn botonera btn-success" onclick="completamonto(1)">1</button>
<button type="button" class="btn botonera btn-success" onclick="completamonto(2)">2</button>
<button type="button" class="btn botonera btn-success" onclick="completamonto(3);">3</button>
</div>
<div class="row" style="margin-top:1px;">
<button type="button" class="btn botonera btn-success" onclick="completamonto(4);">4</button>
<button type="button" class="btn botonera btn-success" onclick="completamonto(5);">5</button>
<button type="button" class="btn botonera btn-success" onclick="completamonto(6);">6</button>
</div>	
<div class="row" style="margin-top:1px;">
<button type="button" class="btn botonera btn-success" onclick="completamonto(7);">7</button>
<button type="button" class="btn botonera btn-success" onclick="completamonto(8);">8</button>
<button type="button" class="btn botonera btn-success" onclick="completamonto(9);">9</button>
</div>	
<div class="row" style="margin-top:1px;">
<button type="button" class="btn botonera btn-success" onclick="completamonto(0);">0</button>
<button type="button" class="btn botonera btn-danger" onclick="borracant();"><?=$i_error;?></button>
<button type="button" class="btn botonera btn-warning" onclick="cambiacant();"><?=$i_check;?></button>
</div>
<div class="row mt-3">
<div class='contbillete'><span class='manito'><img src="images/20000.jpg" onclick='recibirdinero(20000);'></span></div>
<div class='contbillete'><span class='manito'><img src="images/10000.jpg" onclick='recibirdinero(10000);'></span></div>
</div>
<div class="row mt-3">
<div class='contbillete'><span class='manito'><img src="images/5000.jpg" onclick='recibirdinero(5000);'></span></div>
<div class='contbillete'><span class='manito'><img src="images/2000.jpg" onclick='recibirdinero(2000);'></span></div>
</div>
<div class="row mt-3">
<div class='contbillete'><span class='manito'><img src="images/1000.jpg" onclick='recibirdinero(1000);'></span></div>
<div class='contmoneda'><span class='manito'><img src="images/500.png" onclick='recibirdinero(500);'></span></div>
<div class='contmoneda'><span class='manito'><img src="images/100.png" onclick='recibirdinero(100);'></span></div>
</div>
</div>


<div class="col-4 offset-md-1">
<div class="row">
<div class="col-12"><h5>Últimas Recargas</h5></div>
</div>
<div class="row">
<div class="col-12">
<table class="table table-bordered table-striped" id="v6_tbrecargas">
<thead>
<th>Patente</th><th>Empresa</th><th>Monto</th><th>fecha</th>
</thead>
<tbody>
<?
$s="select * from ultimosmovimientos where cuenta=2 && tipo=1 && estado!=2  order by id desc limit 0,10";
$r=$link->query($s);
while($filmultiple=mysqli_fetch_array($r)){
$tarjeta=$filmultiple["codigo"];
if($tarjeta==""){$tarjeta="error";}
$reemplazar=$tarjeta;
for($i=0;$i<=8;$i++){$reemplazar[$i] = "*";}
$caracteres=strlen($tarjeta);
$idpatente=obtenervalor("tarjetas","idpatente","where codigo='".$tarjeta."'");
$patente=obtenervalor("buses","patentebus","where idbus='".$idpatente."'");
$idempresa=obtenervalor("buses","codigoempresa","where idbus='".$idpatente."'");
$idclient=$filmultiple["cliente"];
$client=obtenervalor("clientes","nombre","where id='".$idclient."'");
$cash=$filmultiple["monto"];
$montopesos=enpesos($cash);
$date=devfechalsegundos($filmultiple["fecha"]);
echo"<tr ";if($caracteres < 13 || $patente=="" || $client==""){echo "class='errordigitacion'";}
echo">";?>
<!--<td><?=$reemplazar;?></td>-->
<td><?=$patente;?></td>
<td><?=$client;?></td>
<td><?=$montopesos;?></td>
<td><?=$date;?></td>
</tr>
<?}?>
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
$("#monto").focus();
$("#inpcompletar").val("monto");
});

$("#codigo").on('keypress',function(e) {
if(e.which == 13) {
validar();
}
});


$("#monto").focus(function(e) {
$("#inpcompletar").val("monto");
// $("#divbotonera").show();
});
$("#dinero").focus(function(e) {
$("#inpcompletar").val("dinero");
// $("#divbotonera").show();
});

$("#codigo").focus(function(e) {
$("#inpcompletar").val("");
// $("#divbotonera").hide();
darvuelto();
});

function completamonto(n){
inp = $("#inpcompletar").val();
va = $("#"+inp+"").val();
va+=n;
$("#"+inp+"").val(va).select().focus();
}

function recibirdinero(n){
inp = $("#inpcompletar").val();
va = parseInt($("#"+inp+"").val());
if(isNaN(va)){va=0;}
console.log(va);
va = va + n;
$("#"+inp+"").val(va).select().focus();
}

function borracant(){
inp = $("#inpcompletar").val();
$("#"+inp+"").val("").select().focus();
}

function validar() {
let monto= $("#monto").val();
let dinero= $("#dinero").val();
let vuelto= (dinero-monto);
let tarjeta= $("#codigo").val();
if(parseInt(monto) > 0 && parseInt(dinero) >= monto && tarjeta.length == 13 ){
$.get("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'validaTarjeta',codigotarjeta:''+tarjeta+'',retornar:0},function(data){
console.log(data);
estado = $.parseJSON(data);
if(estado["existe"]){
if(estado["activa"]){
if(estado["normal"]){

formview="<div class='row'><div class='col-12'><table class='table table-bordered'><tr><td>Monto</td><td>$"+enpesos(monto)+"</td></tr><tr><td>Dinero Recibido</td><td>$"+enpesos(dinero)+"</td></tr><tr><td>Vuelto</td><td>$"+enpesos(vuelto)+"</td></tr><tr><td>Tarjeta</td><td>"+tarjeta+"</td></tr></table></div></div>";
$("#m_t6 .modal-header").removeClass("header-rojo").addClass("header-verde");
$("#m_t6 .modal-title").html("Confirmar Recarga");
$("#m_t6 .modal-body").html(formview);
$("#m_t6 .modal-footer").html("<button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Cancelar</button><button type='button' class='btn btn-success' onclick='confirmarRecarga(this,"+monto+",\""+tarjeta+"\","+estado["idcliente"]+","+estado["id"]+")'>Confirmar</button>");
$("#m_t6").modal("toggle")




$("#montorecarga").val(monto);
$("#valorrecarga").val(monto);
$("#dinerorecarga").val(dinero);
$("#vueltorecarga").val(vuelto);
$("#tarjetarecarga").val(tarjeta);
$("#codigotarjeta").val(tarjeta);
$("#validar-salida").modal();	
}else{
$("#validaciondetarjeta .toast-body").html("No se puede cargar tarjeta,el c&oacute;digo <strong>"+tarjeta+"</strong> Afecta Pozo Empresa");	
$("#validaciondetarjeta").show();	
}
}else{
$("#validaciondetarjeta .toast-body ").html("Error al registrar recarga, tarjeta inv&aacute;lida o declarada como extraviada");	
$("#validaciondetarjeta").show();	
}
}else{
// error tarjeta no encontrada
$("#validaciondetarjeta .toast-body").html("El c&oacute;digo de la tarjeta no se encuentra registrado en el sistema");
// $("#validaciondetarjeta").fadeIn(2000).fadeOut(3000);
$("#validaciondetarjeta").show()
}
});
}else{
$("#validaciondetarjeta .toast-body").html("Error al recargar, validar los campos Monto, Dinero Recibido y C&oacute;digo de Tarjeta");
$("#validaciondetarjeta").show();
console.log("No pasa");
}
$("#monto").val("").focus();
$("#dinero").val("");
$("#vuelto").val("");
$("#codigo").val("");
};

function checkSubmit() {
    document.getElementById("btsubmit").value = "Enviando...";
    document.getElementById("btsubmit").disabled = true;
    return true;
} 

function cancelar(){
$.modal.close();
}

function cerrarAlerta(){
$("#validaciondetarjeta").hide();
$("#monto").val("").focus();
$("#dinero").val("");
$("#vuelto").val("");
$("#codigo").val("");
}

function confirmarRecarga(e,monto,codigo,idcliente,idtarjeta){
$(e).html("espera por favor..."+i_cargando+"");
// return;
// e.addEventListener('click', function(event) {
        // event.target.disabled = true;
    // });
$(e).attr("disabled",true);
let iduser=$("#userid").val();
let ippos=$("#ippos").val();
let codsofland=$("#cod_softland").val();
var randomNo = Math.floor(Math.random()*9999999);
$.post("operaciones.php", {numero:''+randomNo+'',operacion:'recargatarjeta',t_monto:monto,t_codigo:codigo,t_idtarjeta:idtarjeta,t_cliente:idcliente,t_user:iduser,t_ippos:ippos,t_codsofland:codsofland,retornar:0},function(data){
location.reload();
}); 



}
</script>