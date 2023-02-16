<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Departamento</th>
                                <th>Destino</th>
                                <th>Necesidad</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($data as $dato)
                                <tr>
                                    <td>{{ $dato->fecha }}</td>
                                    <td>{{ $dato->departamento }}</td>
                                    <td>{{ $dato->destino }}</td>
                                    <td>{{ $dato->necesidad }}</td>

                                    <td>
                                        <button type="button" style="font-weight: bold; color: white !important;" class="btn btn-info btn-xs" onclick="informacionMaterial({{ $dato->id }})">
                                            <i class="fas fa-list-alt" title="Materiales"></i>&nbsp; Materiales
                                        </button>

                                        <button type="button" style="font-weight: bold; color: white !important;" class="btn btn-success btn-xs" onclick="informacionAprobar({{ $dato->id }})">
                                            <i class="fas fa-check" title="Aprobar"></i>&nbsp; Aprobar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    $(function () {
        $("#tabla").DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "pagingType": "full_numbers",
            "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "Todo"]],
            "language": {

                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            "responsive": true, "lengthChange": true, "autoWidth": false,
        });
    });


</script>