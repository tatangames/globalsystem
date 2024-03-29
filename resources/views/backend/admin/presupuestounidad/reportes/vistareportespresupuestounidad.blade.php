@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
@stop


<div class="content-wrapper" id="divcc" style="display: none">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">

        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="callout callout-info">
                        <h5><i class="fas fa-info"></i> Generar Reportes por Fecha</h5>
                        <div class="card">
                            <form class="form-horizontal">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-sm-5">
                                            <div class="info-box shadow">
                                                <span class="info-box-icon bg-transparent"><i class="far fa-calendar-alt"></i></span>
                                                <div class="info-box-content">
                                                    <label>Año de Presupuesto</label>
                                                    <select class="form-control" id="select-anio" style="width: 35%">
                                                        @foreach($anios as $item)
                                                            <option value="{{$item->id}}">{{$item->nombre}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h5><i class="fas fa-file"></i> Generar Totales</h5> <br>

                                    <div class="row">
                                        <button type="button" onclick="generarPdfTotales()" class="btn" style="margin-left: 15px; border-color: black; border-radius: 0.1px;">
                                            <img src="{{ asset('images/logopdf.png') }}" width="55px" height="55px">
                                            Generar PDF
                                        </button>

                                        <button type="button" onclick="generarExcelTotales()" class="btn" style="margin-left: 25px; border-color: black; border-radius: 0.1px;">
                                            <img src="{{ asset('images/logoexcel.png') }}" width="48px" height="55px">
                                            Generar Excel
                                        </button>
                                    </div>

                                    <hr style="height: 0.5px; background-color: grey">

                                    <h5><i class="fas fa-file"></i> Generar Consolidado</h5> <br>

                                    <div class="row">
                                        <button type="button" onclick="verificarConsolidado()" class="btn" style="margin-left: 15px; border-color: black; border-radius: 0.1px;">
                                            <img src="{{ asset('images/logopdf.png') }}" width="48px" height="55px">
                                            Generar PDF
                                        </button>

                                        <button type="button" onclick="verificarConsolidadoExcel()" class="btn" style="margin-left: 25px; border-color: black; border-radius: 0.1px;">
                                            <img src="{{ asset('images/logoexcel.png') }}" width="48px" height="55px">
                                            Generar Excel
                                        </button>
                                    </div>

                                    <br><br>

                                    <hr style="height: 0.5px; background-color: grey">

                                    <h5><i class="fas fa-info"></i> Generar Presupuesto por Unidad</h5>
                                    <p><i class="fas fa-info"></i> Solo buscara Presupuestos Aprobados</p>
                                    <div class="form-group row">
                                        <div class="col-sm-9">
                                            <div class="info-box shadow">
                                                <span class="info-box-icon bg-transparent"><i class="far fa-calendar-alt"></i></span>
                                                <div class="info-box-content">
                                                    <label>Fecha</label>
                                                    <select class="form-control" id="select-anio-unidad"  style="width: 35%">
                                                        @foreach($anios as $item)
                                                            <option value="{{$item->id}}">{{$item->nombre}}</option>
                                                        @endforeach
                                                    </select>

                                                    <br>

                                                    <label>Unidades</label>
                                                    <select class="form-control" id="select-unidad" style="height: 150px" multiple="multiple">
                                                        @foreach($departamentos as $item)
                                                            <option value="{{$item->id}}">{{$item->nombre}}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <button type="button" onclick="generarPdfPorUnidad()" class="btn" style="margin-left: 15px; border-color: black; border-radius: 0.1px;">
                                            <img src="{{ asset('images/logopdf.png') }}" width="48px" height="55px">
                                            Generar PDF
                                        </button>

                                        <button type="button" onclick="generarExcelPorUnidad()" class="btn" style="margin-left: 25px; border-color: black; border-radius: 0.1px;">
                                            <img src="{{ asset('images/logoexcel.png') }}" width="48px" height="55px">
                                            Generar Excel
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="content" id="divcontenedor" style="display: none">
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Listado</h3>
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


    <div class="modal fade" id="modalPendiente">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Presupuestos aun sin Aprobar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="formulario">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <select class="form-control" id="select-departamento">
                                    </select>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
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

    <script>
        $(document).ready(function() {
            document.getElementById("divcc").style.display = "block";

            $('#select-unidad').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function(){
                        return "Búsqueda no encontrada";
                    }
                },
            });
        });

    </script>

    <script>

        function verificarConsolidado(){

            var anio = document.getElementById('select-anio').value;

            if(anio === ''){
                toastr.error('Año es requerido');
                return;
            }

            let formData = new FormData();
            formData.append('anio', anio);

            openLoading();

            axios.post(url+'/p/generador/verificar/consolidado/presupuesto', formData, {
            })
                .then((response) => {

                    closeLoading();
                    if(response.data.success === 1){
                        // generar tabla
                        var anio = document.getElementById('select-anio').value;
                        window.open("{{ URL::to('admin/p/generador/consolidado/pdf/presupuesto') }}/" + anio);                    }

                    else if(response.data.success === 2){
                        // departamentos si aprobar aun
                        $('#modalPendiente').modal('show');

                        document.getElementById("select-departamento").options.length = 0;

                        $.each(response.data.lista, function( key, val ){
                            $('#select-departamento').append('<option value="0">'+val.nombre+'</option>');
                        });
                    }
                    else{
                        toastr.error('error');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al generar');
                    closeLoading();
                });
        }

        function verificarConsolidadoExcel(){

            var anio = document.getElementById('select-anio').value;

            if(anio === ''){
                toastr.error('Año es requerido');
                return;
            }

            let formData = new FormData();
            formData.append('anio', anio);

            openLoading();

            axios.post(url+'/p/generador/verificar/consolidado/presupuesto', formData, {
            })
                .then((response) => {

                    closeLoading();
                    if(response.data.success === 1){
                        // generar tabla
                        var fecha = document.getElementById('select-anio').value;
                        window.open("{{ URL::to('admin/p/generador/excel/consolidado') }}/" + fecha);
                    }

                    else if(response.data.success === 2){
                        // departamentos si aprobar aun
                        $('#modalPendiente').modal('show');

                        document.getElementById("select-departamento").options.length = 0;

                        $.each(response.data.lista, function( key, val ){
                            $('#select-departamento').append('<option value="0">'+val.nombre+'</option>');
                        });
                    }
                    else{
                        toastr.error('Error al generar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al generar');
                    closeLoading();
                });
        }

        function generarPdfTotales(){
            var idanio = document.getElementById('select-anio').value;
            window.open("{{ URL::to('admin/p/generador/pdf/totales') }}/" + idanio);
        }

        function generarExcelTotales(){
            var fecha = document.getElementById('select-anio').value;
            window.open("{{ URL::to('admin/p/generador/excel/totales') }}/" + fecha);
        }

        function generarPdfPorUnidad(){

            var idanio = document.getElementById('select-anio-unidad').value;
            var departamento = document.getElementById('select-unidad').value;

            var valores = $('#select-unidad').val();
            if(valores.length ==  null || valores.length === 0){
                toastr.error('Seleccionar mínimo 1 Unidad');
                return;
            }

            var selected = [];
            for (var option of document.getElementById('select-unidad').options){
                if (option.selected) {
                    selected.push(option.value);
                }
            }

            let listado = selected.toString();
            let reemplazo = listado.replace(/,/g, "-");

            if(selected.length > 1){

                let formData = new FormData();
                formData.append('idanio', idanio);
                formData.append('unidades', reemplazo);

                openLoading();

                axios.post(url+'/p/ver/unidades/tiene/presupuesto/anio', formData, {
                })
                    .then((response) => {

                        closeLoading();
                        if(response.data.success === 1){
                            let nombre = response.data.departamento;

                            Swal.fire({
                                title: 'Presupuesto No Encontrado',
                                html: "El Departamento " + nombre + ". Con su Año de Presupuesto no se encontró",
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
                            // si hay presupuesto
                            window.open("{{ URL::to('admin/p/generador/pdf/porunidad') }}/" + idanio + "/" + reemplazo);
                        }
                        else if(response.data.success === 3){
                            Swal.fire({
                                title: 'Presupuesto No Encontrado',
                                html: "Ningún Departamento cuenta con Presupuesto Creado o Aprobado",
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
                            toastr.error('Error al buscar');
                        }
                    })
                    .catch((error) => {
                        toastr.error('Error al buscar');
                        closeLoading();
                    });




            }else{

                let formData = new FormData();
                formData.append('idanio', idanio);
                formData.append('iddepartamento', departamento);

                openLoading();

                axios.post(url+'/p/ver/unidad/tiene/presupuesto/anio', formData, {
                })
                    .then((response) => {

                        closeLoading();
                        if(response.data.success === 1){
                            // si hay presupuesto
                            window.open("{{ URL::to('admin/p/generador/pdf/unaunidad') }}/" + idanio + "/" + departamento);
                        }

                        else if(response.data.success === 2){
                            Swal.fire({
                                title: 'Presupuesto No Encontrado',
                                html: "El Departamento con su Año de Presupuesto no se encontró",
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
                            toastr.error('Error al buscar');
                        }
                    })
                    .catch((error) => {
                        toastr.error('Error al buscar');
                        closeLoading();
                    });
            }
        }

        function generarExcelPorUnidad(){

            var idanio = document.getElementById('select-anio-unidad').value;
            var departamento = document.getElementById('select-unidad').value;

            var valores = $('#select-unidad').val();
            if(valores.length ==  null || valores.length === 0){
                toastr.error('Seleccionar mínimo 1 Unidad');
                return;
            }

            var selected = [];
            for (var option of document.getElementById('select-unidad').options){
                if (option.selected) {
                    selected.push(option.value);
                }
            }

            let listado = selected.toString();
            let reemplazo = listado.replace(/,/g, "-");

            if(selected.length > 1){

                // ARRAY DE DEPARTAMENTOS

                let formData = new FormData();
                formData.append('idanio', idanio);
                formData.append('unidades', reemplazo);

                openLoading();

                axios.post(url+'/p/ver/unidades/tiene/presupuesto/anio', formData, {
                })
                    .then((response) => {

                        closeLoading();
                        if(response.data.success === 1){
                            let nombre = response.data.departamento;

                            Swal.fire({
                                title: 'Presupuesto No Encontrado',
                                html: "El Departamento " + nombre + ". Con su Año de Presupuesto no se encontró",
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
                            // si hay presupuesto
                            window.open("{{ URL::to('admin/p/generador/excel/porunidad') }}/" + idanio + "/" + reemplazo);
                        }
                        else if(response.data.success === 3){
                            Swal.fire({
                                title: 'Presupuesto No Encontrado',
                                html: "Ningún Departamento cuenta con Presupuesto Creado o Aprobado",
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
                            toastr.error('Error al buscar');
                        }
                    })
                    .catch((error) => {
                        toastr.error('Error al buscar');
                        closeLoading();
                    });


            }else{

                // SOLO 1 DEPARTAMENTO

                // se debe verificar que haya presupuesto de esta unidad

                let formData = new FormData();
                formData.append('idanio', idanio);
                formData.append('iddepartamento', departamento);

                openLoading();

                axios.post(url+'/p/ver/unidad/tiene/presupuesto/anio', formData, {
                })
                    .then((response) => {

                        closeLoading();
                        if(response.data.success === 1){
                           // si hay presupuesto
                            window.open("{{ URL::to('admin/p/generador/excel/unaunidad') }}/" + idanio + "/" + departamento);
                        }

                        else if(response.data.success === 2){
                            Swal.fire({
                                title: 'Presupuesto No Encontrado',
                                html: "El Departamento con su Año de Presupuesto no se encontró",
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
                            toastr.error('Error al buscar');
                        }
                    })
                    .catch((error) => {
                        toastr.error('Error al buscar');
                        closeLoading();
                    });
            }
        }


    </script>


@endsection
