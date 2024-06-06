<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t48">
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
<div class="row mt20">
<div class="col-12">
<h4>Agregar Ubicación</h4>
</div>
</div>
<div class="row mt-2">
<div class="col-md-12 top20">
<div class="card">
<div class="card-body">
<div class="row mt-3">
<div class="col-md-6">
<form action="operaciones.php"  method="post" >
<INPUT TYPE="hidden" name="operacion" value="nuevaubicacion">
<INPUT TYPE="hidden" name="retornar" value="index.php?mod=9&subid=ter-48">
<div class="row">
<div class="col-md-6">
<label>Nombre</label>
<INPUT TYPE="text" name="ubicacion"  id="ubicacion"  class="form-control" autocomplete="off">
</div>
<div class="col-md-6 mt27">
<button type="submit" class="btn btn-success">Guardar</button>
</div>
</div>
</form>
</div>

<div class="col-md-6">
<table class="table table-bordered table-striped" id="tb48_ubicaciones">
<thead><th>Ubicacion</th><th></th></thead>
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
<script>
$(function(){
getUbicaciones();
});
let ubicaciones;
let tb48_ubicaciones = new DataTable('#tb48_ubicaciones');
function getUbicaciones(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getUbicaciones',retornar:0},function(data){
ubicaciones=$.parseJSON(data);
fu="";
$.each(ubicaciones,function(i,v){
fu+="<tr><td>"+v+"</td><td class='text-center'><button type='button' class='btn btn-outline-danger btn-xs' onclick='eliminarUbicacion("+i+")'>"+i_borrar+"</button></td></tr>";
});
$("#tb48_ubicaciones tbody").html("");
tb48_ubicaciones.destroy();
$("#tb48_ubicaciones tbody").html(fu);
tb_tarjetas=$('#tb48_ubicaciones').DataTable({
"language":{ url: 'dtspanish.json'},
"paging": true,
"autoWidth": false,
"lengthChange": false,
"lengthMenu": [[10,-1], [10,"Todos"]],
"pageLength":10,
"searching": true,
"ordering": false,
// "order": [[ 0, "desc" ]],
"info": true
});	
});
}

function eliminarUbicacion(i){
ubi= ubicaciones[i];
info="Realmente desea eliminar esta ubicación : <b>"+ubi+"</b>";
$("#m_t48 .modal-dialog").css({'min-width':'30%'});
$("#m_t48 .modal-header").removeClass("header-verde").addClass("header-rojo");
$("#m_t48 .modal-title").html("Eliminar Ubicación");
$("#m_t48 .modal-body").html(info);
// $("#vehiculo .modal-footer").css({display:"none"})
$("#m_t48 .modal-footer").html("<button type='button' class='btn btn-danger pull-left' data-bs-dismiss='modal'>Cancelar</button><button type='button' class='btn btn-success ' onclick='borrarUbicacion("+i+")'>Confirmar</button>")
$("#m_t48").modal("toggle");

}

function borrarUbicacion(i){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'eliminarUbicacion',id:i,retornar:0},function(data){
location.reload();
});
}

</script>
