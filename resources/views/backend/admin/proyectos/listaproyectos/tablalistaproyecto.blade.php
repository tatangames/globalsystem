<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th style="width: 10%">Código</th>
                                <th style="width: 30%">Nombre</th>
                                <th style="width: 10%">Fecha Inicio</th>
                                <th style="width: 15%">Encargado</th>
                                <th style="width: 14%">Presupuesto</th>
                                <th style="width: 14%">Monto Sobrante</th>
                                <th style="width: 12%">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($lista as $dato)
                                <tr>
                                    <td>{{ $dato->codigo }}</td>
                                    <td>{{ $dato->nombre }}</td>
                                    <td>{{ $dato->fechaini }}</td>
                                    <td>{{ $dato->encargado }}</td>
                                    <td style="font-weight: bold">{{ $dato->montopartida }}</td>
                                    <td style="font-weight: bold">{{ $dato->montosobrante }}</td>


                                    <td>
                                        <button type="button"  class="btn btn-primary btn-xs" onclick="modalOpciones({{ $dato }})">
                                            <i class="fas fa-edit" title="Opciones"></i>&nbsp; Opciones
                                        </button>

                                        @can('boton.modal.estados.proyectos')
                                        <button type="button" style="margin-top: 2px;" class="btn btn-success btn-xs" onclick="modalEstados({{ $dato->id }})">
                                            <i class="fas fa-list-alt" title="Estados"></i>&nbsp; Estados
                                        </button>
                                        @endcan

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
