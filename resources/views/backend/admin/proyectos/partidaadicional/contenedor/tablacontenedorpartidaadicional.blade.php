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
                                <th>Estado</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($lista as $dato)
                                <tr>
                                    <td>{{ $dato->fecha }}</td>
                                    <td>
                                        @if($dato->estado == 0)
                                            <span class="badge bg-info">En Desarrollo</span>
                                        @elseif($dato->estado == 1)
                                            <span class="badge bg-gray">En Revisión</span>
                                        @else
                                            <span class="badge bg-success">Partida Adicional Aprobada</span>
                                        @endif
                                    </td>

                                    <td>

                                        <!-- ver todas las partidas detalle -->
                                        <button type="button" class="btn btn-primary btn-xs" onclick="vistaPartidasAdicionales({{ $dato->id }})">
                                            <i class="fas fa-list-alt" title="Partidas Adicionales"></i>&nbsp; Partidas
                                        </button>

                                         @if($dato->estado != 2)
                                            <!-- solo aparece el botón sino esta aprobada la partida adicional -->
                                            <br><br>
                                            <button type="button" class="btn btn-success btn-xs" onclick="vistaInformacionEstado({{ $dato->id }})">
                                                <i class="fas fa-check" title="Estado"></i>&nbsp; Estado
                                            </button>
                                         @endif

                                        <!-- sacar pdf -->
                                        <br><br>
                                        <button type="button" class="btn btn-success btn-xs" onclick="infoPdf({{ $dato->id }})">
                                            <i class="fas fa-file-pdf" title="PDF"></i>&nbsp; PDF
                                        </button>

                                        <!-- solo autorizado podrá borrar contenedor de partidas adicionales -->
                                        @can('boton.borrar.contenedor.partida.adicional')
                                            @if($dato->estado == 0)
                                                <br><br>
                                                <button type="button" class="btn btn-danger btn-xs" onclick="infoBorrarContenedor({{ $dato->id }})">
                                                    <i class="fas fa-list-alt" title="Borrar"></i>&nbsp; Borrar
                                                </button>
                                            @endif
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