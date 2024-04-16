<?
date_default_timezone_set("America/Santiago");
$i_edit = "<i class='fas fa-edit'></i>";
$i_borrar = "<i class='fas fa-trash'></i>";
$i_ver = "<i class='far fa-eye'></i>";
$i_descarga = "<i class='fas fa-file-download'></i>";
$i_copy = "<i class='far fa-copy'></i>";
$i_upload="<i class='fas fa-file-upload'></i>";
$i_unlock="<i class='fas fa-unlock-alt'></i>";
$i_lock="<i class='fas fa-lock'></i>";
$i_cargando="<i class='fas fa-spinner fa-spin'></i>";
$i_pdf="<i class='fas fa-file-pdf'></i>";
$i_activo="<i class='fa fa-lg fa-toggle-on' aria-hidden='true'></i>";
$i_inactivo="<i class='fa fa-lg fa-toggle-off' aria-hidden='true'></i>";
$i_bodega="<i class='fas fa-warehouse'></i>";
$i_print="<i class='fas fa-print'></i>";
$i_back="<i class='fas fa-arrow-circle-left'></i>";
$i_check="<i class='fas fa-check'></i>";
$i_error="<i class='fas fa-times'></i>";
$i_foto="<i class='fas fa-images'></i>";
$i_lista="<i class='fas fa-clipboard-list'></i>";
$i_marker="<i class='fas fa-map-marker-alt'></i>";
$i_uploadcircle="<i class='fas fa-chevron-circle-up'></i>";
$i_sync="<i class='fas fa-sync'></i>";
$i_senddata="<i class='fas fa-file-import'></i>";
$i_mas="<i class='fas fa-plus'></i>";
$i_add="<i class='fas fa-plus-circle'></i>";
$i_buscar="<i class='fas fa-search'></i>";
$i_slider="<i class='fas fa-sliders-h'></i>";
$i_info="<i class='fas fa-info-circle'></i>";
$i_caja="<i class='fas fa-box'></i>";
$i_save="<i class='fas fa-save'></i>";
$i_lentes="<i class='fas fa-glasses'></i>";
$i_instock="<i class='fas fa-sign-in-alt'></i>";
$i_outstock="<i class='fas fa-sign-out-alt fa-rotate-180'></i>";

/* fechas */
function convfecha($fechagringa){
$cadena=explode('/',$fechagringa);
$nuevafecha="".$cadena[2]."/".$cadena[1]."/".$cadena[0]."";
return $nuevafecha;
}

function convfechaguion($fechagringa){
$cadena=explode('-',$fechagringa);
$nuevafecha="".$cadena[2]."-".$cadena[1]."-".$cadena[0]."";
return $nuevafecha;
}

function convfechadate($fechagringa){$cadena=explode('/',$fechagringa);
$nuevafecha="".$cadena[2]."-".$cadena[1]."-".$cadena[0]."";return $nuevafecha;
}
function devfecha($fechagringa){
list($elano, $elmes, $eldia) = explode('-', $fechagringa);
$nuevafecha = "" . $eldia . "/" . $elmes . "/" . $elano . "";
return $nuevafecha;
}

function devfechahora($fecha){list($fechagringa,$lahora) = explode(' ',$fecha);list($elano,$elmes,$eldia) = explode('-',$fechagringa);$nuevafecha="".$eldia."/".$elmes."/".$elano." ".$lahora."";return $nuevafecha;}

function devfechalsegundos($fecha){
  list($fechagringa,$lahora) = explode(' ',$fecha);
  list($elano,$elmes,$eldia) = explode('-',$fechagringa);
  $nuevafecha="".$eldia."/".$elmes."/".$elano." ".$lahora."";
  return $nuevafecha;
  }
 
 function enpesos($precio){
$precioamostrar=number_format ($precio, 0, '.', '.');
return $precioamostrar;
}

function obtenervalor($tabla,$b,$where){
global $link;$elid="";
$sqlf="select * from ".$tabla." ".$where."";$resf=mysqli_query($link, $sqlf);if(mysqli_errno($link)) die(mysqli_error($link));
while($fila=mysqli_fetch_array($resf, MYSQLI_ASSOC)) {$elid=$fila["".$b.""];}return $elid;}

function htmlselect($name,$idselect,$tabla,$campoid,$campovalue,$ancho,$enganche,$sqlextra,$orden,$onchange,$tamano='1',$partircero,$ocultarselect='no',$multiple="no"){
$elid="";
$trozos = explode("|", $campovalue);// campos a mostrar como textos de los options
$nvariables = count($trozos);// cuento los campos separados
//$i=1;
global $link;
if($ocultarselect!='si'){// si el select no debe estar oculto
$varser="<select name=\"".$name."\" class='form-control' ";// inicializo variable del tipo string para crear select
if($ancho!=''){// si valor de parametro no es igual a vacio agrega ancho al selects
$varser.= "style=\"width:".$ancho."px;\" ";
}
$varser.="id=\"".$idselect."\"";// agrega id al select
if($onchange!=""){ $varser.=" onchange=\"".$onchange."\"";}// si parametro de funcion onchange no esta vacion agrega la funcion enviada
//$varser.=" size=\"".$tamano."\"";// agrega tamaño al select
if($multiple=="multiple"){ $varser.=" multiple";}// si es un select  multiple
$varser.=">";// finalizo la apertura de la etiqueta select
}
if($partircero!='no'){// si patir de sero es igual a si entonces agrega option vacio como primer elemento
$varser.="<option value=\"\">-- --</option>";
}

$sqlf="select * from ".$tabla." ".$sqlextra." order by ".$orden."";//ejecuto consulta
$resf=$link->query($sqlf);
$total=mysqli_num_rows($resf);//cuento resultados
//mysqli_set_charset($link,"utf8");// para problemas con los acentos
while($fila= mysqli_fetch_array($resf, MYSQLI_ASSOC)) {// recorro resultado y creo opciones
$elid=$fila["".$campoid.""];
//$elvalue=$fila["".$campovalue.""];
for ($i = 0; $i < $nvariables; $i++) {
$txtrozos[$i]=$fila["".$trozos[$i].""];
}
$varser.="<option value=\"$elid\" ";
if($enganche==$elid){
$varser.= "selected ";
}
$varser.=">";
for ($i = 0; $i < $nvariables; $i++) {
$varser.="".$txtrozos[$i]." ";
}
$varser.="</option>";
}
$varser.="</select>";
echo $varser;
}

function ean($cadena){
$cadena = strrev($cadena);
$i = 0;
while($i < strlen($cadena))
{
if($i%2 == 0) $impares += $cadena[$i];
else $pares += $cadena[$i];
$i++;
}
$suma = $pares + ($impares*3); 
$total=10 -($suma%10);
if($total==10){$total=0;}
return $total;
}


function getRecorridos(){
global $link;
$data=[];
$s="select iddestino,nombredestino,tipodestino from recorridos";
$r=$link->query($s);
while($f=mysqli_fetch_array($r)){
$data[]=[
"id"=>$f["iddestino"],
"nombre"=>$f["nombredestino"],
"tipo"=>$f["tipodestino"]
];
}
return $data;
}

function saldoTarjeta($cuenta,$idcliente,$idtarjeta,$codigo){
global $link;
$tipo = intval($cuenta);
if($tipo===1){
$cargas = $link->query("SELECT saldo from clientes where id=".$idcliente."");
}else{
$cargas = $link->query("SELECT saldo from tarjetas where idtarjeta=".$idtarjeta."");
}
$row=mysqli_fetch_array($cargas);
return $row['saldo'];
}


?>