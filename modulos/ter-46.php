
<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t46">
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
<h4>Agregar Bulto</h4>
</div>
</div>
<div class="row mt-2">
<div class="col-md-12 top20">
<div class="card">
<div class="card-body">
<div class="row mt-3">
<div class="col-md-6">
<form action="operaciones.php"  method="post" >
<INPUT TYPE="hidden" name="operacion" value="nuevobulto">
<INPUT TYPE="hidden" name="retornar" value="index.php?mod=9&subid=ter-46">
<div class="row">
<div class="col-md-6">
<label>Nombre</label>
<INPUT TYPE="text" name="nombre"  id="nombre"  class="form-control" autocomplete="off">
</div>
<div class="col-md-6 mt27">
<button type="submit" class="btn btn-success">Guardar</button>
</div>
</div>
</form>
</div>

<div class="col-md-3">
<table class="table table-bordered table-striped" id="tb46_bultos">
<thead><th>Nombre</th><th></th></thead>
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
getBultos();
});
let bultos;
function getBultos(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getBultos',retornar:0},function(data){
bultos=$.parseJSON(data);
fb="";
$.each(bultos,function(i,v){
fb+="<tr><td>"+v+"</td><td class='text-center'><button type='button' class='btn btn-outline-danger btn-xs' onclick='eliminarBulto("+i+")'>"+i_borrar+"</button></td></tr>";
});
$("#tb46_bultos tbody").html(fb);
});
}

function eliminarBulto(i){
bulto= bultos[i];
info="Realmente desea eliminar este Bulto : <b>"+bulto+"</b>";
$("#m_t46 .modal-dialog").css({'min-width':'30%'});
$("#m_t46 .modal-header").removeClass("header-verde").addClass("header-rojo");
$("#m_t46 .modal-title").html("Eliminar Bulto");
$("#m_t46 .modal-body").html(info);
// $("#vehiculo .modal-footer").css({display:"none"})
$("#m_t46 .modal-footer").html("<button type='button' class='btn btn-danger pull-left' data-bs-dismiss='modal'>Cancelar</button><button type='button' class='btn btn-success ' onclick='borrarBulto("+i+")'>Confirmar</button>")
$("#m_t46").modal("toggle");

}

function borrarBulto(i){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'eliminarBulto',id:i,retornar:0},function(data){
location.reload();
});
}
</script>

