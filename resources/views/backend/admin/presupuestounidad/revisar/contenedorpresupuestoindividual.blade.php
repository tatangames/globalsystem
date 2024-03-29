<link href="{{ asset('css/cssacordeon.css') }}" type="text/css" rel="stylesheet" />


<section class="col-12" id="bloquecontenedor" style="display: none">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <form class="form-vertical">
                        <div style="margin-left: 20px">
                            <label style="color: darkgreen; font-size: 20px; font-family: arial">Total:  ${{$totalvalor}}</label>
                        </div>

                        <div class="col-12">
                            <!-- Custom Tabs -->
                            <div class="card">
                                <div class="card-header d-flex p-0">
                                    <h3 class="card-title p-3"></h3>
                                    <ul class="nav nav-pills ml-auto p-2">
                                        <button type="button" onclick="recargar()" class="btn btn-success"  style="margin-right: 25px"><i class="fa fa-redo-alt"></i> Recargar</button>
                                        <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Base Presupuesto</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Nuevos Materiales</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Proyectos</a></li>
                                    </ul>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_1">

                                            <!-- inicio -->
                                            <div>

                                                <form>
                                                    <div class="card-body">


                                                        @foreach($rubro as $item)

                                                            <div class="accordion-group" data-behavior="accordion">

                                                                <label class="accordion-header" style="background-color: #c5c6c8; color: black !important;">{{ $item->codigo }} - {{ $item->nombre }}  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  ${{ $item->sumarubro }}</label>

                                                                <!-- foreach para cuenta -->
                                                                <div class="accordion-body">

                                                                    @foreach($item->cuenta as $cc)

                                                                        <div class="accordion-group" data-behavior="accordion" data-multiple="true">
                                                                            <p class="accordion-header" style="background-color: #b0c2f2; color: black !important;">{{ $cc->codigo }} - {{ $cc->nombre }}  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  ${{ $cc->sumaobjetototal }}</p>

                                                                            <div class="accordion-body">
                                                                                <div class="accordion-group" data-behavior="accordion" data-multiple="true">

                                                                                    <!-- foreach para objetos -->
                                                                                    @foreach($cc->objeto as $obj)

                                                                                        <p class="accordion-header" style="background-color: #b0f2c2; color: black !important;">{{ $obj->codigo }} | {{ $obj->nombre }}  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  ${{ $obj->sumaobjeto }}</p>
                                                                                        <div class="accordion-body">

                                                                                            <table data-toggle="table">
                                                                                                <thead>
                                                                                                <tr>
                                                                                                    <th style="width: 30%; text-align: center">Descripción</th>
                                                                                                    <th style="width: 20%; text-align: center">U/M</th>
                                                                                                    <th style="width: 15%; text-align: center">Costo ($)</th>
                                                                                                    <th style="width: 10%; text-align: center">Unidades</th>
                                                                                                    <th style="width: 10%; text-align: center">Periodo</th>
                                                                                                    <th style="width: 10%; text-align: center">Total</th>

                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>

                                                                                                <!-- foreach para material -->

                                                                                                @foreach($obj->material as $mm)

                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <input type="hidden" name="idmaterial[]" value='{{ $mm->id }}'>
                                                                                                            <input value="{{ $mm->descripcion }}" disabled class="form-control" type="text">
                                                                                                        </td>
                                                                                                        <td><input value="{{ $mm->unimedida }}" disabled class="form-control"  type="text"></td>
                                                                                                        <td><input value="${{ $mm->precio }}" disabled class="form-control" style="max-width: 170px" ></td>
                                                                                                        <td><input value="{{ $mm->cantidad }}" disabled name="unidades[]" class="form-control" type="number" onchange="multiplicar(this)" maxlength="6"  style="max-width: 180px" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"></td>
                                                                                                        <td><input value="{{ $mm->periodo }}" disabled name="periodo[]" class="form-control" min="1" type="number" onchange="multiplicar(this)" maxlength="6"  style="max-width: 180px" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"></td>
                                                                                                        <td><input value="${{ $mm->total }}" disabled name="total[]" class="form-control" type="text" style="max-width: 180px"></td>
                                                                                                    </tr>

                                                                                                    <!-- fin foreach material -->
                                                                                                @endforeach

                                                                                                <!-- AGREGAR EL PROYECTO APROBADO -->

                                                                                                @foreach($listadoProyectoAprobados as $lpa)

                                                                                                    @if($obj->codigo == $lpa->codigoobj)

                                                                                                        <tr style="background-color: #FAD7A0;">
                                                                                                            <td>
                                                                                                                <input style="background-color: #FAD7A0; color: black; font-weight: bold" value="{{ $lpa->descripcion }}" disabled class="form-control" type="text">
                                                                                                            </td>
                                                                                                            <td><input style="background-color: #FAD7A0; color: black; font-weight: bold" value="" disabled class="form-control" type="text"></td>
                                                                                                            <td><input style="background-color: #FAD7A0; color: black; font-weight: bold; max-width: 170px" type="text" value="{{ $lpa->costoFormat }}" disabled class="form-control"></td>
                                                                                                            <td><input style="background-color: #FAD7A0; color: black; font-weight: bold; max-width: 180px" type="text" value="" disabled class="form-control"></td>
                                                                                                            <td><input style="background-color: #FAD7A0; color: black; font-weight: bold; max-width: 180px" type="text" value="" disabled class="form-control"></td>
                                                                                                            <td><input style="background-color: #FAD7A0; color: black; font-weight: bold; max-width: 180px" type="text" value="{{ $lpa->costoFormat }}" disabled class="form-control"></td>
                                                                                                        </tr>

                                                                                                    @endif

                                                                                                @endforeach

                                                                                                </tbody>

                                                                                            </table>

                                                                                        </div>

                                                                                @endforeach
                                                                                <!-- finaliza foreach para objetos-->

                                                                                </div>
                                                                            </div>


                                                                        </div>

                                                                @endforeach
                                                                <!-- fin foreach para cuenta -->
                                                                </div>
                                                            </div>

                                                            @if($loop->last)
                                                                <script>
                                                                    setTimeout(function () {
                                                                        mostrarContenedor();
                                                                        closeLoading();
                                                                    }, 1000);
                                                                </script>
                                                        @endif

                                                    @endforeach
                                                    <!-- fin foreach para rubro -->

                                                    </div>
                                                </form>
                                            </div>
                                        </div>


                                        <!-- LISTA DE NUEVOS MATERIALES - TABS 2 -->
                                        <div class="tab-pane" id="tab_2">

                                            <form>
                                                <div class="card-body">
                                                    <table class="table" id="matrizMateriales" data-toggle="table">
                                                        <thead>
                                                        <tr>
                                                            <th style="width: 30%; text-align: center">Descripción</th>
                                                            <th style="width: 12%; text-align: center">U/M</th>
                                                            <th style="width: 14%; text-align: center">Costo ($)</th>
                                                            <th style="width: 14%; text-align: center">Cantidad</th>
                                                            <th style="width: 9%; text-align: center">Periodo</th>

                                                            <th style="width: 10%; text-align: center">Opciones</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @foreach($listado as $ll)

                                                            <tr id="{{ $ll->id }}">
                                                                <td><input disabled value="{{ $ll->descripcion }}" class="form-control" ></td>
                                                                <td><input disabled value="{{ $ll->simbolo }}" data-unidadmedia="{{ $ll->id_unidadmedida }}"  class="form-control" ></td>
                                                                <td><input disabled value="{{ $ll->costo }}" class="form-control" ></td>
                                                                <td><input disabled value="{{ $ll->cantidad }}" class="form-control" ></td>
                                                                <td><input disabled value="{{ $ll->periodo }}" class="form-control" ></td>

                                                                <td><button type="button" class="btn btn-block btn-success" onclick="verificarTransferirMaterial(this)">Transferir</button></td>
                                                            </tr>

                                                        @endforeach

                                                        </tbody>

                                                    </table>

                                                </div>

                                            </form>

                                        </div>


                                        <!-- LISTA DE NUEVOS PROYECTOS - TABS 3 -->
                                        <div class="tab-pane" id="tab_3">

                                            <form>
                                                <div class="card-body">
                                                    <h3>Proyectos Pendientes</h3>
                                                    <table class="table" id="matrizProyectosPendientes" style="border: 80px" data-toggle="table">
                                                        <thead>
                                                        <tr>
                                                            <th style="width: 30%; text-align: center">Descripción</th>
                                                            <th style="width: 15%; text-align: center">Costo</th>

                                                            <th style="width: 10%; text-align: center">Opciones</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @foreach($listadoProyecto as $lp)

                                                            <tr id="{{ $lp->id }}">
                                                                <td style="width: 30%">
                                                                    <input  disabled value="{{ $lp->descripcion }}" class="form-control" type="text">
                                                                </td>
                                                                <td style="width: 15%;"><input disabled value="{{ $lp->costo }}" class="form-control" type="number"></td>
                                                                <td>
                                                                    <input value="{{ $lp->id }}" type="hidden">
                                                                    <button type="button" class="btn btn-block btn-success" onclick="transferirProyecto(this)">Transferir</button>
                                                                </td>
                                                            </tr>

                                                        @endforeach

                                                        </tbody>
                                                    </table>


                                                </div>

                                            </form>

                                            <!-- MOSTRAR PROYECTOS APROBADOS - TABS 3 -->

                                            <br>
                                            <hr>
                                            <form>
                                                <div class="card-body">
                                                    <h3>Proyectos Aprobados</h3>
                                                    <table class="table" id="matrizProyectosAprobados" style="border: 80px" data-toggle="table">
                                                        <thead>
                                                        <tr>
                                                            <th style="width: 15%; text-align: center">Descripción</th>
                                                            <th style="width: 10%; text-align: center">Costo</th>
                                                            <th style="width: 12%; text-align: center">Obj. Específico</th>
                                                            <th style="width: 12%; text-align: center">Fuente Recursos</th>
                                                            <th style="width: 12%; text-align: center">Línea Trabajo</th>
                                                            <th style="width: 12%; text-align: center">Área Gestión</th>
                                                            <th style="width: 12%; text-align: center">Opción</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @foreach($listadoProyectoAprobados as $lp)

                                                            <tr>
                                                                <td><input disabled value="{{ $lp->descripcion }}" class="form-control"></td>
                                                                <td><input disabled value="{{ $lp->costoFormat }}" class="form-control"></td>
                                                                <td><input disabled value="{{ $lp->objeto }}" class="form-control"></td>
                                                                <td><input disabled value="{{ $lp->fuenterecurso }}" class="form-control"></td>
                                                                <td><input disabled value="{{ $lp->lineatrabajo }}" class="form-control"></td>
                                                                <td><input disabled value="{{ $lp->areagestion }}" class="form-control"></td>
                                                                <td>
                                                                    @if($idestado != 3)
                                                                        <button type="button" class="btn btn-warning" style="color: white" onclick="infoFilaEditar({{ $lp->id }})">Editar</button>
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                        @endforeach

                                                        </tbody>
                                                    </table>


                                                </div>

                                            </form>


                                        </div>



                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>



<script src="{{ asset('js/jquery.simpleaccordion.js') }}"></script>

<script>

    $(document).ready(function() {

        $('.seleccion').select2({
            theme: "bootstrap-5",
            "language": {
                "noResults": function(){
                    return "Búsqueda no encontrada";
                }
            },
        });

        $('[data-behavior=accordion]').simpleAccordion({cbOpen:accOpen, cbClose:accClose});
    });


    function accClose(e, $this) {
        $this.find('span').fadeIn(200);
    }

    function accOpen(e, $this) {
        $this.find('span').fadeOut(200)
    }

    function mostrarContenedor(){
        document.getElementById("bloquecontenedor").style.display = "block";
    }

    function recargar(){
        location.reload();
    }

    function verificarTransferirMaterial(e){
        var table = e.parentNode.parentNode;

        var descripcion = table.cells[0].childNodes[0].value;
        var idunidadmedida = table.cells[1].childNodes[0].getAttribute("data-unidadmedia");
        var costo = table.cells[2].childNodes[0].value;
        var cantidad = table.cells[3].childNodes[0].value;
        var periodo = table.cells[4].childNodes[0].value;

        // id fila material para borrarla
        var idproborrar = $(e).closest('tr').attr('id');

        document.getElementById("formulario-nuevo-material").reset();

        openLoading();

        // buscar información de unidad de medida y registrar material
        // solo obtener las unidades de medida
        axios.post(url+'/p/presupuesto/obtener/unidad/medida', {
        })
            .then((response) => {

                closeLoading();

                if(response.data.success === 1) {

                    document.getElementById("select-material-unidadmedida").options.length = 0;

                    // objeto especifico
                    $.each(response.data.arrayunidad, function( key, val ){
                        if(idunidadmedida == val.id){
                            $('#select-material-unidadmedida').append('<option value="' +val.id +'" selected="selected">'+ val.nombre +'</option>');
                        }else{
                            $('#select-material-unidadmedida').append('<option value="' +val.id +'">'+ val.nombre +'</option>');
                        }
                    });


                    $('#material-descripcion-nuevo').val(descripcion);
                    $('#material-costo-nuevo').val(costo);
                    $('#material-cantidad-nuevo').val(cantidad);
                    $('#material-periodo-nuevo').val(periodo);

                    $('#material-id-aborrar').val(idproborrar);

                    $('#modalNuevoMaterial').modal('show');

                }else{
                    toastr.error('Error al buscar');
                }
            })
            .catch((error) => {
                toastr.error('Error al buscar');
                closeLoading();
            });
    }


    function transferirProyecto(e){
        var table = e.parentNode.parentNode;
        var descripcion = table.cells[0].children[0].value;
        var costo = table.cells[1].children[0].value;

        // id de proyecto para borrarlo
        var idproborrar = $(e).closest('tr').attr('id');

        document.getElementById("formulario-nuevo-proyecto").reset();
        $('#modalNuevoProyecto').modal('show');

        $('#proyecto-descripcion-nuevo').val(descripcion);
        $('#proyecto-costo-nuevo').val(costo);
        $('#proyecto-id-aborrar').val(idproborrar);
    }


    function infoFilaEditar(idproyecto){
        // tabla: P_ProyectosAprobados

        openLoading();
        document.getElementById("formulario-edit-proy").reset();

        axios.post(url+'/p/proyectos/informacion/deaprobados',{
            'id': idproyecto
        })
            .then((response) => {
                closeLoading();

                console.log(response)

                if(response.data.success === 1){
                    $('#modalEditarFilaProy').modal('show');
                    $('#id-proyecto-aprobado').val(idproyecto);
                    $('#info-descripcion-proyecto').val(response.data.info.descripcion);
                    $('#info-costo-proyecto').val(response.data.info.costo);

                }else{
                    toastr.error('Información no encontrada');
                }
            })
            .catch((error) => {
                closeLoading();
                toastr.error('Información no encontrada');
            });


    }


</script>
