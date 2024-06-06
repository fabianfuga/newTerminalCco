<!-- modal -->
<div class="modal" tabindex="-1" role="dialog" id="m_t30">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title"></h5>
<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
</div>
<div class="modal-footer">
</div>
</div>
</div>
</div>
<!-- fin modal -->
<div class="container">
<div class="row mt-2">
<div class="col-md-12"><h4>Libro Mayor</h4></div>
</div>
<div class="row mt-2">
<div class="col-md-3" id="h_fechas">
<label>Periodo</label>
<input type="text" class="form-control" id="fechadc">
<input type="hidden" name="filter[start_date]" id="finicio" value=<?=date("Y-m-d");?>>
<input type="hidden" name="filter[end_date]" id="ffin" value=<?=date("Y-m-d");?>>
</div>
<div class="col-md-4">
<label>Empresa</label>
<? htmlselect('cliente','cliente','clientes','id','nombre','','','','nombre','getPatentes(this)','','si','no','no');?>
</div>
<div class="col-md-2">
<label>Transacción</label>
<select name="transac" id="transac" class="form-control">
<option value=1>Ambas</option>
<option value=2>Recargas</option>
<option value=3>Salidas</option>
</select>
</div>
<div class="col-md-3">
<label>Tarifas</label>
<? htmlselect('tarifa','tarifa','tiporecorrido','idtipodestino','tipodestino','','','','idtipodestino','cargarecorridos(this)','','si','no','no');?>
</div>
</div>
<div class="row mt-2">
<div class="col-md-3">
<label>Destino</label>
<select name="destino" id="destino" class="form-control">
<option value=0>--</option>
</select>
</div>
<div class="col-md-3">
<label>Patente</label>
<select name="destino" id="destino" class="form-control">
<option value=0>--</option>
</select>
</div>
<div class="col-md-3">
<label>Usuario</label>
<? htmlselect('usuario','usuario','usuarios','id','nombre','','','','nombre','','','si','no','no');?>
</div>
<div class="col-md-2 mt27">
<button type="button" class="btn btn-success btn-block" id="btnbuscarmov" onclick="BuscarMovimientos()">Buscar</button>
</div>
</div>
</div>

<script>

function cargarecorridos(e){

}
</script>