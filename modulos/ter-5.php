<div class="container">
<div class="row mt20 c_5">
<div class="col-12">
<h3>Generar Tarjeta</h3>
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
<INPUT TYPE="hidden" name="operacion" value="nuevatarjeta">
<INPUT TYPE="hidden" name="tabla" value="tarjetas">
<INPUT TYPE="hidden" name="saldo-n" value="0">
<INPUT TYPE="hidden" name="retornar" value="index.php?mod=2&subid=ter-5&<?=rand(1,3000);?>">
<INPUT TYPE="hidden" name="codigo">
<INPUT TYPE="hidden" name="usuario" value="<?=$id;?>">

<div class="row">
<div class="col-12">
<label>Para Cliente</label>
<? htmlselect('cliente','cliente','clientes','id','nombre','','','','nombre','getPatentes(this)','','si','no','no');?>
</div>
</div>

<div class="row mt10">
<div class="col-12">
<label>Patente</label>
<select name="patente" id="patente" class="form-control"><option value=0>--</option></select>
</div>
</div>

<div class="row mt10">
<div class="col-8">
<label>Fecha de Emisión</label>
<input type="text" class="form-control fecha" placeholder="dd/mm/aaaa" name="fecha" id="fecha">
</div>
</div>

<div class="row mt10">
<div class="col-8">
<label>Tipo de Transacción</label>
<? htmlselect('tipo','tipo','tipotransaccion','idtipotransaccion','ntipotransaccion','','','','ntipotransaccion','','','si','no','no');?>
</div>
</div>


<div class="row mt10">
<div class="col-8">
<label>Código único</label>
<IMG SRC="" id="cu">
</div>
</div>

<div class="row mt10">
<div class="col-8">
<button type="submit" class="btn btn-success">Crear Tarjeta</button>
</div>
</div>

</form>
</div>
</div>

</div>

<div class="col-6">
<div class="row">
<div class="col-12">
<div class="row">
<div class="col-12"><h4>Últimas 20 Tarjetas Generadas</h4></div>
</div>
<div class="row">
<div class="col-12">
<table class="table table-bordered table-striped" id="v5_tbtarjetas">
<thead>
<th>Código</th><th>Cliente</th><th>patente</th><th>Transacción</th><th></th>
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
<div style="display:none;">
<iframe height="0" width="0" name="imprimir" src=""></iframe>
</div>
<script>
$(function(){
getGeneraCodigoTarjeta();	
getLastTarjetas();
$("#cliente,#patente").chosen();
});

let codigo;
function getGeneraCodigoTarjeta(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getGeneraCodigoTarjeta',retornar:0},function(data){
// console.log(data);
codigo= data;
$("input[name='codigo']").val(codigo);
$("#cu").attr("src","cds_codigobarras.php?claveunica="+codigo+"");
});

}
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

let tarjetas;
function getLastTarjetas(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getLastTarjetas',retornar:0},function(data){
tarjetas = $.parseJSON(data);
listarTarjetas();

});

}

let tb_tarjetas = new DataTable('#v5_tbtarjetas');
function listarTarjetas(){
ft="";
$.each(tarjetas,function(i,v){
ft+="<tr><td>"+v.codigo+"</td><td>"+v.cliente+"</td><td>"+v.patente+"</td><td>"+v.tipo+"</td><td class='text-center'><a href='index.php?mod=3&subid=ter-5-imprimir&id="+v.id+"' class='btn btn-outline-primary btn-sm' target='imprimir'>"+i_print+"</a></td></tr>";
	
});

$("#v5_tbtarjetas tbody").html("");
tb_tarjetas.destroy();
$("#v5_tbtarjetas tbody").html(ft);
tb_tarjetas=$('#v5_tbtarjetas').DataTable({
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

</script>