<?
session_start();
include("conexion.php");
include("funciones.php");
require_once 'includes/phpexcel/PHPExcel/IOFactory.php';
$ope=$_REQUEST["operacion"];
require_once('sg_classdte.php');

switch($ope){
	
/**********************************
GENERICAS
********************************/
case "guardaestandar":
foreach($_REQUEST as $nombre_campo => $valor){
list($campo, $tipo) = explode("-", $nombre_campo);
if($campo=="tabla"){$tabla=$valor;}
if($campo=="retornar"){$sale_a=$valor;}
if($campo!=="tabla" and $campo!=="operacion" and $campo!=="retornar"){
if($tipo=="f"){
$valor=convfecha($valor);
}
$cadenaA.="$campo,";
$cadenaB.="'".$valor."',";
}
}
$cadenaA=substr($cadenaA,0,-1);
$cadenaB=substr($cadenaB,0,-1);
$s="insert into ".$tabla." (".$cadenaA.")values(".$cadenaB.")";
$r=$link->query($s);
//echo $sqlge;
break;

case 'delestandar':
$s="delete from ".$_REQUEST["tabla"]." WHERE  ".$_REQUEST["campo"]."='".$_REQUEST["id"]."'";
$r=$link->query($s);
break;
/*****************************************
operaciones login 
******************************************/
case 'loginterminal':
$ippos=getMyPrint();
$tipoacceso=intval($_REQUEST["tipo"]);
$tarjeta = $_REQUEST["tarjeta"];
$rut=$_REQUEST["rutuser"];
$clave=$_REQUEST["clave"];
if($tipoacceso===1){
//tarjeta
$condicion="where codigo='".$tarjeta."' and activo='1'";
}else{
//rut y clave
$condicion="where rut='".$rut."' and clave='".$clave."' and activo='1'";
}
$s="select * from usuarios ".$condicion."";
$r=$link->query($s);
if(mysqli_num_rows($r) > 0 ){
// usuario identificado
$f=mysqli_fetch_array($r);
$idperfil=intval($f["perfil"]);
$_SESSION["terminal"]="terminal2013";
$_SESSION["terautorizado"]=$f["id"];
$_SESSION["user"]=$f["nombre"];
$_SESSION["perfilusuario"]=$idperfil;
$_SESSION["ip_pc"] = $ippos["ip"];
$_SESSION["ip_pos"] = $ippos["ippos"];
$_SESSION["cajero"] = $f["cod_softland"];

// actualizar ultimo acceso
$s1="update usuarios set ultimologin='".date("Y-m-d H:i:s")."' where id=".$f["id"]."";
switch($idperfil){
case 3:
$ultimaurl="index.php?mod=3&subid=recaudacion";
$s1="update usuarios set enturno=1,ultimologin='".date("Y-m-d H:i:s")."' where id=".$f["id"]."";
break;
case 4:
$ultimaurl="index.php?mod=6&subid=ter-8";
break;
case 7:
$ultimaurl="index.php?mod=12&subid=ter-51";
break;
default:
$ultimaurl="index.php";
break;
}
$r1=$link->query($s1);

$res=[
"error"=>false,
"ir"=>$ultimaurl
];
}else{
$res=[
"error"=>true,
"ir"=>"index.php",
"mensaje"=>"Error, credenciales de acceso no reconocidas"
];
}
echo json_encode($res);
break;

/**********************************
OPERACIONES INDEX 
*************************************/

case 'getCobrosAdicionales':
$sql="select * from historialmcu where hmcu_cajero='".$_REQUEST["usuario"]."' && hmcu_tipo='".$_REQUEST["tipo"]."' && hmcu_estado=0 group by hmcu_codigo";
$res=$link->query($sql);
if(mysqli_num_rows($res)> 0){
$cobros=array();
while($fila=mysqli_fetch_array($res)){
$cobros[]=array("id"=>$fila["hmcu_id"],"fechahora"=>devfechahora($fila["hmcu_fechahora"]),"cajero"=>$fila["hmcu_cajero"],"codigo"=>$fila["hmcu_codigo"],"detalle"=>$fila["hmcu_detalle"],"monto"=>$fila["hmcu_montoadicional"]);
}
}else{
$cobros[0]="NO";
}
echo json_encode($cobros);
break;


/****************************************************
OPERACIONES EMPRESAS 
****************************************************/
case 'getEmpresas':
$empresas=[];
$s="select id,nombre,telefono,nombrecontacto,saldo,boleta from clientes order by nombre asc";
$r=$link->query($s);
while($f=mysqli_fetch_array($r)){
$telefono =($f["telefono"]) ?: '--';
$contacto =($f["nombrecontacto"]) ?: '--';
$empresas[]=[
"id"=>$f["id"],
"nombre"=>$f["nombre"],
"rut"=>"--",
"telefono"=>$telefono,
"contacto"=>$contacto,
"saldo"=>$f["saldo"],
"esboleta"=>$f["boleta"]
];
}
echo json_encode($empresas);
break;

case 'getBusesxEmpresa':
$busesxempresa=[];
$s="select idbus,numero,patentebus from buses where codigoempresa=".$_REQUEST["idcliente"]."";
$r=$link->query($s);
while($f=mysqli_fetch_array($r)){
$busesxempresa[]=[
"id"=>$f["idbus"],
"numero"=>$f["numero"],
"patente"=>$f["patentebus"]
];
}
echo json_encode($busesxempresa);
break;

case 'getRecorridosxBus':
// obtener recorridos
$recorridos = getRecorridos();
// recorrido x bus
$rxbus=[];
$s="select idrecorrido from busesrecorridos where idbus=".$_REQUEST["idbus"]."";
$r=$link->query($s);
while($f=mysqli_fetch_array($r)){
array_push($rxbus,$f["idrecorrido"]);
}

$res=[
"recorridos"=>$recorridos,
"rxbus"=>$rxbus
];
echo json_encode($res);
break;

case 'asignarecorrido':
$s="delete from busesrecorridos where idbus='".$_REQUEST["ibus"]."' and idrecorrido='".$_REQUEST["irecorrido"]."'";

if(intval($_REQUEST["asignar"])){
$s="insert into busesrecorridos (idbus,idrecorrido,usuario)values(".$_REQUEST["ibus"].",".$_REQUEST["irecorrido"].",".$_REQUEST["usuario"].")";
}
$r=$link->query($s);
break;

/***************************************
OPERACIONES BUSES 
***************************************/
case 'getAllBuses':
$s="select b.idbus,b.numero,b.patentebus,c.nombre as empresa from buses b left outer join clientes c on b.codigoempresa = c.id order by b.patentebus asc";
$r=$link->query($s);
$buses=[];
while($f=mysqli_fetch_array($r)){
$buses[]=[
"id"=>$f["idbus"],
"numero"=>$f["numero"],
"patente"=>$f["patentebus"],
"empresa"=>$f["empresa"]
];
}
echo json_encode($buses);
break;

case 'getTipoPatente':
$s="select idtarjeta,codigo,tipotransaccion,saldo from tarjetas where idpatente=".$_REQUEST["idbus"]."";
$r=$link->query($s);
if(mysqli_num_rows($r)>0){
$f=mysqli_fetch_array($r);
$res=[
"error"=>false,
"idtarjeta"=>$f["idtarjeta"],
"idtipo"=>$f["tipotransaccion"],
"codigo"=>$f["codigo"],
"saldo"=>$f["saldo"]
];
}else{
$res=[
"error"=>true
];	
}
echo json_encode($res);
break;

/************************************
OPERACIONES TARIFAS
************************************/
case 'getLastTarifas':
$s="select t.*, tr.tipodestino as recorrido from tarifas t left outer join tiporecorrido tr on t.idtiporecorrido = tr.idtipodestino order by t.idtarifa desc limit 0,10 ";
$r=$link->query($s);
$tarifas=[];
while($f=mysqli_fetch_array($r)){
$tarifas[]=[
"id"=>$f["idtarifa"],
"recorrido"=>$f["recorrido"],
"fecha"=>devfecha($f["fecha"]),
"valor"=>$f["valor"]
];

}
echo json_encode($tarifas);
break;

case 'getAllTarifas':
$s="select t.*, tr.tipodestino as recorrido,u.nombre as usuario  from tarifas t left outer join tiporecorrido tr on t.idtiporecorrido = tr.idtipodestino left outer join usuarios u on t.usuario = u.id order by t.idtarifa desc";
$r=$link->query($s);
$tarifas=[];
while($f=mysqli_fetch_array($r)){
$tarifas[]=[
"id"=>$f["idtarifa"],
"recorrido"=>$f["recorrido"],
"fecha"=>devfecha($f["fecha"]),
"valor"=>$f["valor"],
"usuario"=>$f["usuario"]
];

}
echo json_encode($tarifas);
break;

case 'getTarifas':
$s="select t.*, tr.tipodestino as recorrido from tarifas t left outer join tiporecorrido tr on t.idtiporecorrido = tr.idtipodestino where t.idtiporecorrido=".$_REQUEST["tipo"]." order by t.idtarifa desc ";
$r=$link->query($s);
$tarifas=[];
while($f=mysqli_fetch_array($r)){
$tarifas[]=[
"id"=>$f["idtarifa"],
"recorrido"=>$f["recorrido"],
"fecha"=>devfecha($f["fecha"]),
"valor"=>$f["valor"]
];

}
echo json_encode($tarifas);
break;

case 'guardartarifa':
$idtipo=$_REQUEST["trecorrido"];
$fecha=convfecha($_REQUEST["tfecha"]);
$valor=$_REQUEST["tvalor"];
$iduser=$_REQUEST["usuario"];
$res=[];
$s="select * from tarifas where idtiporecorrido=".$idtipo." and fecha='".$fecha."' and valor=".$valor."";
$r=$link->query($s);
if(mysqli_num_rows($r) > 0){
$res=["error"=>true,"mensaje"=>"tarifa ya existe"];	
}else{
$s1="insert  into tarifas(idtiporecorrido,fecha,valor,usuario) values(".$idtipo.",'".$fecha."',".$valor.",".$iduser.")";
$r1=$link->query($s1);
$res=["error"=>false,"mensaje"=>"tarifa registrada exitosamente"];	
}

echo json_encode($res);
break;

/****************************************
OPERACIONES TARJETAS
****************************************/
case 'getGeneraCodigoTarjeta':
$largo=2;
$possible = "9632581047"; 
$contador = 0; 
while ($contador < $largo) { 
$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
if (!strstr($password, $char)) { 
$clave .= $char;
$contador++;
}
}	 
$fechaunix=date('U');
$codigounico="".$fechaunix."".$clave."";
$codigo=ean($codigounico);
$codigocompleto="$codigounico"."$codigo";
echo $codigocompleto;
break;

case 'getLastTarjetas':
$s="select t.idtarjeta, t.codigo,c.nombre as cliente,p.patentebus as patente,tt.ntipotransaccion as tipo from tarjetas t left outer join clientes c on t.idcliente = c.id left outer join buses p on t.idpatente = p.idbus left outer join tipotransaccion tt on t.tipotransaccion = tt.idtipotransaccion order by t.idtarjeta desc limit 0,20";
$r=$link->query($s);
$tarjetas=[];
while($f=mysqli_fetch_array($r)){
$tarjetas[]=[
"id"=>$f["idtarjeta"],
"codigo"=>$f["codigo"],
"cliente"=>$f["cliente"],
"patente"=>$f["patente"],
"tipo"=>$f["tipo"]
];
}
echo json_encode($tarjetas);
break;

case 'nuevatarjeta':
$codigo=$_REQUEST["codigo"];
$idcliente=$_REQUEST["cliente"];
$fecha=convfechadate($_REQUEST["fecha"]);
$poruser=$_REQUEST["usuario"];
$idpatente=$_REQUEST["patente"];

$tipo=$_REQUEST["tipo"];
if($tipo==1){
$saldo=obtenervalor("clientes","saldo","where id='".$idcliente."'");
}else{
$saldo=0;
}
$sql="insert into tarjetas(codigo,idcliente,fechaemision,poruser,saldo,tipotransaccion,idpatente)values('".$codigo."','".$idcliente."','".$fecha."','".$poruser."','".$saldo."','".$tipo."','".$idpatente."')";
$res=$link->query($sql);
// echo $sql;
// return;

$sale_a=$_REQUEST["retornar"];
break;

case 'consultaSaldoTarjeta':
$s="select t.idtarjeta,t.estado, t.codigo, t.idcliente,t.tipotransaccion,c.nombre as cliente,p.patentebus as patente,tt.ntipotransaccion as tipo from tarjetas t left outer join clientes c on t.idcliente = c.id left outer join buses p on t.idpatente = p.idbus left outer join tipotransaccion tt on t.tipotransaccion = tt.idtipotransaccion where t.codigo='".$_REQUEST["tarjeta"]."'";
$r=$link->query($s);
if(mysqli_num_rows($r) > 0){
$f=mysqli_fetch_array($r);
$saldo=saldoTarjeta($f["tipotransaccion"],$f["idcliente"],$f["idtarjeta"],$_REQUEST["tarjeta"]);
$info=[
"patente"=>$f["patente"],
"cliente"=>$f["cliente"],
"tipo"=>$f["tipo"],
"saldo"=>$saldo,
"idestado"=>$f["estado"]
];

$res=["error"=>false,"info"=>$info];	
}else{
$res=["error"=>true,"mensaje"=>"Código de tarjeta <b>(".$_REQUEST["tarjeta"].")</b> no encontrado"];	
}
echo json_encode($res);
break;

case 'getAlltarjetas':
$s="select t.idtarjeta, t.codigo,t.fechaemision,t.saldo,c.nombre as cliente,p.patentebus as patente,tt.ntipotransaccion as tipo from tarjetas t left outer join clientes c on t.idcliente = c.id left outer join buses p on t.idpatente = p.idbus left outer join tipotransaccion tt on t.tipotransaccion = tt.idtipotransaccion order by t.idtarjeta desc";
$r=$link->query($s);
$tarjetas=[];
while($f=mysqli_fetch_array($r)){
$tarjetas[]=[
"id"=>$f["idtarjeta"],
"codigo"=>$f["codigo"],
"cliente"=>$f["cliente"],
"patente"=>$f["patente"],
"fecha"=>devfecha($f["fechaemision"]),
"saldo"=>$f["saldo"],
"tipo"=>$f["tipo"]
];
}
echo json_encode($tarjetas);
break;

case 'traspasarsaldo':
$idcliente = $_REQUEST["cliente"];
$newcode = $_REQUEST["newcode"];
$oldcode = $_REQUEST["oldcode"];
$oldidtarjeta = $_REQUEST["oldtarjeta"];
$idtipo = $_REQUEST["tipo"];
$idbus = $_REQUEST["bus"];
$usuario = $_REQUEST["usuario"];
$saldo = saldoTarjeta($idtipo,$idcliente,$oldidtarjeta,"");

$comentarios_tarjeta_antigua="Tarjeta deshabilitada en el sistema, movimiento generado por un traspaso de saldo";
$moviantigua=4; // tarjeta antigua
$movinueva=3; // tarjeta nueva
$fechaemision=date("Y-m-d");
$salida=2;
$recarga=1;
$recorrido=0;
$rendido=1;

$s="insert into movimientos(cuenta,codigo,tipo,monto,cliente,recorrido,estado,usuario,saldo,comentarios,tipo_movimiento)values(".$idtipo.",'".$oldcode."',".$salida.",'".$saldo."',".$idcliente.",".$recorrido.",".$rendido.",".$usuario.",'0','".$comentarios_tarjeta_antigua."','".$moviantigua."')";
$r=$link->query($s);

$s1="update tarjetas set estado=2,saldo=0 where idtarjeta=".$oldidtarjeta."";
$r1=$link->query($s1);

$s2="insert into tarjetas(codigo,idcliente,fechaemision,poruser,tipotransaccion,idpatente,estado,saldo)values('".$newcode."',".$idcliente.",'".$fechaemision."',".$usuario.",".$idtipo.",".$idbus.",1,".$saldo.")";
$r2=$link->query($s2);

$s3="insert into movimientos(cuenta,codigo,tipo,monto,cliente,recorrido,estado,usuario,saldo,tipo_movimiento)values(".$idtipo.",'".$newcode."',".$recarga.",'".$saldo."',".$idcliente.",".$recorrido.",".$rendido.",".$usuario.",'".$saldo."',".$movinueva.")";
$r3=$link->query($s3);
break;

/**************************
OPERACIONES RECARGAS
****************************/
case 'validaTarjeta':
$sql="select * from tarjetas where codigo='".$_REQUEST["codigotarjeta"]."'";
$res=$link->query($sql);
$cuenta = mysqli_num_rows($res);
if($cuenta >  0){
$tarjeta["existe"]=1;
$fila=mysqli_fetch_array($res);
$tarjeta["id"]=$fila["idtarjeta"];
if(intval($fila["estado"])===1){
$tarjeta["activa"]=1;	
if(intval($fila["tipotransaccion"])===2){
$tarjeta["normal"]=1;
}else{
$tarjeta["normal"]=0;	
}
}else{
$tarjeta["activa"]=0;
$tarjeta["normal"]=0;	
}
$tarjeta["idcliente"]=$fila["idcliente"];	
}else{
$tarjeta["existe"]=0;
$tarjeta["activa"]=0;
$tarjeta["normal"]=0;	
$tarjeta["idcliente"]=0;	
}
echo json_encode($tarjeta);
break;


case 'recargatarjeta':
$res=[];
$cuenta=2;// nornal
$monto=$_REQUEST["t_monto"];
$tarjeta=$_REQUEST["t_codigo"];
$idtarjeta=$_REQUEST["t_idtarjeta"];
$idcliente=$_REQUEST["t_cliente"];
$usuario=$_REQUEST["t_user"];
$ippos=$_REQUEST["t_ippos"];
$codsofland=$_REQUEST["t_codsofland"];
$codigo=date('U');

$tipo=1; //recarga
$recorrido=0;
$rendido=0;
$fechaemision=date("Y-m-d");
$saldo=saldoTarjeta($tipo,$idcliente,$idtarjeta,"");


if($saldo < 0){
$negativo = $saldo;
$comentarios="Recarga normal, pero con descuento por salidas sin saldo";
}else{
$negativo = 0;
$comentarios="Recarga normal";
}

$neto=round($monto/1.19);
$iva= $monto - $neto;
$saldocuenta= $saldo + $monto;


$doc=new Dte();		
$doc->idempotency="Idempotency-Key:".$codigo;
$doc->Receptor=array("RUTRecep"=>"66666666-6");
$doc->FchEmis=$fechaemision;
$doc->Totales=array("MntNeto"=>$neto,"IVA"=>$iva,"MntTotal"=>$monto,"VlrPagar"=>$monto,"TotalPeriodo"=>$monto);
$Detalle[]=array("NroLinDet"=>"1","NmbItem"=>"RECARGA DE LOZA","QtyItem"=>1,"PrcItem"=>$monto,"MontoItem"=>$monto);
$doc->Detalle=$Detalle;
$doc->bafecta();		
$result=$doc->enviadte($codigo);
if(array_key_exists('error', $result)) {
$res["error"]=true;
$res["mensaje"]="Error al emitir boleta";
$res["api_response"]=$result;
echo json_encode($res);
break;
}
else{
$s="INSERT INTO boletas(tipo_doc, folio, fecha, cajero, cod_producto, descripcion, monto) VALUES ('A',".$result['folio'].",'".$fechaemision."',".$codsofland.",'I10','RECARGA DE LOZA',".$monto.")";
$r=$link->query($s);

$tipo_movimiento=1; //recarga normal
// registramos el movimiento en tabla ultimos movimientos para mostrar informacion
$s1="insert into ultimosmovimientos(cuenta,codigo,tipo,monto,cliente,recorrido,estado,usuario,saldo,tipo_movimiento,comentarios)values(".$cuenta.",'".$tarjeta."',".$tipo.",'".$monto."',".$idcliente.",".$recorrido.",".$rendido.",".$usuario.",'".$saldocuenta."',".$tipo_movimiento.",'".$comentarios."')";
$r1=$link->query($s1);

$s2="insert into movimientos(cuenta,codigo,tipo,monto,cliente,recorrido,estado,usuario,saldo,tipo_movimiento,comentarios,folio,timbre)values(".$cuenta.",'".$tarjeta."',".$tipo.",'".$monto."',".$idcliente.",".$recorrido.",".$rendido.",".$usuario.",'".$saldocuenta."',".$tipo_movimiento.",'".$comentarios."',".$result['folio'].",'".$result['timbre']."')";
$r2=$link->query($s2);
$id=$link->insert_id;

$s3="update salidasnegativas set san_estado=1,san_recarga=".$id." where san_estado=0 && san_tarjeta=".$tarjeta."";
$r3=$link->query($s3);

$s4="update tarjetas set saldo=".$saldocuenta." where codigo='".$tarjeta."'";
$r4=$link->query($s4);

$s5="update clientes set saldo = saldo + ".$monto."  where id=".$idcliente."";
$r5=$link->query($s5);

if($ippos !=="NOPERTENECEALSISTEMA"){
// print_recarga($id,$negativo,$ippos);
} 

$res["error"]=false;
$res["mensaje"]="tarjeja recargada correcamente";
$res["negativo"]=$negativo;
echo json_encode($res);
}

break;


case 'getClientesEmpresa':
$s="select t.idcliente,c.nombre as cliente ,c.boleta as esboleta from tarjetas t left outer join clientes c on t.idcliente=c.id where t.tipotransaccion=1 && t.estado = 1 group by t.idcliente";
$r=$link->query($s);
$data=[];
while($f=mysqli_fetch_array($r)){
$data[$f["idcliente"]]=[
"cliente"=>$f["cliente"],
"cliboleta"=>$f["esboleta"]
];
}
echo json_encode($data);
	
break;

case 'recargaempresa':
$res=[];
$cuenta=1;// empresa
$monto=$_REQUEST["re_monto"];
$idcliente=$_REQUEST["re_cliente"];
$usuario=$_REQUEST["re_user"];
$ippos=$_REQUEST["re_ippos"];
$codsofland=$_REQUEST["re_codsofland"];
$esboleta=intval($_REQUEST["re_boleta"]);
$codigo=date('U');
$tarjeta="**********
***";
$tipo=1; //recarga
$recorrido=0;
$rendido=0;
$fechaemision=date("Y-m-d");
$saldo=saldoTarjeta($tipo,$idcliente,0,"");
$saldocuenta=$saldo + $monto;

$s0="select * from tarjetas where idcliente=".$idcliente." && tipotransaccion=1 && estado = 1";
$r0=$link->query($s0);
$totalempresa=mysqli_num_rows($r0);
if($totalempresa > 0){
	
$comentarios="Recarga normal empresa";
// validamos si cliente requiere boleta
if($esboleta){
$doc=new Dte();		
$doc->idempotency="Idempotency-Key:".$codigo;
$doc->Receptor=array("RUTRecep"=>"66666666-6");
$doc->FchEmis=$fechaemision;
$doc->Totales=array("MntNeto"=>$neto,"IVA"=>$iva,"MntTotal"=>$monto,"VlrPagar"=>$monto,"TotalPeriodo"=>$monto);
$Detalle[]=array("NroLinDet"=>"1","NmbItem"=>"RECARGA DE LOZA","QtyItem"=>1,"PrcItem"=>$monto,"MontoItem"=>$monto);
$doc->Detalle=$Detalle;
$doc->bafecta();		
$result=$doc->enviadte($codigo);
if(array_key_exists('error', $result)) {
$res["error"]=true;
$res["mensaje"]="Error al emitir boleta";
$res["api_response"]=$result;
echo json_encode($res);
break;
}
else{
$s="INSERT INTO boletas(tipo_doc, folio, fecha, cajero, cod_producto, descripcion, monto) VALUES ('A',".$result['folio'].",'".$fechaemision."',".$codsofland.",'I10','RECARGA DE LOZA',".$monto.")";
$r=$link->query($s);

$tipo_movimiento=1; //recarga normal
// registramos el movimiento en tabla ultimos movimientos para mostrar informacion
$s1="insert into ultimosmovimientos(cuenta,codigo,tipo,monto,cliente,recorrido,estado,usuario,saldo,tipo_movimiento,comentarios)values(".$cuenta.",'".$tarjeta."',".$tipo.",'".$monto."',".$idcliente.",".$recorrido.",".$rendido.",".$usuario.",'".$saldocuenta."',".$tipo_movimiento.",'".$comentarios."')";
$r1=$link->query($s1);

$s2="insert into movimientos(cuenta,codigo,tipo,monto,cliente,recorrido,estado,usuario,saldo,tipo_movimiento,comentarios,folio,timbre)values(".$cuenta.",'".$tarjeta."',".$tipo.",'".$monto."',".$idcliente.",".$recorrido.",".$rendido.",".$usuario.",'".$saldocuenta."',".$tipo_movimiento.",'".$comentarios."',".$result['folio'].",'".$result['timbre']."')";
$r2=$link->query($s2);
$id=$link->insert_id;

$s3="update tarjetas set saldo=".$saldocuenta." where idcliente=".$idcliente."";
$r3=$link->query($s3);

// actualizar salidas negativas
while($f0=mysqli_fetch_array($r0)){
$s4="update salidasnegativas set san_estado=1,san_recarga=".$id." where san_estado=0 && san_tarjeta='".$f0["codigo"]."'";
$r4=$link->query($s4);
}
			
$s5="update clientes set saldo=".$saldocuenta."'where id=".$idcliente."";
$r5=$link->query($s5);			

if($ippos !=="NOPERTENECEALSISTEMA"){
//print_recargaEmpresab($id,$idcliente,$ippos);
} 

$res["error"]=false;
$res["mensaje"]="Recarga empresa exitosa";
echo json_encode($res);

}
	
}else{

$tipo_movimiento=1; //recarga normal
// registramos el movimiento en tabla ultimos movimientos para mostrar informacion
$s1="insert into ultimosmovimientos(cuenta,codigo,tipo,monto,cliente,recorrido,estado,usuario,saldo,tipo_movimiento,comentarios)values(".$cuenta.",'".$tarjeta."',".$tipo.",'".$monto."',".$idcliente.",".$recorrido.",".$rendido.",".$usuario.",'".$saldocuenta."',".$tipo_movimiento.",'".$comentarios."')";
$r1=$link->query($s1);

$s2="insert into movimientos(cuenta,codigo,tipo,monto,cliente,recorrido,estado,usuario,saldo,tipo_movimiento,comentarios)values('".$cuenta."','".$tarjeta."','".$tipo."','".$monto."','".$idcliente."','".$recorrido."','".$rendido."','".$usuario."','".$saldocuenta."','".$tipo_movimiento."','".$comentarios."')";
$r2=$link->query($s2);
$id=$link->insert_id;

$s3="update tarjetas set saldo=".$saldocuenta." where idcliente=".$idcliente."";
$r3=$link->query($s3);

// actualizar salidas negativas
while($f0=mysqli_fetch_array($r0)){
$s4="update salidasnegativas set san_estado=1,san_recarga=".$id." where san_estado=0 && san_tarjeta='".$f0["codigo"]."'";
$r4=$link->query($s4);
}
			
$s5="update clientes set saldo=".$saldocuenta." where id=".$idcliente."";
$r5=$link->query($s5);			

if($ippos !=="NOPERTENECEALSISTEMA"){
//print_recargaEmpresab($id,$idcliente,$ippos);
} 

$res["error"]=false;
$res["mensaje"]="Recarga empresa exitosa";
echo json_encode($res);

}
}else{

$res["error"]=true;
$res["mensaje"]="Cliente no mantiene tarjetas activas";
echo json_encode($res);
}
break;

case 'getMovimientosTer27':
$desde=$_REQUEST["desde"];
$hasta=$_REQUEST["hasta"];
if($_REQUEST["idempresa"]!=""){$empresa = "&& cliente=".$_REQUEST["idempresa"]."";}else{$empresa="";}

if($_REQUEST["idpatente"]!="" && $_REQUEST["idpatente"]){
$tarjeta = obtenervalor("tarjetas","codigo","where idpatente=".$_REQUEST["idpatente"]."");
if($tarjeta){
$patente = "&& codigo='".$tarjeta."'";	
}else{
$patente="";
}

}else{$patente="";}

if($_REQUEST["idmovimiento"]!=""){$movimiento="&& tipo_movimiento=".$_REQUEST["idmovimiento"]."";}else{$movimiento="";}

$s="select * from movimientos  where date(fecha) >='".$desde."' && tipo=1  && date(fecha) <= '".$hasta."' ".$empresa." ".$patente." ".$movimiento." order by fecha desc";
// echo $s;
// return;


$r=$link->query($s);
$data=[];
while($f=mysqli_fetch_array($r)){

$codigotarjeta=$f["codigo"];

if(intval($f["cuenta"])===1){
$tipo="EMPRESA";
$patente="";
}else{
$tipo="NORMAL";
$idpatente=obtenervalor("tarjetas","idpatente","where codigo='".$codigotarjeta."'");
$patente=obtenervalor("buses","patentebus","where idbus=".$idpatente."");
}

if($codigotarjeta==""){$codigotarjeta="error";}
$reemplazar=$codigotarjeta;
for($i=0;$i<=8;$i++){$reemplazar[$i] = "*";}

$cliente=obtenervalor("clientes","nombre","where id=".$f["cliente"]."");
$estado="";
switch(intval($f["estado"])){
case 0:$estado="PENDIENTE";break;
case 1:$estado="RENDIDO";break;
case 2:$estado="ELIMINADA";break;
}
$usuario=obtenervalor("usuarios","nombre","where id=".$f["usuario"]."");
if(intval($f["editadopor"])){$useredit=obtenervalor("usuarios","nombre","where id=".$f["editadopor"]."");}else{$useredit="";}

$data[$f["id"]]=[
"fechaunix"=>strtotime($f["fecha"]),
"fecha"=>devfechahora($f["fecha"]),
"codigo"=>$codigotarjeta,
"tarjeta"=>$reemplazar,
"patente"=>$patente,
"idcliente"=>$f["cliente"],
"empresa"=>$cliente,
"idcuenta"=>$f["cuenta"],
"tipo"=>$tipo,
"monto"=>$f["monto"],
"saldo"=>$f["saldo"],
"idestado"=>intval($f["estado"]),
"estado"=>$estado,
"usuario"=>$usuario,
"editadopor"=>$useredit,
"comentario"=>$f["comentarios"]
];
}

echo json_encode($data);
break;

case 'eliminarrecarga':
$id=$_REQUEST["m_id"];
$usuario=$_REQUEST["m_usuario"];
$username=$_REQUEST["m_username"];
$monto=$_REQUEST["m_monto"];
$cuenta=intval($_REQUEST["m_cuenta"]);
$tarjeta=$_REQUEST["m_codigo"];
$cliente=$_REQUEST["m_cliente"];
$fechahora=date("Y-m-d H:i:s",$_REQUEST["m_fecha"]);
$fechahoy=date('Y-m-d');
if($_REQUEST["m_comentarios"] !=""){$comentarios = ", ".$_REQUEST["m_comentarios"];}else{$comentarios="";}
$comentarios="ElIMINADA por: ".$username."".$comentarios."";
$recorrido=0;
$nuevomonto=0;
$nuevosaldo=0;
$estado=2;
$saldoanterior=getSaldoAnterior($cuenta,$cliente,$tarjeta,$fechahora);

$s="update movimientos set recorrido=".$recorrido.", monto=".$nuevomonto.", saldo=".$saldoanterior.", estado=".$estado.", editadopor=".$usuario.", comentarios='".$comentarios."' where id='".$id."'";
$r=$link->query($s);

if($cuenta===1){$consulta="where cliente=".$cliente." and fecha > '".$fechahora."'";}
if($cuenta===2){$consulta="where codigo='".$tarjeta."' and fecha > '".$fechahora."'";}
// actualiza todos los movimientos posteriores
$s1="select id,tipo,monto from movimientos ".$consulta." order by fecha asc";
$r1=$link->query($s1);
if(mysqli_num_rows($r1) > 0){
// exiten registros posteriores 
$saldo = $saldoanterior;
while($f1=mysqli_fetch_array($r1)){
$tipo=intval($f1["tipo"]);
$tarifa=$f1["monto"];
if($tipo===1){$saldo +=$tarifa;}//recarga
if($tipo===2){$saldo -= $tarifa;}//salida

$s3= "update movimientos set saldo=".$saldo." where id=".$f1["id"]."";
$r3=$link->query($s3);
}

if($cuenta===1){
$s4="update tarjetas set saldo = ".$saldo." where idcliente= ".$cliente."";
$r4=$link->query($s4);
}
if($cuenta===2){
$s4="update tarjetas set saldo= ".$saldo." where codigo='".$tarjeta."'";
$r4=$link->query($s4);
}
$s5="update clientes set saldo = ".$saldo." where id =".$cliente."";
$r5=$link->query($s5);
}else{
if($cuenta===1){
$s6="update tarjetas set saldo = ".$saldoanterior." where idcliente=".$cliente."";
$r6=$link->query($s6);
}
if($cuenta===2){
$s6="update tarjetas set saldo= ".$saldoanterior." where codigo='".$tarjeta."'";
$r6=$link->query($s6);
}
$s7="update clientes set saldo = ".$saldoanterior." where id =".$cliente."";
$r7=$link->query($s7);
}

$res=[
"error"=>false,
"mensaje"=>"Movimiento eliminado, registros actualizados",
"saldoanterior"=>$saldoanterior
];
echo json_encode($res);
break;

/*****************************************************
OPERACIONES INFORMES
****************************************************/
case 'getDataCustodia':
$res=[];
$idcajero=$_REQUEST["cajero"];
$cajero=obtenervalor("usuarios","nombre","where id=".$idcajero."");
/**** total recaudacion de cajero ******/
$suma = $link->query("select sum(monto) as total from movimientos where tipo=1 && estado=0 && usuario=".$idcajero."");
$ressuma=mysqli_fetch_array($suma);
$debe=$ressuma["total"];
if(empty($debe)){
$res["error"]=true;

}else{
$res["error"]=false;
$recaudacion=enpesos($debe);

$res["recaudacion"]=$debe;

/***** PRIMER REGISTRO DE CAJERO *************/
$sqlprimero="select * from movimientos where tipo=1 and usuario='".$idcajero."'&& estado=0 order by fecha asc limit 0,1";
$resprimero=$link->query($sqlprimero);
while($filprimero=mysqli_fetch_array($resprimero)){$primero=devfechal($filprimero["fecha"]);$fechaprimero=solofechag($filprimero["fecha"]);}

$res["primero"]=$primero;
$res["fechaprimero"]=$fechaprimero;

/****** ULTIMO REGISTRO DE CAJERO **************/
$sqlultimo="select * from movimientos where tipo=1 && usuario='".$idcajero."' && estado=0 order by fecha desc limit 0,1";
$resultimo=$link->query($sqlultimo);
while($filultimo=mysqli_fetch_array($resultimo)){$ultimo=devfechal($filultimo["fecha"]);$fechaultimo=solofechag($filultimo["fecha"]);}

$res["ultimo"]=$ultimo;
$res["fechaultimo"]=$fechaultimo;

/******* TABLA DETALLES DE RECARGAS CAJERO **********************/
$sqlmultiple="select * from movimientos where tipo=1 && estado=0 && usuario='".$idcajero."' order by fecha";
$resmultiple=$link->query($sqlmultiple);
$totalfilas=mysqli_num_rows($resmultiple);
if($totalfilas > 0){
while($filmultiple=mysqli_fetch_array($resmultiple)){
$card=$filmultiple["codigo"];
$cuenta=$filmultiple["cuenta"];
$idclient=$filmultiple["cliente"];
$client=obtenervalor("clientes","nombre","where id='".$idclient."'");
if($cuenta=="1"){$tiporecarga="EMPRESA";}else{$tiporecarga="NORMAL";}
$cash=$filmultiple["monto"];
$date=devfechal($filmultiple["fecha"]);
$res["movimientos"][strtotime($filmultiple["fecha"])]=[
"card"=>$card,
"cuenta"=>$cuenta,
"idclient"=>$idclient,
"client"=>$client,
"cash"=>$cash,
"tiporecarga"=>$tiporecarga,
"date"=>$date
];

}
}	
}	


echo json_encode($res);
break;

case "rendirturno":
$usuario=$_REQUEST["idusuario"];
$primero=$_REQUEST["primero"];
$ultimo=$_REQUEST["ultimo"];
$sql="update  movimientos set estado=1  where estado=0  and usuario='".$usuario."'";
$resulta=$link->query($sql);
break;

/************************************************************
OPERACIONES SALIDAS 
*************************************************************/
case 'consultaTarjetaSalida':
$res=[];
$fechahoy=date('Y-m-d');
$codigocard=$_REQUEST["codigotarjeta"];
$s="select t.idtarjeta,t.saldo,t.idcliente,t.idpatente,t.tipotransaccion,t.estado,b.patentebus from tarjetas t left outer join buses b on t.idpatente = b.idbus where t.codigo='".$codigocard."'";
$r=$link->query($s);
if(mysqli_num_rows($r) > 0){
$f=mysqli_fetch_array($r);
if(intval($f["estado"])===2){
$res=[
"error"=>true,
"codigo"=>100,
"mensaje"=>"La tarjeta se encuentra desactivada"
];	
}else{
$idtarjeta=$f["idtarjeta"];
$tipotransaccion=intval($f["tipotransaccion"]);
$saldo=$f["saldo"];
$idcliente=$f["idcliente"];
$idbus=$f["idpatente"];
$patente=$f["patentebus"];
$countsnegativas = cuentaSalidasNegativas($codigocard);
// obtengo recorridos 
$recorridos =getRecorridosTarifados($idbus);
if(intval($countsnegativas) > 1){
$res=[
"error"=>true,
"codigo"=>200,
"mensaje"=>"Tarjeta con saldo en contra, registra <b>".$countsnegativas."</b> salidas con saldo negativo, no puede registrar una nueva salida sin realizar una recarga"
];	
}else{
$fsc=0;
$ultimasalida = getUltimaSalida($codigocard);
if(!$ultimasalida["error"] && strtotime(convfecha($ultimasalida["fecha"])) === strtotime($fechahoy) && intval($ultimasalida["monto"]) === 0){
// ultima salida es un fuera de servicio, no puede volver a salir fuera de servicio
$fsc=1;
}

$res=[
"error"=>false,
"idtarjeta"=>intval($idtarjeta),
"tipotransaccion"=>$tipotransaccion,
"saldo"=>intval($saldo),
"idcliente"=>intval($idcliente),
"idbus"=>intval($idbus),
"patente"=>$patente,
"countsnegativas"=>intval($countsnegativas),
"recorridos"=>$recorridos,
"fsc"=>$fsc
];		
}

}
}else{
$res=[
"error"=>true,
"codigo"=>300,
"mensaje"=>"Código de tarjeta no encontrado"
];
}
echo json_encode($res);
break;

case 'registrarsalida':
$fechahoy=date('d/m/Y');
$cuenta=intval($_REQUEST["tipotransaccion"]);//tipo de cuenta cargo transaccion
// 1=> pozo empresa
// 2=> normal
$transaccion=2;// tipo de transaccion 2=salidas 1=> recargas
$codigo=$_REQUEST["codigo"];
$idtarjeta=$_REQUEST["idtarjeta"];
$tarifa=intval($_REQUEST["monto"]);
$cliente=$_REQUEST["idcliente"];
$recorrido=$_REQUEST["iddestino"];
$destino=$_REQUEST["idtipodestino"];
$saldo=intval(saldoTarjeta($cuenta,$cliente,$idtarjeta,""));
$estado=0;
$newsaldo= $saldo - $tarifa;

if($newsaldo < 0 && $tarifa > 0){
$tipo_movimiento=10;
$comentarios="Segunda salida sin saldo, si no recarga tarjeta no puede salir en su próxima salida";
$salida_negativa=1;
}

if($saldo < $tarifa && $tarifa > 0){
$tipo_movimiento=7;
$comentarios="Salida sin Saldo, tarifa supera monto de tarjeta";
$salida_negativa=1;
}

if($saldo >= $tarifa && $tarifa > 0){
$tipo_movimiento=2;
$comentarios="Salida Normal";
$salida_negativa=0;
}
if(!$tarifa){
$tipo_movimiento=8;
$comentarios="Fuera de servicio normal";
$salida_negativa=0;
}
// if($tarifa > 0){
// afecta pozo empresa
if($cuenta==1){
//tarjetas del mismo cliente
$uptarjeta="update tarjetas set saldo = saldo - ".$tarifa." where idcliente=".$cliente."";
}else{
// normal
$uptarjeta="update tarjetas set saldo= saldo - ".$tarifa." where codigo='".$codigo."'";
}	
$resultuptarjeta=$link->query($uptarjeta);
//clientes
$upcliente="update clientes set saldo = saldo - ".$tarifa." where id=".$cliente."";
$resultupcliente=$link->query($upcliente);

$sqlmov="insert into movimientos (cuenta,codigo,tipo,monto,cliente,destino,recorrido,estado,usuario,saldo,tipo_movimiento,comentarios,salida_negativa) values(".$cuenta.",'".$codigo."',".$transaccion.",'".$tarifa."',".$cliente.",".$destino.",".$recorrido.",".$estado.",".$_REQUEST["usuario"].",'".$newsaldo."',".$tipo_movimiento.",'".$comentarios."',".$salida_negativa.")";
$resultamov=$link->query($sqlmov);
$ultimo=$link->insert_id;
// registramos el movimiento en tabla ultimos movimientos para mostrar informacion
$sqlultmov="insert into ultimosmovimientos (cuenta,codigo,tipo,monto,cliente,destino,recorrido,estado,usuario,saldo,tipo_movimiento,comentarios,salida_negativa) values('".$cuenta."','".$codigo."',".$transaccion.",'".$tarifa."',".$cliente.",".$destino.",".$recorrido.",".$estado.",".$_REQUEST["usuario"].",'".$newsaldo."',".$tipo_movimiento.",'".$comentarios."',".$salida_negativa.")";
$resultmov=$link->query($sqlultmov);
//registramos salida negativa
if($salida_negativa == 1){
$nombredestino=obtenervalor("recorridos","nombredestino","where iddestino=".$recorrido."");
$sql1="insert into salidasnegativas(san_monto,san_tarjeta,san_destino,san_movimiento,san_estado)values(".$tarifa.",'".$codigo."','".$nombredestino."',".$ultimo.",0)";
$res1=$link->query($sql1);
}

//registramos la ultima salida
$sql2="select count(*) as total from ultimasalida where usa_tarjeta='".$codigo."'";
$res2=$link->query($sql2);
$fila2=mysqli_fetch_array($res2);
if($fila2["total"] == 0){
$sql3="insert into ultimasalida(usa_fecha,usa_tarjeta,usa_monto)values('".date("Y-m-d")."','".$codigo."',".$tarifa.")";
}else{
$sql3="update ultimasalida set usa_fecha='".date("Y-m-d")."',usa_monto=".$tarifa." where usa_tarjeta='".$codigo."'";
}
$res3=$link->query($sql3);

if($_REQUEST["ippos"] !=="NOPERTENECEALSISTEMA"){
impresion_pos($ultimo,$_REQUEST["ippos"]);
}
$res=[
"error"=>false,
"salida_negativa"=>$salida_negativa,
"mensaje"=>"Salida se registro correctamente",
"comentarios"=>$comentarios
];

echo json_encode($res);
break;

case 'getMovimientosTer26':
$desde=$_REQUEST["desde"];
$hasta=$_REQUEST["hasta"];
if($_REQUEST["idempresa"]!=""){$empresa = "&& cliente=".$_REQUEST["idempresa"]."";}else{$empresa="";}

if($_REQUEST["idpatente"]!="" && $_REQUEST["idpatente"]){
$tarjeta = obtenervalor("tarjetas","codigo","where idpatente=".$_REQUEST["idpatente"]."");
if($tarjeta){
$patente = "&& codigo='".$tarjeta."'";	
}else{
$patente="";
}

}else{$patente="";}

if($_REQUEST["idmovimiento"]!=""){$movimiento="&& tipo_movimiento=".$_REQUEST["idmovimiento"]."";}else{$movimiento="";}

$s="select * from movimientos  where date(fecha) >='".$desde."' && tipo=2  && date(fecha) <= '".$hasta."' ".$empresa." ".$patente." ".$movimiento." order by fecha desc";
// echo $s;
// return;


$r=$link->query($s);
$data=[];
while($f=mysqli_fetch_array($r)){

$codigotarjeta=$f["codigo"];

if(intval($f["cuenta"])===1){
$tipo="EMPRESA";
$patente="";
}else{
$tipo="NORMAL";
$idpatente=obtenervalor("tarjetas","idpatente","where codigo='".$codigotarjeta."'");
$patente=obtenervalor("buses","patentebus","where idbus=".$idpatente."");
}

if($codigotarjeta==""){$codigotarjeta="error";}
$reemplazar=$codigotarjeta;
for($i=0;$i<=8;$i++){$reemplazar[$i] = "*";}

$cliente=obtenervalor("clientes","nombre","where id=".$f["cliente"]."");
$estado="";
switch(intval($f["estado"])){
case 0:$estado="PENDIENTE";break;
case 1:$estado="RENDIDO";break;
case 2:$estado="ELIMINADA";break;
}
$usuario=obtenervalor("usuarios","nombre","where id=".$f["usuario"]."");
if(intval($f["editadopor"])){$useredit=obtenervalor("usuarios","nombre","where id=".$f["editadopor"]."");}else{$useredit="";}

$idrecorrido=$f["recorrido"];
$tiporecorrido=obtenervalor("recorridos","tipodestino","where iddestino='".$idrecorrido."'");
$recorrido=obtenervalor("recorridos","nombredestino","where iddestino='".$idrecorrido."'");
$data[$f["id"]]=[
"fechaunix"=>strtotime($f["fecha"]),
"fecha"=>devfechahora($f["fecha"]),
"codigo"=>$codigotarjeta,
"tarjeta"=>$reemplazar,
"patente"=>$patente,
"idpatente"=>$idpatente,
"idcliente"=>$f["cliente"],
"empresa"=>$cliente,
"idcuenta"=>$f["cuenta"],
"tipo"=>$tipo,
"monto"=>$f["monto"],
"saldo"=>$f["saldo"],
"idestado"=>intval($f["estado"]),
"idrecorrido"=>$idrecorrido,
"recorrido"=>$recorrido,
"estado"=>$estado,
"usuario"=>$usuario,
"editadopor"=>$useredit,
"comentario"=>$f["comentarios"]
];
}

echo json_encode($data);
break;

case 'getRecorridosxBusT26':
$idbus=$_REQUEST["idpatente"];
$idrecorrido=$_REQUEST["idrecorrido"];
$recorridos=[];
$s="select rxb.idrecorrido,r.nombredestino as recorrido,r.idtipodestino, d.tipodestino as tiporecorrido from busesrecorridos rxb left outer join recorridos r on rxb.idrecorrido = r.iddestino left outer join tiporecorrido d on r.idtipodestino = d.idtipodestino where rxb.idbus=".$idbus."";
$r=$link->query($s);
while($f=mysqli_fetch_array($r)){
if($idrecorrido != $f["idrecorrido"]){
$recorridos[$f["idrecorrido"]]=[
"recorrido"=>$f["recorrido"],
"tiporecorrido"=>$f["tiporecorrido"],
"idtipodestino"=>$f["idtipodestino"]
];	
}
}
echo json_encode($recorridos);
break;

case 'editarsalida':
$fechahoy=date('Y-m-d');// fecha actual registro
$id=$_REQUEST["id"];// id del movimiento
$usuario=$_REQUEST["usuario"];// usuario que esta registrando al edición
$tipo_movimiento=6;// tipo de movimientos  6 para salidas auditadas
$nombre_usuario=obtenervalor("usuarios","nombre","where id=".$usuario."");
$detalle_audicion=$_REQUEST["comentarios"];
$comentarios="Salida Normal, auditada por $nombre_usuario con el siguiente comentario: <br>".$detalle_audicion;
$recorrido=$_REQUEST["idnew"];// id del nuevo recorrido
$tipodestino=obtenervalor("recorridos","idtipodestino","where iddestino=".$recorrido."");
$tarifa= intval(getTarifa($fechahoy,$tipodestino));
$detallemov=getDetalleMov($id);
$cuenta=$detallemov[$id]["cuenta"];
$tarjeta=$detallemov[$id]["codigo"];
$cliente=$detallemov[$id]["cliente"];
$fechahora=$detallemov[$id]["fecha"];
$tarifacobrada=intval($detallemov[$id]["monto"]);
$saldomov=intval($detallemov[$id]["saldo"]);
$saldo=($saldomov + $tarifacobrada) - $tarifa;// nuevo saldo
$sql= "update movimientos set recorrido=".$recorrido.", monto='".$tarifa."', saldo='".$saldo."', tipo_movimiento=".$tipo_movimiento.",editadopor=".$usuario.", comentarios='".$comentarios."' where id=".$id."";
$resulta=$link->query($sql);
if($cuenta==1){
$consulta="where cliente=".$cliente." and fecha > '".$fechahora."'";
}else{
$consulta="where codigo='".$tarjeta."' and fecha > '".$fechahora."'";
}
$saldoup=$saldo;
$sqlup="select * from movimientos ".$consulta." order by fecha asc";
$resultaup=$link->query($sqlup);
$numerox=mysqli_num_rows($resultaup);
if($numerox > 0){
while($filaup=mysqli_fetch_array($resultaup)){
if($filaup["tipo"]==1){
$nuevosaldo= $saldoup + intval($filaup["monto"]);
}else{
$nuevosaldo= $saldoup -  intval($filaup["monto"]);
}
$saldoup = $nuevosaldo;
$sqlupzz="update movimientos set saldo = '".$nuevosaldo."' where id=".$filaup["id"]."";
$resupzz=$link->query($sqlupzz);
}
if($cuenta==1){
//tarjetas del mismo cliente
$uptarjeta="update tarjetas set saldo = ".$saldoup." where idcliente=".$cliente."";
$resultuptarjeta=$link->query($uptarjeta);
}else{
// normal
$uptarjeta="update tarjetas set saldo= ".$saldoup." where codigo='".$tarjeta."'";
$resultuptarjeta=$link->query($uptarjeta);
}
// actualizar saldo del cliente 
$upcliente="update clientes set saldo = ".$saldoup." where id=".$cliente."";
$resultupcliente=$link->query($upcliente);

}else{
if($cuenta==1){
//tarjetas del mismo cliente
$uptarjeta="update tarjetas set saldo = ".$saldo." where idcliente=".$cliente."";
$resultuptarjeta=$link->query($uptarjeta);
}else{
// normal
$uptarjeta="update tarjetas set saldo= ".$saldo." where codigo='".$tarjeta."'";
$resultuptarjeta=$link->query($uptarjeta);
}
//actualizar saldo de cliente
$upcliente="update clientes set saldo = ".$saldo." where id=".$cliente."";
$resultupcliente=$link->query($upcliente);
}

$res=[
"error"=>false,
"comentario"=>$comentarios,
"saldo"=>$saldo,
"tarifa"=>$tarifa
];

echo json_encode($res);
break;

case 'eliminarsalida':
$fechahoy=date('Y-m-d');
$id=$_REQUEST["id"];
$usuario=$_REQUEST["usuario"];
$comentarios="ElIMINADA";
$recorrido=0;
$tarifa=0;
$estado=2;
$detallemov=getDetalleMov($id);
$cuenta=$detallemov[$id]["cuenta"];
$tarjeta=$detallemov[$id]["codigo"];
$cliente=$detallemov[$id]["cliente"];
$fechahora=$detallemov[$id]["fecha"];
$tarifacobrada=intval($detallemov[$id]["monto"]);
$saldomov=intval($detallemov[$id]["saldo"]);
$saldo=($saldomov + $tarifacobrada) - $tarifa;

$sql= "update movimientos set recorrido=".$recorrido.", monto='".$tarifa."',estado=".$estado.", saldo='".$saldo."',editadopor=".$usuario.", comentarios='".$comentarios."' where id=".$id."";
$resulta=$link->query($sql);
if($cuenta==1){
$consulta="where cliente=".$cliente." and fecha > '".$fechahora."'";
$whereultimo=" where cliente=".$cliente."";
}

if($cuenta==2){
$consulta="where codigo='".$tarjeta."' and fecha > '".$fechahora."'";
$whereultimo=" where codigo='".$tarjeta."' ";
}

$sqlup="select * from movimientos ".$consulta." order by fecha asc";
$resultaup=$link->query($sqlup);
$numerox=mysqli_num_rows($resultaup);
$saldoup=$saldo;
if($numerox>1){
while($filaup=mysqli_fetch_array($resultaup)){
// echo "saldo inicio : ".$saldoup."<br>";
if($filaup["tipo"]==1){
// echo "Recarga  : nuevosaldo = ".$saldoup." + ".$filaup["monto"]."<br>";
$nuevosaldo= $saldoup + intval($filaup["monto"]);
}else{
// echo "Salida  : nuevosaldo = ".$saldoup." - ".$filaup["monto"]."<br>";
$nuevosaldo= $saldoup - intval($filaup["monto"]);
}
$saldoup = $nuevosaldo;
// echo "saldo final ".$saldoup."<br><br>";
$sqlupzz="update movimientos set saldo = '".$saldoup."' where id=".$filaup["id"]."";
$resupzz=$link->query($sqlupzz);
}
/*return;*/

if($cuenta==1){
$uptarjeta="update tarjetas set saldo = ".$saldoup." where idcliente=".$cliente."";
$resultuptarjeta=$link->query($uptarjeta);
}else{
$uptarjeta="update tarjetas set saldo= ".$saldoup." where codigo='".$tarjeta."'";
$resultuptarjeta=$link->query($uptarjeta);
}
// actualizar saldo de cliente
$upcliente="update clientes set saldo = ".$saldoup." where id=".$cliente."";
$resultupcliente=$link->query($upcliente);
}else{
	/**/
if($cuenta==1){
$uptarjeta="update tarjetas set saldo = ".$saldo." where idcliente=".$cliente."";
$resultuptarjeta=$link->query($uptarjeta);
}else{
$uptarjeta="update tarjetas set saldo= ".$saldo." where codigo='".$tarjeta."'";
$resultuptarjeta=$link->query($uptarjeta);
}
//actualizar saldo de cliente
$upcliente="update clientes set saldo = ".$saldo." where id=".$cliente."";
$resultupcliente=$link->query($upcliente);
}

$res=[
"error"=>false,
"comentario"=>$comentarios,
"saldo"=>$saldo,
"tarifa"=>$tarifa,
"idestado"=>$estado
];

echo json_encode($res);
break;

case 'getRecorridosTarifadosxBus':
$id=$_REQUEST["id"];
// buscamos si tiene tarjeta activa
$s="select codigo, tipotransaccion,saldo from tarjetas where idpatente=".$id." && estado=1";
$r=$link->query($s);
if(mysqli_num_rows($r) > 0){
$f=mysqli_fetch_array($r);
$codigo=$f["codigo"];
$tipo=$f["tipotransaccion"];
$saldo=$f["saldo"];
}else{
$codigo="";$tipo=0;$saldo=0;
}
$recorridos = getRecorridosTarifados($id);
$res=[
"recorridos"=>$recorridos,
"codigo"=>$codigo,
"tipo"=>$tipo,
"saldo"=>$saldo
];
echo json_encode($res);
break;

case 'tempsalidas':
$cliente=$_REQUEST["cliente"];//cliente asociado a tarjeta
$recorrido=$_REQUEST["recorrido"];//id del recorrido
$tarjeta=trim($_REQUEST["tarjeta"]);//codigo tarjeta
$tarifa=trim($_REQUEST["tarifa"]);// valor tarifa recorrido seleccionado
$destino=$_REQUEST["destino"];
$fecha=convfecha($_REQUEST["fecha"]);
$hora=$_REQUEST["hora"];
$tipotarjeta=trim($_REQUEST["tipotarjeta"]);
$fechahora="".$fecha." ".$hora."";

$s="insert into _tempsalidas(cliente,recorrido,destino,tarifa,fecha,tarjeta,idtipotarjeta)values(".$cliente.",".$recorrido.",".$destino.",".$tarifa.",'".$fechahora."','".$tarjeta."',".$tipotarjeta.")";
$r=$link->query($s);
break;

case 'getSalidasTemporales':
$s="select st.*, c.nombre as n_cliente, r.nombredestino as n_recorrido, d.tipodestino as n_destino, t.idpatente, b.patentebus as patente from _tempsalidas st left outer join clientes c on st.cliente = c.id left outer join recorridos r on st.recorrido = r.iddestino left outer join tiporecorrido d on st.destino = d.idtipodestino left outer join tarjetas t on st.tarjeta = t.codigo left outer join buses b on t.idpatente = b.idbus where st.estado=0 ";
$r=$link->query($s);
$salidas=[];
while($f=mysqli_fetch_array($r)){
$salidas[$f["id"]]=[
"idcliente"=>$f["cliente"],
"idrecorrido"=>$f["recorrido"],
"iddestino"=>$f["destino"],
"tarifa"=>$f["tarifa"],
"fechahora"=>devfechahora2($f["fecha"]),
"cliente"=>$f["n_cliente"],
"recorrido"=>$f["n_recorrido"],
"destino"=>$f["n_destino"],
"patente"=>$f["patente"],
"tarjeta"=>$f["tarjeta"]
];
}
echo json_encode($salidas);
break;

case 'registrarSalidaTemp':
$sal = json_decode($_REQUEST["datos"], true);
$id=$sal["idtemp"];
$cliente=$sal["idcliente"];
$recorrido=$sal["idrecorrido"];
$destino=$sal["iddestino"];
$tarifa=intval($sal["tarifa"]);
$fechahora=convfechadate2($sal["fechahora"]);
$tarjeta=$sal["tarjeta"];
list($fechasinmodificar,$hora)= explode(" ",$fechahora);
$fechasinmodificar=devfecha($fechasinmodificar);
$estadotarjeta=intval(obtenervalor("tarjetas","estado","where codigo='".$tarjeta."'"));
if($estadotarjeta===1){
$cuenta=intval(obtenervalor("tarjetas","tipotransaccion","where codigo='".$tarjeta."'"));
$transaccion=2;
$estado=0;
$usuario=$sal["usuario"];
$salidasnegativas = cuentaSalidasNegativas($tarjeta);

if($cuenta===1){
$consulta="where cliente=".$cliente." and fecha > '".$fechahora."'";
$whereultimo=" where cliente=".$cliente." ";
$whereprimero="where fecha < '".$fechahora."' && cliente=".$cliente."";
}

if($cuenta===2){
$consulta="where codigo='".$tarjeta."' and fecha > '".$fechahora."'";
$whereultimo=" where codigo='".$tarjeta."' ";
$whereprimero="where fecha < '".$fechahora."' && codigo='".$tarjeta."'";
}
$sqlul="select id,saldo from movimientos ".$whereprimero." && estado != 2 order by fecha desc limit 0,1";
$resul=$link->query($sqlul);
$existe = mysqli_num_rows($resul);
// SI HAY UN REGISTRO
if($existe > 0){
$filaul=mysqli_fetch_array($resul);
$idul=$filaul["id"];
$saldo=intval($filaul["saldo"]);
}else{
$saldo=0;
}
$saldocuenta=$saldo - $tarifa;
//si saldo es negativo y tarifa es distinta de 0
if($saldo < 0 && $tarifa!=0){
$tipo_movimiento=11;
$comentarios="".$salidasnegativas." salida(s) sin saldo, si no recarga tarjeta no puede salir en su proxima salida";
$salida_negativa=1;
}

//saldo no es negatigo pero es inferior a la tarifa quedara saldo negativo
if($saldo < $tarifa && $tarifa!=0){
$tipo_movimiento=11;
$comentarios="Salida sin Saldo, tarifa supera monto de tarjeta";
$salida_negativa=1;
}
//saldo es mayor que la tarifa y tarifa es distinta a 0
if($saldo >= $tarifa && $tarifa!=0){
$tipo_movimiento=11;
$comentarios="Salida Normal";
$salida_negativa=0;
}
if($tarifa == 0){
$ultimasalida=getUltimaSalida($tarjeta);
if(!$ultimasalida["error"]){
$montosalida=$ultimasalida["monto"];
$fechaultimo=$ultimasalida["fecha"]; 
if($fechaultimo == $fechasinmodificar && $montosalida==$tarifa){
$tipo_movimiento=11;
$comentarios="Fuera de servicio consecutivo";
$salida_negativa=0;
}else{
$tipo_movimiento=11;
$comentarios="Fuera de servicio normal";
$salida_negativa=0;
}
}
else{
$tipo_movimiento=11;
$comentarios="Fuera de servicio normal";
$salida_negativa=0;
}
}

$sql="insert into movimientos (fecha,cuenta,codigo,tipo,monto,cliente,destino,recorrido,estado,usuario,saldo,tipo_movimiento,comentarios,salida_negativa) values('".$fechahora."',".$cuenta.",'".$tarjeta."',".$transaccion.",'".$tarifa."',".$cliente.",".$destino.",".$recorrido.",".$estado.",".$usuario.",'".$saldocuenta."',".$tipo_movimiento.",'".$comentarios."',".$salida_negativa.")";

$resulta=$link->query($sql);
$idmov=$link->insert_id;

// $sqluphora="update movimientos set fecha='".$fechahora."' where id='".$idmov."'";
// $resuphora=$link->query($sqluphora);

/*******************************************************************
ACTUALIZAR MOVIMIENTOS 
******************************************************************/
$sqlup="select * from movimientos ".$consulta." order by fecha asc";
$resultaup=$link->query($sqlup);
$numerox=mysqli_num_rows($resultaup);
$saldoup=$saldocuenta;

if($numerox > 0){	
while($filaup=mysqli_fetch_array($resultaup)){
//$nuevosaldozz=$filaup["saldo"] - $tarifa;
if($filaup["tipo"]==1){
$nuevosaldo= $saldoup + $filaup["monto"];
}else{
$nuevosaldo= $saldoup - $filaup["monto"];	
}
$saldoup = $nuevosaldo;
$sqlupzz="update movimientos set saldo = '".$nuevosaldo."' where id='".$filaup["id"]."'";
$resupzz=$link->query($sqlupzz);
}
if($cuenta==1){
//tarjetas del mismo cliente
$uptarjeta="update tarjetas set saldo = ".$saldoup." where idcliente='".$cliente."'";
$resultuptarjeta=$link->query($uptarjeta);
}else{
// normal
$uptarjeta="update tarjetas set saldo= ".$saldoup." where codigo='".$tarjeta."'";
$resultuptarjeta=$link->query($uptarjeta);
}
$upcliente="update clientes set saldo = ".$saldoup." where id='".$cliente."'";
$resultupcliente=$link->query($upcliente);
}
else{
if($cuenta==1){
//tarjetas del mismo cliente
$uptarjeta="update tarjetas set saldo = ".$saldocuenta." where idcliente='".$cliente."'";
$resultuptarjeta=$link->query($uptarjeta);
}else{
// normal
$uptarjeta="update tarjetas set saldo= ".$saldocuenta." where codigo='".$tarjeta."'";
$resultuptarjeta=$link->query($uptarjeta);
}

$upcliente="update clientes set saldo = ".$saldocuenta." where id='".$cliente."'";
$resultupcliente=$link->query($upcliente);
}


}
$s1="update _tempsalidas set estado=1 where id=".$id."";
$r1=$link->query($s1);
break;

/*******************************************************************
USUARIOS
******************************************************************/
case 'getUsuariosActivos':
$s="select * from usuarios where activo=1 order by nombre ";
$r=$link->query($s);
$usuarios=[];
while($f= mysqli_fetch_array($r)){
$id=$f["id"];
$rut=$f["rut"];
$nombre=$f["nombre"];
$correo=$f["email"];
$fono=$f["telefono"];
$ultimo=devfechal($f["ultimologin"]);
$estado=$f["activo"];
if ($f["imagen"] == "") {$foto = "avatar_usuario.jpg";} else {$foto = $f["imagen"];}
$usuarios[$id]=[
"rut"=>$rut,
"nombre"=>$nombre,
"correo"=>$correo,
"fono"=>$fono,
"ultimo"=>$ultimo,
"idestado"=>$estado,
"codigo"=>$f["codigo"],
"usuario"=>$f["rut"],
"idperfil"=>$f["perfil"],
"codsoftland"=>$f["cod_softland"],
"foto"=>$foto
];
}

echo json_encode($usuarios);
break;

case 'changePassword':
// $nueva = post_clave($_REQUEST["clave"]);
$nueva = $_REQUEST["clave"];
$sql="update usuarios set clave='".$nueva."' where id='".$_REQUEST["id"]."'";
$res=$link->query($sql);
break;

case 'editarusuario':
$s="update usuarios set nombre='".$_REQUEST["nombre"]."',email='".$_REQUEST["correo"]."',telefono='".$_REQUEST["fono"]."',perfil=".$_REQUEST["perfil"].", cod_softland='".$_REQUEST["codsoftland"]."' where id=".$_REQUEST["id"]."";
$r=$link->query($s);
break;
case 'actualizarFotoUser':
$archivo=$_FILES['foto']['name'];// nombre archivo a cargar
$temporal=$_FILES['foto']['tmp_name'];//nombre temporal en equipo cliente
$codigo=generarCodigo(6);
$foto=$codigo."_".$archivo;
if($temporal!=""){
$permitidos =  array('gif','png' ,'jpg');
$ext = pathinfo($archivo, PATHINFO_EXTENSION);
if(in_array($ext,$permitidos)){move_uploaded_file($temporal,"images/usuarios/".$foto);}
}else{
$foto="avatar_usuario.jpg";
}
$sql="update usuarios set imagen ='".$foto."' where id=".$_REQUEST["id"]."";
$res=$link->query($sql);
echo $foto;
break;

case 'eliminarusuario':
$s = "delete from modulos_menus_usuarios where id_usuario=".$_REQUEST["idusuario"]."";
$r = $link->query($s);
$s1 = "delete from modulo_usuario where usuario=".$_REQUEST["idusuario"]."";
$r1= $link->query($s1);
$s2 = "delete from usuarios where id=".$_REQUEST["idusuario"]."";
$r2 = $link->query($s2);
break;

case 'permisosxusuario':
$sql="select * from modulos order by posicion";
$res=$link->query($sql);
$modulos=array();
while($fila=mysqli_fetch_array($res)){
$idmenu=$fila["id"];
$menu=$fila["modulo"];
$sql1="select * from modulos_menus where idmodulo=".$idmenu."";
$res1=$link->query($sql1);
while($fila1=mysqli_fetch_array($res1)){
$idmo=$fila1["id"];
$modulo=$fila1["menu"];
$modulos[$menu][$idmo]=[
"idmod"=>$idmenu,
"modulo"=>$modulo
];
}
}

$permisos=array();
$sqlf="select * from modulos_menus_usuarios where id_usuario='".$_REQUEST["user"]."'";
$resf=$link->query($sqlf);
while($fila=mysqli_fetch_array($resf)) {
$permisos[$fila["id_modulo_menus"]]=$fila["id_modulo_menus"];
}
$data["permisos"]=$permisos;
$data["modulos"]=$modulos;
echo json_encode($data);
break;

case "permisousuario":
if(intval($_REQUEST["accion"])){
$sql="insert into modulos_menus_usuarios (id_modulo_menus,id_usuario)values('".$_REQUEST["idmod"]."','".$_REQUEST["idusuario"]."')";
$sqladdmod="insert into modulo_usuario(modulo,usuario)values('".$_REQUEST["idmenu"]."','".$_REQUEST["idusuario"]."')";
}else{
$sql="delete from modulos_menus_usuarios where id_modulo_menus='".$_REQUEST["idmod"]."' and id_usuario='".$_REQUEST["idusuario"]."'";
$sqladdmod="delete from modulo_usuario where modulo='".$_REQUEST["idmenu"]."' and usuario='".$_REQUEST["idusuario"]."'";
}
$resultado=$link->query($sql);
$resultaaddmod=$link->query($sqladdmod);
$op="OPTIMIZE TABLE modulos_menus_usuarios";
$rop=$link->query($op);

break;

case 'cambiarestadoUsuario':
$sql="update usuarios set activo =".$_REQUEST["idestado"]." where id=".$_REQUEST["id"]."";
$res=$link->query($sql);
break;

case 'nuevousuario':
$archivo=$_FILES['foto']['name'];// nombre archivo a cargar
$temporal=$_FILES['foto']['tmp_name'];//nombre temporal en equipo cliente
$codigo=generarCodigo(6);
$foto=$codigo."_".$archivo;
if($temporal!=""){
$permitidos =  array('gif','png' ,'jpg');
$ext = pathinfo($archivo, PATHINFO_EXTENSION);
if(in_array($ext,$permitidos)){move_uploaded_file($temporal,"images/usuarios/".$foto);}
}else{
$foto="avatar_usuario.jpg";
}
// $clave=post_clave($_REQUEST["clave"]);
$s="insert into usuarios(rut,nombre,email,telefono,clave,codigo,perfil,imagen,cod_softland)values('".$_REQUEST["rut"]."','".$_REQUEST["nombre"]."','".$_REQUEST["correo"]."','".$_REQUEST["fono"]."','".$_REQUEST["clave"]."','".$_REQUEST["codigo"]."',".$_REQUEST["perfil"].",'".$foto."','".$_REQUEST["codsoftland"]."')";
$r=$link->query($s);
break;

case 'getUserEmpresas':
$s="select ue.*, e.nombre as empresa  from userempresa ue left outer join clientes e on ue.idcliente = e.id where ue.estado = 1 ";
$r=$link->query($s);
$ue=[];
while($f=mysqli_fetch_array($r)){
$ue[$f["id"]]=[
"usuario"=>$f["usuario"],
"empresa"=>$f["empresa"],
"correo"=>$f["mail"],
"idestado"=>$f["estado"],
"ultimo"=>devfechal($f["ultimologin"]),
"ultimodt"=>strtotime($f["ultimologin"])
];
}

echo json_encode($ue);
break;
case 'changePasswordUE':
// $nueva = post_clave($_REQUEST["clave"]);
$nueva = $_REQUEST["clave"];
$pass=post_clave($nueva);
$sql="update userempresa set clave='".$nueva."',pass='".$pass."' where id='".$_REQUEST["id"]."'";
$res=$link->query($sql);
break;

case 'editarUE':
$res=[];
// $existe = intval(existeUserEmpresa($_REQUEST["e_usuario"]));
// if($existe){
// $res=[
// "error"=>true,
// "mensaje"=>"Error al actualizar usuario, el nombre de usuario ya se encuentra registrado"
// ];	
// }else{}
$s="update userempresa set mail='".$_REQUEST["e_correo"]."' where id=".$_REQUEST["user"]."";
$r=$link->query($s);
$res=[
"error"=>false,
"mensaje"=>"Usuario empresa actualizado correctamente"
];	

echo json_encode($res);
break;

case 'nuevoUE':
$res=[];
$existe = intval(existeUserEmpresa($_REQUEST["n_usuario"]));
if($existe){
$res=[
"error"=>true,
"mensaje"=>"Error al registrar, el nombre de usuario ya se encuentra registrado"
];	
}else{
$pass=post_clave($_REQUEST["n_password"]);

$s="insert into userempresa(idcliente, usuario,clave,pass,mail)values(".$_REQUEST["n_cliente"].",'".$_REQUEST["n_usuario"]."','".$_REQUEST["n_password"]."','".$pass."','".$_REQUEST["n_correo"]."')";
$r=$link->query($s);
$res=[
"error"=>false,
"mensaje"=>"Usuario empresa registrado correctamente"
];

}
echo json_encode($res);
break;

case 'eliminarUE':
$s = "delete from userempresa where id=".$_REQUEST["idusuario"]."";
$r = $link->query($s);
break;

case 'cambiarestadoUE':
$sql="update userempresa set estado =".$_REQUEST["idestado"]." where id=".$_REQUEST["id"]."";
$res=$link->query($sql);
break;

case 'getUsuariosDesactivados':
$usuarios=[];
// usuarios empresas
$s="select ue.*, e.nombre as empresa  from userempresa ue left outer join clientes e on ue.idcliente = e.id where ue.estado = 0 ";
$r=$link->query($s);
while($f=mysqli_fetch_array($r)){
$usuarios["empresa"][$f["id"]]=[
"usuario"=>$f["usuario"],
"empresa"=>$f["empresa"],
"correo"=>$f["mail"],
"idestado"=>$f["estado"],
"ultimo"=>devfechal($f["ultimologin"]),
"ultimodt"=>strtotime($f["ultimologin"])
];
}

// usuarios de sistema
$s1="select * from usuarios where activo=0 order by nombre ";
$r1=$link->query($s1);
while($f1= mysqli_fetch_array($r1)){
$id=$f1["id"];
$rut=$f1["rut"];
$nombre=$f1["nombre"];
$correo=$f1["email"];
$fono=$f1["telefono"];
$ultimo=devfechal($f1["ultimologin"]);
$estado=$f["activo"];
$usuarios["sistema"][$id]=[
"rut"=>$rut,
"nombre"=>$nombre,
"correo"=>$correo,
"fono"=>$fono,
"ultimo"=>$ultimo,
"idestado"=>$estado,
"codigo"=>$f1["codigo"],
"usuario"=>$f1["rut"],
"idperfil"=>$f1["perfil"],
"codsoftland"=>$f1["cod_softland"]
];
}
echo json_encode($usuarios);
break;

/******************************************************************************************
OPERACIONES CUSTODIA 
******************************************************************************************/
case 'nuevobulto':
$sql="insert into bultos (bul_nombre)values('".$_REQUEST["nombre"]."')";
$res=$link->query($sql);
$sale_a=$_REQUEST["retornar"];
break;

case 'getBultos':
$s="select * from bultos";
$r=$link->query($s);
$res=[];
while($f=mysqli_fetch_array($r)){
$res[$f["bul_id"]]=$f["bul_nombre"];
}
echo json_encode($res);
break;
case 'eliminarBulto':
$s="delete from bultos where bul_id=".$_REQUEST["id"]."";
$r=$link->query($s);
break;
case 'getTarifasxBulto':
$sql="select * from tarifasxbulto where txb_bulto='".$_REQUEST["bul_id"]."'";
$res=$link->query($sql);
$txb=array();
while($fila=mysqli_fetch_array($res)){
$usuario=obtenervalor("usuarios","nombre","where id='".$fila["txb_usuario"]."'");
$txb[]=array("usuario"=>$usuario,"fecha"=>devfecha($fila["txb_fecha"]),"valor"=>enpesos($fila["txb_valor"]));
}
echo json_encode($txb);
break;
case 'registrartarifaxbulto':
$sql="insert into tarifasxbulto(txb_bulto,txb_fecha,txb_valor,txb_usuario)values('".$_REQUEST["txb_bulto"]."','".convfecha($_REQUEST["txb_fecha"])."','".$_REQUEST["txb_valor"]."',".$_REQUEST["txb_usuario"].")";
$res=$link->query($sql);
break;

case 'postTarifaCobroAdicional':
$sql="insert into  tarifacobroadicional(tad_fecha,tad_valor,tad_usuario)values('".convfecha($_REQUEST["tad_fecha"])."','".$_REQUEST["tad_valor"]."',".$_REQUEST["tad_usuario"].")";
$res=$link->query($sql);
break;

case 'getTarifasCobroAdicional':
$sql="select * from tarifacobroadicional";
$res=$link->query($sql);
$tad=array();
while($fila=mysqli_fetch_array($res)){
$usuario=obtenervalor("usuarios","nombre","where id='".$fila["tad_usuario"]."'");
$tad[]=array("usuario"=>$usuario,"fecha"=>devfecha($fila["tad_fecha"]),"valor"=>enpesos($fila["tad_valor"]));
}
echo json_encode($tad);
break;

/****************** ubicaciones ****************/
case 'nuevaubicacion':
$sql="insert into ubicaciones (ubi_nombre)values('".$_REQUEST["ubicacion"]."')";
$res=$link->query($sql);
$sale_a=$_REQUEST["retornar"];
break;
case 'getUbicaciones':
$s="select * from ubicaciones";
$r=$link->query($s);
$res=[];
while($f=mysqli_fetch_array($r)){
$res[$f["ubi_id"]]=$f["ubi_nombre"];
}
echo json_encode($res);
break;
case 'eliminarUbicacion':
$s="delete from ubicaciones where ubi_id=".$_REQUEST["id"]."";
$r=$link->query($s);
break;
/************* bodegas ******************/
case 'nuevabodega':
$sql="insert into bodegas (bod_nombre)values('".$_REQUEST["bodega"]."')";
$res=$link->query($sql);
$sale_a=$_REQUEST["retornar"];
break;
case 'getBodegas':
$s="select * from bodegas";
$r=$link->query($s);
$res=[];
while($f=mysqli_fetch_array($r)){
$res[$f["bod_id"]]=$f["bod_nombre"];
}
echo json_encode($res);
break;
case 'eliminarBodega':
$s="delete from bodegas where bod_id=".$_REQUEST["id"]."";
$r=$link->query($s);
break;

/**************** ingresos ******************/
case 'getBultosIngreso':
$sql="select * from bultos order by bul_id desc";
$res=$link->query($sql);
$bultos=array();
while($fila=mysqli_fetch_array($res)){
$tarifa=obtenervalor("tarifasxbulto","txb_valor","where txb_bulto = ".$fila["bul_id"]." order by txb_fecha desc limit 0,1 ");
$bultos[$fila["bul_id"]]=array("nombre"=>$fila["bul_nombre"],"color"=>$fila["bul_color"],"tarifa"=>$tarifa);
}
echo json_encode($bultos);
break;
case 'getUbicacionesIngreso':
$sql="select * from ubicaciones order by ubi_id desc";
$res=$link->query($sql);
$ubicaciones=array();
while($fila=mysqli_fetch_array($res)){
//$bultosxubicacion=getBultosxUbicacion($fila["ubi_id"]);
$ubicaciones[$fila["ubi_id"]]=array("nombre"=>$fila["ubi_nombre"]);
}
echo json_encode($ubicaciones);
break;

case 'nuevoingresocustodia':
$sqlultimo="select * from movimientoscustodia order by mcu_id desc limit 0,1";
$resultimo=$link->query($sqlultimo);
$sihay=mysqli_num_rows($resultimo);
if($sihay > 0){
while($filaultimo=mysqli_fetch_array($resultimo)){
$ultimocodigo=$filaultimo["mcu_codigo"];
}
$ultimacantidad=substr($ultimocodigo,1,12); // correlativo ticket del 1 al n
//echo $ultimacantidad."<br>";
//if(empty($ultimacantidad)){$ultimacantidad=0;}
$ultimosinceros=(int)$ultimacantidad;
$parte=$ultimosinceros+1;
$cantidad=$ultimosinceros + 1;
}
else{
$parte=1;
$cantidad=1;
}
$codigo=0;
//for($x=$parte;$x<=$cantidad;$x++){}
$codigo=3;
//$codigo.=str_pad($_REQUEST["mcu_bulto"], 3, '0', STR_PAD_LEFT);
//$codigo.=str_pad($_REQUEST["mcu_ubicacion"], 4, '0', STR_PAD_LEFT);
$x=str_pad($parte, 11, '0', STR_PAD_LEFT);
$codigo.=$x;
$codigoactual = strrev($codigo);//se presentan los 12 números de manera inversa
$i = 0;
while($i < strlen($codigoactual)){
if($i%2 == 0) $impares += $codigoactual[$i];//se suman los impares
else $pares += $codigoactual[$i];//se suman los pares
$i++;
}

$suma = $pares + ($impares*3); //se realiza la suma de los pares con el resultado de multiplicar la suma de los impares por 3
$total=10 -($suma%10);//se resta a 10 el resto de dividir esa suma por 10.
if($total==10){$total=0;}
$codigo.=$total;
$codigo=substr($codigo,0,12);
//echo $codigo."<br>";

$datos= json_decode($_REQUEST["datos"],true);
$totalmov=0;

foreach($datos["bultos"] as $index=>$valor){
$totalmov=$totalmov + $valor["dmc_total"];
}
$fechahora = new DateTime();
$fechahora->modify('+24 hours');
$fechahoramax=$fechahora->format('Y-m-d H:i:s');
$fechaemision=date("Y-m-d");

// estado 1 => en custodia
if($datos["mcu_cajero"]==0){$cajero = $datos["mcu_usuario"];}else{$cajero=$datos["mcu_cajero"];}



$doc=new Dte();		
$doc->idempotency="Idempotency-Key:".$datos['codigo'];
$doc->Receptor=array("RUTRecep"=>"66666666-6");
$doc->FchEmis=$fechaemision;

$neto=round($totalmov/1.19);
$iva=$totalmov-$neto;

$doc->Totales=array("MntNeto"=>$neto,"IVA"=>$iva,"MntTotal"=>$totalmov,"VlrPagar"=>$totalmov,"TotalPeriodo"=>$totalmov);
$Detalle[]=array("NroLinDet"=>"1","NmbItem"=>"INGRESO CUSTODIA","QtyItem"=>1,"PrcItem"=>$totalmov,"MontoItem"=>$totalmov);
$doc->Detalle=$Detalle;
$doc->bafecta();		
//echo $doc->data;
$result=$doc->enviadte($datos['codigo']);
if(array_key_exists('error', $result)) {
//print_r($result['error']);
echo "error";
}
else{

$sql3="INSERT INTO boletas(tipo_doc, folio, fecha, cajero, cod_producto, descripcion, monto) VALUES ('A',".$result['folio'].",'".$fechaemision."',".$datos['cod_softland'].",'I3','INGRESO CUSTODIA',".$totalmov.")";
$res3=$link->query($sql3);

$sql="insert into movimientoscustodia(mcu_usuario,mcu_cajero,mcu_codigo,mcu_valor,mcu_estado,mcu_fechahoramax,folio,timbre) values('".$datos["mcu_usuario"]."','".$cajero."','".$codigo."','".$totalmov."',1,'".$fechahoramax."','".$result['folio']."','".$result['timbre']."')";
$res=$link->query($sql);
$idreg=$link->insert_id;

foreach($datos["bultos"] as $index=>$valor){
$totalmov=$totalmov + $valor["total"];
$sql1="insert into  detallemovcustodia(dmc_movimiento,dmc_cantidad,dmc_bulto,dmc_tarifa,dmc_ubicacion,dmc_tipobulto,dmc_valordeclarado,dmc_codigo,dmc_total)values('".$idreg."','".$valor["dmc_cantidad"]."','".$valor["dmc_bulto"]."','".$valor["dmc_tarifa"]."','".$valor["dmc_ubicacion"]."','".$valor["dmc_tipobulto"]."','".$valor["dmc_valordeclarado"]."','".$codigo."','".$valor["dmc_total"]."')";
$res1=$link->query($sql1);
}
	
if($datos["ippos"] !=="NOPERTENECEALSISTEMA"){
impresioncustodia($idreg,$datos["ippos"]);
}

echo "registrado";
}
break;

/******************** retiro ****************/
case 'consultaTicketCustodia':
$codigo = substr($_REQUEST["ticket"],0,12);
/******************************************************
FECHA DE ACTUALIZACION : 09-02-2020 
SE AGREGAR PARAMETRO AUXILIAR A FUNCION postHistorialMcu
*****************************************************/
postHistorialMcu(0,$codigo,'consultaticket','consulta codigo de ticket en registro de entrega custodia',0,$_REQUEST["auxiliar"]);

$sql="select * from movimientoscustodia where mcu_codigo ='".$codigo."'";
$res=$link->query($sql);
$fila=mysqli_fetch_array($res);
$inicio = strtotime($fila["mcu_fechahora"]);
//$tiempotrans=horastranscurridas($inicio);
$tiempotrans=horastranscurridas($fila["mcu_fechahora"],$fila["mcu_fecharetiro"]);
$usuario=obtenervalor("usuarios","nombre","where id='".$fila["mcu_usuario"]."'");
$fhingreso=devfechahora($fila["mcu_fechahora"]);
$detallebultos = getDetalleMovCus($fila["mcu_id"]);
$fhmax=devfechahora($fila["mcu_fechahoramax"]);
$fechadev=date("d/m/Y H:i:s");
$nticket=intval(substr($fila["mcu_codigo"],1,12));
$detalle=array("id"=>$fila["mcu_id"],"fechaingreso"=>$fhingreso,"tiempotrans"=>$tiempotrans,"usuario"=>$usuario,"bultos"=>$detallebultos,"fechamax"=>$fhmax,"fechadevolucion"=>$fechadev,"fecharetiro"=>devfechahora($fila["mcu_fecharetiro"]),"numero"=>$nticket,"liberado"=>$fila["mcu_liberado"],"valoradicional"=>$fila["mcu_valoradicional"],"observaciones"=>$fila["mcu_observaciones"],"estado"=>$fila["mcu_estado"]);
echo json_encode($detalle);
break;

case 'posthistorialmcu':
$codigo = substr($_REQUEST["codigo"],0,12);
postHistorialMcu($_REQUEST["cajero"],$codigo,$_REQUEST["tipo"],$_REQUEST["detalle"],$_REQUEST["monto"]);
break;

case 'entregarBulto':
$cajero=$_REQUEST["cajero"];
$auxiliar = $_REQUEST["auxiliar"];
$idaccion=$_REQUEST["idaccion"];
if($idaccion==2){
$sql="update movimientoscustodia set mcu_estado=3  where mcu_id='".$_REQUEST["id"]."'";
}
if($idaccion==3){
$sql="update movimientoscustodia set mcu_estado=4  where mcu_id='".$_REQUEST["id"]."'";
}
if($idaccion==1){
$sql="update movimientoscustodia set mcu_estado=2,mcu_fecharetiro='".convfechahora($_REQUEST["fecharetiro"])."',mcu_cajeroentrega='".$cajero."',mcu_auxiliarentrega='".$auxiliar."',mcu_valoradicional='".$_REQUEST["valoradicional"]."' where mcu_id='".$_REQUEST["id"]."'";	
}
$res=$link->query($sql);
echo "bulto entregado";
break;
case 'actualizarUbicacion':
$sql="update detallemovcustodia set dmc_ubicacion='".$_REQUEST["mcu_ubicacion"]."' where dmc_id='".$_REQUEST["mcu_id"]."'";
$res=$link->query($sql);
break;

/************ entrega administracion ************/
case 'getTicketsCustodia':
$tickets=array();
$sql="select * from movimientoscustodia where mcu_estado = 3 || mcu_estado=4";
$res=$link->query($sql);
while($fila=mysqli_fetch_array($res)){
if($fila["mcu_estado"]==3){
$estado="Entrega Administracion";
}else{
$estado="Ticket Perdido";
}
$tiempotrans=horastranscurridas($fila["mcu_fechahora"],$fila["mcu_fecharetiro"]);
$usuario=obtenervalor("usuarios","nombre","where id='".$fila["mcu_usuario"]."'");
$fhingreso=devfechahora($fila["mcu_fechahora"]);
$detallebultos = getDetalleMovCus($fila["mcu_id"]);
$fhmax=devfechahora($fila["mcu_fechahoramax"]);
$fechadev=date("d/m/Y H:i:s");
$nticket=intval(substr($fila["mcu_codigo"],1,12));
$tickets[]=[
"id"=>$fila["mcu_id"],
"codigo"=>$fila["mcu_codigo"],
"fechaingreso"=>$fhingreso,
"tiempotrans"=>$tiempotrans,
"usuario"=>$usuario,
"bultos"=>$detallebultos,
"fechamax"=>$fhmax,
"fechadevolucion"=>$fechadev,
"fecharetiro"=>devfechahora($fila["mcu_fecharetiro"]),
"numero"=>$nticket,
"estado"=>$fila["mcu_estado"],
"estadoticket"=>$estado
];	
}
echo json_encode($tickets);
break;

case 'liberarBulto':
if($_REQUEST["estadoticket"]==4){
// registrar formulario de custodia
$sql0="insert into formularioscustodia(fcu_ticket,fcu_nombre,fcu_rut,fcu_direccion,fcu_telefono,fcu_descripcion,fcu_usuario)values('".$_REQUEST["id"]."','".$_REQUEST["retiranombre"]."','".$_REQUEST["retirarut"]."','".$_REQUEST["retiradireccion"]."','".$_REQUEST["retiratelefono"]."','".$_REQUEST["retiradescripcion"]."','".$_REQUEST["auxiliar"]."')";
$res0=$link->query($sql0);
}

//liberar bulto si valor adicional es = a 0
$cajero=$_REQUEST["cajero"];
$auxiliar = $_REQUEST["auxiliar"];
if($_REQUEST["valoradicional"] > 0){
$codigo=obtenervalor("movimientoscustodia","mcu_codigo","where mcu_id='".$_REQUEST["id"]."'");
$detalle="ticket custodia registra un cobro adicional por $".enpesos($_REQUEST["valoradicional"])."";
postHistorialMcu($cajero,$codigo,'cobroadicional',$detalle,$_REQUEST["valoradicional"],$auxiliar);

$sql="update movimientoscustodia set mcu_estado=1,mcu_fecharetiro='".convfechahora($_REQUEST["fecharetiro"])."',mcu_valoradicional='".$_REQUEST["valoradicional"]."', mcu_liberado=1,mcu_observaciones='".$_REQUEST["mcu_observaciones"]."' where mcu_id='".$_REQUEST["id"]."'";
}else{
$sql="update movimientoscustodia set mcu_estado=2,mcu_fecharetiro='".convfechahora($_REQUEST["fecharetiro"])."',mcu_cajeroentrega='".$cajero."',mcu_auxiliarentrega='".$auxiliar."',mcu_valoradicional='".$_REQUEST["valoradicional"]."',mcu_liberado=1,mcu_observaciones='".$_REQUEST["mcu_observaciones"]."' where mcu_id='".$_REQUEST["id"]."'";
}
 
$res=$link->query($sql);	
break;

case 'devACustodia':
$s="update movimientoscustodia set mcu_estado=1  where mcu_id=".$_REQUEST["id"]."";
$r=$link->query($s);
break;

/**************** libro mayor **************/
case 'getMovcustodia':
$filtros = json_decode($_REQUEST["filtros"],true);
$fdesde=$filtros["fdesde"];
$fhasta=$filtros["fhasta"];
$idbulto=intval($filtros["bulto"]);
$idubicacion=intval($filtros["ubicacion"]);
$idestado=$filtros["estado"];

$hoy=date("Y-m-d");
$hace3meses=date("Y-m-d",strtotime($hoy."- 6 month"));
if($fdesde==""){$fdesde=$hace3meses;}
if($fhasta==""){$fhasta=$hoy;}
if($idestado!=0){$andestado="&& mcu_estado='".$idestado."'";}else{$andestado="";}
$movimientos=[];
$sql="select * from movimientoscustodia where date(mcu_fechahora) >='".$fdesde."' && date(mcu_fechahora) <= '".$fhasta."' ".$andestado." order by mcu_fechahora desc";
// echo $sql;
// return;
$res=$link->query($sql);
while($fila=mysqli_fetch_array($res)){
switch($fila["mcu_estado"]){
case 1:
$estado="En custodia";
break ;
case 2:
$estado="Entregado";
break ;
case 3:
$estado="Entrega Administracion";
break ;
case 4:
$estado="Ticket Perdido";
break ;

}
// if($fila["mcu_estado"]==3){
// $estado="Entrega Administracion";
// }else{
// $estado="Ticket Perdido";
// }
$tiempotrans=horastranscurridas($fila["mcu_fechahora"],$fila["mcu_fecharetiro"]);
$usuario=obtenervalor("usuarios","nombre","where id='".$fila["mcu_usuario"]."'");
/**************************************************
ACTUALIZACION 09-02-2020
SI EL COBRO ADICIONAL ES MAYOR QUE 0 QUIERE DECIR
QUE LO ENTRGO EL CAJERO, POR LO TANTO SE MODIFICA 
EL USUARIO DE ENTREGA
**************************************************/
if($fila["mcu_valoradicional"] > 0){
$auxiliar=obtenervalor("usuarios","nombre","where id='".$fila["mcu_cajeroentrega"]."'");	
}else{
$auxiliar=obtenervalor("usuarios","nombre","where id='".$fila["mcu_auxiliarentrega"]."'");	
}
$fhingreso=devfechahora($fila["mcu_fechahora"]);
$detallebultos = getDetalleMovCus($fila["mcu_id"]);



$historialconsultas=getHistorialConsultas($fila["mcu_codigo"]);
$cuentaconsultas = count($historialconsultas);
$fhmax=devfechahora($fila["mcu_fechahoramax"]);
$fechadev=date("d/m/Y H:i:s");
$nticket=intval(substr($fila["mcu_codigo"],1,12));

// echo $idbulto." ".$idubicacion;
// return;

if($idbulto || $idubicacion){
foreach($detallebultos as $index=>$valor){
if($idbulto !=0){
if($idbulto == $valor["idbulto"]){
$movimientos[]=array("id"=>$fila["mcu_id"],"codigo"=>$fila["mcu_codigo"],"fechaingreso"=>$fhingreso,"tiempotrans"=>$tiempotrans,"usuario"=>$usuario,"auxiliar"=>$auxiliar,"valor"=>$fila["mcu_valor"],"valoradicional"=>$fila["mcu_valoradicional"],"bultos"=>$detallebultos,"fechamax"=>$fhmax,"fechadevolucion"=>$fechadev,"fecharetiro"=>devfechahora($fila["mcu_fecharetiro"]),"numero"=>$nticket,"estado"=>$fila["mcu_estado"],"estadoticket"=>$estado,"cuentaconsultas"=>$cuentaconsultas,"historialconsultas"=>$historialconsultas,"varendido"=>$fila["mcu_varendido"],"rendido"=>$fila["mcu_rendido"]);
break;	
}
}
if($idubicacion !=0){
if($idubicacion == $valor["idubicacion"]){
$movimientos[]=array("id"=>$fila["mcu_id"],"codigo"=>$fila["mcu_codigo"],"fechaingreso"=>$fhingreso,"tiempotrans"=>$tiempotrans,"usuario"=>$usuario,"auxiliar"=>$auxiliar,"valor"=>$fila["mcu_valor"],"valoradicional"=>$fila["mcu_valoradicional"],"bultos"=>$detallebultos,"fechamax"=>$fhmax,"fechadevolucion"=>$fechadev,"fecharetiro"=>devfechahora($fila["mcu_fecharetiro"]),"numero"=>$nticket,"estado"=>$fila["mcu_estado"],"estadoticket"=>$estado,"cuentaconsultas"=>$cuentaconsultas,"historialconsultas"=>$historialconsultas);
break;	
}
}		
}
}else{
$movimientos[]=array("id"=>$fila["mcu_id"],"codigo"=>$fila["mcu_codigo"],"fechaingreso"=>$fhingreso,"tiempotrans"=>$tiempotrans,"usuario"=>$usuario,"auxiliar"=>$auxiliar,"valor"=>$fila["mcu_valor"],"valoradicional"=>$fila["mcu_valoradicional"],"bultos"=>$detallebultos,"fechamax"=>$fhmax,"fechadevolucion"=>$fechadev,"fecharetiro"=>devfechahora($fila["mcu_fecharetiro"]),"numero"=>$nticket,"estado"=>$fila["mcu_estado"],"estadoticket"=>$estado,"cuentaconsultas"=>$cuentaconsultas,"historialconsultas"=>$historialconsultas);		
}
/***************************************
ACTUALIZADO EL 12-03-2020
PARA EXPORTACIÓN DE DATOS A EXCEL
******************************************/
$bultosxtipo=getBultosxTipo($detallebultos);
$hhad= $tiempotrans["HH"] - 24;
if($hhad < 0){$hhad = 0;}
$datos[]=array("numero"=>$nticket,
"codigo"=>$fila["mcu_codigo"],
"fechaingreso"=>$fhingreso,
"fecharetiro"=>devfechahora($fila["mcu_fecharetiro"]),
"tiempotrans"=>$tiempotrans["tiempo"],
"estadoticket"=>$estado,
"valor"=>$fila["mcu_valor"],
"horasad"=>$hhad,
"valoradicional"=>$fila["mcu_valoradicional"],
"historialconsultas"=>count($historialconsultas),
"usuarioingresa"=>$usuario,
"usuarioretira"=>$auxiliar,
"usuarioconsulta"=>$historialconsultas[0]["auxiliar"],
"bchico"=>$bultosxtipo[1],
"bmediano"=>$bultosxtipo[2],
"bgrande"=>$bultosxtipo[3],
"bxg"=>$bultosxtipo[4],
"bxxg"=>$bultosxtipo[5],
"comentarios"=>$fila["mcu_observaciones"]
);

}

/***************************************
ACTUALIZADO EL 12-03-2020
PARA EXPORTACIÓN DE DATOS A EXCEL
******************************************/
$cabecera=["Numero","Codigo","Fecha Ingreso","Fecha Entrega","Tiempo Custodia","Estado","Valor","Horas Adicionales","Valor Adicional","Consultas","Usuario Ingresa","Usuario Retiro","Usuario Consulta","Bulto Chico","Bulto Mediano","Bulto Grande","Bulto XG","Bulto XXG","Comentarios"];
$nombrexls="LibroMayorCustodia";
$excel= generarExcel($nombrexls,$cabecera,$datos);
$data["movimientos"]=$movimientos;
$data["informe"]=$excel;
echo json_encode($data);
break;
case 'eliminarMovCustodia':
// eliminar historial de consultas mcu
$sql="delete  from historialmcu where hmcu_codigo='".$_REQUEST["codigo"]."'";
$res=$link->query($sql);
// eliminar detalle de mcu
$sql1="delete from detallemovcustodia where dmc_movimiento='".$_REQUEST["id"]."'";
$res1=$link->query($sql1);
// eliminar mcu
$sql2="delete from movimientoscustodia where mcu_id='".$_REQUEST["id"]."'";
$res2=$link->query($sql2);
break;

/************* rendir bultos *********************/
case 'rendirBultos':
$bultos=array();
$totalcustodia=0;
$sql0="select * from bultos";
$res0=$link->query($sql0);
while($fila0=mysqli_fetch_array($res0)){
$bultos[$fila0["bul_id"]]["nombre"]=$fila0["bul_nombre"];
$bultos[$fila0["bul_id"]]["cantidad"]=0;
$bultos[$fila0["bul_id"]]["total"]=0;
}
$valoradicional=0;
// registros de movimientos si el cajer entrega el bulto y realiza un cobro adicional
$sql="select * from movimientoscustodia where mcu_varendido = 0 && mcu_cajeroentrega='".$_REQUEST["cajero"]."'";
$res=$link->query($sql);
while($fila=mysqli_fetch_array($res)){
$valoradicional = $valoradicional + $fila["mcu_valoradicional"];
}
$totalcustodia = $totalcustodia + $valoradicional;
// registro del cajero
$sql1="select * from movimientoscustodia where mcu_rendido = 0 && mcu_cajero='".$_REQUEST["cajero"]."' order by mcu_fechahora asc";
$res1=$link->query($sql1);
$fechas=array();
while($fila1=mysqli_fetch_array($res1)){
$valor = $fila1["mcu_valor"];
$fechas[]= devfechahora($fila1["mcu_fechahora"]);
$sql2="select * from detallemovcustodia where dmc_movimiento ='".$fila1["mcu_id"]."'";
$res2=$link->query($sql2);
while($fila2=mysqli_fetch_array($res2)){
$cantidad = $bultos[$fila2["dmc_bulto"]]["cantidad"] + $fila2["dmc_cantidad"];
$total = $bultos[$fila2["dmc_bulto"]]["total"] + $fila2["dmc_total"];
$totalcustodia = $totalcustodia + $fila2["dmc_total"];
$bultos[$fila2["dmc_bulto"]]["cantidad"] = $cantidad;
$bultos[$fila2["dmc_bulto"]]["total"] = $total;
}
}

$data["valoradicional"]=$valoradicional;
$data["totalcustodia"]=$totalcustodia;
$data["bultos"]=$bultos;
$data["fechas"]=$fechas;
echo json_encode($data);
break;

case "rendirCustodia":
$usuario=$_REQUEST["cajero"];
// consulta para rendir movimientos de custodia
$sql1="update movimientoscustodia set mcu_rendido=1  where mcu_rendido=0  && mcu_cajero='".$usuario."'";
$res1=$link->query($sql1);
$sql2="update movimientoscustodia set mcu_varendido=1  where mcu_varendido=0  && mcu_cajeroentrega='".$usuario."'";
$res2=$link->query($sql2);
break;


}


if($_REQUEST["retornar"]){
header("location:".$sale_a."");
}

?>
