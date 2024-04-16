<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_ltar">
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
<div class="row mt20 c_16" id="v16_listado">
<div class="col-12">
<h3>Listado de Tarjetas</h3>
<div class="card mt20">
<!--<div class="card-header">
<h3 class="card-title"></h3>
</div>-->
<div class="card-body">
<table class="table table-bordered table-striped" id="v16_tbtarjetas">
<thead>
<th>Código</th>
<th>Cliente</th>
<th>Bus</th>
<th>Fecha</th>
<th>Saldo</th>
<th>Tipo</th>
<th></th>
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
<div style="display:none;">
<iframe height="0" width="0" name="imprimir" src=""></iframe>
</div>
<script>
$(function(){
getAlltarjetas();
});
let tarjetas;
let tb_tarjetas = new DataTable('#v16_tbtarjetas');
function getAlltarjetas(){
$("#v16_tbtarjetas tbody").html("<tr><td colspan='9' class='text-center'><p class='text-green negrita'>Cargando informacón ..."+i_cargando+"</p></td></tr>");
// return;
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getAlltarjetas',retornar:0},function(data){
tarjetas = $.parseJSON(data);
listarTarjetas();

});
}

function listarTarjetas(){
ft="";
$.each(tarjetas,function(i,v){
if(parseInt(v.saldo) > 0){csaldo="bg-success";}else{csaldo="bg-danger";}
saldo="<span class='badge "+csaldo+"'>$"+enpesos(v.saldo)+"</span>";

ft+="<tr ftar"+i+"><td>"+v.codigo+"</td><td>"+v.cliente+"</td><td>"+v.patente+"</td><td>"+v.fecha+"</td><td>"+saldo+"</td><td>"+v.tipo+"</td><td class='text-center'><button type='button' class='btn btn-outline-primary' onclick='verTarjeta("+i+")'>"+i_ver+"</button></td><td class='text-center'><a href='index.php?mod=3&subid=ter-5-imprimir&id="+v.id+"' class='btn btn-outline-primary btn-sm' target='imprimir'>"+i_print+"</a></td><td class='text-center'><button type='button' class='btn btn-outline-danger btn-sm' onclick='delajax("+v.id+",\"tarjetas\",\"idtarjeta\",\"ftar"+i+"\",\"n\")'>"+i_borrar+"</button></td></tr>";
	
});

$("#v16_tbtarjetas tbody").html("");
tb_tarjetas.destroy();
$("#v16_tbtarjetas tbody").html(ft);
tb_tarjetas=$('#v16_tbtarjetas').DataTable({
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

function verTarjeta(i){
tar = tarjetas[i];
formview="<div class='row'><div class='col-12'><div class='card bg-light d-flex flex-fill'><div class='card-header text-muted border-bottom-0'>Información de tarjeta</div><div class='card-body pt-0'><div class='row'><div class='col-12'><h2 class=lead><b>"+tar.codigo+"</b></h2><p class='text-muted text-sm'><b>Cliente: </b> "+tar.cliente+" </p><ul class='ml-4 mb-0 fa-ul text-muted'><li class='lip_5'><span class='fa-li lispan_icon'>"+i_bus+"</span> <b>Patente:</b> "+tar.patente+"</li><li class='lip_5'><span class='fa-li lispan_icon'>"+i_tipotran+"</span> <b>Tipo Transacción:</b> "+tar.tipo+"</li><li class='lip_5'><span class='fa-li lispan_icon'>"+i_saldo+"</span> <b>Saldo:</b> $"+enpesos(tar.saldo)+"</li></ul></div></div><div class='row'><div class='col-12 mt20'><img src='cds_codigobarras.php?claveunica="+tar.codigo+"'></div></div></div></div></div></div>";
$("#m_ltar .modal-header").removeClass("header-rojo").addClass("header-verde");
$("#m_ltar .modal-title").html("Tarjeta");
$("#m_ltar .modal-body").html(formview);
$("#m_ltar .modal-footer").html("");
$("#m_ltar").modal("toggle")

}

</script>