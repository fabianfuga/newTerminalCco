<?
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



?>