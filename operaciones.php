<?
session_start();
include("conexion.php");
include("funciones.php");
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
"comentario"=>$f["comentarios"]
];
}

echo json_encode($data);
break;

case 'eliminarrecarga':
$id=$_REQUEST["m_id"];
$usuario=$_REQUEST["m_usuario"];
$monto=$_REQUEST["m_monto"];
$cuenta=$_REQUEST["m_cuenta"];
$tarjeta=$_REQUEST["m_codigo"];
$cliente=$_REQUEST["m_cliente"];
$fechahora=date("Y-m-d H:i:s",$_REQUEST["m_fecha"]);
$fechahoy=date('Y-m-d');
$comentarios="ElIMINADA ".$_REQUEST["comentarios"]."";
$recorrido=0;
$nuevomonto=0;
$nuevosaldo=0;
$estado=2;

$s="update movimientos set recorrido=".$recorrido.", monto=".$nuevomonto.", saldo=".$nuevosaldo.", estado=".$estado.", editadopor=".$usuario.", comentarios='".$comentarios."' where id='".$id."'";
// $r=$link->query($s);


break;



}


if($_REQUEST["retornar"]){
header("location:".$sale_a."");
}

?>
