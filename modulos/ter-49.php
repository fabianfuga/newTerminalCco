<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t49">
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
<h4>Agregar una Bodega</h4>
</div>
</div>
<div class="row mt-2">
<div class="col-md-12 top20">
<div class="card">
<div class="card-body">
<div class="row mt-3">
<div class="col-md-6">
<form action="operaciones.php"  method="post" >
<INPUT TYPE="hidden" name="operacion" value="nuevabodega">
<INPUT TYPE="hidden" name="retornar" value="index.php?mod=9&subid=ter-49">
<div class="row">
<div class="col-md-6">
<label>Nombre</label>
<INPUT TYPE="text" name="bodega"  id="bodega"  class="form-control" autocomplete="off">
</div>
<div class="col-md-6 mt27">
<button type="submit" class="btn btn-success">Guardar</button>
</div>
</div>
</form>
</div>

<div class="col-md-4">
<table class="table table-bordered table-striped" id="tb48_ubicaciones">
<thead><th>Bodega</th><th></th></thead>
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
getBodegas();
});
let bodegas;
function getBodegas(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getBodegas',retornar:0},function(data){
bodegas=$.parseJSON(data);
fb="";
$.each(bodegas,function(i,v){
fb+="<tr><td>"+v+"</td><td class='text-center'><button type='button' class='btn btn-outline-danger btn-xs' onclick='eliminarBodega("+i+")'>"+i_borrar+"</button></td></tr>";
});
$("#tb48_ubicaciones tbody").html(fb);
});
}


function eliminarBodega(i){
bod= bodegas[i];
info="Realmente desea eliminar esta Bodega : <b>"+bod+"</b>";
$("#m_t49 .modal-dialog").css({'min-width':'30%'});
$("#m_t49 .modal-header").removeClass("header-verde").addClass("header-rojo");
$("#m_t49 .modal-title").html("Eliminar Bodega");
$("#m_t49 .modal-body").html(info);
// $("#vehiculo .modal-footer").css({display:"none"})
$("#m_t49 .modal-footer").html("<button type='button' class='btn btn-danger pull-left' data-bs-dismiss='modal'>Cancelar</button><button type='button' class='btn btn-success ' onclick='borrarBodega("+i+")'>Confirmar</button>")
$("#m_t49").modal("toggle");

}

function borrarBodega(i){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'eliminarBodega',id:i,retornar:0},function(data){
location.reload();
});
}

</script>