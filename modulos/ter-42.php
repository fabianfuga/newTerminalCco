<div class="container">
<div class="row mt20 c_42">
<div class="col-12">
<h3>Salidas sin Tarjetas</h3>
<div class="card mt20">
<!--<div class="card-header">
<h3 class="card-title"></h3>
</div>-->
<div class="card-body">
<div class="row">
<div class="col-6">
<div class="row">
<div class="col-8">
<form action="operaciones.php"  method="post" class="form-horizontal">
<INPUT TYPE="hidden" name="operacion" value="tempsalidas">
<INPUT TYPE="hidden" name="usuario" value="<?=$id;?>">

<div class="row">
<div class="col-12">
<label>Cliente</label>
<? htmlselect('cliente','cliente','clientes','id','nombre','','','','nombre','getPatentes(this)','','si','no','no');?>
</div>
</div>

<div class="row mt10">
<div class="col-12">
<label>Patente</label>
<select name="patente" id="patente" class="form-control"  onchange="getRecorridosTarifadosxBus();"><option value=0>--</option></select>
</div>
</div>

<div class="row mt10">
<div class="col-12">
<label>Recorrido</label>
<select name="recorrido" id="recorrido" class="form-control" onchange="viewTarifa();"><option value=0>--</option></select>
</div>
</div>

<div class="row mt10">
<div class="col-6">
<label>Tarifa</label>
<input type="hidden" id="tarifa" value=0>
<input type="text" class="form-control" name="tarifa" disabled>
</div>
</div>


<div class="row mt10">
<div class="col-6">
<label>Fecha de Salida</label>
<input type="text" class="form-control fecha" placeholder="dd/mm/aaaa" name="fechasalida" id="fechasalida" autocomplete="off">
</div>
</div>

<div class="row mt10">
<div class="col-6">
<label>Hora de Salida</label>
<input type="text" class="form-control hora" placeholder="00:00:00" name="horasalida" id="horasalida">
</div>
<div class="col-6 mt27"><small>* Ingresar solo numeros</small></div>
</div>



<div class="row mt10">
<div class="col-8">
<label>Código Tarjeta</label>
<IMG SRC="" id="cu">
</div>
</div>

<div class="row mt10">
<div class="col-8">
<button type="button" class="btn btn-success" onclick="registrarSalida(this)">Registrar Salida</button>
</div>
</div>

</form>
</div>
</div>

</div>

<div class="col-6">
<div class="row oculto" id="r42_infosaldo">
<div class="col-6">
<div class="d-flex justify-content-between align-items-center border-bottom mb-3">
<p class="text-xl" id="i42_iconsaldo">
<?=$i_checksaldo_lg;?>
</p>
<p class="d-flex flex-column text-right">
<span class="font-weight-bold" id="i42_saldo">
</span>
<span class="text-muted">SALDO</span>
</p>
</div>
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
$("#cliente,#patente,#recorrido").chosen();
});

let busesxempresa;
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




let recorridosxbus;
function getRecorridosTarifadosxBus(){
idbus=parseInt($("#patente").val());
if(idbus){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getRecorridosTarifadosxBus',id:idbus,retornar:0},function(data){

recorridosxbus = $.parseJSON(data);
console.log(recorridosxbus);

o_rec="<option value=0>--</option>";
$.each(recorridosxbus.recorridos, function(i,v){
o_rec+="<option value="+i+">"+v.recorrido+"</option>";	
});
$("#recorrido").html(o_rec);
$("#recorrido").trigger("chosen:updated");

$("#cu").attr("src","cds_codigobarras.php?claveunica="+recorridosxbus.codigo+"");

// info SALDO
let saldo = parseInt(recorridosxbus.saldo);
if( saldo > 0){
rclass="text-danger text-primary";aclass="text-success";
}else if(saldo < 0){
rclass="text-success text-primary";	aclass="text-danger";
}else{
rclass="text-success text-danger";	aclass="text-primary";	
}
$("#i42_iconsaldo").removeClass(rclass).addClass(aclass);
$("#i42_saldo").html("$"+enpesos(saldo));
$("#r42_infosaldo").show();

});	

}else{
$("#recorrido").html("<option value=0>--</option>");
$("#recorrido").trigger("chosen:updated");
console.log("no busca recorridos, no se ha seleccionado una patente");
$("#cu").attr("src","");
$("#r42_infosaldo").hide();
}
}

function viewTarifa(){
idrec=parseInt($("#recorrido").val());
if(idrec){
tarifa = recorridosxbus["recorridos"][idrec];
$("input[name='tarifa']").val(enpesos(tarifa.tarifa));
$("#tarifa").val(tarifa.tarifa);	
}else{
$("input[name='tarifa'],#tarifa").val("");
}

}

function registrarSalida(e){
console.log(recorridosxbus);


if(typeof recorridosxbus !== "undefined"){
idcliente=parseInt($("#cliente").val());
idpatente=parseInt($("#patente").val());
idrecorrido=parseInt($("#recorrido").val());
codigo = recorridosxbus.codigo;
if(idrecorrido){
tarifasel = recorridosxbus["recorridos"][idrecorrido]["tarifa"];
iddestino = recorridosxbus["recorridos"][idrecorrido]["idtipodestino"];	
}else{
alert("Error al registrar salida sin tarjeta, debes seleccionar un recorrido"); return;	
}

fechasalida=$("input[name='fechasalida']").val();
horasalida=$("input[name='horasalida']").val();
tipo=recorridosxbus.tipo;

if(idcliente && idpatente && idrecorrido && iddestino && fechasalida !="" && horasalida !=""){
let s_iduser=$("#userid").val();
let s_user=$("#username").val();
var randomNo = Math.floor(Math.random()*9999999);
$.post("operaciones.php", {numero:''+randomNo+'',operacion:'tempsalidas',cliente:idcliente,patente:idpatente,recorrido:idrecorrido,tarjeta:codigo,tipotarjeta:tipo,destino:iddestino,tarifa:tarifasel,fecha:fechasalida, hora:horasalida,usuario:s_iduser,retornar:0},function(data){
location.reload();
});



}else{
alert("Error al registrar salida sin tarjeta, todos los campos son obligatorios"); return;

}
}else{
alert("Error al registrar salida sin tarjeta, todos los campos son obligatorios"); return;
}



}

</script>