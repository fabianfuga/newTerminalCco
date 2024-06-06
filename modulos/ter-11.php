<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t11">
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


<div class="container-fluid">

<?
if($_SESSION["perfilusuario"]==3){
echo "<div class='row mt-2'><div class='col-md-12'><div class='alert alert-success'><a href ='index.php?mod=2&subid=recaudacion' class='btn btn-primary '><span>Volver al Menu</span></a></div></div></div>";
}
?>

<div id="validacionEmpresa" class="toasts-top-right fixed oculto">
<div class="toast bg-danger fade show" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-header"><strong class="mr-auto">Error al registrar recarga</strong><button  type="button" class="ml-2 mb-1 close" aria-label="Close" onclick='cerrarAlerta()'><span aria-hidden="true">×</span></button></div><div class="toast-body"></div></div>
</div>



<div class="row mt20 c_11" id="v11">
<div class="col-12">
<h3>Recargar Saldo Empresa</h3>
<div class="card mt20">
<!--<div class="card-header">
<h3 class="card-title"></h3>
</div>-->
<div class="card-body">
<div class="row mt-3">

<div class="col-4">
<div class="row mb-3">
<div class="col-4"><label class="col-form-label">Monto</label></div>
<div class="col-4"><input type="text" id="monto" class="form-control" onkeypress="return solonumeros(event);" name="monto"></div>
</div>

<div class="row mb-3">
<div class="col-4"><label class="col-form-label">Dinero Recibido</label></div>
<div class="col-4"><input type="text" id="dinero" class="form-control" onkeypress="return solonumeros(event);" name="dinero"></div>
</div>

<div class="row mb-3">
<div class="col-4"><label class="col-form-label">Vuelto</label></div>
<div class="col-4"><input type="text" id="vuelto" class="form-control" name="vuelto" disabled></div>
</div>

<div class="row mb-3">
<div class="col-4"><label class="col-form-label">Empresa</label></div>
<div class="col-8">
<select id="cliente_re" name="cliente_re" class="form-control"></select></div>
</div>

<div class="row mb-3">
<div class="col-8 offset-md-4"><button type="button" class="btn btn-success" onclick="validar();">Recargar</button></div>
</div>
</div>

<div class="col-3" id="divbotonera">
<input type="hidden" id="inpcompletar">
<div class="row">
<button type="button" class="btn botonera btn-success" onclick="completamonto(1)">1</button>
<button type="button" class="btn botonera btn-success" onclick="completamonto(2)">2</button>
<button type="button" class="btn botonera btn-success" onclick="completamonto(3);">3</button>
</div>
<div class="row" style="margin-top:1px;">
<button type="button" class="btn botonera btn-success" onclick="completamonto(4);">4</button>
<button type="button" class="btn botonera btn-success" onclick="completamonto(5);">5</button>
<button type="button" class="btn botonera btn-success" onclick="completamonto(6);">6</button>
</div>	
<div class="row" style="margin-top:1px;">
<button type="button" class="btn botonera btn-success" onclick="completamonto(7);">7</button>
<button type="button" class="btn botonera btn-success" onclick="completamonto(8);">8</button>
<button type="button" class="btn botonera btn-success" onclick="completamonto(9);">9</button>
</div>	
<div class="row" style="margin-top:1px;">
<button type="button" class="btn botonera btn-success" onclick="completamonto(0);">0</button>
<button type="button" class="btn botonera btn-danger" onclick="borracant();"><?=$i_error;?></button>
<button type="button" class="btn botonera btn-warning" onclick="cambiacant();"><?=$i_check;?></button>
</div>
<div class="row mt-3">
<div class='contbillete'><span class='manito'><img src="images/20000.jpg" onclick='recibirdinero(20000);'></span></div>
<div class='contbillete'><span class='manito'><img src="images/10000.jpg" onclick='recibirdinero(10000);'></span></div>
</div>
<div class="row mt-3">
<div class='contbillete'><span class='manito'><img src="images/5000.jpg" onclick='recibirdinero(5000);'></span></div>
<div class='contbillete'><span class='manito'><img src="images/2000.jpg" onclick='recibirdinero(2000);'></span></div>
</div>
<div class="row mt-3">
<div class='contbillete'><span class='manito'><img src="images/1000.jpg" onclick='recibirdinero(1000);'></span></div>
<div class='contmoneda'><span class='manito'><img src="images/500.png" onclick='recibirdinero(500);'></span></div>
<div class='contmoneda'><span class='manito'><img src="images/100.png" onclick='recibirdinero(100);'></span></div>
</div>
</div>


<div class="col-4 offset-md-1">
<div class="row">
<div class="col-12"><h5>Últimas Recargas</h5></div>
</div>
<div class="row">
<div class="col-12">
<table class="table table-bordered table-striped" id="v6_tbrecargas">
<thead>
<th>Cliente</th><th>Monto</th><th>fecha</th>
</thead>
<tbody>
<?
$s="select um.cliente,um. monto,um.fecha, cli.nombre  from ultimosmovimientos um left outer join clientes cli on um.cliente = cli.id  where  um.cuenta=1 and um.tipo=1 and um.estado!=1 && um.usuario=".$id."  order by um.id desc limit 0,10";
/* $r=$link->query($s);
if(mysqli_num_rows($r) > 0){
while($f=mysqli_fetch_array($r)){
echo "<tr><td>".$f["nombre"]."</td><td>".enpesos($f["monto"])."</td><td>".devfechalsegundos($f["fecha"])."</td></tr>";
}
}else{
echo "<tr><td colspan=3 class='text-center'>Usuario no ha registrado recargas</td></tr>";
} */
?>
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

<script>
$(function(){

$("#monto").focus();
$("#inpcompletar").val("monto");

getClientesEmpresa();



});





$("#monto").focus(function(e) {
$("#inpcompletar").val("monto");
// $("#divbotonera").show(); 
});
$("#dinero").focus(function(e) {
$("#inpcompletar").val("dinero");
// $("#divbotonera").show();
});
$("#cliente_re").click(function(e) {

});

let clientes;
function getClientesEmpresa(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getClientesEmpresa',retornar:0},function(data){
optcli="<option value=0>--</option>";
clientes=$.parseJSON(data);
$.each(clientes,function(i,v){
optcli+="<option value="+i+">"+v.cliente+"</option>";	
});
$("#cliente_re").html(optcli);

$("#cliente_re").chosen().on('chosen:showing_dropdown', function() {
$("#inpcompletar").val("");
// $("#divbotonera").hide();
darvuelto(); 	
});

});

}

function completamonto(n){
inp = $("#inpcompletar").val();
va = $("#"+inp+"").val();
va+=n;
$("#"+inp+"").val(va).select().focus();
}

function recibirdinero(n){
inp = $("#inpcompletar").val();
va = parseInt($("#"+inp+"").val());
if(isNaN(va)){va=0;}
console.log(va);
va = va + n;
$("#"+inp+"").val(va).select().focus();
}

function borracant(){
inp = $("#inpcompletar").val();
$("#"+inp+"").val("").select().focus();
}


function validar() {
let monto= $("#monto").val();
let dinero= $("#dinero").val();
let vuelto= (dinero-monto);
let idcliente= parseInt($("#cliente_re").val());
let cliente= $("#cliente_re option:selected").text();



if(parseInt(monto) > 0 && parseInt(dinero) >= monto && !isNaN(idcliente)){



formview="<div class='row'><div class='col-12'><table class='table table-bordered'><tr><td>Monto</td><td>$"+enpesos(monto)+"</td></tr><tr><td>Dinero Recibido</td><td>$"+enpesos(dinero)+"</td></tr><tr><td>Vuelto</td><td>$"+enpesos(vuelto)+"</td></tr><tr><td>Empresa</td><td>"+cliente+"</td></tr></table></div></div>";
$("#m_t11 .modal-header").removeClass("header-rojo").addClass("header-verde");
$("#m_t11 .modal-title").html("Confirmar Recarga");
$("#m_t11 .modal-body").html(formview);
$("#m_t11 .modal-footer").html("<button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Cancelar</button><button type='button' class='btn btn-success' onclick='confirmarRecarga(this,"+monto+","+idcliente+")'>Confirmar</button>");
$("#m_t11").modal("toggle");
$("#validar-salida").modal();	

}else{
$("#validacionEmpresa .toast-body").html("Error al recargar, validar los campos Monto, Dinero Recibido y Empresa");
$("#validacionEmpresa").show();
}
$("#monto").val("").focus();
$("#dinero").val("");
$("#vuelto").val("");
$("#cliente_re").val("");
};

function cancelar(){
$.modal.close();
}

function cerrarAlerta(){
$("#validacionEmpresa").hide();
$("#monto").val("").focus();
$("#dinero").val("");
$("#vuelto").val("");
$("#cliente_re").val("");
}


function confirmarRecarga(e,monto,idcliente){
$(e).html("espera por favor..."+i_cargando+"");
// return;
// e.addEventListener('click', function(event) {
        // event.target.disabled = true;
    // });
$(e).attr("disabled",true);
let iduser=$("#userid").val();
let ippos=$("#ippos").val();
let codsofland=$("#cod_softland").val();
let esboleta = clientes[idcliente].cliboleta;


var randomNo = Math.floor(Math.random()*9999999);
$.post("operaciones.php", {numero:''+randomNo+'',operacion:'recargaempresa',re_monto:monto,re_cliente:idcliente,re_user:iduser,re_ippos:ippos,re_codsofland:codsofland,re_boleta:esboleta,retornar:0},function(data){
location.reload();
}); 



}


</script>