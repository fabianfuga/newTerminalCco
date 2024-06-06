<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t8">
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
<div class="row mt20 c_8">
<div class="col-12">
<h3>Registro de Salidas</h3>
<div class="card mt20">
<div class="card-body">
<div class="row">
<div class="col-sm-4">
<div class="row">
<div class="col-8">
<label>Código de Tarjeta</label>
<input type="text" class="form-control" name="codigocard" id="codigo">
</div>
<div class="col-4 mt27"><button type="button" class="btn btn-success" onclick="consultaTarjetaSalida()">Consultar</button></div>
</div>
</div>
<div class="col-sm-8">
<div class="row">
<div class="col-sm-12 oculto" id="validaciondetarjeta" >
<div class="alert alert-success" role="alert" id="vt-alert">
  <h5 id="vt-icon" class="alert-heading"></h5>
  <p id="vt-mensaje"></p>
  <!--<hr>
  <p class="mb-0" id="vt-opciones"></p>-->
</div>

<!--<div class="alert alert-primary d-flex align-items-center" role="alert" id="vt-alert">
<span id="vt-icon"></span>
<div id="vt-mensaje" style="margin-left:20px;"></div>
</div>-->
</div>
</div>

<div class="row">
<div class="col-sm-12 table-responsive" id="c8_listarecorridos">
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
$("#codigo").focus();
$('#codigo').keypress(function(e){ if(e.which == 13){consultaTarjetaSalida(); } });	
});

let restarjeta;
function consultaTarjetaSalida(){
$("#validaciondetarjeta").hide();
codigocard=$("#codigo").val();
$.get("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'consultaTarjetaSalida',codigotarjeta:codigocard,retornar:0},function(data){
console.log(data);
res=$.parseJSON(data);
restarjeta = res;
$("#codigo").val("").focus();
if(res.error){
$("#c8_listarecorridos").html("");
switch(parseInt(res.codigo)){
case 100: 
$("#vt-icon").html(i_alertxl+" "+res.mensaje+"");
$("#vt-mensaje").html("La tarjeta asociada al código : <b>"+codigocard+"</b> no se encuentra habilitada para realizar salidas");
break;
case 200:
$("#vt-icon").html(i_alertxl+" Tarjeta acumula salidas negativas");
$("#vt-mensaje").html(res.mensaje);
break;
case 300:
$("#vt-icon").html(i_alertxl+" "+res.mensaje);
$("#vt-mensaje").html("");
break;
}
$("#vt-alert").removeClass("alert-primary,alert-success").addClass("alert-danger");
$("#validaciondetarjeta").show();
return;
}else{
// console.log(res);
if(parseInt(res.fsc)){mfsc="Ultima salida corresponde a fuera de servicio, no puede registrar fuera de sericio consecutivo";}else{mfsc="";}
if(res.saldo < 0){
$("#vt-icon").html(i_alertxl+" Tarjeta con saldo negativo $"+enpesos(res.saldo)+"");
$("#vt-mensaje").html(mfsc);
$("#vt-alert").removeClass("alert-primary alert-success").addClass("alert-danger");
$("#validaciondetarjeta").show();	
}else{
$("#vt-icon").html(i_okcxl+" Tarjeta con saldo disponible");
$("#vt-mensaje").html(mfsc);
$("#vt-alert").removeClass("alert-primary alert-danger").addClass("alert-success");
$("#validaciondetarjeta").show();	
}



recorridos = res.recorridos;
// console.log(recorridos);
frec="";
$.each(recorridos,function(i,v){
if(parseInt(res.fsc) && parseInt(i) == 1){a_btn="disabled";}else{a_btn="";}
frec+="<tr><td>"+res.patente+"</td><td>"+v.recorrido+"</td><td>"+v.tiporecorrido+"</td><td>$"+enpesos(v.tarifa)+"</td><td class='text-center'><button type='button' class='btn btn-primary' "+a_btn+" onclick='salida(\""+codigocard+"\","+res.idtarjeta+","+v.tarifa+","+res.idcliente+","+i+","+v.idtipodestino+","+res.saldo+","+res.tipotransaccion+")'>Registrar</button></td></tr>";	
});

tbrec="<table class='table table-bordered table-striped'><thead><th>Patente</th><th>Recorrido</th><th>Tipo</th><th>Tarifa</th><th></th><tbody>"+frec+"</tbody></table>";
$("#c8_listarecorridos").html(tbrec);
}


});
return;


iduser=$("#userid").val();
codigocard=$("input[name='codigocard']").val();
if(codigocard !=""){
tbrecorridos = "";	
$.get("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'consultaTarjetaSalida',codigotarjeta:''+codigocard+'',retornar:'no'},function(data){
console.log(data);
recorridos = $.parseJSON(data);
tbrecorridos+="<table  style='font-size:20px;font-weight:bold;'id= listado class=\"table table-condensed table-striped table-bordered table-hover\"><thead>";
tbrecorridos+="<th>Patente</th>";
tbrecorridos+="<th>Recorrido</th>";
tbrecorridos+="<th>Tipo</th>";
tbrecorridos+="<th>Tarifa</th>";
tbrecorridos+="<th></th>";
tbrecorridos+="</thead><tbody>";
$.each(recorridos["recorridos"],function(index,valor){
tbrecorridos+="<tr>";
tbrecorridos+="<td>"+recorridos["bus"]+"</td>";
tbrecorridos+="<td>"+valor.recorrido+"</td>";
tbrecorridos+="<td>"+valor.tipodestino+"</td>";
tbrecorridos+="<td>$ "+valor["valor"]+"</td>";
tbrecorridos+="<td><button type='button' class='btn btn-primary' onclick=\"salida('"+recorridos["identificador"]+"','"+recorridos["codigoticket"]+"','"+recorridos["codigotarjeta"]+"','"+recorridos["bus"]+"','"+valor.recorrido+"','"+valor.idrecorrido+"','"+valor.tiporecorrido+"','"+valor.tipodestino+"','"+recorridos["cliente"]+"','"+recorridos["tipocuenta"]+"','"+iduser+"','"+valor["valor"]+"')\">Registrar</button></td>";	
tbrecorridos+="</tr>";
});
tbrecorridos+="</tbody></table>";
$("#listarecorridos").html(tbrecorridos);
});	
}else{
$("input[name='codigocard']").focus()
}


}


function salida(s_codigo,s_idtarjeta,s_monto,s_idcliente,s_iddestino,s_idtipodestino,s_saldo,s_tipotransaccion){
console.log(restarjeta);
recs=restarjeta["recorridos"][s_iddestino];

formview="<div class='row'><div class='col-12'><table class='table table-bordered'><tr><td>Patente Bus</td><td>"+restarjeta.patente+"</td></tr><tr><td>Recorrido</td><td>"+recs.recorrido+"</td></tr><tr><td>Tipo Recorrido</td><td>"+recs.tiporecorrido+"</td></tr><tr><td>Valor</td><td>$"+enpesos(s_monto)+"</td></tr></table></div></div>";
$("#m_t8 .modal-header").removeClass("header-rojo").addClass("header-verde");
$("#m_t8 .modal-title").html("Confirmar Salida");
$("#m_t8 .modal-body").html(formview);
$("#m_t8 .modal-footer").html("<button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Cancelar</button><button type='button' id='btn_CSalida' class='btn btn-success' onclick='confirmarSalida(this,\""+s_codigo+"\","+s_idtarjeta+","+s_monto+","+s_idcliente+","+s_iddestino+","+s_idtipodestino+","+s_saldo+","+s_tipotransaccion+")'>Confirmar</button>");
$("#m_t8").modal("toggle")
$("#btn_CSalida").focus();

}

function confirmarSalida(e,s_codigo,s_idtarjeta,s_monto,s_idcliente,s_iddestino,s_idtipodestino,s_saldo,s_tipotransaccion){
$(e).html("espera por favor..."+i_cargando+"");
// return;
// e.addEventListener('click', function(event) {
        // event.target.disabled = true;
    // });
$(e).attr("disabled",true);
let s_iduser=$("#userid").val();
let s_ippos=$("#ippos").val();
let s_codsofland=$("#cod_softland").val();
var randomNo = Math.floor(Math.random()*9999999);
$.post("operaciones.php", {numero:''+randomNo+'',operacion:'registrarsalida',codigo:s_codigo,idtarjeta:s_idtarjeta,monto:s_monto,idcliente:s_idcliente,iddestino:s_iddestino,idtipodestino:s_idtipodestino,saldo:s_saldo,tipotransaccion:s_tipotransaccion,usuario:s_iduser,ippos:s_ippos,retornar:0},function(data){
console.log(data);
// location.reload();
}); 



}

</script>