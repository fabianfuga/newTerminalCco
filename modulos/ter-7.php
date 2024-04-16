<div class="container">
<div class="row mt20 c_7">
<div class="col-12">
<h3>Consultar Saldo</h3>
<div class="card mt20">
<!--<div class="card-header">
<h3 class="card-title"></h3>
</div>-->
<div class="card-body">
<div class="row">
<div class="col-3">
<label>Código Tarjeta</label>
<input type="text" name="codigocard" id="codigo" class="form-control"/>
</div>
<div class="col-3 mt27">
<button type="button" class="btn btn-success" onclick="consultaSaldo(this)">Consultar</button>
</div>
<div class="col-6" id="c_resultconsulta">





</div>

</div>
</div>
</div>
</div>
</div>
</div>
<script>
function consultaSaldo(e){
$("#c_resultconsulta").html("");
$("#c_resultconsulta").hide();
$(e).html("consultando..."+i_cargando+"");
var randomNo = Math.floor(Math.random()*9999999);
codigo = $("#codigo").val();
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'consultaSaldoTarjeta',tarjeta:codigo,retornar:0},function(data){
info = $.parseJSON(data);
$("#codigo").val("").focus();	
$(e).html("Consultar");

resulta="";
if(!info.error){
resulta+="<div class='row'><div class='col-12'><div class='card bg-light d-flex flex-fill'><div class='card-header text-muted border-bottom-0'>Información de tarjeta</div><div class='card-body pt-0'><div class='row'><div class='col-7'><h2 class=lead><b>"+codigo+"</b></h2><p class='text-muted text-sm'><b>Cliente: </b> "+info["info"].cliente+" </p><ul class='ml-4 mb-0 fa-ul text-muted'><li class='lip_5'><span class='fa-li lispan_icon'>"+i_bus+"</span> <b>Patente:</b> "+info["info"].patente+"</li><li class='lip_5'><span class='fa-li lispan_icon'>"+i_tipotran+"</span> <b>Tipo Transacción:</b> "+info["info"].tipo+"</li><li class='lip_5'><span class='fa-li lispan_icon'>"+i_saldo+"</span> <b>Saldo:</b> $"+enpesos(info["info"].saldo)+"</li></ul></div><div class='col-5 text-center'><span class='lispan_ig text-success'>"+i_check+"</span></div></div></div></div></div></div>";
// resulta+="<div class='row mt20'><div class='col-12'><div class='card-body box-profile'><div class='text-center'></div><h3 class='profile-username text-center'>"+codigo+"</h3><p class='text-muted text-center'>Código</p><ul class='list-group list-group-unbordered mb-3'><li class='list-group-item'><b>Patente</b> <span class='float-right'></span></li><li class='list-group-item'><b>Cliente</b> <a class='float-right'></a></li><li class='list-group-item'><b>Tipo Transacción</b> <a class='float-right'></a></li><li class='list-group-item'><b>Saldo</b> <a class='float-right'></a></li></ul></div></div></div>";
}else{
resulta+="<div class='error-page'><h2 class='headline text-danger'><span class='lispan_ig text-danger'>"+i_error+"</span></h2><div class='error-content' style='margin-left:80px !important;'><h3>Oops! error al consultar código.</h3><p>"+info.mensaje+"</p></div></div>";
}
$("#c_resultconsulta").html(resulta);
$("#c_resultconsulta").show();
});

}
</script>