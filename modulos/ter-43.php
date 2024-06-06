<div class="container">
<div class="row mt20 c_42">
<div class="col-12">
<h3>Confirmar salidas sin tarjeta</h3>
<div class="card mt20">
<!--<div class="card-header">
<h3 class="card-title"></h3>
</div>-->
<div class="card-body">
<div class="row">
<div class="col-12">
<table class="table table-bordered table-striped" id="tb43_stemp">
<thead>
<th></th>
<th>Cliente</th>
<th>Patente</th>
<th>Recorrido</th>
<th>Destino</th>
<th>Fecha</th>
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
</div>
</div>
<script>
$(function(){
getSalidasTemporales();	
});

let salidasTemp;
function getSalidasTemporales(){
var randomNo = Math.floor(Math.random()*9999999);
$.get("operaciones.php", {numero:''+randomNo+'',operacion:'getSalidasTemporales',retornar:0},function(data){
salidasTemp = $.parseJSON(data);
console.log(salidasTemp);
listarSalidasTemp();
});

}

function listarSalidasTemp(){
f="";x=0;
$.each(salidasTemp,function(i,v){
x++;
f+="<tr><td>"+x+"</td><td>"+v.cliente+"</td><td>"+v.patente+"</td><td>"+v.recorrido+"</td><td>"+v.destino+"</td><td>"+v.fechahora+"</td><td class='text-center' id='td"+i+"_registrar'><button type='button' class='btn btn-success btn-xs' onclick='registrar("+i+",this)'>Registrar</button></td><td class='text-center' id='td"+i+"_eliminar'><button type='button' class='btn btn-danger btn-xs' onclick='elimtemporal("+i+")'>Eliminar</button></td></tr>";
});
$("#tb43_stemp tbody").html("");
let tb43_stemp = new DataTable('#tb43_stemp');
$("#tb43_stemp tbody").html("");
tb43_stemp.destroy();
$("#tb43_stemp tbody").html(f);
tb_mov26=$('#tb43_stemp').DataTable({
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
}

function registrar(i,e){
sal = salidasTemp[i];
let s_iduser=$("#userid").val();
let s_user=$("#username").val();
sal["usuario"]=s_iduser;
sal["idtemp"]=i;


// console.log(sal);
// return;
$("#td"+i+"_registrar").html("<span class='text-success'>"+i_cargando+"</span>");

// return;
json = JSON.stringify(sal);
$.post("operaciones.php", {numero:''+Math.floor(Math.random()*9999999)+'',operacion:'registrarSalidaTemp',datos:json,retornar:0},function(data){
$("#td"+i+"_registrar").html("<span class='text-success'>"+i_check+"</span>");
$("#td"+i+"_eliminar").html("");
// location.reload();
})


}



</script>

