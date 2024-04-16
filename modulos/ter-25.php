<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t25">
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
<div class="row mt20 c_25">
<div class="col-12">
<h3>Traspasar Saldo</h3>
<div class="card mt20">
<!--<div class="card-header">
<h3 class="card-title"></h3>
</div>-->
<div class="card-body">
<div class="row">
<div class="col-6">
<div class="row">
<div class="col-8">
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
<input type="text" class="form-control" placeholder="dd/mm/aaaa" name="fecha" id="fecha" value="<?=date("d/m/Y");?>" disabled>
</div>
</div>

<div class="row mt10">
<div class="col-8">
<label>Tipo de Transacción</label>
<input type="text" class="form-control" name="tipo" id="tipo" disabled>
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
<button type="button" class="btn btn-success" onclick="traspasarSaldo()">Traspasar Saldo</button>
</div>
</div>


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
<table class="table table-bordered table-striped" id="v25_tbtarjetas">
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
$("#patente").change(function(){
infoPatente();
});

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

let tb_tarjetas = new DataTable('#v25_tbtarjetas');
function listarTarjetas(){
ft="";
$.each(tarjetas,function(i,v){
ft+="<tr><td>"+v.codigo+"</td><td>"+v.cliente+"</td><td>"+v.patente+"</td><td>"+v.tipo+"</td><td class='text-center'><a href='index.php?mod=3&subid=ter-5-imprimir&id="+v.id+"' class='btn btn-outline-primary btn-sm' target='imprimir'>"+i_print+"</a></td></tr>";
	
});

$("#v25_tbtarjetas tbody").html("");
tb_tarjetas.destroy();
$("#v25_tbtarjetas tbody").html(ft);
tb_tarjetas=$('#v25_tbtarjetas').DataTable({
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

let s_patente=[];
function infoPatente(){
id=$("#patente").val();
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getTipoPatente',idbus:id,retornar:0},function(data){
console.log(data);
res = $.parseJSON(data);
if(!res.error){
s_patente=res;
if(parseInt(res.idtipo)==1){tipotra="Afecta Pozo Empresa";}
if(parseInt(res.idtipo)==2){tipotra="Afecta Pozo Normal";}
$("#tipo").val(tipotra);
}

});
}


function traspasarSaldo(){
empresa=$("#cliente option:selected").text();
idempresa=parseInt($("#cliente").val());
patente=$("#patente option:selected").text();
idpatente=parseInt($("#patente").val());
old_codigo=s_patente.codigo;
saldo=s_patente.saldo;
tipo = $("#tipo").val();
fecha = $("#fecha").val();
// console.log(idempresa,idpatente);

if(idempresa && idpatente){
formview="<div class='row'><div class='col-12'><table class='table table-bordered'><tr><td>Empresa</td><td>"+empresa+"</td></tr><tr><td>Patente</td><td>"+patente+"</td></tr><tr><td>Código Tarjeta</td><td>"+old_codigo+"</td></tr><tr><td>Saldo</td><td>$"+enpesos(saldo)+"</td></tr><tr><td>Código nueva tarjeta</td><td>"+codigo+"</td></tr><tr><td>Tipo</td><td>"+tipo+"</td></tr><tr><td>Fecha Emisión</td><td>"+fecha+"</td></tr></table></div></div><div class='row mt20'><div class='col-6 offset-md-6 text-right'><button type='button' class='btn btn-success' onclick='confirmarTraspaso(this,"+idempresa+",\""+codigo+"\","+s_patente.idtarjeta+","+s_patente.idtipo+","+idpatente+",\""+old_codigo+"\")'>Confirmar Traspaso</button></div></div>";
$("#m_t25 .modal-header").removeClass("header-rojo").addClass("header-verde");
$("#m_t25 .modal-title").html("Confirmar Traspaso");
$("#m_t25 .modal-body").html(formview);
$("#m_t25 .modal-footer").html("");
$("#m_t25").modal("toggle")	
}else{
alert("Error al traspasar saldo, cliente y patente son requeridos");
return;
}

}

function confirmarTraspaso(e,idcliente,codigo,idtarjeta,idtipo,idpatente,oldcodigo){
$(e).html("generando traspaso..."+i_cargando+"");
// return;
userid=$("#userid").val();
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'traspasarsaldo',cliente:idcliente,newcode:codigo,oldtarjeta:idtarjeta,tipo:idtipo,bus:idpatente,oldcode:oldcodigo,usuario:userid,retornar:0},function(data){
	
location.reload();

});


}
</script>