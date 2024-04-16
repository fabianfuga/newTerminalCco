<div class="container">
<div class="row mt20 c_ter2" id="v_listado">
<div class="col-12">
<h3>Actualizar Tarifas</h3>
<div class="card mt20">
<!--<div class="card-header">
<h3 class="card-title"></h3>
</div>-->
<div class="card-body">
<div class="row">
<div class="col-6">
<div class="row">
<div class="col-8">
<div class="row">
<div class="col-12">
<label>Tipo Recorrido</label>
<? htmlselect('tipo','tipo','tiporecorrido','idtipodestino','tipodestino','','','','idtipodestino','','','si','no','no');?>
</div>
</div>

<div class="row mt10">
<div class="col-8">
<label>Fecha</label>
<input type="text" class="form-control fecha" placeholder="dd/mm/aaaa" name="fecha" id="fecha">
</div>
</div>

<div class="row mt10">
<div class="col-8">
<label>Valor</label>
<input type="text" placeholder="$" name="valor"  id="valor" size="9" class="form-control">
</div>
</div>

<div class="row mt10">
<div class="col-8">
<button type="button" class="btn btn-success" onclick="guardarecorrido();">Guardar</button>
</div>
</div>
</div>
</div>
</div>
<div class="col-6">
<div class="row">
<div class="col-12">
<div class="row">
<div class="col-12"><h4>Últimas actualizaciones</h4></div>
</div>
<div class="row">
<div class="col-12">
<table class="table table-bordered table-striped" id="v15_tbtarifas">
<thead>
<th>Recorrido</th><th>Fecha</th><th>Valor</th>
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
</div>
</div>

<script>
$(function(){
getLastTarifas();
$("#tipo").change(function(){
getTarifas();
});

});

let tarifas;
function getLastTarifas(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getLastTarifas',retornar:0},function(data){
tarifas = $.parseJSON(data);
listarTarifas();

});

}

function getTarifas(){
idtipo = $("#tipo").val();
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getTarifas',tipo:idtipo,retornar:0},function(data){
tarifas = $.parseJSON(data);
listarTarifas();

});
}
let tb_tarifas = new DataTable('#v15_tbtarifas');
function listarTarifas(){
ft="";
$.each(tarifas,function(i,v){
ft+="<tr><td>"+v.recorrido+"</td><td>"+v.fecha+"</td><td>"+v.valor+"</td></tr>";
	
});

$("#v15_tbtarifas tbody").html("");
tb_tarifas.destroy();
$("#v15_tbtarifas tbody").html(ft);
tb_tarifas=$('#v15_tbtarifas').DataTable({
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

function guardarecorrido(){
let idtipo=$("#tipo").val();
let fecha=$("#fecha").val();
let valor=$("#valor").val();
let randomNo = Math.floor(Math.random()*9999999);
let user=$("#userid").val();
if(!idtipo || !fecha || !valor){
alert("Error al regitrar tarifa, los campos recorrido, fecha y valor son obligatorios");
return;
}


// return;

$.post("operaciones.php", {numero:''+randomNo+'',operacion:'guardartarifa',trecorrido:idtipo, tfecha:fecha,tvalor:valor,usuario:user,retornar:0},function(data){
res = $.parseJSON(data);
if(res.error){
alert(res.mensaje);
}else{
getLastTarifas();
$("#tipo").val('');
$("#fecha").val('');
$("#valor").val('');

}
});

	
}
 
</script>
