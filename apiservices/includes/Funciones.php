<?
require_once dirname(__FILE__) . '/Conexion.php';
class Funciones extends Conexion{
private $con;
function __construct(){
$db = new Conexion();
$this->con = $db->conectar();
}
/************ funciones genericas  *******************/
public function post_clave($password, $cost = 11){
// Genera sal de forma aleatoria
$salt = substr(base64_encode(openssl_random_pseudo_bytes(17)), 0, 22);
// reemplaza caracteres no permitidos
$salt = str_replace("+", ".", $salt);
// genera una cadena con la configuración del algoritmo
$param = '$' . implode('$', array("2y", str_pad($cost, 2, "0", STR_PAD_LEFT), $salt));
// obtiene el hash de la contraseña
return crypt($password, $param);
}


public function devfechacorta($fechagringa){list($elano,$elmes,$eldia) = explode('-',$fechagringa);$nuevafecha="".$eldia."-".$elmes."";return $nuevafecha;}
public function devfecha($fechagringa){
list($elano,$elmes,$eldia) = explode('-',$fechagringa);
$nuevafecha="".$eldia."/".$elmes."/".$elano."";
return $nuevafecha;
}
public function devfechahoracorta($fecha){
list($fechagringa,$lahora) = explode(' ',$fecha);
list($elano,$elmes,$eldia) = explode('-',$fechagringa);
$nuevafecha="".$eldia."-".$elmes." ".$lahora."";
return $nuevafecha;
}

public function obtenervalor($tabla,$campo,$where){
$valor="";
$sql=$this->con->prepare("select * from ".$tabla." ".$where."");
$sql->execute();
$res = $sql->get_result();
while($fila = $res->fetch_assoc()){
$valor=$fila["".$campo.""];
}
return $valor;
}

/************ funciones login *************************/
public function UserExist($usuario){
$sql= $this->con->prepare("select id from userempresa where usuario ='".$usuario."'");
$sql->execute();
$res = $sql->get_result();
$cuenta = $res->num_rows;
if($cuenta > 0){
while($f1 = $res->fetch_assoc()) {
$data["existe"]=true;
$data["id"]=$f1["id"];
}
}else{
$data["existe"]=false;
$data["id"]=0;
}
return $data;
}

public function getPasswordUser($id){
$sql=$this->con->prepare("select pass from userempresa where id=?");
$sql->bind_param("s",$id);
$sql->execute();
$sql->bind_result($per_clave);
$sql->fetch();
return $per_clave;
}
}
?>