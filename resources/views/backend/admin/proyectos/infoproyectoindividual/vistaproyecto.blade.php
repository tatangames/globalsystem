@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/bootstrap-select.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/jquery-ui.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }

    .dropdown-menu {
        max-height: 280px;
        overflow-y: auto;
        width: 75%;
    }


    .modal-xl { max-width: 95% !important; }

</style>

<div id="divcontenedor" style="display: none">

    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6" style="margin-right: 10px;">
                <h1>Control Individual de Proyecto</h1>
            </div>

            @can('boton.pdf.generar.presupuesto')
            <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" onclick="modalGenerarPresupuesto()"
                    class="button button-3d button-rounded button-pill button-small">
                <i class="fas fa-file-pdf"></i>
                Generar Presupuesto
            </button>
            @endcan

        </div>
    </section>

    <!------------------ INFORMACION DE UN PROYECTO ESPECIFICO ---------------->
    <section class="content">
        <div class="col-sm-6 float-left">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Datos del Proyecto</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10">
                                <table>
                                    <tr>
                                        <td style="font-weight: bold">Código: </td>
                                        <td>{{ $proyecto->codigo }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold">Nombre: </td>
                                        <td>{{ $proyecto->nombre }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold">Dirección: </td>
                                        <td>{{ $proyecto->ubicacion }}</td>
                                    </tr>
                                </table>

                                @can('boton.editar.imprevisto.administrador')
                                <button type="button" style="margin-top: 15px; font-weight: bold; background-color: #28a745; color: white !important;" onclick="modalImprevisto()" class="button button-3d button-rounded button-pill button-small">
                                    <i class="fas fa-percent"></i>
                                    Imprevisto
                                </button>
                                @endcan


                                <!-- permiso para habilitar botón y agregar partidas adicionales -->
                                <!-- debe estar proyecto en estado INICIADO -->

                                <button type="button" style="margin-top: 15px; font-weight: bold; background-color: #28a745; color: white !important;" onclick="vistaPartidaAdicional()" class="button button-3d button-rounded button-pill button-small">
                                    <i class="fas fa-list-alt"></i>
                                    Partidas Adicionales
                                </button>






                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!------------------ PRESUPUESTO DEL PROYECTO INDIVIDUAL ---------------->
        @can('modulo.agregar.requisicion.proyecto')
        <div class="col-sm-6 float-right">
              <div class="container-fluid">
                  <div class="card card-default">
                      <div class="card-header">
                          <h3 class="card-title"><strong>Requisiciones de Proyecto</strong></h3>

                          @if($proyecto->presu_aprobado !== 2)
                              <br><br>
                              <span class="badge bg-warning">Esperando aprobación de Presupuesto</span>
                          @else
                              <br>
                              <div class="row">

                                  <div class="form-group">
                                      <button type="button" style="font-weight: bold; background-color: #6c757d; color: white !important;"
                                              class="button button-3d button-rounded button-pill button-small" onclick="vistaCatalogoMaterial()">
                                          <i class="fas fa-list-alt" title="Material Presupuesto"></i>&nbsp; Material Presupuesto
                                      </button>
                                  </div>

                                @if($boolPartidaAdicional)
                                  <div class="form-group">
                                      <button type="button" style="font-weight: bold; margin-left: 15px; background-color: #6c757d; color: white !important;"
                                              class="button button-3d button-rounded button-pill button-small" onclick="vistaCatalogoMaterialPartidaAdic()">
                                          <i class="fas fa-list-alt" title="Material Partida Adicional"></i>&nbsp; Material Partida Adicional
                                      </button>
                                  </div>
                                @endif

                          </div>

                            <!-- unicamente puede agregar requisición si proyecto esta iniciado -->
                              @can('boton.agregar.requisicion')
                                @if($proyecto->id_estado == 2)


                                      <button style="margin-left: 15px; float: right; margin-bottom: 10px; font-weight: bold; background-color: #28a745; color: white !important;"
                                              type="button" onclick="verModalRequisicion()" class="button button-3d button-rounded button-pill button-small">
                                          Agregar Requisición
                                      </button>
                                @elseif($proyecto->id_estado == 3)

                                      <label style="float: right; color: red; font-size: 16px">Proyecto Esta Pausado</label>

                                @elseif($proyecto->id_estado == 4)
                                      <label style="float: right; color: red; font-size: 16px">Proyecto esta Finalizado</label>
                                @else
                                      <label style="float: right">Esperando Presupuesto</label>
                                @endif

                              @endcan
                          @endif

                      </div>

                      <div class="card-body">
                          <div class="row">
                              <div class="col-md-12">
                                  <div id="tablaDatatableRequisicion">
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        @endcan


        <!-- ******************** MODULO DE INGENIERIA ************************ -->

        <!------------------ PRESUPUESTO DE PROYECTO ---------------->
        @can('modulo.agregar.partida.proyecto')
         <div class="col-sm-6 float-right">
            <div class="container-fluid">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Presupuesto de Proyecto</strong></h3>
                        <br>
                        <br>

                        <!-- Estado para que revise el presupuesto UACI -->

                        @if($proyecto->presu_aprobado == 0 || $proyecto->presu_aprobado == 1)

                            <!-- solo cuando proyecto esta PRIORIZADO -->
                            @if($proyecto->id_estado == 1)
                                <div class="form-group">
                                    <label>Estado Presupuesto:</label>
                                    <select class="form-control" id="select-estado" onchange="cambiarEstado()" style="width: 45%">
                                        @if($estado == 0)
                                            <option value="0" selected>Presupuesto Pendiente</option>
                                            <option value="1">Listo para Revisión</option>
                                        @else
                                            <option value="0">Presupuesto Pendiente</option>
                                            <option value="1" selected>Listo para Revisión</option>
                                        @endif
                                    </select>
                                </div>
                            @endif

                            <!-- el presupuesto no debe estar aprobado -->
                            @if($proyecto->presu_aprobado == 0)
                                <!-- solo se puede agregar partidas si esta priorizado el proyecto -->
                                @if($proyecto->id_estado == 1)
                                    <button type="button" style="margin-left: 15px; float: right; margin-bottom: 10px ;font-weight: bold; background-color: #6c757d; color: white !important;" onclick="verModalPresupuesto()" class="button button-3d button-rounded button-pill button-small">
                                        Agregar Partida
                                    </button>
                                @else
                                    <span class="badge bg-info" style="float: right; font-size: 15px">Sin Permiso para Crear Presupuesto</span>
                                @endif
                            @endif

                        @else
                            <!-- estado 2. presupuesto ya aprobado -->
                                <span class="badge bg-success" style="font-size: 14px">{{ $preaprobacion }}</span>
                        @endif

                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="tablaDatatablePresupuesto">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        <!------------------ CONTROL DE BITACORAS ---------------->
        @can('modulo.agregar.bitacoras.proyecto')
            <div class="col-sm-6 float-left">
                <div class="container-fluid">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Control de Bitácoras</strong></h3>
                            <button style="font-weight: bold; background-color: #6c757d; color: white !important; margin-left: 15px; float: right; margin-bottom: 10px" type="button" onclick="modalAgregarBitacora()"
                                    class="button button-3d button-rounded button-pill button-small">
                                Agregar Bitacora
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="tablaDatatableBitacora">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

    </section>

</div>

<!------------------ MODAL PARA AGREGAR REQUISICION ---------------->
<div class="modal fade" id="modalAgregarRequisicion" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar Requisición de Proyecto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formulario-requisicion-nuevo">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha *:</label>
                                    <input style="width:50%;" type="date" class="form-control" id="fecha-requisicion-nuevo">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Número Req.:</label>
                                    <input  type="text" class="form-control" id="conteo-requisicion" value="{{ $conteo }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Destino:</label>
                                    <input  type="text" class="form-control" autocomplete="off" id="destino-requisicion-nuevo">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>Necesidad:</label>
                                    <textarea class="form-control" id="necesidad-requisicion-nuevo" autocomplete="off" maxlength="15000" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <br>
                                    <button type="button" onclick="addAgregarFilaNuevaRequisicion()" class="button button-3d button-rounded button-pill button-small"
                                            style="margin-top:10px; font-weight: bold; background-color: #17a2b8; color: white !important;">
                                        <i class="fas fa-plus" title="Agregar"></i>&nbsp; Agregar</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table" id="matriz-requisicion"  data-toggle="table">
                                <thead>
                                <tr>
                                    <th style="width: 3%">#</th>
                                    <th style="width: 5%">Cantidad</th>
                                    <th style="width: 15%">Descripción</th>
                                    <th style="width: 5%">Opciones</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="button button-3d button-rounded button-pill button-small" style="font-weight: bold; background-color: #28a745; color: white !important;"
                        onclick="preguntaGuardarRequisicion()">Guardar</button>
            </div>
        </div>
    </div>
</div>



<!------------------ MODAL AGREGAR BITACORA ---------------->
<div class="modal fade" id="modalAgregarBitacora">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nueva Bitacora</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-bitacora-nuevo">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Observaciones</label>
                                    <textarea type="text" maxlength="10000" rows="4" cols="50" class="form-control" id="descripcion-bitacora-nuevo"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Fecha *</label>
                                    <input type="date" class="form-control" id="fecha-bitacora-nuevo">
                                </div>

                                <div class="form-group">
                                    <label>Documento</label>
                                    <input type="file" id="documento-bitacora" class="form-control" accept="image/jpeg, image/jpg, image/png"/>
                                </div>

                                <div class="form-group">
                                    <label>Nombre para Imagen</label>
                                    <input type="text" maxlength="300" class="form-control" id="nombre-bitacora-doc-nuevo">
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="guardarBitacora()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!------------------ MODAL EDITAR BITACORA ---------------->
<div class="modal fade" id="modalEditarBitacora">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Bitacora</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-bitacora-editar">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Observaciones</label>
                                    <input type="hidden" id="id-bitacora-editar">
                                    <textarea type="text" maxlength="10000" rows="4" cols="50" class="form-control" id="descripcion-bitacora-editar"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Fecha *</label>
                                    <input type="date" class="form-control" id="fecha-bitacora-editar">
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="editarBitacora()">Actualizar</button>
            </div>
        </div>
    </div>
</div>


<!------------------ MODAL IMPREVISTO DE PRESUPUESTO ---------------->
<div class="modal fade" id="modalImprevisto">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Porcentajes de Presupuesto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-imprevisto">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Imprevisto %</label>
                                    <input type="number" class="form-control" id="imprevisto-editar" placeholder="0%">
                                </div>

                                <div class="form-group">
                                    <label>Herramientas %</label>
                                    <input type="number" class="form-control" id="herramienta-editar" placeholder="0%">
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="editarImprevisto()">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!------------------ MODAL PARA EDITAR REQUISICIÓN ---------------->
<div class="modal fade" id="modalEditarRequisicion" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Requisición de Proyecto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formulario-requisicion-editar">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha *:</label>
                                    <input type="hidden" id="id-requisicion-editar">
                                    <input style="width:50%;" type="date" class="form-control" id="fecha-requisicion-editar">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Número Req.:</label>
                                    <input  type="text" class="form-control" id="conteo-requisicion-editar" readonly>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Destino:</label>
                                    <input  type="text" class="form-control" id="destino-requisicion-editar">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>Necesidad:</label>
                                    <textarea class="form-control" id="necesidad-requisicion-editar" maxlength="15000" rows="2"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <table class="table" id="matriz-requisicion-editar"  data-toggle="table">
                                <thead>
                                <tr>
                                    <th style="width: 3%">#</th>
                                    <th style="width: 4%">Cantidad</th>
                                    <th style="width: 4%">Precio</th>
                                    <th style="width: 4%">Total</th>
                                    <th style="width: 7%">Código Específico</th>
                                    <th style="width: 15%">Descripción</th>
                                    <th style="width: 5%">Opciones</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;"
                        class="button button-rounded button-pill button-small" id="botonGuardarRequiDetalle" onclick="preguntaGuardarRequisicionEditar()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!-- ****** INGENIERIA MODALES ******* !-->
<!-- modal agregar nuevo presupuesto -->
<div class="modal fade" id="modalAgregarPresupuesto" tabindex="-1">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar Presupuesto de Proyecto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formulario-presupuesto-nuevo">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Partida:</label>

                                    <select id="select-partida-nuevo" class="form-control">
                                        @foreach($tipospartida as $dd)
                                            <option value="{{ $dd->id }}">{{ $dd->nombre }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Item:</label>
                                    <input type="text" class="form-control" id="conteo-partida" readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cantidad C/ Unidad:</label>
                                    <input class="form-control" type="text" autocomplete="off" maxlength="50" id="cantidad-partida-nuevo">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>Partida *:</label>
                                    <input class="form-control" autocomplete="off" id="nombre-partida-nuevo" maxlength="600">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <br>
                                    <button type="button" onclick="addAgregarFilaPresupuestoNueva()" style="margin-top:10px; font-weight: bold; background-color: #17a2b8; color: white !important;" class="button button-rounded button-pill button-small float-right">
                                        <i class="fas fa-plus" title="Agregar"></i>&nbsp; Agregar</button>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <table class="table" id="matriz-presupuesto"  data-toggle="table">
                                <thead>
                                <tr>
                                    <th style="width: 3%">#</th>
                                    <th style="width: 5%">Cantidad</th>
                                    <th style="width: 15%">Descripción</th>
                                    <th style="width: 3%">Multiplicar <i class="fas fa-question-circle" data-toggle="popover" title="Multiplicar" data-content="Se multiplica el mismo material si se coloca mayor a 0"></i></th>
                                    <th style="width: 5%">Opciones</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="preguntaGuardarPresupuesto()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- modal editar presupuesto -->
<div class="modal fade" id="modalEditarPresupuesto" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Partida Presupuesto de Proyecto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formulario-presupuesto-editar">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Partida:</label>

                                    <select id="select-partida-editar" disabled class="form-control">
                                        @foreach($tipospartida as $dd)
                                            <option value="{{ $dd->id }}">{{ $dd->nombre }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Item:</label>
                                    <input  type="text" class="form-control" id="conteo-partida-editar" readonly>
                                    <input  type="hidden" id="id-partida-editar">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cantidad C/ Unidad:</label>
                                    <input class="form-control" autocomplete="off" type="text" maxlength="50" id="cantidad-partida-editar">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>Partida *:</label>
                                    <input class="form-control" id="nombre-partida-editar" maxlength="600">
                                </div>
                            </div>

                            @if($proyecto->presu_aprobado == 0)
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <br>
                                            <button type="button" onclick="addAgregarFilaPresupuestoEditar()" class="btn btn-primary btn-sm float-right" style="margin-top:10px;">
                                                <i class="fas fa-plus" title="Agregar"></i>&nbsp; Agregar</button>
                                    </div>
                                </div>
                            @endif

                        </div>
                        <div class="row">
                            <table class="table" id="matriz-presupuesto-editar"  data-toggle="table">
                                <thead>
                                <tr>
                                    <th style="width: 3%">#</th>
                                    <th style="width: 5%">Cantidad</th>
                                    <th style="width: 15%">Descripción</th>
                                    <th style="width: 3%">Multiplicar <i class="fas fa-question-circle" data-toggle="popover" title="Multiplicar" data-content="Se multiplica el mismo material si se coloca mayor a 0"></i></th>
                                    <th style="width: 5%">Opciones</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                @if($proyecto->presu_aprobado == 0)
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="preguntaEditarPresupuestoEditar()">Guardar</button>
                @endif
            </div>
        </div>
    </div>
</div>


<!-- ****** USUARIO HACE REQUISICIONES ****** !-->

<!-- catalogo de materiales para que hacer requerimiento -->
<div class="modal fade" id="modalCatalogoMaterial">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Catálogo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="tablaCatalogoMaterial">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";

            // variable global para setear input al buscar nuevo material
            window.txtContenedorGlobal = this;
            window.seguroBuscador = true;

            var id = {{ $id }};
            var ruta = "{{ URL::to('/admin/proyecto/vista/bitacora') }}/" + id;
            $('#tablaDatatableBitacora').load(ruta);

            // vista otros
            var rutaR = "{{ URL::to('/admin/proyecto/vista/requisicion') }}/" + id;
            $('#tablaDatatableRequisicion').load(rutaR);

            // vista ingeniera
            var rutaP = "{{ URL::to('/admin/proyecto/vista/presupuesto') }}/" + id;
            $('#tablaDatatablePresupuesto').load(rutaP);

            // para el numero de item
            window.contadorGlobal = {{ $conteoPartida }};

            $(document).click(function(){
                $(".droplista").hide();
                $(".droplistaeditar").hide();
                $(".droplistapresupuesto").hide();
                $(".droplistapresupuestoEditar").hide();
            });

            $(document).ready(function() {
                $('[data-toggle="popover"]').popover({
                    placement: 'top',
                    trigger: 'hover'
                });
            });
        });
    </script>

    <script type="text/javascript">

        //*********************** CÓDIGO PARA REQUISICIONES ********************************

        // para modal agregar Requisicion por parte de administradora
        function buscarMaterialRequisicion(e){

            // seguro para evitar errores de busqueda continua
            if(seguroBuscador){
                seguroBuscador = false;

                // id proyecto
                var idpro = {{ $id }};

                var row = $(e).closest('tr');
                txtContenedorGlobal = e;

                let texto = e.value;

                if(texto === ''){
                    // si se limpia el input, setear el atributo id
                    $(e).attr('data-info', 0);
                }

                axios.post(url+'/buscar/material/soloproyecto', {
                    'query' : texto,
                    'idpro' : idpro
                })
                    .then((response) => {
                        seguroBuscador = true;
                        $(row).each(function (index, element) {
                            $(this).find(".droplista").fadeIn();
                            $(this).find(".droplista").html(response.data);
                        });

                    })
                    .catch((error) => {
                        seguroBuscador = true;
                    });
            }
        }

        function addAgregarFilaNuevaRequisicion(){

            var nFilas = $('#matriz-requisicion >tbody >tr').length;
            nFilas += 1;

            var markup = "<tr>"+

                "<td>"+
                "<p id='fila"+(nFilas)+"' class='form-control' style='max-width: 65px'>"+(nFilas)+"</p>"+
                "</td>"+

                "<td>"+
                "<input name='cantidadarray[]' maxlength='10' class='form-control' type='number'>"+
                "</td>"+

                "<td>"+
                "<input name='descripcionarray[]' data-info='0' autocomplete='off' class='form-control' style='width:100%' onkeyup='buscarMaterialRequisicion(this)' maxlength='400'  type='text'>"+
                "<div class='droplista' style='position: absolute; z-index: 9; width: 75% !important;'></div>"+
                "</td>"+

                "<td>"+
                "<button type='button' class='btn btn-block btn-danger' onclick='borrarFilaRequiDetalle(this)'>Borrar</button>"+
                "</td>"+

                "</tr>";

            $("#matriz-requisicion tbody").append(markup);
        }

        // borrar fila para tabla nueva requisicion material
        function borrarFilaRequiDetalle(elemento){
            var tabla = elemento.parentNode.parentNode;
            tabla.parentNode.removeChild(tabla);
            setearFilaRequisicion();
        }

        // borrar fila para tabla editar requisicion material
        function borrarFilaRequiEditar(elemento){
            var tabla = elemento.parentNode.parentNode;
            tabla.parentNode.removeChild(tabla);
            setearFilaRequisicionEditar()
        }

        // cambiar # de fila cada vez que se borra la fila de
        // tabla nuevo material
        function setearFilaRequisicion(){

            var table = document.getElementById('matriz-requisicion');
            var conteo = 0;
            for (var r = 1, n = table.rows.length; r < n; r++) {
                conteo +=1;
                var element = table.rows[r].cells[0].children[0];
                document.getElementById(element.id).innerHTML = ""+conteo;
            }
        }

        function setearFilaRequisicionEditar(){

            var table = document.getElementById('matriz-requisicion-editar');
            var conteo = 0;
            for (var r = 1, n = table.rows.length; r < n; r++) {
                conteo +=1;
                var element = table.rows[r].cells[0].children[0];
                document.getElementById(element.id).innerHTML = ""+conteo;
            }
        }

        // recargar tabla de requisiciones
        function recargarRequisicion(){
            var id = {{ $id }};
            var rutaR = "{{ URL::to('/admin/proyecto/vista/requisicion') }}/" + id;
            $('#tablaDatatableRequisicion').load(rutaR);
        }

        // ver modal requisición
        function verModalRequisicion(){
            document.getElementById("formulario-requisicion-nuevo").reset();

            colorBlancoTablaRequisicion();
            $('#modalAgregarRequisicion').css('overflow-y', 'auto');
            $('#modalAgregarRequisicion').modal({backdrop: 'static', keyboard: false})
        }

        // ver modal detalle requisicon
        function verModalDetalleRequisicion(){
            document.getElementById("formulario-requisicion-deta-nuevo").reset();
            $('#modalAgregarRequisicionDeta').modal('show');
        }

        // preguntar si quiere guardar la nueva requisicion
        function preguntaGuardarRequisicion(){
            colorBlancoTablaRequisicion();

            Swal.fire({
                title: 'Guardar Requisición',
                text: "Se Reservara Monto en Saldo Retenido",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    verificarRequisicion();
                }
            })
        }

        // preguntar si quiere guardar la editada de requisicion
        function preguntaGuardarRequisicionEditar(){
            colorBlancoTablaRequisicionEditar();

            Swal.fire({
                title: 'Actualizar Requisición',
                text: "",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    verificarRequisicionEditar();
                }
            })
        }

        // verificar la requisicin para agregar a la base
        function verificarRequisicion(){

            var fecha = document.getElementById('fecha-requisicion-nuevo').value;
            var destino = document.getElementById('destino-requisicion-nuevo').value; // null
            var necesidad = document.getElementById('necesidad-requisicion-nuevo').value; // text

            if(fecha === ''){
                toastr.error('Fecha requisición es requerido');
                return;
            }

            if(destino.length > 300){
                toastr.error('Destino, máximo 300 caracteres');
                return;
            }

            if(necesidad.length > 15000){
                toastr.error('Necesidad debe tener máximo 15,000 caracteres');
                return;
            }

            var nRegistro = $('#matriz-requisicion >tbody >tr').length;
            let formData = new FormData();
            var id = {{ $id }};

            if (nRegistro <= 0) {
                toastr.error('Detalle es requerido');
                return;
            }

            var cantidad = $("input[name='cantidadarray[]']").map(function(){return $(this).val();}).get();
            var descripcion = $("input[name='descripcionarray[]']").map(function(){return $(this).val();}).get();
            var descripcionAtributo = $("input[name='descripcionarray[]']").map(function(){return $(this).attr("data-info");}).get();
            var reglaNumeroDosDecimal = /^([0-9]+\.?[0-9]{0,2})$/;

            for(var a = 0; a < cantidad.length; a++){
                let detalle = descripcionAtributo[a];
                let datoCantidad = cantidad[a];

                // identifica si el 0 es tipo number o texto
                if(detalle == 0){
                    colorRojoTablaRequisicion(a);
                    alertaMensaje('info', 'No encontrado', 'En la Fila #' + (a+1) + " El material no se encuentra. Por favor buscar de nuevo el Material. Recordar que debe hacer clic en el Material para Seleccionarlo");
                    return;
                }

                if(datoCantidad === ''){
                    colorRojoTablaRequisicion(a);
                    toastr.error('Fila #' + (a+1) + ' Cantidad es requerida');
                    return;
                }

                if(!datoCantidad.match(reglaNumeroDosDecimal)) {
                    colorRojoTablaRequisicion(a);
                    toastr.error('Fila #' + (a+1) + ' Cantidad debe ser Número Decimal Positivo. Solo se permite 2 Decimales');
                    return;
                }

                if(datoCantidad <= 0){
                    colorRojoTablaRequisicion(a);
                    toastr.error('Fila #' + (a+1) + ' Cantidad no debe ser negativo o igual a Cero');
                    return;
                }

                if(datoCantidad > 1000000){
                    colorRojoTablaRequisicion(a);
                    toastr.error('Fila #' + (a+1) + ' Cantidad máximo 1 millón');
                    return;
                }
            }

            // SOLO TIENE QUE IR UNA LETRA, ESTO NO SE ENVÍA A SERVER
            for(var b = 0; b < descripcion.length; b++){

                var datoDescripcion = descripcion[b];

                if(datoDescripcion === ''){
                    colorRojoTablaRequisicion(b);
                    toastr.error('Fila #' + (b+1) + ' la descripción es requerida');
                    return;
                }

                // cantidad de caracteres no se valida, ya que no se envía
            }

            // como tienen la misma cantidad de filas, podemos recorrer
            // todas las filas de una vez
            for(var p = 0; p < cantidad.length; p++){

                formData.append('cantidad[]', cantidad[p]);
                formData.append('datainfo[]', descripcionAtributo[p]);
            }

            openLoading();
            formData.append('fecha', fecha);
            formData.append('destino', destino);
            formData.append('necesidad', necesidad);
            formData.append('id', id); // id proyecto

            axios.post(url+'/proyecto/vista/requisicion/nuevo', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        let fila = response.data.fila;
                        let obj = response.data.obj; // codigo especifico
                        let restanteFormat = response.data.restanteFormat;
                        let retenidoFormat = response.data.retenidoFormat;
                        let retenido = response.data.retenido;
                        let solicita = response.data.solicita;

                        colorRojoTablaRequisicion(fila);

                        var texto = '';

                        // en true, mostramos el saldo Retenido
                        if(retenido > 0){
                            texto = "Fila #" + (fila+1) + ", el objeto específico: " + obj + "<br>" +
                                "Tiene Saldo Restante $" + restanteFormat + "<br>" +
                                "Saldo Retenido $" + retenidoFormat + "<br>" +
                                " Y se esta solicitando $" + solicita;
                        }else{
                            texto = "Fila #" + (fila+1) + ", el objeto específico: " + obj + "<br>" +
                                "Tiene Saldo Restante $" + restanteFormat + "<br>" +
                                " Y se esta solicitando $" + solicita + "<br>";
                        }

                        Swal.fire({
                            title: 'Cantidad No Disponible',
                            html: texto,
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })
                    }
                    else if(response.data.success === 2){
                        $('#modalAgregarRequisicion').modal('hide');
                        toastr.success('Registrado correctamente');
                        recargarRequisicion();
                        limpiarRequisicion(response.data.contador);
                    }
                    else if(response.data.success === 3) {

                        let mensaje = response.data.mensaje;

                        Swal.fire({
                            title: 'Estado Proyecto',
                            html: mensaje,
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })
                    }
                    else{
                        toastr.error('Error al crear requisición');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al crear requisición');
                    closeLoading();
                });
        }

        // cambio de color de fila tabla a rojo
        function colorRojoTablaRequisicion(index){
            $("#matriz-requisicion tr:eq("+(index+1)+")").css('background', '#F1948A');
        }

        // cambio de color de fila tabla a blanco
        function colorBlancoTablaRequisicion(){
            $("#matriz-requisicion tbody tr").css('background', 'white');
        }

        // cambio de color de fila tabla a rojo
        function colorRojoTablaRequisicionEditar(index){
            $("#matriz-requisicion-editar tr:eq("+(index+1)+")").css('background', '#F1948A');
        }

        // cambio de color de fila tabla a blanco
        function colorBlancoTablaRequisicionEditar(){
            $("#matriz-requisicion-editar tbody tr").css('background', 'white');
        }

        // limpiar modal requisicion y su tabla
        function limpiarRequisicion(contador){
            document.getElementById('conteo-requisicion').value = contador;
            document.getElementById('fecha-requisicion-nuevo').value = '';
            document.getElementById('destino-requisicion-nuevo').value = '';
            document.getElementById('necesidad-requisicion-nuevo').value = '';

            $("#matriz-requisicion tbody tr").remove();
        }


        function vistaEditarRequisicion(dato){

            let id = dato.id;
            let conteo = dato.numero;

            openLoading();
            document.getElementById("formulario-requisicion-editar").reset();
            $("#matriz-requisicion-editar tbody tr").remove();

            axios.post(url+'/proyecto/vista/requisicion/informacion', {
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){

                        $('#id-requisicion-editar').val(response.data.info.id);
                        $('#fecha-requisicion-editar').val(response.data.info.fecha);
                        $('#conteo-requisicion-editar').val(conteo);
                        $('#destino-requisicion-editar').val(response.data.info.destino);
                        $('#necesidad-requisicion-editar').val(response.data.info.necesidad);

                        let nopuedoEditar = response.data.btneditar;

                        if(nopuedoEditar){
                            // ocultar botón
                            document.getElementById("botonGuardarRequiDetalle").style.display = "none";

                            document.getElementById("fecha-requisicion-editar").disabled = true;
                            document.getElementById("destino-requisicion-editar").disabled = true;
                            document.getElementById("necesidad-requisicion-editar").disabled = true;
                        }else{
                            // mostrar botón
                            document.getElementById("botonGuardarRequiDetalle").style.display = "block";

                            document.getElementById("fecha-requisicion-editar").disabled = false;
                            document.getElementById("destino-requisicion-editar").disabled = false;
                            document.getElementById("necesidad-requisicion-editar").disabled = false;
                        }

                        var infodetalle = response.data.detalle;

                        for (var i = 0; i < infodetalle.length; i++) {
                            // id requi detalle
                            var markup = "<tr id='"+infodetalle[i].id+"'>";

                            markup += "<td>"+
                                "<p id='fila"+(i+1)+"' class='form-control' style='max-width: 65px'>"+(i+1)+"</p>"+
                                "</td>"+

                                "<td>"+
                                "<input name='cantidadarrayeditar[]' disabled value='"+infodetalle[i].cantidad+"' class='form-control'>"+
                                "</td>"+

                                "<td>"+
                                "<input value='$"+infodetalle[i].dinero+"' disabled class='form-control'>"+
                                "</td>"+

                                "<td>"+
                                "<input value='$"+infodetalle[i].multiplicado+"' disabled class='form-control'>"+
                                "</td>"+

                                "<td>"+
                                "<input value='"+infodetalle[i].codigo+"' disabled class='form-control'>"+
                                "</td>"+

                                "<td>"+
                                "<input class='form-control' disabled value='"+infodetalle[i].descripcion+"' style='width:100%' type='text'>"+
                                "</td>";

                            // si hay cotización
                            if(infodetalle[i].haycoti){

                                // cotizacion aprobada, no se puede borrar
                                if(infodetalle[i].cotizado === 1){
                                    markup += "<td>" +
                                        "<span class='badge bg-success'>Material Aprobado</span>"+
                                        "</td>"+
                                        "</tr>";

                                    // cotizacion denegada, puede CANCELAR
                                }else if(infodetalle[i].cotizado === 2){

                                    if(infodetalle[i].cancelado === 0){
                                        markup += "<td>"+
                                            "<button type='button' class='btn btn-block btn-danger' onclick='cancelarFilaRequiEditar(this)'>Cancelar</button>"+
                                            "</td>"+

                                            "</tr>";
                                    }else { // cuando material esta cancelado
                                        markup += "<td>"+
                                            "<span class='badge bg-danger'>Material Cancelado</span>"+
                                            "</tr>";
                                    }

                                }

                            }else{

                                if(nopuedoEditar){
                                    markup += "<td>"+
                                        "<button type='button' class='btn btn-block btn-danger' onclick='modalBorrarFilaRequiEditar(this)'>Borrar</button>"+
                                        "</td>"+

                                        "</tr>";
                                }else{
                                    // no tiene cotizacion, asi que puede BORRAR
                                    markup += "<td>"+
                                        "<button type='button' class='btn btn-block btn-danger' onclick='borrarFilaRequiEditar(this)'>Borrar</button>"+
                                        "</td>"+

                                        "</tr>";
                                }
                            }

                            // cotizacion aprobada, no puede borrar

                            $("#matriz-requisicion-editar tbody").append(markup);
                        }

                        $('#modalEditarRequisicion').css('overflow-y', 'auto');
                        $('#modalEditarRequisicion').modal({backdrop: 'static', keyboard: false})
                    }
                    else{
                        toastr.error('error buscar información');
                    }
                })
                .catch((error) => {
                    toastr.error('error buscar información');
                    closeLoading();
                });
        }

        // cancelar un material si fue denegada la cotizacion
        function cancelarFilaRequiEditar(e){

            // ID REQUI_DETALLE
            var id = $(e).closest('tr').attr('id');

            Swal.fire({
                title: 'Cancelar Material',
                text: "Si el material no puede ser Cotizado. Se cancelara y se libera el saldo Retenido",
                icon: 'info',
                showCancelButton: true,
                allowOutsideClick: false,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Salir',
                confirmButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    cancelarMaterialCotizado(id);
                }
            })
        }

        // borrar material requi detalle, aquí se hace especificamente a un material Fila
        // porque el boton guardar desparece porque otros materiales ya tiene cotización
        function modalBorrarFilaRequiEditar(e){
            // ID REQUI_DETALLE
            var id = $(e).closest('tr').attr('id');

            Swal.fire({
                title: 'Borrar Material',
                text: "Este material no tiene Cotización aun. Se puede eliminar",
                icon: 'info',
                showCancelButton: true,
                allowOutsideClick: false,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Borrar'
            }).then((result) => {
                if (result.isConfirmed) {
                    borrarRequiDetalleFila(id);
                }
            })
        }

        // solo elimina una fila
        function borrarRequiDetalleFila(id){
            openLoading();

            axios.post(url+'/proyecto/requisicion/material/borrarfila', {
                'id': id
            })
                .then((response) => {
                    closeLoading();

                    // el material ya tiene una cotización
                    if(response.data.success === 1) {

                        Swal.fire({
                            title: "Cotización Encontrada",
                            text: "No se puede Borrar el Material. Se encontro una cotización en Proceso",
                            icon: 'info',
                            showCancelButton: false,
                            allowOutsideClick: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#modalEditarRequisicion').modal('hide');
                                recargarRequisicion();
                            }
                        })

                    }else if(response.data.success === 2){
                        toastr.success('Borrado correctamente');
                        recargarRequisicion();
                    }
                    else{
                        toastr.error('Error al borrar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al borrar');
                    closeLoading();
                });
        }

        function cancelarMaterialCotizado(id){
            openLoading();

            axios.post(url+'/proyecto/requisicion/material/cancelar', {
                'id': id
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1) {

                        // si es 1, la coti fue aprobada, sino se esta esperando que sea
                        // aprobada o denegada
                        let tipo = response.data.tipo;

                        var mensaje = '';
                        var titulo = '';
                        if(tipo > 0){
                            titulo = "Cotización Aprobada";
                            mensaje = "El material fue aprobado. No se puede cancelar";
                        }else{
                            titulo = "Material en Espera";
                            mensaje = "El material esta esperando que su cotización sea Aprobado o Denegada. No se puede Cancelar por el momento.";
                        }

                        Swal.fire({
                            title: titulo,
                            text: mensaje,
                            icon: 'info',
                            showCancelButton: false,
                            allowOutsideClick: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Recargar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#modalEditarRequisicion').modal('hide');
                                recargarRequisicion();
                            }
                        })

                    }else if(response.data.success === 2){
                        toastr.success('Cancelado correctamente');
                        $('#modalEditarRequisicion').modal('hide');
                        recargarRequisicion();
                    }
                    else if(response.data.success === 3) {

                        // solo formulador puede cancelar material

                        Swal.fire({
                            title: 'No Puede Modificar',
                            html: 'Solo el Usuario Asignado como Formulador puede modificar el proyecto',
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })

                    }
                    else{
                        toastr.error('error al actualizar');
                    }
                })
                .catch((error) => {
                    toastr.error('error al actualizar');
                    closeLoading();
                });
        }

        // ver modal para detalle requisicion editar
        function verModalDetalleRequisicionEditar(){
            document.getElementById("formulario-requisicion-deta-editar").reset();
            $('#modalAgregarRequisicionDetaEditar').modal('show');
        }

        // verificar la editada de requisicion
        function verificarRequisicionEditar(){

            var fecha = document.getElementById('fecha-requisicion-editar').value;
            var idrequisicion = document.getElementById('id-requisicion-editar').value;
            var destino = document.getElementById('destino-requisicion-editar').value; // null
            var necesidad = document.getElementById('necesidad-requisicion-editar').value; // text

            if(fecha === ''){
                toastr.error('Fecha requisición es requerido');
                return;
            }

            if(destino.length > 300){
                toastr.error('Destino, máximo 300 caracteres');
                return;
            }

            if(necesidad.length > 15000){
                toastr.error('Necesidad debe tener máximo 15,000 caracteres');
                return;
            }

            var nRegistro = $('#matriz-requisicion-editar >tbody >tr').length;
            let formData = new FormData();

            if (nRegistro <= 0){
                toastr.error('Detalle Requisición es requerida');
                return;
            }

            var cantidad = $("input[name='cantidadarrayeditar[]']").map(function(){return $(this).val();}).get();

            // como tienen la misma cantidad de filas, podemos recorrer
            // todas las filas de una vez
            for(var p = 0; p < cantidad.length; p++){
                // obtener el id de la fila, si el id fila es 0, significa que sera nuevo registro
                var id = $("#matriz-requisicion-editar tr:eq("+(p+1)+")").attr('id');
                formData.append('idarray[]', id); // ID REQUI DETALLE
            }

            openLoading();
            formData.append('fecha', fecha);
            formData.append('destino', destino);
            formData.append('necesidad', necesidad);
            formData.append('idrequisicion', idrequisicion);

            axios.post(url+'/proyecto/vista/requisicion/editar', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1) {

                        let nombre = response.data.nombre;

                        Swal.fire({
                            title: 'Material Ya Cotizado',
                            text: "El material " + nombre + " Ya fue cotizado. Recargar Tabla",
                            icon: 'info',
                            showCancelButton: false,
                            allowOutsideClick: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Recargar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#modalEditarRequisicion').modal('hide');
                                recargarRequisicion();
                            }
                        })

                    }else if(response.data.success === 2){
                        toastr.success('Actualizado correctamente');
                        recargarRequisicion();
                        $('#modalEditarRequisicion').modal('hide');
                    }
                    else if(response.data.success === 3){

                        let mensaje = response.data.mensaje;

                        Swal.fire({
                            title: 'Estado Proyecto',
                            html: mensaje,
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })
                    }


                    else{
                        toastr.error('error al actualizar');
                    }
                })
                .catch((error) => {
                    toastr.error('error al actualizar');
                    closeLoading();
                });
        }

        //*********************** CÓDIGO PARA BITACORAS ********************************


        // recargar tabla solo para bitacoras
        function recargarBitacora(){
            var id = {{ $id }};
            var ruta = "{{ URL::to('/admin/proyecto/vista/bitacora') }}/" + id;
            $('#tablaDatatableBitacora').load(ruta);
        }


        // modal agregar bitacora
        function modalAgregarBitacora() {

            // verificar estado del proyecto
            var estado = {{ $estado }};

            if (estado !== 2) { // priorizado
                alertaEstado('Información', 'No puede agregar Bitácoras, porque el Presupuesto no ha sido Aprobado');
                return;
            }

            document.getElementById("formulario-bitacora-nuevo").reset();

            var fecha = new Date();
            document.getElementById('fecha-bitacora-nuevo').value = fecha.toJSON().slice(0, 10);

            $('#modalAgregarBitacora').modal('show');
        }

        // registro de bitacora
        function guardarBitacora() {

            var fecha = document.getElementById('fecha-bitacora-nuevo').value;
            var observaciones = document.getElementById('descripcion-bitacora-nuevo').value;
            var documento = document.getElementById('documento-bitacora'); // null file
            var nombreDocumento = document.getElementById('nombre-bitacora-doc-nuevo').value;

            if (fecha === '') {
                toastr.error('Fecha para Bitacora es requerida');
                return;
            }

            if (observaciones.length > 10000) {
                toastr.error('Descripción máximo 10,000 caracteres');
                return;
            }

            if (documento.files && documento.files[0]) { // si trae doc
                if (!documento.files[0].type.match('image/jpeg|image/jpeg|image/png')) {
                    toastr.error('formato para Imagen permitido: .png .jpg .jpeg');
                    return;
                }
            } else {
                // si imagen viene vacio, verificar texto
                if (nombreDocumento.length > 0) {
                    toastr.error('Imagen es requerida si ingresa Nombre para Imagen');
                    return;
                }
            }

            if (nombreDocumento.length > 300) {
                toastr.error('Nombre para Documento máximo 300 caracteres');
                return;
            }

            // id del proyecto
            var id = {{ $id }};

            openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('fecha', fecha);
            formData.append('observaciones', observaciones);
            formData.append('documento', documento.files[0]);
            formData.append('nombredocumento', nombreDocumento);

            axios.post(url + '/proyecto/vista/bitacora/registrar', formData, {})
                .then((response) => {
                    closeLoading();

                    if (response.data.success === 1) {
                        let mensaje = response.data.mensaje;

                        Swal.fire({
                            title: 'Estado Proyecto',
                            html: mensaje,
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })
                    }
                    else if (response.data.success === 2) {

                        $('#modalAgregarBitacora').modal('hide');
                        recargarBitacora();
                        toastr.success('Agregado correctamente');
                    }
                    else if (response.data.success === 3) {

                        Swal.fire({
                            title: 'No Puede Modificar',
                            html: 'Solo el Usuario Asignado como Formulador puede modificar el proyecto',
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })

                    }
                    else {
                        toastr.error('Error al registrar');
                    }

                })
                .catch((error) => {
                    toastr.error('Error al registrar');
                    closeLoading();
                });
        }

        // preguntar si quiere borrar la bitacora
        function preguntaBorrarBitacora(id) {
            Swal.fire({
                title: 'Borrar Bitacora',
                text: "Se eliminaran los registros",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    borrarBitacora(id);
                }
            })
        }

        // borrar la bitacora
        function borrarBitacora(id) {
            openLoading();

            axios.post(url + '/proyecto/vista/bitacora/borrar', {
                'id': id
            })
                .then((response) => {
                    closeLoading();

                    if (response.data.success === 1) {

                        let mensaje = response.data.mensaje;

                        Swal.fire({
                            title: 'Estado Proyecto',
                            html: mensaje,
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })

                    }
                    else if (response.data.success === 2) {
                        recargarBitacora();
                        toastr.success('Borrado correctamente');
                    }

                    else if (response.data.success === 3) {

                        Swal.fire({
                            title: 'No Puede Modificar',
                            html: 'Solo el Usuario Asignado como Formulador puede modificar el proyecto',
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })
                    }

                    else {
                        toastr.error('Error al borrar');
                    }

                })
                .catch((error) => {
                    toastr.error('Error al borrar');
                    closeLoading();
                });
        }

        // ver modal para editar bitacora
        function vistaEditarBitacora(id) {

            openLoading();
            document.getElementById("formulario-bitacora-editar").reset();

            axios.post(url + '/proyecto/vista/bitacora/informacion', {
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        $('#modalEditarBitacora').modal('show');
                        $('#id-bitacora-editar').val(response.data.bitacora.id);
                        $('#descripcion-bitacora-editar').val(response.data.bitacora.observaciones);
                        $('#fecha-bitacora-editar').val(response.data.bitacora.fecha);

                    } else {
                        toastr.error('Información no encontrada');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
        }

        // editar registro de bitacora
        function editarBitacora() {
            var id = document.getElementById('id-bitacora-editar').value;
            var fecha = document.getElementById('fecha-bitacora-editar').value;
            var observaciones = document.getElementById('descripcion-bitacora-editar').value;

            if (fecha === '') {
                toastr.error('Fecha para Bitacora es requerida');
                return;
            }

            if (observaciones.length > 10000) {
                toastr.error('Descripción máximo 10,000 caracteres');
                return;
            }

            openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('fecha', fecha);
            formData.append('observaciones', observaciones);

            axios.post(url + '/proyecto/vista/bitacora/editar', formData,)
                .then((response) => {
                    closeLoading();

                    if (response.data.success === 1) {

                        let mensaje = response.data.mensaje;

                        Swal.fire({
                            title: 'Estado Proyecto',
                            html: mensaje,
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })
                    }
                    else if (response.data.success === 2) {

                        $('#modalEditarBitacora').modal('hide');
                        recargarBitacora();
                        toastr.success('Actualizado correctamente');

                    }
                    else {
                        toastr.error('Error al actualizar');
                    }

                })
                .catch((error) => {
                    toastr.error('Error al actualizar');
                    closeLoading();
                });
        }


        // vista para bitacora detalle
        function vistaBitacora(id) {
            // id tabla bitacoras
            window.location.href = "{{ url('/admin/proyecto/vista/bitacora-detalle') }}/" + id;
        }

    </script>

    <script>

        // **** INGENIERIA  ****

        function verModalPresupuesto(){
            document.getElementById("formulario-presupuesto-nuevo").reset();
            document.getElementById("conteo-partida").value = window.contadorGlobal;

            $("#matriz-presupuesto tbody tr").remove();

            // habilitar select tipo partida
            document.getElementById("select-partida-nuevo").disabled = false;

            $('#modalAgregarPresupuesto').css('overflow-y', 'auto');
            $('#modalAgregarPresupuesto').modal({backdrop: 'static', keyboard: false})
        }

        function addAgregarFilaPresupuestoNueva(){

            var nFilas = $('#matriz-presupuesto >tbody >tr').length;
            nFilas += 1;

            // ******* DETECCIÓN MANUAL PARA EL TIPO DE PARTIDA *******

            var tipopartida = document.getElementById('select-partida-nuevo').value;

            // desactivar select porque ya eligio el tipo de partida
            document.getElementById("select-partida-nuevo").disabled = true;

            // Esto para desactivar el input 'cantidad' si esta seleccionado Aporte Patronal
            // APORTE MANO DE OBRA

            var markup = "<tr>" +

                "<td>" +
                "<p id='fila" + (nFilas) + "' class='form-control' style='max-width: 65px'>" + (nFilas) + "</p>" +
                "</td> " +

                "<td>" +
                "<input name='cantidadPresupuestoArray[]' maxlength='10' class='form-control' type='number'>" +
                "</td> " +

                "<td>" +
                "<input name='descripcionPresupuestoArray[]' data-infopresupuesto='0' autocomplete='off' class='form-control' style='width:100%' onkeyup='buscarMaterialPresupuesto(this)' maxlength='400'  type='text'>" +
                "<div class='droplistaPresupuesto' style='position: absolute; z-index: 9; width: 75% !important;'></div>" +
                "</td>" +

                "<td>" +
                "<input name='duplicarPresupuestoArray[]' maxlength='3' class='form-control' value='0' type='number'>" +
                "</td>" +

                "<td>" +
                "<button type='button' class='button button-caution button-rounded button-pill button-small' onclick='borrarFilaPresupuestoDetalle(this)'>Borrar</button>" +
                "</td>" +

                "</tr>";

            $("#matriz-presupuesto tbody").append(markup);
        }

        // borrar fila para tabla editar requisicion material
        function borrarFilaPresupuestoDetalle(elemento){
            var tabla = elemento.parentNode.parentNode;
            tabla.parentNode.removeChild(tabla);
            setearFilaPresupuesto()
        }

        // cambiar # de fila cada vez que se borra la fila de
        // tabla nuevo material
        function setearFilaPresupuesto(){

            var table = document.getElementById('matriz-presupuesto');
            var conteo = 0;
            for (var r = 1, n = table.rows.length; r < n; r++) {
                conteo +=1;
                var element = table.rows[r].cells[0].children[0];
                document.getElementById(element.id).innerHTML = ""+conteo;
            }

            // activar tipo partida hasta que no haya filas
            var nRegistro = $('#matriz-presupuesto > tbody >tr').length;
            if (nRegistro <= 0){
                // activar select porque ya no hay filas
                document.getElementById("select-partida-nuevo").disabled = false;
            }
        }

        function buscarMaterialPresupuesto(e){

            // seguro para evitar errores de busqueda continua
            if(seguroBuscador){
                seguroBuscador = false;

                var row = $(e).closest('tr');
                txtContenedorGlobal = e;

                let texto = e.value;

                if(texto === ''){
                    // si se limpia el input, setear el atributo id
                    $(e).attr('data-infopresupuesto', 0);
                }

                axios.post(url+'/proyecto/buscar/material-presupuesto', {
                    'query' : texto
                })
                    .then((response) => {
                        seguroBuscador = true;
                        $(row).each(function (index, element) {
                            $(this).find(".droplistaPresupuesto").fadeIn();
                            $(this).find(".droplistaPresupuesto").html(response.data);
                        });
                    })
                    .catch((error) => {
                        seguroBuscador = true;
                    });
            }
        }

        // al hacer clic en material buscado
        function modificarValorPresupuesto(edrop){

            // obtener texto del li
            let texto = $(edrop).text();
            // setear el input de la descripcion
            $(txtContenedorGlobal).val(texto);

            // agregar el id al atributo del input descripcion
            $(txtContenedorGlobal).attr('data-infopresupuesto', edrop.id);

            //$(txtContenedorGlobal).data("info");
        }

        function preguntaGuardarPresupuesto(){
            colorBlancoTablaPresupuesto();

            Swal.fire({
                title: 'Guardar Partida',
                text: "",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    verificarPresupuesto();
                }
            })
        }

        function colorBlancoTablaPresupuesto(){
            $("#matriz-presupuesto tbody tr").css('background', 'white');
        }

        // cambio de color de fila tabla a rojo
        function colorRojoTablaPresupuesto(index){
            $("#matriz-presupuesto tr:eq("+(index+1)+")").css('background', '#F1948A');
        }

        // verificar para guardar una Partida con su partida detalle
        function verificarPresupuesto(){

            var cantidadPartida = document.getElementById('cantidad-partida-nuevo').value; // decimal
            var nombrePartida = document.getElementById('nombre-partida-nuevo').value; // 600 caracteres
            var tipopartida = document.getElementById('select-partida-nuevo').value;

            var reglaNumeroDosDecimal = /^([0-9]+\.?[0-9]{0,2})$/;

            if(cantidadPartida.length > 50){
                toastr.error('Cantidad Partida debe tener máximo 50 caracteres');
                return;
            }

            if(nombrePartida === ''){
                toastr.error('Partida es requerida');
                return;
            }

            if(nombrePartida.length > 600){
                toastr.error('Partida debe tener máximo 600 caracteres');
                return;
            }

            var nRegistro = $('#matriz-presupuesto > tbody >tr').length;
            let formData = new FormData();
            var id = {{ $id }}; // id proyecto

            if (nRegistro <= 0){
                toastr.error('Detalles Partida son requeridos');
                return;
            }

            var cantidad = $("input[name='cantidadPresupuestoArray[]']").map(function(){return $(this).val();}).get();
            var descripcionAtributo = $("input[name='descripcionPresupuestoArray[]']").map(function(){return $(this).attr("data-infopresupuesto");}).get();
            var duplicado = $("input[name='duplicarPresupuestoArray[]']").map(function(){return $(this).val();}).get();

                for(var a = 0; a < cantidad.length; a++){

                    let detalle = descripcionAtributo[a];
                    let datoCantidad = cantidad[a];

                    // identifica si el 0 es tipo number o texto
                    // ESTO IDENTIFICA EL MATERIAL ID
                    if(detalle == 0){
                        colorRojoTablaPresupuesto(a);
                        alertaMensaje('info', 'No encontrado', 'En la Fila #' + (a+1) + " El material no se encuentra. Por favor buscar de nuevo el Material. Recordar que debe hacer clic en el Material para Seleccionarlo");
                        return;
                    }

                    if (datoCantidad === '') {
                        colorRojoTablaPresupuesto(a);
                        toastr.error('Fila #' + (a + 1) + ' Cantidad es requerida');
                        return;
                    }

                    if (!datoCantidad.match(reglaNumeroDosDecimal)) {
                        colorRojoTablaPresupuesto(a);
                        toastr.error('Fila #' + (a + 1) + ' Cantidad debe ser decimal Positivo. Solo se permite 2 Decimales');
                        return;
                    }

                    if (datoCantidad <= 0) {
                        colorRojoTablaPresupuesto(a);
                        toastr.error('Fila #' + (a + 1) + ' Cantidad no debe ser negativo o cero');
                        return;
                    }

                    if (datoCantidad > 99000000) {
                        colorRojoTablaPresupuesto(a);
                        toastr.error('Fila #' + (a + 1) + ' Cantidad no puede superar 9 millones');
                        return;
                    }
                }

                // LA DESCRIPCIÓN NO ES NECESARIA VALIDAR, YA QUE SE VALIDA QUE LLEVE EL ID LA FILA

                var reglaNumeroEntero = /^[0-9]\d*$/;

                // verificar duplicado
                for(var d = 0; d < duplicado.length; d++){

                    let datoDuplicado = duplicado[d];

                    if(datoDuplicado === ''){
                        colorRojoTablaPresupuesto(d);
                        toastr.error('Fila #' + (d+1) + ' Multiplicar debe ser 0 como mínimo');
                        return;
                    }

                    if(!datoDuplicado.match(reglaNumeroEntero)) {
                        colorRojoTablaPresupuesto(d);
                        toastr.error('Fila #' + (d+1) + ' Multiplicar debe ser número Entero y no Negativo');
                        return;
                    }

                    if(datoDuplicado < 0){
                        colorRojoTablaPresupuesto(d);
                        toastr.error('Fila #' + (d+1) + ' Multiplicar no debe ser negativo');
                        return;
                    }

                    if(datoDuplicado > 999){
                        colorRojoTablaPresupuesto(d);
                        toastr.error('Fila #' + (d+1) + ' Multiplicar no debe superar Número 999');
                        return;
                    }
                }

                // como tienen la misma cantidad de filas, podemos recorrer
                // todas las filas de una vez
                for(var p = 0; p < cantidad.length; p++){
                    formData.append('cantidad[]', cantidad[p]);
                    formData.append('datainfo[]', descripcionAtributo[p]);
                    formData.append('duplicado[]', duplicado[p]);
                }

            openLoading();

            formData.append('cantidadpartida', cantidadPartida);
            formData.append('nombrepartida', nombrePartida);
            formData.append('id', id);
            formData.append('tipopartida', tipopartida);

            axios.post(url+'/proyecto/agregar/presupuesto', formData, {
            })
                .then((response) => {
                    closeLoading();
                    $('#modalAgregarPresupuesto').modal('hide');

                    if(response.data.success === 1) {

                        Swal.fire({
                            title: 'En Revisión',
                            text: "El presupuesto esta en modo Revisión",
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            allowOutsideClick: false,
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        })
                    }

                    else if(response.data.success === 2) {

                        Swal.fire({
                            title: 'No Guardado',
                            text: "El presupuesto ya había sido Aprobado",
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            allowOutsideClick: false,
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        })
                    }

                    else if(response.data.success === 3){

                        toastr.success('Registrado correctamente');

                        window.contadorGlobal = response.data.contador;

                        recargarPresupuesto(); // recarga la tabla
                        limpiarPresupuesto(); // limpia la tabla
                    }
                    else if(response.data.success === 4){

                        // solo formulador puede agregar partida

                        Swal.fire({
                            title: 'No Puede Crear',
                            html: 'Solo el Usuario Asignado como Formulador puede crear Partida al proyecto',
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })

                    }
                    else{
                        toastr.error('error al crear presupuesto');
                    }
                })
                .catch((error) => {

                    toastr.error('error al crear presupuesto');
                    closeLoading();
                });
        }

        function recargarPresupuesto(){
            var id = {{ $id }};
            var rutaP = "{{ URL::to('/admin/proyecto/vista/presupuesto') }}/" + id;
            $('#tablaDatatablePresupuesto').load(rutaP);
        }

        function limpiarPresupuesto(){
            $("#matriz-presupuesto tbody tr").remove();
        }

        function informacionPresupuesto(dato){
            // habilitar boton
            let id = dato.id;
            let numero = dato.item;

            openLoading();
            document.getElementById("formulario-presupuesto-editar").reset();
            $("#matriz-presupuesto-editar tbody tr").remove();

            axios.post(url+'/proyecto/vista/presupuesto/informacion', {
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){

                        $('#id-partida-editar').val(response.data.info.id);
                        $('#cantidad-partida-editar').val(response.data.info.cantidadp);
                        $('#nombre-partida-editar').val(response.data.info.nombre);

                        $('#conteo-partida-editar').val(numero);

                        document.getElementById("select-partida-editar").value = response.data.info.id_tipopartida;

                        var infodetalle = response.data.detalle;

                        for (var i = 0; i < infodetalle.length; i++) {

                            var markup = "<tr id='" + infodetalle[i].id + "'>" +

                            "<td>" +
                            "<p id='fila" + (i + 1) + "' class='form-control' style='max-width: 65px'>" + (i + 1) + "</p>" +
                            "</td> " +

                            "<td>" +
                            "<input name='cantidadPresupuestoEditar[]' value='" + infodetalle[i].cantidad + "' maxlength='10' class='form-control' type='number'>" +
                            "</td> " +

                                "<td>" +
                            "<input name='descripcionPresupuestoEditar[]' disabled class='form-control' data-infopresupuestoeditar='" + infodetalle[i].material_id + "' value='" + infodetalle[i].descripcion + "' style='width:100%' type='text'>" +
                            "<div class='dropListaPresupuestoEditar' style='position: absolute; z-index: 9; width: 75% !important;'></div>" +
                            "</td>" +

                            "<td>" +
                            "<input name='duplicarPresupuestoEditarArray[]' maxlength='3' value='" + infodetalle[i].duplicado + "' class='form-control' type='number'>" +
                            "</td>";

                            // PRESUPUESTO EN DESARROLLO
                            if(response.data.estado === 0){
                                markup += "<td>" +
                                    "<button type='button' class='btn btn-block btn-danger' onclick='borrarFilaPresupuestoEditar(this)'>Borrar</button>" +
                                    "</td>" +

                                "</tr>";
                            }else{
                                markup += "<td>" +"</tr>";
                            }

                            $("#matriz-presupuesto-editar tbody").append(markup);
                        }

                        $('#modalEditarPresupuesto').css('overflow-y', 'auto');
                        $('#modalEditarPresupuesto').modal({backdrop: 'static', keyboard: false})
                    }
                    else{
                        toastr.error('error buscar información');
                    }
                })
                .catch((error) => {
                    toastr.error('error buscar información');
                    closeLoading();
                });
        }

        function borrarFilaPresupuestoEditar(elemento){
            var tabla = elemento.parentNode.parentNode;
            tabla.parentNode.removeChild(tabla);
            setearFilaPresupuestoEditar();
        }

        // cambiar # de fila cada vez que se borra la fila de
        // tabla nuevo material
        function setearFilaPresupuestoEditar(){

            var table = document.getElementById('matriz-presupuesto-editar');
            var conteo = 0;
            for (var r = 1, n = table.rows.length; r < n; r++) {
                conteo +=1;
                var element = table.rows[r].cells[0].children[0];
                document.getElementById(element.id).innerHTML = ""+conteo;
            }
        }

        function addAgregarFilaPresupuestoEditar(){

            var nFilas = $('#matriz-presupuesto-editar >tbody >tr').length;
            nFilas += 1;

            // APORTE MANO DE OBRA no lleva cantidad
                var markup = "<tr>"+

                    "<td>"+
                    "<p id='fila"+(nFilas)+"' class='form-control' style='max-width: 65px'>"+(nFilas)+"</p>"+
                    "</td>" +
                    "<td>"+

                    "<input name='cantidadPresupuestoEditar[]' maxlength='10' class='form-control' type='number'>"+
                    "</td> " +

                    "<td>"+
                    "<input name='descripcionPresupuestoEditar[]' autocomplete='off' data-infopresupuestoeditar='0' class='form-control' style='width:100%' onkeyup='buscarMaterialPresupuestoEditar(this)' maxlength='400'  type='text'>"+
                    "<div class='dropListaPresupuestoEditar' style='position: absolute; z-index: 9;'></div>"+
                    "</td>"+

                    "<td>"+
                    "<input name='duplicarPresupuestoEditarArray[]' maxlength='3' value='0' class='form-control' type='number'>"+
                    "</td>"+

                    "<td>"+
                    "<button type='button' class='btn btn-block btn-danger' onclick='borrarFilaPresupuestoEditar(this)'>Borrar</button>"+
                    "</td>"+

                    "</tr>";
                $("#matriz-presupuesto-editar tbody").append(markup);

        }

        function buscarMaterialPresupuestoEditar(e){

            // seguro para evitar errores de busqueda continua
            if(seguroBuscador){
                seguroBuscador = false;

                var row = $(e).closest('tr');
                txtContenedorGlobal = e;

                let texto = e.value;

                if(texto === ''){
                    // si se limpia el input, setear el atributo id
                    $(e).attr('data-infopresupuestoeditar', 0);
                }

                axios.post(url+'/proyecto/buscar/material-presupuesto-editar', {
                    'query' : texto
                })
                    .then((response) => {
                        seguroBuscador = true;
                        $(row).each(function (index, element) {
                            $(this).find(".droplistaPresupuestoEditar").fadeIn();
                            $(this).find(".droplistaPresupuestoEditar").html(response.data);
                        });
                    })
                    .catch((error) => {
                        seguroBuscador = true;
                    });
            }
        }

        function modificarValorPresupuestoEditar(edrop){

            // obtener texto del li
            let texto = $(edrop).text();
            // setear el input de la descripcion
            $(txtContenedorGlobal).val(texto);

            // agregar el id al atributo del input descripcion
            $(txtContenedorGlobal).attr('data-infopresupuestoeditar', edrop.id);
            //$(txtContenedorGlobal).data("info");
        }

        function preguntaEditarPresupuestoEditar(){
            colorBlancoTablaPresupuestoEditar();

            Swal.fire({
                title: 'Editar Partida Presupuesto',
                text: "",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    verificarPresupuestoEditado();
                }
            })
        }

        function infoBorrar(id){
            // borrar el presupuesto
            Swal.fire({
                title: 'Borrar Presupuesto',
                text: "",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Borrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    borrarPresupuesto(id);
                }
            })
        }

        function borrarPresupuesto(id){

            openLoading();

            axios.post(url+'/proyecto/vista/presupuesto/borrar', {
                'id' : id
            })
                .then((response) => {
                    closeLoading();

                    // el presupuesto esta en revisión
                    if(response.data.success === 1){
                        Swal.fire({
                            title: 'Error al Borrar',
                            text: "El Presupuesto esta en Revisión",
                            icon: 'info',
                            showCancelButton: false,
                            allowOutsideClick: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        })
                    }
                    else if(response.data.success === 2){
                        Swal.fire({
                            title: 'Error al Borrar',
                            text: "El Presupuesto ya fue Aprobado",
                            icon: 'info',
                            showCancelButton: false,
                            allowOutsideClick: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        })
                    }
                    else if(response.data.success === 3){
                        toastr.success('Borrado correctamente');
                        window.contadorGlobal = response.data.contador;
                        recargarPresupuesto();
                    }
                    else if(response.data.success === 4){

                        Swal.fire({
                            title: 'No Puede Modificar',
                            html: 'Solo el Usuario Asignado como Formulador puede modificar el proyecto',
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })
                    }

                    else{
                        toastr.error('error al buscar');
                    }
                })
                .catch((error) => {
                    toastr.error('error al buscar');
                    closeLoading();
                });
        }

        function verificarPresupuestoEditado(){

            var tipopartida = document.getElementById('select-partida-editar').value;
            var idpartida = document.getElementById('id-partida-editar').value;
            var cantidadPartida = document.getElementById('cantidad-partida-editar').value; // decimal
            var nombre = document.getElementById('nombre-partida-editar').value; // 300 caracteres
            var reglaNumeroDosDecimal = /^([0-9]+\.?[0-9]{0,2})$/;

            if(cantidadPartida.length > 50){
                toastr.error('Cantidad Partida debe tener máximo 50 caracteres');
                return;
            }

            if(nombre === ''){
                toastr.error('Partida es requerida');
                return;
            }

            if(nombre.length > 600){
                toastr.error('Partida debe tener máximo 600 caracteres');
                return;
            }

            var nRegistro = $('#matriz-presupuesto-editar >tbody >tr').length;
            let formData = new FormData();

            if (nRegistro < 1){
                toastr.error('Mínimo 1 Detalle Partida');
                return;
            }

            var cantidad = $("input[name='cantidadPresupuestoEditar[]']").map(function(){return $(this).val();}).get();
            var descripcion = $("input[name='descripcionPresupuestoEditar[]']").map(function(){return $(this).val();}).get();
            var descripcionAtributo = $("input[name='descripcionPresupuestoEditar[]']").map(function(){return $(this).attr("data-infopresupuestoeditar");}).get();
            var duplicado = $("input[name='duplicarPresupuestoEditarArray[]']").map(function(){return $(this).val();}).get();

            for(let a = 0; a < cantidad.length; a++){
                let detalle = descripcionAtributo[a];
                let datoCantidad = cantidad[a];

                // identifica si el 0 es tipo number o texto
                if(detalle == 0){
                    colorRojoTablaPresupuestoEditar(a);
                    alertaMensaje('info', 'No encontrado', 'En la Fila #' + (a+1) + " El material no se encuentra. Por favor buscar de nuevo el Material. Recordar que debe hacer clic en el Material para Seleccionarlo");
                    return;
                }

                if(tipopartida != '4'){

                    if(datoCantidad === ''){
                        colorRojoTablaPresupuestoEditar(a);
                        toastr.error('Fila #' + (a+1) + ' Cantidad es requerida');
                        return;
                    }

                    if(!datoCantidad.match(reglaNumeroDosDecimal)) {
                        colorRojoTablaPresupuestoEditar(a);
                        toastr.error('Fila #' + (a+1) + ' Cantidad debe ser Decimal Positivo. Solo se permite 2 Decimales');
                        return;
                    }

                    if(datoCantidad <= 0){
                        colorRojoTablaPresupuestoEditar(a);
                        toastr.error('Fila #' + (a+1) + ' Cantidad no debe ser negativo');
                        return;
                    }

                    if(datoCantidad > 99000000){
                        colorRojoTablaPresupuestoEditar(a);
                        toastr.error('Fila #' + (a+1) + ' Cantidad máximo 9 millones');
                        return;
                    }
                }
            }

                for(let b = 0; b < descripcion.length; b++){

                    let datoDescripcion = descripcion[b];

                    if(datoDescripcion === ''){
                        colorRojoTablaPresupuestoEditar(b);
                        toastr.error('Fila #' + (b+1) + ' la descripción es requerida');
                        return;
                    }

                    // MATERIAL CARACTERES NO ES NECESARIO VALIDAR, YA QUE NO SE ENVÍA
                }

                let reglaNumeroEntero = /^[0-9]\d*$/;

                // verificar duplicado
                for(let d = 0; d < duplicado.length; d++){

                    let datoDuplicado = duplicado[d];

                    if(datoDuplicado === ''){
                        colorRojoTablaPresupuestoEditar(d);
                        toastr.error('Fila #' + (d+1) + ' Multiplicar debe ser 0 como mínimo');
                        return;
                    }

                    if(!datoDuplicado.match(reglaNumeroEntero)) {
                        colorRojoTablaPresupuestoEditar(d);
                        toastr.error('Fila #' + (d+1) + ' Multiplicar debe ser número Entero y no Negativo');
                        return;
                    }

                    if(datoDuplicado < 0){
                        colorRojoTablaPresupuestoEditar(d);
                        toastr.error('Fila #' + (d+1) + ' Multiplicar no debe ser negativo');
                        return;
                    }

                    if(datoDuplicado > 999){
                        colorRojoTablaPresupuestoEditar(d);
                        toastr.error('Fila #' + (d+1) + ' Multiplicar máximo Número 999');
                        return;
                    }
                }

                // como tienen la misma cantidad de filas, podemos recorrer
                // todas las filas de una vez
                for(var p = 0; p < cantidad.length; p++){
                    // obtener el id de la fila, si el id fila es 0, significa que sera nuevo registro
                    var id = $("#matriz-presupuesto-editar tr:eq("+(p+1)+")").attr('id');
                    formData.append('idarray[]', id);
                    formData.append('datainfo[]', descripcionAtributo[p]);

                    if(tipopartida == '4'){
                        formData.append('cantidad[]', 0);
                    }else{
                        formData.append('cantidad[]', cantidad[p]);
                    }

                    formData.append('duplicado[]', duplicado[p]);
                }

            openLoading();
            formData.append('cantidadpartida', cantidadPartida);
            formData.append('nombrepartida', nombre);
            formData.append('idpartida', idpartida);
            formData.append('tipopartida', tipopartida);

            axios.post(url+'/proyecto/vista/presupuesto/editar', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){

                        Swal.fire({
                            title: 'No Actualizado',
                            text: "El Presupuesto esta en modo revisión",
                            icon: 'info',
                            showCancelButton: false,
                            allowOutsideClick: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        })
                    }
                    else if(response.data.success === 2){
                        Swal.fire({
                            title: 'No Actualizado',
                            text: "El Presupuesto ya fue Aprobado",
                            icon: 'info',
                            showCancelButton: false,
                            allowOutsideClick: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        })
                    }
                    else if(response.data.success === 3){
                        toastr.success('Actualizado correctamente');
                        recargarPresupuesto();
                        $('#modalEditarPresupuesto').modal('hide');
                    }
                    else if(response.data.success === 4) {

                        // solo formulador puede editar

                        Swal.fire({
                            title: 'No Puede Modificar',
                            html: 'Solo el Usuario Asignado como Formulador puede modificar el proyecto',
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })
                    }
                    else{
                        toastr.error('Error al actualizar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al actualizar');
                    closeLoading();
                });
        }

        function colorBlancoTablaPresupuestoEditar(){
            $("#matriz-presupuesto-editar tbody tr").css('background', 'white');
        }

        // cambio de color de fila tabla a rojo
        function colorRojoTablaPresupuestoEditar(index){
            $("#matriz-presupuesto-editar tr:eq("+(index+1)+")").css('background', '#F1948A');
        }

        function modalGenerarPresupuesto(){

            // verificar primero si se ha creado la partida de mano de obra
            openLoading();

            var idproyecto = {{ $id }};

            axios.post(url+'/proyecto/partida/manoobra/existe', {
                'id' : idproyecto
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        window.open("{{ URL::to('admin/generar/pdf/presupuesto') }}/"+idproyecto);
                    }
                    else if(response.data.success === 2){
                        Swal.fire({
                            title: 'Partida Requerida',
                            text: "Se debe crear la Partida para Mano de Obra (por Administración)",
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })
                    }
                    else{
                        toastr.error('error al buscar');
                    }
                })
                .catch((error) => {
                    toastr.error('error al buscar');
                    closeLoading();
                });
        }


        // cambiar estado de presupuesto ingenieria para ser aprobado
        function cambiarEstado(){
            openLoading();
            let estado = document.getElementById('select-estado').value;
            let id = {{ $id }};

            let formData = new FormData();
            formData.append('estado', estado);
            formData.append('id', id);

            axios.post(url+'/proyecto/estado/presupuesto', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        document.getElementById('select-estado').value = '0';
                        Swal.fire({
                            title: 'Partida Requerida',
                            text: "Se debe crear la Partida para Mano de Obra (por Administración) para cambiar de Estado",
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })
                    }

                    else if(response.data.success === 2){

                        Swal.fire({
                            title: 'Presupuesto Ya Aprobado',
                            text: "El presupuesto ya se encontraba Aprobado",
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        })
                    }
                    else if(response.data.success === 3){
                        Swal.fire({
                            title: 'Estado Actualizado',
                            text: "",
                            icon: 'info',
                            showCancelButton: false,
                            allowOutsideClick: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        })
                    }

                    else{
                        toastr.error('error al actualizar');
                    }
                })
                .catch((error) => {
                    toastr.error('error al actualizar');
                    closeLoading();
                });
        }

        // ver catalogo de materiales por parte de quien hace requisiciones
        function vistaCatalogoMaterial(){

            let id = {{ $id }}; // id proyecto
            var ruta = "{{ URL::to('/admin/ver/materiales/admin/requisicion') }}/" + id;
            $('#tablaCatalogoMaterial').load(ruta);
            $('#modalCatalogoMaterial').modal('show');
        }

        // materiales de todas las partidas adicionales aprobadas
        function vistaCatalogoMaterialPartidaAdic(){
            let id = {{ $id }}; // id proyecto
            var ruta = "{{ URL::to('/admin/ver/materiales/partida/adicional') }}/" + id;
            $('#tablaCatalogoMaterial').load(ruta);
            $('#modalCatalogoMaterial').modal('show');
        }

        // preguntar si quiere borrar una requisicion, solo aparece el boton, sino ha sido
        // cotizado ningun de sus materiales
        function modalBorrarRequisicion(id){
            Swal.fire({
                title: 'Borrar Requisición',
                text: "",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    borrarRequisicion(id);
                }
            })
        }

        function borrarRequisicion(id){

            openLoading();
            let formData = new FormData();
            formData.append('id', id);

            axios.post(url+'/proyecto/requisicion/borrar/todo', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        Swal.fire({
                            title: 'Ya hay Cotización',
                            text: "Uno o todos los materiales ya tiene una cotización en proceso",
                            icon: 'info',
                            showCancelButton: false,
                            allowOutsideClick: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // quitara el boton borrar de la requisición
                                recargarRequisicion();
                            }
                        })
                    }
                    else if(response.data.success === 2){
                     // cotización borrada
                        toastr.success('Cotización Borrada');
                        recargarRequisicion();
                    }
                    else if(response.data.success === 3) {

                        let mensaje = response.data.mensaje;

                        Swal.fire({
                            title: 'Estado Proyecto',
                            html: mensaje,
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })
                    }
                    else{
                        toastr.error('error al borrar');
                    }
                })
                .catch((error) => {
                    toastr.error('error al borrar');
                    closeLoading();
                });
        }

        // abrir modal para cambiar imprevisto para presupuesto proyecto
        function modalImprevisto(){

            openLoading();

            axios.post(url+'/proyecto/buscar/imprevisto', {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1) {
                        $('#imprevisto-editar').val(response.data.imprevisto);
                        $('#herramienta-editar').val(response.data.herramienta);
                        $('#modalImprevisto').modal('show');
                    }
                    else{
                        toastr.error('Error al buscar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al borrar');
                    closeLoading();
                });
        }


        function editarImprevisto(){

            let imprevisto = document.getElementById('imprevisto-editar').value;
            let herramienta = document.getElementById('herramienta-editar').value;

            var reglaNumeroDosDecimal = /^([0-9]+\.?[0-9]{0,2})$/;

            if(imprevisto === ''){
                toastr.error('Imprevisto es requerido');
                return;
            }

            if(!imprevisto.match(reglaNumeroDosDecimal)) {
                toastr.error('Imprevisto debe ser número Decimal y no Negativo, solo se permite 2 decimales');
                return;
            }

            if(imprevisto < 0){
                toastr.error('Imprevisto no debe ser negativo');
                return;
            }

            if(imprevisto > 50){
                toastr.error('Imprevisto no puede ser mayor a 50%');
                return;
            }

            //*******

            if(herramienta === ''){
                toastr.error('Herramienta Porcentaje es requerido');
                return;
            }

            if(!herramienta.match(reglaNumeroDosDecimal)) {
                toastr.error('Herramienta Porcentaje debe ser número Decimal y no Negativo, solo se permite 2 decimales');
                return;
            }

            if(herramienta < 0){
                toastr.error('Herramienta Porcentaje no debe ser negativo');
                return;
            }

            if(herramienta > 50){
                toastr.error('Herramienta Porcentaje no puede ser mayor a 50%');
                return;
            }

            openLoading();

            let formData = new FormData();
            formData.append('imprevisto', imprevisto);
            formData.append('herramienta', herramienta);

            axios.post(url+'/proyecto/editar/imprevisto', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        toastr.success('Actualizado correctamente');
                        $('#modalImprevisto').modal('hide');
                    }
                    else{
                        toastr.error('Error al buscar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al borrar');
                    closeLoading();
                });
        }

        // cuando se busca un material en requisición y se hace clic en material se modifica el valor
        function modificarValorRequisicion(edrop){

            // obtener texto del li
            let texto = $(edrop).text();
            // setear el input de la descripcion
            $(txtContenedorGlobal).val(texto);

            // agregar el id al atributo del input descripcion
            $(txtContenedorGlobal).attr('data-info', edrop.id);
            //$(txtContenedorGlobal).data("info");
        }


        //*************** OTROS *******************


        function alertaEstado(titulo, mensaje){
            Swal.fire({
                title: titulo,
                text: mensaje,
                icon: 'info',
                showCancelButton: false,
                confirmButtonColor: '#707070',
                confirmButtonText: 'Aceptar',
            }).then((result) => {
                if (result.isConfirmed) {

                }
            })
        }


        function vistaPartidaAdicional(){
            var id = {{ $id }}; // id proyecto
            window.location.href = "{{ url('/admin/partida/adicional/contenedor/index') }}/" + id;
        }


    </script>


@endsection
