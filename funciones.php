<?
require __DIR__ . '/escpos/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

require_once('includes/tcpdf/config/tcpdf_config.php');
require_once('includes/tcpdf/tcpdf.php');
require_once('includes/numeroaLetras.php');


/**** CONSTANTES *********************/
//192.168.0.114 -> print torre de control -> default
//192.168.0.111 -> print porteria principal
//192.168.0.112 -> print porteria auxiliar
//192.168.0.113 -> print recargas
include("f_genericas.php");

define('printers', ['192.168.0.123'=>'192.168.0.114','192.168.0.120'=>'192.168.0.111','192.168.0.121'=>'192.168.0.112','192.168.0.122'=>'192.168.0.113','Desconocido'=>'NOPERTENECEALSISTEMA']);


function getMyPrint(){
$printers = printers;
$ip="";
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) { $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; }
elseif (isset($_SERVER['HTTP_VIA'])) { $ip = $_SERVER['HTTP_VIA']; }
elseif (isset($_SERVER['REMOTE_ADDR'])) { $ip = $_SERVER['REMOTE_ADDR']; }
else {$ip = "Desconocido";}// cambiar valor de variable para asignar ip 114
if(array_key_exists($ip,$printers)){
$ippos=$printers[$ip];	
}else{
// $ippos="192.168.0.114";
$ippos="NOPERTENECEALSISTEMA";
}

$res=[
"ip"=>$ip,
"ippos"=>$ippos
];
return $res;
}


function usuariologeado($id){
global $link;
$s="select * from usuarios where id='".$id."'";
$r=$link->query($s);
$datos=array();
while($f=mysqli_fetch_array($r)){
$datos["ultimo"]=devfechahora($f["ultimologin"]);
$datos["rut"]=$f["rut"];
$datos["correo"]=$f["email"];
$foto = $f["imagen"];
if($foto ==""){$foto="avatar_usuario.jpg";}
$datos["foto"]=$foto;
$datos["nombre"]=$f["nombre"];
}
return $datos;
}

function getRecorridosTarifados($idbus){
global $link;
$tarifas = getTarifasxRecorridos();
$recorridos=[];
$s="select rxb.idrecorrido,r.nombredestino as recorrido,r.idtipodestino, d.tipodestino as tiporecorrido from busesrecorridos rxb left outer join recorridos r on rxb.idrecorrido = r.iddestino left outer join tiporecorrido d on r.idtipodestino = d.idtipodestino where rxb.idbus=".$idbus."";
$r=$link->query($s);
while($f=mysqli_fetch_array($r)){
$recorridos[$f["idrecorrido"]]=[
"recorrido"=>$f["recorrido"],
"tiporecorrido"=>$f["tiporecorrido"],
"idtipodestino"=>$f["idtipodestino"],
"tarifa"=>$tarifas[$f["idtipodestino"]]
];
}
return $recorridos;
}


?>