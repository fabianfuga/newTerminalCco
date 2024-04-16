<?
// $urlempresas="http://172.233.190.224/rodoviarioempresarios";
// header("Location:".$urlempresas."");
// die();


session_start();
if(empty($_SESSION["terautorizado"])){include("terminal_entrada.php");}else{
include("conexion.php");
include("funciones.php");
$id=$_SESSION["terautorizado"];
$ippos=$_SESSION["ip_pos"];
$codsofland=$_SESSION["cajero"];
$misdatos=usuariologeado($id);
	
?>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Plaza Terminal Curic&oacute;</title>



<link href="includes/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link href="includes/daterangepicker.css" rel="stylesheet">
<link href="includes/adminlte/css/adminlte.min.css" rel="stylesheet">
<link href="css/chosen.css" rel="stylesheet">
<link href="includes/fontawesome/css/all.css" rel="stylesheet">
<link href="includes/datatables/datatables.min.css" rel="stylesheet">
<!--<link href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">-->
<link href="includes/jquery-ui-1.8.7.custom.css" rel="stylesheet" />
<link href="includes/jquery.datetimepicker.css" rel="stylesheet"/>
<link href="css/terminal.css?<?=date("U");?>" rel="stylesheet">


<script src="includes/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="includes/jquery-3.7.1.min.js"></script>
<script src="includes/moment.min.js" type="text/javascript"></script>
<script src="includes/daterangepicker.js" type="text/javascript"></script>
<script src="includes/chosen.jquery.js" type="text/javascript"></script>
<script src="includes/fontawesome/js/all.js" type="text/javascript"></script>
<script src="includes/adminlte/js/adminlte.min.js"></script>
<script src="includes/datatables/datatables.min.js"></script>
<!--<script type="text/javascript" src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>-->
<script src="includes/jquery-ui.min.js"></script>
<script src="includes/jquery.datetimepicker.full.js"></script>

<script src="f_genericas.js"></script>

</head>
<body class="sidebar-mini layout-fixed sidebar-collapse">

<div class="wrapper">
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
<ul class="navbar-nav">
<li class="nav-item">
<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
</li>
</ul>
<ul class="navbar-nav ml-auto">
<li class="nav-item dropdown user user-menu">
<!-- Menu Toggle Button -->
<a href="#"  data-toggle="dropdown">
<!-- The user image in the navbar-->
<img src="images/<?=$misdatos["foto"];?>" class="user-image" alt="User Image">
<!-- hidden-xs hides the username on small devices so only the image appears.
<span class="hidden-xs"><?=$misdatos["nombre"];?></span>-->
</a>
<div class="dropdown-menu dropdown-menu-right animated flipInY">
<ul class="dropdown-user">
<li>
<div class="dw-user-box">
<div class="u-img"><img src="images/<?=$misdatos["foto"];?>" alt="user"></div>
<div class="u-text">
<h4><?=$misdatos["nombre"];?></h4>
<p class="text-muted"><?=$misdatos["correo"];?></p><button class="btn btn-block btn-outline-success btn-sm">Mi Perfil</button></div>
</div>
</li>
<li role="separator" class="divider"></li>
<li><a href="salir.php" class="text-red fright"><i class="fa fa-power-off"></i> Salir</a></li>
</ul>
</div>
</li>
</ul>
</nav><!-- /.navbar -->


<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
<!-- Brand Logo -->
<a href="index.php" class="brand-link">
<img src="images/logo_blanco.png" alt="PLAZA TERMINAL" class="brand-image">
<span class="brand-text font-weight-light">&nbsp;</span>
</a>
<!-- Sidebar -->
<div class="sidebar">
<nav class="mt-2">
<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

<?
$mods=[];
$s1="select mmu.id_modulo_menus,mm.menu,mm.idmodulo,mm.posicion as posmodulo,m.modulo,m.posicion as posmenu,m.disponible,m.icono from modulos_menus_usuarios mmu inner join modulos_menus mm on mmu.id_modulo_menus=mm.id left outer join modulos m on mm.idmodulo=m.id where mmu.id_usuario=".$id." order by posmenu asc";
$r1=$link->query($s1);
$datamenu=[];
while($f1=mysqli_fetch_array($r1)){
$modulos[$f1["idmodulo"]][$f1["id_modulo_menus"]]=$f1["menu"];
$datamenu[$f1["idmodulo"]]=[
"menu"=>$f1["modulo"],
"icono"=>$f1["icono"],
"orden"=>$f1["posmenu"],
"modulos"=>$modulos[$f1["idmodulo"]]
];
}
usort($datamenu, fn($a, $b) => $a['orden'] <=> $b['orden']);




if(isset($_REQUEST["subid"])){
$sep=explode("-",$_REQUEST["subid"]);
$idmenu = intval($sep[1]);
}else{$idmenu=0;}
$ma="";$mo="";
foreach($datamenu as $i=>$v){
if($idmenu){
if(isset($v["modulos"][$idmenu])){$mo='menu-open';$ma="active";}else{$mo='';$ma="";}
}

?>
<li class="nav-item has-treeview <?=$mo;?>">
<a href="#" class="nav-link <?=$ma;?>"><i class="nav-icon <?=$v["icono"];?>"></i><p><?=$v["menu"];?><i class="right fas fa-angle-left"></i></p></a>
<ul class="nav nav-treeview">
<?

foreach($v["modulos"] as $i1=>$v1){
	
// $menu=quitarAcentosEspacios($v1,'si','si');
if($idmenu === intval($i1)){$moa="active";}else{$moa="";}
?>
<li class="nav-item">
<a href="index.php?mod=<?=$i;?>&subid=ter-<?=$i1;?>" class="nav-link <?=$moa;?>"><i class="far fa-circle nav-icon"></i><p><?=$v1;?></p></a>
</li>
<?
}?>
</ul>
</li>

<?
}?>
</ul>
</nav>
</div><!-- /.sidebar-menu -->
</aside><!-- /.sidebar -->


<div class="content-wrapper" style="min-height: 444px;">
<section class="content">
<input type="hidden" id="userid" value="<?=$id;?>"/>
<input type="hidden" id="ippos" value="<?=$ippos;?>"/>
<input type="hidden" id="cod_softland" value="<?=$codsofland;?>"/>
<div class="container-fluid">
<?

if(isset($_REQUEST["subid"])){include("modulos/".$_REQUEST["subid"].".php");}else{include("home.php");}
?>
</div>
</section>
</div><!-- fin content-wrapper -->

</div>


<script>
$.datepicker.regional['es'] = {
closeText: 'Cerrar',
prevText: '<Ant',
nextText: 'Sig>',
currentText: 'Hoy',
monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
weekHeader: 'Sm',
dateFormat: 'dd/mm/yy',
firstDay: 1,
isRTL: false,
showMonthAfterYear: false,
yearSuffix: ''
};
$.datepicker.setDefaults($.datepicker.regional['es']);
$('.fecha').datepicker({
	changeMonth: true,
    changeYear: true,
	yearRange: "-100:+00"
});

</script>
</body>
</html>

<?
	
}

?>