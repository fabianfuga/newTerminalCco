<?
class Operaciones{
private $con;
private $fn;
function __construct(){
require_once dirname(__FILE__) . '/Conexion.php';
require_once dirname(__FILE__) . '/Funciones.php';
// use myPHPnotes\Geocoding;
$db = new Conexion();
$this->con = $db->conectar();
$this->fn = new Funciones();
}


public function loginempresa($user, $password){
$json=[];
$validaUser= $this->fn->UserExist($user);
if($validaUser["existe"]){
$hashed_password = $this->fn->getPasswordUser($validaUser["id"]);
if(password_verify($password,$hashed_password)){
$sql=$this->con->prepare("select * from userempresa where id='".$validaUser["id"]."' && pass='".$hashed_password."'");
$sql->execute();
$res = $sql->get_result();
while($fila = $res->fetch_assoc()) {
$json["id"]=$fila["id"];
$json["usuario"]=$fila["usuario"];
$json["idcliente"]=$fila["idcliente"];
$json["lastlogin"]=$fila["ultimologin"];


// update last login
$ultimologin=date("Y-m-d H:i:s");
$s=$this->con->prepare("update userempresa set ultimologin='".$ultimologin."' where id=".$fila["id"]."");
$s->execute();
}
$resulta=[
"error"=>false,
"status"=>USER_VALIDO,
"data"=>json_encode($json)
];



}
else{
$json["mensaje"]="Usuario y/o clave son incorrectos";
$resulta=[
"error"=>true,
"status"=>USER_PASSWORD_ERROR,
"data"=>json_encode($json)
];
}
}else{
$resulta=[
"error"=>true,
"status"=>USER_NO_EXISTE,
"data"=>json_encode($json)
];
}
return $resulta;
}

public function getPatentesEmpresa($id){
$res=[];
$s=$this->con->prepare("select idbus,numero,patentebus from buses where codigoempresa=".$id."");
$s->execute();
$r = $s->get_result();
while($f = $r->fetch_assoc()) {
$res[]=[
"id"=>$f["idbus"],
"numero"=>$f["idbus"],
"patentebus"=>$f["patentebus"]
];
}
return $res;
}

/*********************************************
OPERACIONES APP PLAZA TERMINAL
*********************************************/
public function loginApp($user, $password){
$json=[];
$s=$this->con->prepare("select * from usuarios where rut='".$user."' and clave='".$password."' and activo='1'");
$s->execute();
$r = $s->get_result();
$cuenta = $r->num_rows;
if($cuenta > 0){
while($f = $r->fetch_assoc()) {
$json=[
"id"=>$f["id"],
"rut"=>$f["rut"],
"nombre"=>$f["nombre"]
];

}
$resulta=[
"error"=>false,
"data"=>json_encode($json)
];

}else{
$json["mensaje"]="Usuario y/o clave son incorrectos";
$resulta=[
"error"=>true,
// "status"=>USER_PASSWORD_ERROR,
"data"=>json_encode($json)
];
}
return $resulta;
}

public function validarTarjeta($codigo){
$fechahoy=date('Y-m-d');
$s=$this->con->prepare("select t.idtarjeta,t.idcliente,t.idpatente,t.estado, b.patentebus from tarjetas  t left outer join buses b on t.idpatente = b.idbus where t.codigo='".$codigo."'");
$s->execute();
$r = $s->get_result();
$cuenta = $r->num_rows;
$data=[];
if($cuenta > 0){
while($f = $r->fetch_assoc()){
// recorridos
$s2=$this->con->prepare("select * from busesrecorridos,recorridos,tarifas where idbus=".$f["idpatente"]." and iddestino=idrecorrido and idtiporecorrido=idtipodestino and fecha <='".$fechahoy."' group by idrecorrido");
$s2->execute();
$r2 = $s2->get_result();
$recorridos=[];
while($f2 = $r2->fetch_assoc()){
$tarifa=$this->fn->obtenervalor("tarifas","valor","where idtiporecorrido = '".$f2["idtipodestino"]."' order by fecha desc limit 0,1 ");

$recorridos[]=[
"patente"=>$f["patentebus"],
"idbusrecorrido"=>$f2["idbusreccorrido"],
"idrecorrido"=>$f2["idrecorrido"],
"recorrido"=>$f2["nombredestino"],
"tipodestino"=>$f2["tipodestino"],
"tiporecorrido"=>$f2["idtipodestino"],
"valor"=>$tarifa
];
}
$resulta=[
"error"=>false,
"idtarjeta"=>$f["idtarjeta"],
"idcliente"=>$f["idcliente"],
"idpatente"=>$f["idpatente"],
"patente"=>$f["patentebus"],
"estado"=>$f["estado"],
"recorridos"=>$recorridos
];
}
}else{
$resulta=[
"error"=>true,
"mensaje"=>"Código de tarjeta no encontrado"
];
}

return  $resulta;
}



}



?>