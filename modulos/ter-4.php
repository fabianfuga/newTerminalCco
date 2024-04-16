<div class="container">
<div class="row mt20 c_ter2" id="v_listado">
<div class="col-12">
<h3>Agregar Empresa</h3>
<div class="card mt50">
<!--<div class="card-header">
<h3 class="card-title"></h3>
</div>-->
<div class="card-body">
<div class="row mt20">
<div class="col-8">
<form action="operaciones.php"  method="post">
<INPUT TYPE="hidden" name="operacion" value="guardaestandar">
<INPUT TYPE="hidden" name="tabla" value="clientes">
<INPUT TYPE="hidden" name="retornar" value="index.php?mod=1&subid=ter-2">
<INPUT TYPE="hidden" name="poruser-n" value="<?=$id;?>">
<div class="row">

<div class="col-4"><label>Razón Social</label><input type="text" class="form-control" name="nombre-n" id="nombre"></div>
<div class="col-4"><label>Nombre Fantas&iacute;a/Referencia</label><input type="text" class="form-control" name="nom_fantasia-n" id="nomfantasia"></div>
<div class="col-4"><label>Rut</label><input type="text" class="form-control" name="rut-n" id="rut"></div>
</div>
<div class="row mt10">
<div class="col-4"><label>Representante Legal</label><input type="text" class="form-control" name="representante-n" id="representante"></div>
<div class="col-4"><label>Nombre Contacto</label><input type="text" class="form-control" name="nombrecontacto-n" id="nombrecontacto"></div>
<div class="col-4"><label>Tel&eacute;fono</label><input type="text" class="form-control" name="telefono-n" id="telefono"></div>
</div>

<div class="row mt10">
<div class="col-4"><button type="submit" class="btn btn-success">Agregar Empresa</button></div>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>