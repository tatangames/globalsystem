@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }

</style>

<div id="divcontenedor" style="display: none">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-10">
                <div class="col-sm-10">
                    <h1>Revisión de Solicitudes de Material Para Presupuesto</h1>
                </div>

            </div>
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

    <div class="modal fade" id="modalNuevoSolicitud">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Solicitud de Material</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-nuevo-material">
                        <div class="card-body">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="form-group" style="margin-top: 15px">
                                            <label>Material Solicitado</label>
                                            <input type="hidden" class="form-control" id="id-solicitud">

                                            <input type="text" class="form-control" disabled autocomplete="off" id="material-nuevo">
                                        </div>

                                        <div class="form-group" style="margin-top: 15px">
                                            <label>Objeto Específico</label>
                                            <input type="text" class="form-control" disabled id="objeto-nuevo">
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger" onclick="preguntarBorrar()">Denegar</button>
                    <button type="button" class="btn btn-primary" onclick="preguntarSolicitud()">Aprobar</button>
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

            var ruta = "{{ URL::to('/admin/p/revision/solicitud/material/unidades/tabla') }}";
            $('#tablaDatatable').load(ruta);

            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        function recargar(){
            var ruta = "{{ URL::to('/admin/p/revision/solicitud/material/unidades/tabla') }}";
            $('#tablaDatatable').load(ruta);
        }

        function informacion(id){
            // id p_materialsolicitud

            openLoading();

            var formData = new FormData();
            formData.append('idsolicitud', id);

            axios.post(url+'/p/solicitud/material/revision/presupuesto', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){

                        $('#id-solicitud').val(id);

                        let nommaterial = response.data.nommaterial;
                        let objeto = response.data.objeto;

                        $('#material-nuevo').val(nommaterial);
                        $('#objeto-nuevo').val(objeto);

                        $('#modalNuevoSolicitud').modal('show');
                    }else{
                        toastr.error('información no encontrada');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('información no encontrada');
                });
        }

        function preguntarSolicitud(){

            Swal.fire({
                title: 'Aprobar Solicitud',
                text: "",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aprobar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    aprobarSolicitud();
                }
            })
        }

        function preguntarBorrar(){
            Swal.fire({
                title: 'Denegar Solicitud',
                text: "",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Denegar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    borrarSolicitud();
                }
            })
        }

        function borrarSolicitud(){

            let id = document.getElementById('id-solicitud').value;

            openLoading();

            var formData = new FormData();
            formData.append('idsolicitud', id);

            axios.post(url+'/p/borrar/solicitud/material/presupuesto', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){

                        $('#modalNuevoSolicitud').modal('hide');
                        toastr.success('Solicitud eliminada');
                        recargar();
                    }else{
                        toastr.error('Error al borrar');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Error al borrar');
                });
        }

        function aprobarSolicitud(){

            let id = document.getElementById('id-solicitud').value;

            openLoading();

            var formData = new FormData();
            formData.append('idsolicitud', id);

            axios.post(url+'/p/aprobar/solicitud/material/presupuesto', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1) {

                        $('#modalNuevoSolicitud').modal('hide');
                        toastr.success('Aprobado correctamente');
                        recargar();
                    }
                    else{
                        toastr.error('Error al borrar');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Error al borrar');
                });
        }

    </script>


@endsection
