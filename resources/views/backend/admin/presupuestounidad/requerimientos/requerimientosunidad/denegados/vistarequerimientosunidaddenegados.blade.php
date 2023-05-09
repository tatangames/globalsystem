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
                        <h5><i class="fas fa-info"></i> Requerimientos Denegados por Año</h5>
                        <div class="card">
                            <form class="form-horizontal">
                                <div class="card-body">

                                    <div class="form-group row">
                                        <div class="col-sm-9">
                                            <div class="info-box shadow">
                                                <div class="info-box-content">


                                                    <label>Fecha</label>
                                                    <select class="form-control" id="select-anio-unidad"  style="width: 35%">
                                                        @foreach($anios as $item)
                                                            <option value="{{$item->id}}">{{$item->nombre}}</option>
                                                        @endforeach
                                                    </select>

                                                    <button type="button" style="font-weight: bold; font-size: 14px; color: white !important; margin-top: 35px; width: 15%; padding: 10px" class="btn btn-success btn-xs" onclick="informacionBuscar()">
                                                        <i class="fas fa-search" title="Buscar"></i>&nbsp; Buscar
                                                    </button>

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
        </div>
    </section>


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

        function informacionBuscar(){

            var idanio = document.getElementById('select-anio-unidad').value;

            if(idanio === ''){
                toastr.error('Año es requerido');
                return;
            }

            window.location.href="{{ url('/admin/p/requerimiento/denegados/listado') }}/" + idanio;
        }

    </script>


@endsection