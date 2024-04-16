const i_edit = "<i class='fas fa-edit'></i>";
const i_borrar = "<i class='far fa-trash-alt'></i>";
const i_ver = "<i class='far fa-eye'></i>";
const i_nover = "<i class='far fa-eye-slash'></i>";
const i_descarga = "<i class='fas fa-file-download'></i>";
const i_copy = "<i class='far fa-copy'></i>";
const i_upload="<i class='fas fa-file-upload'></i>";
const i_unlock="<i class='fas fa-unlock-alt'></i>";
const i_lock="<i class='fas fa-lock'></i>";
const i_cargando="<i class='fas fa-spinner fa-spin'></i>";
const i_pdf="<i class='fas fa-file-pdf'></i>";
const i_buscar="<i class='fas fa-search'></i>";
const i_print="<i class='fas fa-print'></i>";
const i_lista="<i class='fas fa-clipboard-list'></i>";
const i_add="<i class='fas fa-plus'></i>";
const i_atras="<i class='fas fa-arrow-left'></i>";
const i_error="<i class='fas fa-times'></i>";
const i_ok="<i class='fas fa-fa-check'></i>";
const i_bolsa="<i class='fas fa-shopping-bag'></i>";
const i_cuadro="<i class='far fa-square'></i>";
const i_cuadrocheck="<i class='far fa-check-square'></i>";
const i_reload="<i class='fa-solid fa-rotate'></i>";
const i_warning="<i class='fa-solid fa-exclamation'></i>";
const i_driver="<i class='fa-regular fa-id-card'></i>";
const i_driveron="<i class='fa-solid fa-person-circle-check'></i>";
const i_drivernon="<i class='fa-solid fa-person-circle-xmark'></i>";
const i_funcionando="<i class='fa-solid fa-gear fa-spin'></i>";
const i_detenida="<i class='fa-solid fa-gear'></i>";
const i_buzon="<i class='fa-solid fa-box-tissue'></i>";
const ia_check="<i class='fa-solid fa-check fa-bounce'></i>";
const i_check="<i class='fa-regular fa-circle-check'></i>";
const i_bus="<i class='fa-solid fa-bus'></i>";
const i_tipotran="<i class='fa-regular fa-credit-card'></i>";
const i_saldo="<i class='fa-solid fa-money-check-dollar'></i>";


$('.solonumeros').keyup(function (){
this.value = (this.value + '').replace(/[^0-9]/g,'');
});	 

function enpesos(n) {
n += '';
var x = n.split('.'),
x1 = x[0],
x2 = x.length > 1 ? '.' + x[1] : '',
rgxp = /(\d+)(\d{3})/;
while (rgxp.test(x1)) {
x1 = x1.replace(rgxp, '$1' + '.' + '$2');
}
return x1 + x2;
}

function delajax(id,tabla,campo,idoculta,r){
 if (confirm("Esta seguro que desea eliminar?")) {
var No = Math.floor(Math.random()*9999999);
$.post("operaciones.php", {numero:''+No+'', operacion:'delestandar',campo:''+campo+'',id:''+id+'',tabla:''+tabla+'',retornar:0},function(data){
$("#"+idoculta+"").hide();
if(r=='r'){location.reload();}
});
}
return false;
}

function solonumeros(e){
var keynum = window.event ? window.event.keyCode : e.which;
if ((keynum == 8) || (keynum == 46))
return true;
 
return /\d/.test(String.fromCharCode(keynum));
}

function darvuelto(){
var monto= $("#monto").val();
var dinero= $("#dinero").val();
var vuelto= (dinero-monto);
$("#vuelto").val(vuelto);
$("#vuelto").css("font-weight","bold");
if(vuelto < 0){$("#vuelto").css("background-color","#eb4c4c");$("#vuelto").css("color","#000");}else{$("#vuelto").css("background-color","#75ad1c");$("#vuelto").css("color","#fff");}
}
