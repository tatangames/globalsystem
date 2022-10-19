@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }

</style>

<div id="divcontenedor" style="display: none">

    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <label style="font-size: 16px">Fecha: {{ $fecha }}</label> <br>

                @if($infoContenedor->estado == 0)
                    <label style="font-size: 16px">Estado: <strong>Partida en Desarrollo</strong></label>
                @elseif($infoContenedor->estado == 1)
                    <label style="font-size: 16px">Estado: <strong>Partida en Revisión</strong></label>
                @else
                    <label style="font-size: 16px">Estado: <strong>Partidas Aprobadas</strong></label>
                @endif

                <br>
                <label style="font-size: 16px">Proyecto: <strong>{{ $nombreProyecto }}</strong></label>

                <br>
                <div class="col-md-8">
                    <a class="btn btn-info mt-3 float-left" href= "javascript:history.back()" target="frameprincipal">
                        <i title="Cancelar"></i> Atras </a>
                </div>

                @can('boton.crear.partida.adicional')
                    <!-- solo en modo desarrollo se puede agregar -->
                    @if($infoContenedor->estado == 0)
                        <button type="button" style="margin-left: 25px; margin-top: 15px" onclick="modalCrearPartida()" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i>
                            Nueva Partida Adicional
                        </button>
                    @endif
                @endcan

            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Partida Adicional</li>
                    <li class="breadcrumb-item active">Listado</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Listado de Partidas Adicionales</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="tablaDatatable">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- modal agregar nuevas partidas adicionales -->
    <div class="modal fade" id="modalAgregarPresupuesto" tabindex="-1">
        <div class="modal-dialog modal-xl" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Agregar Partida Adicional</h4>
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
                                        <input class="form-control" type="text" maxlength="50" id="cantidad-partida-nuevo">
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
                                        <button type="button" onclick="addAgregarFilaPresupuestoNueva()" class="btn btn-primary btn-sm float-right" style="margin-top:10px;">
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
                    <button type="button" class="btn btn-primary" onclick="preguntaGuardarPresupuesto()">Guardar</button>
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
    <script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>


    <script type="text/javascript">
        $(document).ready(function(){

            // id contenedor partida adicional
            let id = {{ $id }};
            var ruta = "{{ URL::to('/admin/partida/adicional/creacion/tabla') }}/" + id;
            $('#tablaDatatable').load(ruta);

            document.getElementById("divcontenedor").style.display = "block";

            // variable global para setear input al buscar nuevo material
            window.txtContenedorGlobal = this;
            window.seguroBuscador = true;

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


    <script>

        function recargar(){
            let id = {{ $id }};
            var ruta = "{{ URL::to('/admin/partida/adicional/creacion/tabla') }}/" + id;
            $('#tablaDatatable').load(ruta);
        }

        function modalCrearPartida(){

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
                "</td>";

            if(tipopartida == '4') {
                markup += "<td>" +
                    "<input name='cantidadPresupuestoArray[]' disabled maxlength='10' class='form-control' type='number'>" +
                    "</td>";
            }
            else{
                markup += "<td>" +
                    "<input name='cantidadPresupuestoArray[]' maxlength='10' class='form-control' type='number'>" +
                    "</td>";
            }

            markup += "<td>" +
                "<input name='descripcionPresupuestoArray[]' data-infopresupuesto='0' autocomplete='off' class='form-control' style='width:100%' onkeyup='buscarMaterialPresupuesto(this)' maxlength='400'  type='text'>" +
                "<div class='droplistaPresupuesto' style='position: absolute; z-index: 9; width: 75% !important;'></div>" +
                "</td>" +

                "<td>" +
                "<input name='duplicarPresupuestoArray[]' maxlength='3' class='form-control' value='0' type='number'>" +
                "</td>" +

                "<td>" +
                "<button type='button' class='btn btn-block btn-danger' onclick='borrarFilaPresupuestoDetalle(this)'>Borrar</button>" +
                "</td>" +

                "</tr>";

            $("#matriz-presupuesto tbody").append(markup);
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
            // setear el input de la descripción
            $(txtContenedorGlobal).val(texto);

            // agregar el id al atributo del input descripción
            $(txtContenedorGlobal).attr('data-infopresupuesto', edrop.id);

            //$(txtContenedorGlobal).data("info");
        }

        // borrar fila para tabla editar requisición material
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

                /*  1- materiales
                    2- herramientas (2% de materiales)
                    3- mano de obra (por administracion)
                    4- aporte mano de obra
                    5- alquiler de maquinaria
                    6- trasporte de concreto fresco
                */

                // unicamente no sera verificado con: APORTE PATRONAL (aporte mano de obra)

                if(tipopartida != '4') {

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

                // SOLO PARA APORTE PATRONAL SIEMPRE SERA 0
                // O APORTE MANO DE OBRA
                if(tipopartida == '4'){
                    formData.append('cantidad[]', 0);
                }else{
                    formData.append('cantidad[]', cantidad[p]);
                }

                formData.append('datainfo[]', descripcionAtributo[p]);
                formData.append('duplicado[]', duplicado[p]);
            }

            var idconte = {{ $id }}; // id partida adicional CONTENEDOR

            openLoading();

            formData.append('cantidadpartida', cantidadPartida);
            formData.append('nombrepartida', nombrePartida);
            formData.append('idcontenedor', idconte);
            formData.append('tipopartida', tipopartida);
            formData.append('idpartidaconte', );

            axios.post(url+'/proyecto/agregar/partida/adicional/presupuesto', formData, {
            })
                .then((response) => {
                    closeLoading();
                    $('#modalAgregarPresupuesto').modal('hide');

                    if(response.data.success === 1) {

                        Swal.fire({
                            title: 'En Revisión',
                            text: "El presupuesto Partida Adicional esta en modo Revisión",
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
                            text: "El presupuesto Partida Adicional ya había sido Aprobado",
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

                        recargar(); // recarga la tabla
                        $("#matriz-presupuesto tbody tr").remove(); // limpia la tabla
                    }
                    else{
                        toastr.error('Error al crear ');
                    }
                })
                .catch((error) => {

                    toastr.error('error al crear presupuesto');
                    closeLoading();
                });
        }

        function colorBlancoTablaPresupuesto(){
            $("#matriz-presupuesto tbody tr").css('background', 'white');
        }

        // cambio de color de fila tabla a rojo
        function colorRojoTablaPresupuesto(index){
            $("#matriz-presupuesto tr:eq("+(index+1)+")").css('background', '#F1948A');
        }


    </script>



@endsection
