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
$i_menos="<i class='fa-solid fa-minus'></i>";
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
$i_alert="<i class='fa-solid fa-triangle-exclamation'></i>";
$i_alert2xl="<i class='fa-solid fa-2xl fa-triangle-exclamation'></i>";
$i_checksaldo="<i class='fa-solid fa-money-check-dollar'></i>";
$i_up="<i class='fa-solid fa-arrow-up'></i>";
$i_checksaldo_lg="<i class='fa-solid fa-lg fa-money-check-dollar'></i>";
$i_checksaldo_xl="<i class='fa-solid fa-xl fa-money-check-dollar'></i>";


/* fechas */
function convfecha($fechagringa){
$cadena=explode('/',$fechagringa);
$nuevafecha="".$cadena[2]."/".$cadena[1]."/".$cadena[0]."";
return $nuevafecha;
}
function solofechag($fecha){
list($fechagringa,$lahora) = explode(' ',$fecha);
$nuevafecha=$fechagringa;
return $nuevafecha;
}

function convfechaguion($fechagringa){
$cadena=explode('-',$fechagringa);
$nuevafecha="".$cadena[2]."-".$cadena[1]."-".$cadena[0]."";
return $nuevafecha;
}
function convfechahora($fecha){
list($fechagringa,$lahora) = explode(' ',$fecha);
list($eldia,$elmes,$elano) = explode('/',$fechagringa);
$nuevafecha="".$elano."/".$elmes."/".$eldia." ".$lahora."";
return $nuevafecha;}
function convfechadate($fechagringa){$cadena=explode('/',$fechagringa);
$nuevafecha="".$cadena[2]."-".$cadena[1]."-".$cadena[0]."";return $nuevafecha;
}
function devfecha($fechagringa){
list($elano, $elmes, $eldia) = explode('-', $fechagringa);
$nuevafecha = "" . $eldia . "/" . $elmes . "/" . $elano . "";
return $nuevafecha;
}


function convfechadate2($fecha){
list($fechagringa,$lahora) = explode(' ',$fecha);
list($eldia,$elmes,$elano) = explode('/',$fechagringa);
$nuevafecha="".$elano."-".$elmes."-".$eldia." ".$lahora."";
return $nuevafecha;}

function devfechahora($fecha){list($fechagringa,$lahora) = explode(' ',$fecha);list($elano,$elmes,$eldia) = explode('-',$fechagringa);$nuevafecha="".$eldia."/".$elmes."/".$elano." ".$lahora."";return $nuevafecha;}

function devfechahora2($fecha){list($fechagringa,$lahora) = explode(' ',$fecha);list($elano,$elmes,$eldia) = explode('/',$fechagringa);$nuevafecha="".$eldia."/".$elmes."/".$elano." ".$lahora."";return $nuevafecha;}

function devfechalsegundos($fecha){
  list($fechagringa,$lahora) = explode(' ',$fecha);
  list($elano,$elmes,$eldia) = explode('-',$fechagringa);
  $nuevafecha="".$eldia."/".$elmes."/".$elano." ".$lahora."";
  return $nuevafecha;
  }
function devfechal($fecha){
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

function getSaldoAnterior($cuenta,$cliente,$tarjeta,$fechahora){
global $link;
if($cuenta==1){$consulta="where cliente='".$cliente."' and fecha < '".$fechahora."'";}
if($cuenta==2){$consulta="where codigo='".$tarjeta."' and fecha < '".$fechahora."'";}
$sql="select * from movimientos ".$consulta." order by id desc limit 0,1";
$res=$link->query($sql);
$cuenta = mysqli_num_rows($res);
if($cuenta > 0){
$fila=mysqli_fetch_array($res);
$saldo=$fila["saldo"];
}else{
$saldo=0;
}
return $saldo;
}

function cuentaSalidasNegativas($codigo){
global $link;
$sql="select count(*) as totalsalidas from salidasnegativas where san_tarjeta='".$codigo."' && san_estado=0";
$res=$link->query($sql);
$fila=mysqli_fetch_array($res);
return $fila["totalsalidas"];
}

function getUltimaSalida($codigo){
global $link;
$sql="select * from ultimasalida where usa_tarjeta = '".$codigo."'";
$res=$link->query($sql);
if(mysqli_num_rows($res) > 0){
// existe registro de ultima salida de tarjeta
$fila=mysqli_fetch_array($res);
$detalle=[
"error"=>false,
"id"=>$fila["usa_id"],
"fecha"=>devfecha($fila["usa_fecha"]),
"tarjeta"=>$fila["usa_tarjeta"],
"monto"=>$fila["usa_monto"]
];
}else{
$detalle=[
"error"=>true,
"mensaje"=>"no existe registro de ultima salida"
];	
}
return $detalle;
}

function getTarifasxRecorridos(){
global $link;
$tarifas=[];
$s="select idtipodestino from  tiporecorrido";
$r=$link->query($s);
while($f=mysqli_fetch_array($r)){
$tarifas[$f["idtipodestino"]]=getTarifaRecorrido($f["idtipodestino"]);
}
return $tarifas;
}

function getTarifaRecorrido($tipo){
global $link;
$s="select valor from tarifas where idtiporecorrido =".$tipo." order by fecha desc limit 0,1";
$r=$link->query($s);
$f=mysqli_fetch_array($r);
$tarifa = intval($f["valor"]);
return $tarifa;
}

function getTarifa($fecha,$destino){
global $link;
$sql="select * from tarifas where fecha <='".$fecha."' and idtiporecorrido=".$destino." order by fecha desc limit 0,1";
$res=$link->query($sql);
$fila=mysqli_fetch_array($res);
$tarifa=$fila["valor"];
return $tarifa;
}

function getDetalleMov($id){
global $link;
$sql="select * from movimientos where id=".$id."";
$res=$link->query($sql);
$detalle=array();
while($fila=mysqli_fetch_array($res)){
$detalle[$id]=[
"monto"=>$fila["monto"],
"saldo"=>$fila["saldo"],
"cuenta"=>$fila["cuenta"],
"codigo"=>$fila["codigo"],
"cliente"=>$fila["cliente"],
"fecha"=>$fila["fecha"]
];	
}
return $detalle;
}

function generarCodigo($longitud){
$key = '';
$pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
$max = strlen($pattern) - 1;
for ($i = 0; $i < $longitud; $i++) $key .= $pattern[
mt_rand(0, $max)];
return $key;
}

function post_clave($password, $cost = 11){
// Genera sal de forma aleatoria
$salt = substr(base64_encode(openssl_random_pseudo_bytes(17)), 0, 22);
// reemplaza caracteres no permitidos
$salt = str_replace("+", ".", $salt);
// genera una cadena con la configuración del algoritmo
$param = '$' . implode('$', array("2y", str_pad($cost, 2, "0", STR_PAD_LEFT), $salt));
// obtiene el hash de la contraseña
return crypt($password, $param);
}


function existeUserEmpresa($user){
global $link;
$s="SELECT * FROM `userempresa` WHERE `usuario` LIKE '".$user."'";
$r=$link->query($s);
$existe = mysqli_num_rows($r);
return $existe;
}


/************************************************************
SE ACTUALIZA FUNCION PARA INCORPORAR PARAMETRO AUXILIAR
SE DEJA PREDETERMINADO A 0 PARA LOS CASOS DE COBRO ADICIONAL
FECHA DE ACTUALIZACION : 09-02-2020
****************************************************************/
function postHistorialMcu($cajero,$codigo,$tipo,$detalle,$monto,$auxiliar=0){
global $link;
$sql="delete from historialmcu where hmcu_codigo='".$codigo."' && hmcu_tipo='".$tipo."'";
$res=$link->query($sql);
$sql1="insert into historialmcu(hmcu_cajero,hmcu_codigo,hmcu_tipo,hmcu_detalle,hmcu_montoadicional,hmcu_auxiliar)values('".$cajero."','".$codigo."','".$tipo."','".$detalle."','".$monto."','".$auxiliar."')";
$res1=$link->query($sql1);
// return $sql1;
}
function horastranscurridas($fechaingreso,$fecharetiro){
if($fecharetiro == "0000-00-00 00:00:00"){
$now = date("Y-m-d H:i:s");
$ahora = time();
}else{
$now=$fecharetiro;
$ahora = strtotime($fecharetiro);
}
$inicio= strtotime($fechaingreso);
$hh	= ($ahora - $inicio)/3600;
$hh 	= abs($hh);
$hh = floor($hh);

$f1=new DateTime($fechaingreso);
$f2=new DateTime($now);
$fecha = $f1->diff($f2);
$tiempo = "";
//años
if($fecha->y > 0){$tiempo .= $fecha->y;
if($fecha->y == 1)$tiempo .= " año, ";else$tiempo .= " años, ";}
//meses
if($fecha->m > 0){$tiempo .= $fecha->m;
if($fecha->m == 1)$tiempo .= " mes, ";else$tiempo .= " meses, ";}
//dias
if($fecha->d > 0){$tiempo .= $fecha->d;
if($fecha->d == 1)$tiempo .= " día, ";else$tiempo .= " días, ";}

//horas
if($fecha->h > 0){$tiempo .= $fecha->h;
if($fecha->h == 1)$tiempo .= " hora, ";else$tiempo .= " horas, ";}
//minutos
if($fecha->i > 0){$tiempo .= $fecha->i;
if($fecha->i == 1)$tiempo .= " minuto";else$tiempo .= " minutos";}
else if($fecha->i == 0) //segundos
$tiempo .= $fecha->s." segundos";

$resultado["HH"]=$hh;
$resultado["tiempo"]=$tiempo;
return $resultado;
}
function getDetalleMovCus($id){
global $link;
$sql="select * from detallemovcustodia where dmc_movimiento='".$id."'";
$res=$link->query($sql);
$detalle=array();
while($fila=mysqli_fetch_array($res)){
$bulto=obtenervalor("bultos","bul_nombre","where bul_id='".$fila["dmc_bulto"]."'");
$colorbulto=obtenervalor("bultos","bul_color","where bul_id='".$fila["dmc_bulto"]."'");
$tarifa=$fila["dmc_tarifa"];
$ubicacion=obtenervalor("ubicaciones","ubi_nombre","where ubi_id='".$fila["dmc_ubicacion"]."'");
// $codigo=$fila["mcu_codigo"];
if($fila["dmc_tipobulto"]==1){$tipobulto="Normal";$valordeclarado="No Declarado";}else{$tipobulto="Frágil";$valordeclarado=$fila["dmc_valordeclarado"];}
$detalle[]=array("id"=>$fila["dmc_id"],"cantidad"=>$fila["dmc_cantidad"],"idbulto"=>$fila["dmc_bulto"],"bulto"=>$bulto,"colorbulto"=>$colorbulto,"tarifa"=>$tarifa,"idubicacion"=>$fila["dmc_ubicacion"],"ubicacion"=>$ubicacion,"tipobulto"=>$tipobulto,"valordeclarado"=>$valordeclarado,"total"=>$fila["dmc_total"]);
}
return $detalle;
}

function getBultosxTipo($datos){
global $link;
$sql="select * from bultos";
$res=$link->query($sql);
$data=array();
while($fila=mysqli_fetch_array($res)){
$cuenta = 0;
foreach($datos as $index=>$valor){
if($valor["idbulto"]== $fila["bul_id"]){
$cuenta++;
}
}
$data[$fila["bul_id"]]=$cuenta;
}

return $data;
}

function getHistorialConsultas($codigo){
global $link;
$historial=array();
$sql="select * from historialmcu where hmcu_tipo='consultaticket' &&  hmcu_codigo='".$codigo."' order by hmcu_fechahora desc ";
$res=$link->query($sql);
while($fila=mysqli_fetch_array($res)){
$auxiliar=obtenervalor("usuarios","nombre","where id='".$fila["hmcu_auxiliar"]."'");	
$historial[]=array("fechaconsulta"=>devfechal($fila["hmcu_fechahora"]),"auxiliar"=>$auxiliar);
}
return $historial;
}

function devabecedario(){
$abc=array();
$abc[0]="A";$abc[1]="B";$abc[2]="C";$abc[3]="D";$abc[4]="E";$abc[5]="F";$abc[6]="G";	
$abc[7]="H";$abc[8]="I";$abc[9]="J";$abc[10]="K";$abc[11]="L";$abc[12]="M";$abc[13]="N";$abc[14]="O";$abc[15]="P";$abc[16]="Q";$abc[17]="R";$abc[18]="S";$abc[19]="T";$abc[20]="U";$abc[21]="V";$abc[22]="W";$abc[23]="X";$abc[24]="Y";$abc[25]="Z";
return $abc;	
}
function generarExcel($nombre,$cabecera,$datos){
$excel = new PHPExcel();
$excel->getProperties()->setCreator("Plaza Terminal Curico");
$excel->getProperties()->setTitle($nombre);
$excel->setActiveSheetIndex(0);
$abc=devabecedario();
$nfila=2;
$nlc=0;
foreach($cabecera as $index=>$valor){
$excel->getActiveSheet()->setCellValue(''.$abc[$nlc].'1', ''.$valor.'');
$nlc++;
}

foreach($datos as $index=>$valor){
$cletras=0;
foreach($valor as $index1=>$valor2){
$excel->getActiveSheet()->setCellValue(''.$abc[$cletras].''.$nfila, ''.$valor2.'');
$cletras++;
}
$nfila++;
}

$excel->getActiveSheet()->setTitle('Hoja 1');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$objWriter->save('documentos/'.$nombre.'.xlsx');
$ruta='documentos/'.$nombre.'.xlsx';
return $ruta;	
}

?>