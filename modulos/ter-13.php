<div class="container">
<div class="row mt20 c_ter2" id="v_listado">
<div class="col-12">
<h3>Agregar un Bus</h3>
<div class="card mt20">
<!--<div class="card-header">
<h3 class="card-title"></h3>
</div>-->
<div class="card-body">
<div class="row mt20">
<div class="col-8">
<form action="operaciones.php"  method="post">
<INPUT TYPE="hidden" name="operacion" value="guardaestandar">
<INPUT TYPE="hidden" name="tabla" value="buses">
<INPUT TYPE="hidden" name="retornar" value="index.php?mod=8&subid=ter-13">
<INPUT TYPE="hidden" name="poruser-n" value="<?=$id;?>">
<div class="row">
<div class="col-8">
<label>Patente</label>
<input type="text" class="form-control" name="patentebus-n" id="idpatente" placeholder="XXYYZZ" size="5">
</div>
</div>
<div class="row mt10">
<div class="col-8">
<label>N&uacute;mero en Terminal</label>
<input type="text" class="form-control" name="numero-n" id="idnumero" placeholder="" size="5">
</div>
</div>
<div class="row mt10">
<div class="col-8">
<label>Empresa</label>
<? htmlselect('codigoempresa-n','codigoempresa-n','clientes','id','nombre','','','','id','','','si','no','no');?>
</div>
</div>
<div class="row mt10">
<div class="col-4"><button type="submit" class="btn btn-success">Agregar Bus</button></div>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<script>
$(function(){
$("#codigoempresa-n").chosen();
});
</script>