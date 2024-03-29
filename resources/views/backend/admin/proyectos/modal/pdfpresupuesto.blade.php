<html>
<head>
    <meta charset="UTF-8" />
    <title>Alcaldía Metapán | Reporte</title>

    <style>
        .firma {
            left: 0;
            font-size: 20px;
            margin-top: 200px;
            text-align: left;
            page-break-inside: avoid;
        }

        br[style] {
            display:none;
        }

        #titulo{
            text-align: center;
            font-size: 18px;
            font-weight: bold;"
        }

        #logo{
            float: right;
            height : 88px;
            width : 71px;
            margin-right: 15px;
        }

        #tablaFor {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            margin-left: 20px;
            margin-right: 20px;
            margin-top: 35px;
            text-align: center;
        }

        #tablaFor td{
            border: 1px solid #1E1E1E;
            padding: 8px;
            text-align: center;
            font-size: 14px;
        }

        #tablaFor th {
            border: 1px solid #1E1E1E;
            padding: 8px;
            text-align: center;
        }

        #tablaFor th {
            padding-top: 12px;
            padding-bottom: 12px;
            color: #1E1E1E;
            text-align: left;
            font-size: 14px;
        }


        .saltopagina{page-break-after:always;}


    </style>
</head>
<body>
<div id="header">
    <div class="content">
        <img id="logo" src="{{ asset('images/logo2.png') }}">
        <p id="titulo">ALCALDÍA MUNICIPAL DE METAPÁN <br>
            Fondo: {{ $fuenter }} <br>
            Hoja de presupuesto <br>
            Fecha: {{ $mes }} <br>
    </div>
</div>


<table id="tablaFor" style="width: 95%">

        <tbody>

        @foreach($partida1 as $dd)

            @if(!$loop->first)

                <tr>
                    <td width="100%" colspan="6"></td>
                </tr>

            @endif

            <tr>
                <td colspan="1" width="10%" style="font-weight: bold">Item</td>
                <td colspan="4" width="30%" style="font-weight: bold">Partida</td>
                <td colspan="2" width="20%" style="font-weight: bold">Cantidad P.</td>
            </tr>

            <tr>
                <td colspan="1" width="10%">{{ $dd->item }}</td>
                <td colspan="4" width="30%">{{ $dd->nombre }}</td>
                <td colspan="2" width="20%">{{ $dd->cantidadp }}</td>
            </tr>

            <tr>
                <td width="10%" style="font-weight: bold">Objeto Específico.</td>
                <td width="15%" style="font-weight: bold">Material</td>
                <td width="11" style="font-weight: bold">U/M</td>
                <td width="12%" style="font-weight: bold">Cantidad</td>
                <td width="10%" style="font-weight: bold">P.U</td>
                <td width="12%" style="font-weight: bold">Sub Total</td>
                <td width="20%" style="font-weight: bold">Total</td>
            </tr>

            @foreach($dd->bloque1 as $gg)

                <tr>

                    <td width="10%">{{ $gg->objespecifico }}</td>
                    <td width="15%">{{ $gg->material }}</td>
                    <td width="10%">{{ $gg->medida }}</td>
                    <td width="10%">{{ $gg->cantidad }}</td>
                    <td width="10%">{{ $gg->pu }}</td>
                    <td width="12%">{{ $gg->subtotal }}</td>
                    <td width="20%"></td>
                </tr>

                @if($loop->last)
                    <tr>
                        <td width="10%"></td>
                        <td width="15%"></td>
                        <td width="10%"></td>
                        <td width="10%"></td>
                        <td width="10%"></td>
                        <td width="10%"></td>
                        <td width="20%" style="font-weight: bold">{{ $dd->total }}</td>
                    </tr>
                @endif

            @endforeach

        @endforeach

        </tbody>

</table>


<table id="tablaFor" style="width: 95%">

    <tbody>

    @foreach($manoobra as $dd)

        @if(!$loop->first)

            <tr>
                <td width="100%" colspan="6"></td>
            </tr>

        @endif

        <tr>
            <td colspan="7" style="font-weight: bold">MANO DE OBRA POR ADMINISTRACIÓN</td>
        </tr>

        <tr>
            <td colspan="1" width="10%" style="font-weight: bold">Item</td>
            <td colspan="4" width="30%" style="font-weight: bold">Partida</td>
            <td colspan="2" width="20%" style="font-weight: bold">Cantidad P.</td>
        </tr>

        <tr>
            <td colspan="1" width="10%">{{ $dd->item }}</td>
            <td colspan="4" width="30%">{{ $dd->nombre }}</td>
            <td colspan="2" width="20%">{{ $dd->cantidadp }}</td>
        </tr>

        <tr>
            <td width="10%" style="font-weight: bold">Objeto Específico.</td>
            <td width="15%" style="font-weight: bold">Material</td>
            <td width="11" style="font-weight: bold">U/M</td>
            <td width="12%" style="font-weight: bold">Cantidad</td>
            <td width="10%" style="font-weight: bold">P.U</td>
            <td width="12%" style="font-weight: bold">Sub Total</td>
            <td width="20%" style="font-weight: bold">Total</td>
        </tr>

        @foreach($dd->bloque3 as $gg)

            <tr>

                @if($gg->objespecifico == null)
                    <td width="10%" style="background: red"></td>
                @else
                    <td width="10%">{{ $gg->objespecifico }}</td>
                @endif

                <td width="15%">{{ $gg->material }}</td>
                    @if($gg->medida == null)
                        <td width="10%" style="background: red"></td>
                    @else
                        <td width="10%">{{ $gg->medida }}</td>
                    @endif

                <td width="10%">{{ $gg->cantidad }}</td>
                <td width="10%">{{ $gg->pu }}</td>
                <td width="12%">{{ $gg->subtotal }}</td>
                <td width="20%"></td>
            </tr>

            @if($loop->last)
                <tr>
                    <td width="10%"></td>
                    <td width="15%"></td>
                    <td width="10%"></td>
                    <td width="10%"></td>
                    <td width="10%"></td>
                    <td width="10%"></td>
                    <td width="20%" style="font-weight: bold">{{ $dd->total }}</td>
                </tr>
            @endif

        @endforeach

    @endforeach

    </tbody>

</table>

    <br>
    <br>
    <!-- partida de mano de obra -->


<table id="tablaFor" style="width: 95%">

    <tbody>

        <tr>
            <td colspan="3" style='font-weight: bold'>APORTE PATRONAL</td>
        </tr>

        <tr>
            <td width="20%" style='font-weight: bold'>Descripción</td>
            <td width="12%" style='font-weight: bold'>Sub Total</td>
            <td width="20%" style='font-weight: bold'>Total</td>
        </tr>

        <tr>
            <td width="20%">ISSS (7.5% mano de obra)</td>
            <td width="12%">{{ $isss }}</td>
            <td width="20%"></td>
        </tr>
        <tr>
            <td width="20%">AFP (7.75% mano de obra)</td>
            <td width="12%">{{ $afp }}</td>
            <td width="20%"></td>
        </tr>
        <tr>
            <td width="20%">INSAFOR (1.0% mano de obra)</td>
            <td width="12%">{{ $insaforp }}</td>
            <td width="20%"></td>
        </tr>

        <tr>
            <td width="20%"></td>
            <td width="12%"></td>
            <td width="20%"><strong>{{ $totalDescuento }}</strong></td>
        </tr>


    </tbody>

</table>

<div style="page-break-after:always;"></div>
<br>
<div id="header">
    <div class="content">
        <img id="logo" src="{{ asset('images/logo2.png') }}">
        <p id="titulo">ALCALDÍA MUNICIPAL DE METAPÁN <br>
            Fondo: {{ $fuenter }} <br>
            Hoja de presupuesto <br>
            Fecha: {{ $mes }} <br>
    </div>
</div>

<br>

<table id="tablaFor" style="width: 75%; margin: 0 auto">

    <tbody>

    <tr>
        <td colspan="2" style="font-weight: bold">RESUMEN DE PARTIDA</td>
    </tr>

    <tr>
        <td width="20%">MATERIALES</td>
        <td width="12%">{{ $sumaMateriales }}</td>
    </tr>

    <tr>
        <td width="20%">HERRAMIENTA ({{ $porcientoHerramienta }}% DE MAT.)</td>
        <td width="12%">{{ $herramienta2Porciento }}</td>
    </tr>

    <tr>
        <td width="20%">ALQUILER DE MAQUINARIA</td>
        <td width="12%">{{ $totalAlquilerMaquinaria }}</td>
    </tr>

    <tr>
        <td width="20%">MANO DE OBRA (POR ADMINISTRACIÓN)</td>
        <td width="12%">{{ $totalManoObra }}</td>
    </tr>

    <tr>
        <td width="20%">APORTE MANO DE OBRA (PATRONAL)</td>
        <td width="12%">{{ $totalDescuento }}</td>
    </tr>

    <tr>
        <td width="20%">TRANSPORTE DE CONCRETO FRESCO</td>
        <td width="12%">{{ $totalTransportePesado }}</td>
    </tr>


    <tr>
        <td width="20%" style="font-weight: bold">SUB TOTAL</td>
        <td width="12%" style="font-weight: bold">{{ $subtotalPartida }}</td>
    </tr>

    <tr>
        <td width="20%" style="font-weight: bold">IMPREVISTOS ({{$imprevistoActual}}% de sub total)</td>
        <td width="12%" style="font-weight: bold">{{ $imprevisto }}</td>
    </tr>

    <tr>
        <td width="20%" style="font-weight: bold">TOTAL</td>
        <td width="12%" style="font-weight: bold">{{ $totalPartidaFinal }}</td>
    </tr>


    </tbody>

</table>

<br><br>

<div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
    <!-- boton aprobar solo sera visible para x usuarios, y solo aparecera si no esta aprobado,
    porque otros usuarios pueden ver esta vista
    -->

    <div class="form-group">
        <button type="button" style="width: 100%;" class="btn btn-success" onclick="verPresupuestoPorAdministrador()">
            <i class="fas fa-file-pdf" title="PDF"></i>&nbsp; PDF
        </button>
    </div>

    @can('boton.aprobar.presupuesto')
        @if($preAprobado == 1)
            <!-- sera visible solamente si no esta aprobado aun -->
            <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;"
                    class="button button-rounded button-pill button-small" id="btnAprobarPresupuesto"  onclick="btnAprobarPresupuesto()">Asignar Bolsón</button>
        @endcan
    @endcan



</div>


</body>
</html>

