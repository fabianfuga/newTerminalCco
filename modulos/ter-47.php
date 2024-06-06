<div class="container">
<div class="row mt20">
<div class="col-12">
<h4>Actualizar Tarifas</h4>
<div class="card mt20">
<!--<div class="card-header">
<h3 class="card-title"></h3>
</div>-->
<div class="card-body">
<div class="row">
<div class="col-md-6">
<div class="row">
<div class="col-md-12">
<div class="row">
<div class="col-md-6">
<label>Bulto</label>
<? htmlselect('bulto','bulto','bultos','bul_id','bul_nombre','','','','bul_id','tarifasxbulto()','','si','no','no');?>
</div>
<div class="col-md-4">
<label>Fecha</label>
<input type="text" class="form-control fecha" placeholder="dd/mm/aaaa" name="fecha" id="fecha" autocomplete="off">
</div>
</div>

<div class="row mt10">
<div class="col-md-4">
<label>Valor</label>
<input type="text" placeholder="$" name="valor"  id="valor" size="9" class="form-control">
</div>
<div class="col-md-4 mt27">
<button type="button" class="btn btn-success" onclick="guardartarifa();">Guardar</button>
</div>
</div>
</div>
</div>

<div class="row mt-2">
<div class="col-md-12" id="v47_listadotarifas">
</div>
</div>
</div>

<div class="col-md-6">
<div class="row mt-2">
<div class="col-md-12">
<div class="row"><div class="col-md-12"><label>Actualizar Tarifa cobro adicional</label></div></div>
<div class="row mt10">
<div class="col-md-4">
<label>Fecha</label>
<input type="text" class="form-control fecha" placeholder="dd/mm/aaaa" name="fechaad" id="fechaad" autocomplete="off">
</div>
<div class="col-md-4">
<label>Valor</label>
<input type="text" placeholder="$" name="valorad"  id="valorad" size="9" class="form-control">
</div>
<div class="col-md-4 mt27">
<button type="button" class="btn btn-success" onclick="guardartarifaadicional();">Guardar</button>
</div>
</div>
</div>
</div>
<div class="row mt-2">
<div class="col-md-12" id="v47_listcad">
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
getTarifasCobroAdicional();	
});
function tarifasxbulto(){
let idbulto =$('#bulto').find(':selected').val();
let bulto =$('#bulto option:selected').text();
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php",{bul_id:idbulto,operacion:'getTarifasxBulto',numero:randomNo,retornar:0} ,function(data){
console.log(data);
tarifas = $.parseJSON(data);
tbtarifas="<div class='row mt-2'><div class='col-md-12'><label>Historial de tarifas bulto : "+bulto+"</label></div></div><div class='row mt-2'><div class='col-md-12'><table class='table table-bordered table-striped'><thead><th>Usuario</th><th>Fecha</th><th>Valor</th></thead>";
$.each(tarifas,function(index,valor){
tbtarifas+="<tr><td>"+valor.usuario+"</td><td>"+valor.fecha+"</td><td>"+valor.valor+"</td></tr>";	
});
tbtarifas+="</table></div></div>";

$("#v47_listadotarifas").html(tbtarifas);
// $("#listado").append(''+data+'');
});
}

function guardartarifa(){
let idbulto=$("#bulto").find(":selected").val();
let fecha=$("#fecha").val();
let valor=$("#valor").val();
let user = $("#userid").val();
let randomNo = Math.floor(Math.random()*9999999);
if(idbulto=="" || fecha=="" || valor==""){alert("Debe Completar los Campos");}
else{
$.get("operaciones.php",{operacion:'registrartarifaxbulto',txb_bulto:idbulto, txb_fecha:fecha, txb_valor:valor, numero:''+randomNo+'', txb_usuario:user, retornar:0}, function(data){
tarifasxbulto();
$("#bulto,#fecha,#valor").val("");
});
}
}

function guardartarifaadicional(){
let fecha=$("#fechaad").val();
let valor=$("#valorad").val();
let randomNo = Math.floor(Math.random()*9999999);
let user = $("#userid").val();
if(fecha=="" || valor==""){alert("Debe Completar los Campos");}
else{
$.get("operaciones.php",{operacion:'postTarifaCobroAdicional',tad_fecha:fecha, tad_valor:valor, numero:''+randomNo+'', tad_usuario:user, retornar:0}, function(data){
location.reload();
});
}
}

function getTarifasCobroAdicional(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php",{operacion:'getTarifasCobroAdicional',numero:''+randomNo+'',retornar:0} ,function(data){
console.log(data);
tarifas = $.parseJSON(data);
tbcad="<div class='row'><div class='col-md-12'><label>Historial de tarifas cobro adicional</label></div></div><div class='row mt-2'><div class='col-md-12'><table class='table table-bordered table-striped'><thead><th>Usuario</th><th>Fecha</th><th>Valor</th></thead>";
$.each(tarifas,function(index,valor){
tbcad+="<tr><td>"+valor.usuario+"</td><td>"+valor.fecha+"</td><td>"+valor.valor+"</td></tr>";	
});
tbcad+="</table></div></div>";
$("#v47_listcad").html(tbcad);
});
}


</script>