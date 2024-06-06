<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t54">
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
<h4>Rendir Bultos</h4>
<div class="row">
<div class="col-md-3">
<div class="card">
<div class="card-body">
<div class="row">
<div class="col-md-12">
<label>Cajero</label>
<? htmlselect('cajero','cajero','usuarios','id','nombre','','','where perfil=3 and activo=1','nombre','','','si','no','no');?>
</div>
</div>
<div class="row mt-2">
<div class="col-md-6 text-end"><button type="button" class="btn btn-success btn-block" onclick="buscarMovimientos()"><?=$i_buscar;?> Buscar</button></div>
</div>

</div>
</div>
</div>

<div class="col-md-6">
<div class="card">
<div class="card-body" id="c54_detalle">
</div>
</div>

</div>

</div>
</div>
</div>
</div>

<script>
let resumen;
function buscarMovimientos(){
id=$("#cajero").val(); 
console.log(id);
if(parseInt(id) !=0){
// buscar info de cajero
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php",{operacion:'rendirBultos',cajero:id,numero:''+randomNo+'',retornar:0} ,function(data){
// console.log(data);
// return;
datos = $.parseJSON(data);
resumen = datos;
cajero = $("#cajero option:selected").text(); 
fechas = datos["fechas"];
nfechas = Object.keys(fechas).length;
primermov = fechas[0];
ultimomov = fechas[nfechas - 1];
// $("#primermov").html(primermov);
// $("#ultimomov").html(ultimomov);
// $("#totalcustodia").html("$"+enpesos(datos["totalcustodia"]));
// $("#usucajero").html(cajero);
detalle="<div class='row'><div class='col-md-12'><table class='table table-bordered'><tr><td>Usuario Cajero</td><td>"+cajero+"</td></tr><tr><td>Usuario Auxiliar</td><td>--</td></tr><tr><td>Primer Movimiento</td><td>"+primermov+"</td></tr><tr><td>Ultimo Movimiento</td><td>"+ultimomov+"</td></tr><tr><td>Total Recaudacion custodia</td><td>"+enpesos(datos["totalcustodia"])+"</td></tr></table></div></div>";



detalle+="<div class='row'><div class='col-md-12'><table class='table table-bordered'><tr><td>Categoria</td><td>Cantidad</td><td>Total</td></tr>";
$.each(datos["bultos"],function(index,valor){
detalle+="<tr><td>"+valor.nombre+"</td><td>"+valor.cantidad+"</td><td>$"+enpesos(valor.total)+"</td></tr>";
});
detalle+="<tr><td colspan=2>Sobre tiempo</td><td>$"+enpesos(datos.valoradicional)+"</td></tr></table></div></div>";

detalle+="<div class='row'><div class='col-md-12'><button type='button' class='btn btn-success' onclick='rendirCustodia()'>Rendir Turno</button>&nbsp;<button type='button' class='btn btn-info' onclick='imprimirResumen()'>Imprimir Resumen</button></div></div>";
$("#c54_detalle").html(detalle);
});
// $("#detallecajero").show();
}else{
alert("no se ha seleccionado ningún cajero");
return;
}
}

function rendirCustodia(){
id=$("#cajero").val(); 
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php",{operacion:'rendirCustodia',cajero:id,numero:''+randomNo+'',retornar:0} ,function(data){
location.reload();
});
}
function imprimirResumen(){
cajero = $("#cajero option:selected").text(); 
detalleresumen='<table border=1 style="border-collapse: collapse;padding:10px;"><tr><td width="200">Usuario Cajero</td><td>'+cajero+'</td></tr><tr><td width="200">Usuario Auxiliar</td><td></td></tr><tr><td width="200">Primer Movimiento</td><td>'+$("#primermov").html()+'</td></tr><tr><td width="200">Ultimo Movimiento</td><td>'+$("#ultimomov").html()+'</td></tr><tr><td width="200">Total Recaudacion custodia</td><td>'+$("#totalcustodia").html()+'</td></tr></table>';
detalleresumen+="<p style='border-bottom:1px solid #ddd;'>Detalle</p><table border=1 style='border-collapse: collapse;padding:10px;'>"+$('#detallemov').html()+"</table>";

 var mywindow = window.open('','PRINT','width=1024,height=768');
    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write('<h1>' + document.title  + '</h1>');
    mywindow.document.write(detalleresumen);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    //mywindow.close();

    return true;	
}
</script>

