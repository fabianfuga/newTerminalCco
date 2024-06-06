<?
include("conexion.php");
session_start();
$user=$_SESSION["terautorizado"];
$ultimasalida=date("Y-m-d H:i:s");
$s="update usuarios set ultimasalida='".$ultimasalida."', enturno=0 where id='".$user."'";
$r=$link->query($s);
unset($_SESSION["terautorizado"]);
session_unset();
session_destroy();
header("Location:index.php"); 
?>