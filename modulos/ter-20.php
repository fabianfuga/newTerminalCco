<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t20">
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
<div class="row mt-3">
<div class="col-md-12">
<h3>Rendir Movimientos</h3>
<div class="row">
<div class="col-md-3">
<div class="card mt20">
<div class="card-body">
<div class="row">
<div class="col-md-12">
<label>Cajero</label>
<? htmlselect('cajero','cajero','usuarios','id','nombre','','','where perfil=3 and activo=1','nombre','','','si','no','no');?>
</div>
</div>



<div class="row mt-2">
<div class="col-md-6 text-end"><button type="button" class="btn btn-success btn-block" onclick="getDataCustodia(this)"><?=$i_buscar;?> Buscar</button></div>
</div>
</div>
</div>
</div>

<div class="col-md-8">
<div class="card mt20">
<div class="card-body" id="c20_detalle">
</div>
</div>
</div>





</div>


</div>
</div>
</div>
<iframe src="#" style="width:1px;height:1px;border:0px;" id="f_printresumen"></iframe>
<script>
$(function(){
$("#cajero").chosen();
});
let dataRendir;
function getDataCustodia(e){
let htmlbtn = $(e).html();
let idcajero =  parseInt($("#cajero").val());
if(idcajero){
$(e).html("buscando..."+i_cargando+"").attr("disabled",true);
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php",{operacion:'getDataCustodia',cajero:idcajero,numero:''+randomNo+'',retornar:0} ,function(data){
console.log(data);
$(e).html(htmlbtn).attr("disabled",false);
res = $.parseJSON(data);
dataRendir=res;
dataRendir["idcajero"]=idcajero;
cajero = $("#cajero option:selected").text(); 
if(!res.error){
detalle="<div class='row'><div class='col-md-12'><table class='table table-bordered'><tr><td>Cajero</td><td>"+cajero+"</td></tr><tr><td>Primera Recarga</td><td>"+res.primero+"</td></tr><tr><td>Ultima Recarga</td><td>"+res.ultimo+"</td></tr><tr><td>Recaudación</td><td>"+enpesos(res.recaudacion)+"</td></tr></table></div></div><div class='row'><div class='col-md-12'><button type='button' class='btn btn-success' onclick='rendirCustodia(this)'>Rendir Turno</button>&nbsp;<button type='button' class='btn btn-info' onclick='imprimirResumen("+idcajero+")'>Imprimir Movimientos</button></div></div>";

detalle+="<div class='row'><div class='col-md-12'><table class='table table-bordered' id='tb20_movimientos'><thead><th>#</th><th>Tarjeta</th><th>Cliente</th><th>Monto</th><th>Tipo Recarga</th><th>Fecha</th></thead><tbody>";
im=0;
$.each(res.movimientos,function(i,v){
im++;
detalle+="<tr><td>"+im+"</td><td>"+v.card+"</td><td>"+v.client+"</td><td>$"+enpesos(v.cash)+"</td><td>"+v.tiporecarga+"</td><td>"+v.date+"</td></tr>";
});
detalle+="</tbody></table></div></div>";

let tb20_movimientos = new DataTable('#tb20_movimientos');


$("#c20_detalle").html(detalle);	
tb53_movimientos=$('#tb20_movimientos').DataTable({
"language":{ url: 'dtspanish.json'},
"paging": true,
"autoWidth": false,
"lengthChange": false,
"lengthMenu": [[20,-1], [20,"Todos"]],
"pageLength":20,
"searching": true,
"ordering": true,
"order": [[ 0, "desc" ]],
"info": true
});	


}else{
alert("Cajero no registra movimientos pendientes de rendición");
}


});
	
}else{
alert("Debes seleccionar un cajero");return;
}


}

function imprimirResumen(id){
$("#f_printresumen").attr("src","printRendirCustodia.php?idcajero="+id+"");
}

function rendirCustodia(e){
let userid=$("#userid").val();

// console.log(dataRendir);
// return;
$(e).html("Rindiendo..."+i_cargando+"").attr("disabled",true);
var randomNo = Math.floor(Math.random()*9999999);
$.post("operaciones.php",{operacion:'rendirturno',idusuario:dataRendir.idcajero,primero:dataRendir.fechaprimero,ultimo:dataRendir.fechaultimo,recaudacion:dataRendir.recaudacion,usuario:userid,numero:''+randomNo+'',retornar:0} ,function(data){
location.reload();
});
}

</script>
