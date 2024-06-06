<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t50">
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

<?
$idcajero=0;
$sql="select id,nombre,cod_softland from usuarios where activo=1 && perfil=3 && enturno=1";
$res=$link->query($sql);
if(mysqli_num_rows($res) == 0 ){
$mensaje="<div class='alert alert-warning'><a href ='index.php?mod=2&subid=recaudacion' class='btn btn-primary '><span>Volver al Menu</span></a>&nbsp;&nbsp;No existe ning&uacute;n cajero  con sesi&oacute;n iniciada</div>";
}elseif(mysqli_num_rows($res) >  1 ){
$mensaje="<div class='alert alert-danger'><a href ='index.php?mod=2&subid=recaudacion' class='btn btn-primary '><span>Volver al Menu</span></a>&nbsp;&nbsp;Existe m&aacute;s de un cajero con sesi&oacute;n iniciada</div>";
}else{
$fila=mysqli_fetch_array($res);
$mensaje="<div class='alert alert-success'><a href ='index.php?mod=2&subid=recaudacion' class='btn btn-primary '><span>Volver al Menu</span></a>&nbsp;&nbsp;Cajero activo : ".$fila["nombre"]."</div>";
$idcajero=$fila["id"];
$cod_softland=$fila["cod_softland"];
}
?>

<div class="container-fluid">
<div id="validarRegistro" class="toasts-top-right fixed oculto">
<div class="toast bg-danger fade show" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-header"><strong class="mr-auto">Error al registrar ingreso</strong><button  type="button" class="ml-2 mb-1 close" aria-label="Close" onclick='cerrarAlerta()'><span aria-hidden="true">×</span></button></div><div class="toast-body"></div></div>
</div>


<input type="hidden" name="idcajero" value="<?=$idcajero;?>">
<input type="hidden" name="cod_softland" value="<?=$cod_softland;?>">
<input type="hidden" name="codigo" value="<?=date("U");?>">
<input type="hidden" name="idtipobulto" value=1>

<div class="row mt-2">
<div class="col-md-12"><?=$mensaje;?></div>
</div>

<div class="row mt-3">
<div class="col-md-12">
<h4>Registrar Ingreso</h4>
<div class="card">
<div class="card-body">
<div class="row">
<div class="col-md-6">
<div class="row">
<div class="col-md-12">
<div class="callout callout-warning oculto" id="infomensaje">
<p class='text-muted'><span class='text-danger'><?=$i_info;?></span>&nbsp;&nbsp;<text></text></p>
</div>
</div>
</div>

<div class="row">
<label>Cantidad</label>
<input type="hidden" id="cantidad" value=1>
<div class="controls">
<button type="button" class="btn btn-default btnubicacion" onclick="nbultos('menos');" id="btnmenos"><?=$i_menos;?></button><input type="text" name="cantidad"  onkeypress="return solonumeros(event);" class="inpmedio" autofocus autocomplete="off" value=1 disabled> &nbsp;&nbsp;<button type="button" class="btn btn-success btnubicacion" onclick="nbultos('mas');" id="btnmas"><?=$i_mas;?></button></div>
</div>

<div class="row mt-2">
<input type="hidden" name="bulto">
<input type="hidden" name="tarifa">
<!--<label class="control-label">Tipo de Bulto</label>-->
<div class="control-group" id="bultos">
</div>
</div>

<div class="row mt-2">
<!--<label class="control-label">Ubicaci&oacute;n</label>-->
<input type="hidden" name="ubicacion">
<div class="controls" id="ubicaciones">
</div>
</div>

<div class="row oculto mt-2" id="inpvalor">
<div class="col-md-6">
<div class="input-group mb-3">
<span class="input-group-text">Declarar Valor</span>
<input type="text" name="valordeclarado" class="form-control" disabled>
</div>
</div>

<!--<div class="input-prepend">
  <span class="add-on">Declarar Valor</span>
<input type="text" name="valordeclarado" class='form-control' disabled>
</div>-->
</div>



<div class="row mt-2">
<div class="controls"><button class="btn btn-primary btningreso" onclick="sumarIngreso()">Agregar</button></div>
</div>

</div>

<div class="col-md-6">
<div class="row">
<div class="col-md-12">
<table class="table table-bordered" id="vistapreviaingresos">
<thead>
<th>Cantidad</th><th>Bulto</th><th>Tipo</th><th>Ubicaci&oacute;n</th><th>Tarifa</th><th>Total</th></thead>
<tbody>
</tbody>
</table>
</div>
</div>


<div class="row mt-2">
<div class="col-md-6">
<button class="btn btn-success btningreso" onclick="registrarIngreso(this)" id="btn50_registrarIngreso">Registrar Ingreso </button>
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
// $("input[name='cantidad']").select();
// inicializamos el boton menos bloqueado 
$("#btnmenos").attr("disabled",true);
$('html').keypress(function(e){
//letra A
if(e.which == 65 || e.which==97){} 
// letra B
if(e.which == 66 || e.which==98){}
// letra C
if(e.which == 67 || e.which==99){}
// letra D
if(e.which == 68 || e.which==100){}
});
$("input").focus(function(){
$("#infomensaje text").html("");
$("#infomensaje").hide();
});
getBultos();
getUbicaciones();
});

// funcion para cambiar la cantidad de bultos con botones - +
function nbultos(operacion){
cantidad = parseInt($("#cantidad").val());
if(operacion =="menos"){cantidad = cantidad  - 1;}else{cantidad = cantidad  + 1;}
if(cantidad > 1){$("#btnmenos").attr("disabled",false);}else{$("#btnmenos").attr("disabled",true);}
$("#cantidad, input[name='cantidad']").val(cantidad);
}


let bultos;
function getBultos(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php",{operacion:'getBultosIngreso',numero:''+randomNo+'',retornar:0} ,function(data){
console.log(data);
bultos=$.parseJSON(data);
btnbultos="";
$.each(bultos,function(index,valor){
btnbultos+="<button type='button' class='"+valor.color+" btnbulto' onclick='selectBulto(\""+index+"\",this)'><b>"+valor.nombre+"</b></button>";
});
$("#bultos").html(btnbultos);
});
}


function selectBulto(index,e){
$("input[name='bulto']").val(index);
bulto = bultos[index];
$("input[name='tarifa']").val(bulto.tarifa);
$("#infomensaje text").html("");
$("#infomensaje").hide();
$(".btnbulto").removeClass("btnselect");
$(e).addClass("btnselect");

}

let ubicaciones;
function getUbicaciones(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php",{operacion:'getUbicacionesIngreso',numero:''+randomNo+'',retornar:0} ,function(data){
ubicaciones=$.parseJSON(data);
btnubicaciones="";
$.each(ubicaciones,function(index,valor){
if(valor.nombre=="A" || valor.nombre=="B" || valor.nombre=="C" || valor.nombre=="D"){color="btn-primary";}else{color="btn btn-default";}
if(valor.nombre =="Bodega de administracion"){clase="btnbodega";color="btn-inverse";}else{clase="btnubicacion";}
btnubicaciones+="<button type='button' class='btn "+color+" "+clase+"' onclick='selectUbicacion("+index+",\""+color+"\",this)'><b>"+valor.nombre+"</b></button>";
});
btnubicaciones+="<button type='button' id='btnnormal' class='btn  btnbultotipo btnselect btn-default' disabled onclick='tipobulto(\"normal\",\"btnfragil\",this);'>Normal</button><button type='button' id='btnfragil' class='btn  btnbultotipo btn btn-default' onclick='tipobulto(\"fragil\",\"btnnormal\",this);' >Fr&aacute;gil</button>";
$("#ubicaciones").html(btnubicaciones);
});

}

function selectUbicacion(index,color,e){
$("input[name='ubicacion']").val(index);
$("#infomensaje text").html("");
$("#infomensaje").hide();
$(".btnbodega, .btnubicacion").removeClass("btnselect");
$(e).addClass("btnselect");
}


function tipobulto(tipo,btn,e){
id=$(e).attr("id");
console.log(id);
$("#"+btn+"").attr("disabled",false);
$("#"+id+"").attr("disabled",true);
if(tipo=="normal"){
$("#inpvalor").hide();
$("input[name='valordeclarado']").attr("disabled",true);
$("input[name='idtipobulto']").val(1);
$("#tdtipo").html("Normal");
}else{
$("#inpvalor").show();
$("input[name='valordeclarado']").attr("disabled",false).focus();	
$("input[name='idtipobulto']").val(2);
$("#tdtipo").html("Fr&aacute;gil");
}
$(".btnbultotipo").removeClass("btnselect");
$(e).addClass("btnselect");
}


ingresos = {};
function sumarIngreso(){
cantidad=$("#cantidad").val();
if(cantidad == "" || parseInt(cantidad) == 0){
$("#infomensaje text").html("La cantidad debe ser mayor que 0 ");	
$("#infomensaje").show();	
return;
}
indexbulto=$("input[name='bulto']").val();
if(indexbulto == ""){
$("#infomensaje text").html("No ha seleccionado ningún bulto");
$("#infomensaje").show();		
return;
}
tarifa=$("input[name='tarifa']").val();
indexu=$("input[name='ubicacion']").val();
if(indexu == ""){
$("#infomensaje text").html("No ha seleccionado ninguna ubicacion para el bulto");
$("#infomensaje").show();	
return;
}
idtipobulto=$("input[name='idtipobulto']").val();	
valor=0;
tiporeg="Normal";
if(parseInt(idtipobulto) == 2){
tiporeg="Frágil";
valor = parseInt($("input[name='valordeclarado']").val());
if(isNaN(parseInt(valor))){
$("#infomensaje text").html("Si el bulto es fr&aacute;gil el valor a declarar no puede ser 0 o vacio");
$("#infomensaje").show();
// $("input[name='valordeclarado']").focus();
return;
}
}

detallebulto = bultos[indexbulto];
indice = Math.floor(Math.random()*9999999);
detalleubicacion = ubicaciones[indexu];
totalingreso = parseInt(cantidad) * parseInt(tarifa);
ingresos[indice]=({dmc_cantidad:cantidad,dmc_bulto:indexbulto,bulto:detallebulto.nombre,dmc_tipobulto:idtipobulto,tipo:tiporeg,dmc_ubicacion:indexu,ubicacion:detalleubicacion.nombre,dmc_valordeclarado:valor,dmc_tarifa:tarifa,dmc_total:totalingreso});
$(".btnbodega, .btnubicacion, .btnbulto, .btnbultotipo").removeClass("btnselect");	
$("#cantidad, input[name='cantidad']").val(1);

fingresos="";
totalingresos=0;
totalbultos=0;
$.each(ingresos, function(index,valor){
fingresos+="<tr id='"+index+"'><td>"+valor.dmc_cantidad+"</td><td>"+valor.bulto+"</td><td>"+valor.tipo+"</td><td>"+valor.ubicacion+"</td><td>$"+enpesos(valor.dmc_tarifa)+"</td><td>$"+enpesos(valor.dmc_total)+"</td></tr>";
totalingresos = totalingresos + valor.dmc_total;
totalbultos = totalbultos + parseInt(valor.dmc_cantidad);
});
fingresos+="<tr class='table-success'><td><b>"+totalbultos+"</b></td><td colspan=4></td><td><b>$"+enpesos(totalingresos)+"</b></td></tr>";
$("#vistapreviaingresos tbody").html(fingresos);

$("input[name='bulto']").val("");
$("input[name='tarifa']").val("");
$("input[name='ubicacion']").val("");
$("input[name='idtipobulto']").val(1);
$("input[name='valordeclarado']").val(0)
// console.log(ingresos);
} 


function registrarIngreso(e){
antes=$(e).html();
console.log(antes);
$(e).html("Registrando ingreso ...<i class='fa fa-refresh fa-spin fa-fw'></i>").attr("disabled",true);
dataing={};
dataing["mcu_cajero"]=$("input[name='idcajero']").val();
dataing["cod_softland"]=$("input[name='cod_softland']").val();
dataing["codigo"]=$("input[name='codigo']").val();
dataing["mcu_usuario"]=$("input[name='usuariojs']").val();
dataing["bultos"]=ingresos;
dataing["ippos"]=$("#ippos").val();
json = JSON.stringify(dataing);
if(parseInt(Object.keys(ingresos).length) > 0){
$.get("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'nuevoingresocustodia',datos:json,retornar:0},function(data){
if(data!="error"){
location.reload();
}
});	
}else{
$("#validarRegistro .toast-body").html("No se puede registrar un ingreso sin bultos");	
$("#validarRegistro").show();
}
}

function cerrarAlerta(){
$("#validarRegistro").hide();
$("#btn50_registrarIngreso").html("Registrar Ingreso").attr("disabled",false);
}



</script>