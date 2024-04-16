<div class="container">
<div class="row mt20 c_36" id="v36_listado">
<div class="col-12">
<h3>Historial de Tarifas</h3>
<div class="callout callout-warning">

<p class='text-muted'><span class='text-danger'><?=$i_info;?></span>&nbsp;&nbsp;Los datos modificados aquí afectan completamente el resultado de las operaciones sobre la plataforma</p>
</div>

<small></small>
<div class="card mt20">
<!--<div class="card-header">
<h3 class="card-title"></h3>
</div>-->
<div class="card-body">
<table class="table table-bordered table-striped" id="v36_tbtarifas">
<thead>
<th>Fecha</th>
<th>Valor</th>
<th>Tipo Recorrido</th>
<th>Creada Por</th>

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
<script>
$(function(){
getAllTarifas();
});

let tarifas;
let tb_tar = new DataTable('#v36_tbtarifas');
function getAllTarifas(){
$("#v36_tbtarifas tbody").html("<tr><td colspan='5' class='text-center'><p class='text-green negrita'>Cargando informacón ..."+i_cargando+"</p></td></tr>");
// return;
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getAllTarifas',retornar:0},function(data){
tarifas = $.parseJSON(data);
listarTarifas();

});
}

function listarTarifas(){
ft="";
$.each(tarifas,function(i,v){
ft+="<tr id='ftar"+i+"'><td>"+v.fecha+"</td><td>"+v.valor+"</td><td>"+v.recorrido+"</td><td>"+v.usuario+"</td><td class='text-center'><button type='button' class='btn btn-outline-danger btn-sm' onclick='delajax("+v.id+",\"tarifas\",\"idtarifa\",\"ftar"+i+"\",\"n\")'>"+i_borrar+"</button></td></tr>";
	
});

$("#v36_tbtarifas tbody").html("");
tb_tar.destroy();
$("#v36_tbtarifas tbody").html(ft);
tb_tar=$('#v36_tbtarifas').DataTable({
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