@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />
@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
</style>

<div id="divcontenedor" style="display: none">

    <section class="content-header">
        <div class="row">
            <h1 style="margin-left: 5px">Fuente de Recursos</h1>
            <button type="button" style="font-weight: bold; margin-left: 20px; background-color: #28a745; color: white !important;"
                    onclick="modalAgregar()" class="button button-3d button-rounded button-pill button-small">
                <i class="fas fa-pencil-alt"></i>
                Nueva Fuente de Recursos
            </button>
        </div>
    </section>

    <section class="content">
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

    <div class="modal fade" id="modalAgregar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nueva Fuente de Recursos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-nuevo">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <label>Año</label>
                                        <select class="form-control" id="select-anios">
                                            @foreach( $anios as $dd)
                                                <option value="{{ $dd->id }}">{{ $dd->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Código</label>
                                        <input type="text" maxlength="100" class="form-control" id="codigo-nuevo" autocomplete="off">
                                    </div>

                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" maxlength="300" class="form-control" id="nombre-nuevo" autocomplete="off">
                                    </div>

                                    <div class="form-group">
                                        <label>Fuente de Financiamiento</label>
                                        <select class="form-control" id="select-fuente-f-nuevo">
                                            <option value="" disabled selected>Seleccione una opción...</option>
                                            @foreach($fuentef as $sel)
                                                <option value="{{ $sel->id }}">{{ $sel->codigo }} {{ $sel->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>




                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="nuevo()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal editar -->
    <div class="modal fade" id="modalEditar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar Fuente de Recursos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-editar">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <label>Año</label>
                                        <select class="form-control" id="select-anios-editar">
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Código</label>
                                        <input type="hidden" id="id-editar">
                                        <input type="text" maxlength="100" class="form-control" id="codigo-editar" autocomplete="off">
                                    </div>

                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" maxlength="300" class="form-control" id="nombre-editar" autocomplete="off">
                                    </div>

                                    <div class="form-group">
                                        <label>Fuente de Financiamiento</label>
                                        <select class="form-control" id="select-fuente-f-editar">
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Disponibilidad</label><br>
                                        <label class="switch" style="margin-top:10px">
                                            <input type="checkbox" id="toggle-editar">
                                            <div class="slider round">
                                                <span class="on">Activo</span>
                                                <span class="off">Inactivo</span>
                                            </div>
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;"
                            class="button button-rounded button-pill button-small" onclick="editar()">Guardar</button>
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

    <script type="text/javascript">
        $(document).ready(function(){
            var ruta = "{{ URL::to('/admin/fuenter/tabla/index') }}";
            $('#tablaDatatable').load(ruta);

            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        function recargar(){
            var ruta = "{{ url('/admin/fuenter/tabla/index') }}";
            $('#tablaDatatable').load(ruta);
        }

        function modalAgregar(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }

        function nuevo(){
            var codigo = document.getElementById('codigo-nuevo').value;
            var nombre = document.getElementById('nombre-nuevo').value;
            var fuente = document.getElementById('select-fuente-f-nuevo').value;
            var anio = document.getElementById('select-anios').value;

            if(anio === ''){
                toastr.error('Año es requerido');
                return;
            }

            if(codigo === ''){
                toastr.error('Código es requerido');
                return;
            }

            if(codigo.length > 100){
                toastr.error('Código máximo 100 caracteres');
                return;
            }

            if(nombre.length > 300){
                toastr.error('Nombre máximo 300 caracteres');
                return;
            }

            if(fuente === ''){
                toastr.error('Seleccionar Fuente de Financiamiento');
                return;
            }

            openLoading();
            var formData = new FormData();
            formData.append('idanio', anio);
            formData.append('codigo', codigo);
            formData.append('nombre', nombre);
            formData.append('fuente', fuente);

            axios.post(url+'/fuenter/nuevo', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        toastr.success('Registrado correctamente');
                        $('#modalAgregar').modal('hide');
                        recargar();
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

        function informacion(id){
            openLoading();
            document.getElementById("formulario-editar").reset();

            axios.post(url+'/fuenter/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalEditar').modal('show');
                        $('#id-editar').val(response.data.fuente.id);
                        $('#codigo-editar').val(response.data.fuente.codigo);
                        $('#nombre-editar').val(response.data.fuente.nombre);

                        document.getElementById("select-fuente-f-editar").options.length = 0;
                        document.getElementById("select-anios-editar").options.length = 0;

                        $.each(response.data.arrayfuente, function( key, val ){
                            if(response.data.idfuente == val.id){
                                $('#select-fuente-f-editar').append('<option value="' +val.id +'" selected="selected">'+val.codigo + ' ' + val.nombre +'</option>');
                            }else{
                                $('#select-fuente-f-editar').append('<option value="' +val.id +'">'+val.codigo + ' ' + val.nombre +'</option>');
                            }
                        });

                        $.each(response.data.arrayanios, function( key, val ){
                            if(response.data.fuente.id_p_anio == val.id){
                                $('#select-anios-editar').append('<option value="' +val.id +'" selected="selected">'+ val.nombre +'</option>');
                            }else{
                                $('#select-anios-editar').append('<option value="' +val.id +'">'+ val.nombre +'</option>');
                            }
                        });

                        if(response.data.fuente.activo === 0){
                            $("#toggle-editar").prop("checked", false);
                        }else{
                            $("#toggle-editar").prop("checked", true);
                        }

                    }else{
                        toastr.error('Información no encontrada');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
        }

        function editar(){
            var id = document.getElementById('id-editar').value;
            var nombre = document.getElementById('nombre-editar').value;
            var codigo = document.getElementById('codigo-editar').value;
            var fuente = document.getElementById('select-fuente-f-editar').value;
            var anio = document.getElementById('select-anios-editar').value;

            var t = document.getElementById('toggle-editar').checked;
            var toggle = t ? 1 : 0;

            if(anio === ''){
                toastr.error('Año es requerido');
                return;
            }

            if(codigo === ''){
                toastr.error('Código es requerido');
                return;
            }

            if(codigo.length > 100){
                toastr.error('Código máximo 100 caracteres');
                return;
            }

            if(nombre.length > 300){
                toastr.error('Nombre máximo 300 caracteres');
                return;
            }

            openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('codigo', codigo);
            formData.append('nombre', nombre);
            formData.append('fuente', fuente);
            formData.append('idanio', anio);
            formData.append('toggle', toggle);

            axios.post(url+'/fuenter/editar', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        toastr.success('Actualizado correctamente');
                        $('#modalEditar').modal('hide');
                        recargar();
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


    </script>


@endsection
