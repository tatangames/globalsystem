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


    </style>
</head>

<body>
<div id="header">
    <div class="content">
        <p id="titulo">ALCALDÍA MUNICIPAL DE METAPÁN <br>
            Estados de Material<br>
    </div>
</div>


<table id="tablaFor" style="width: 95%">

    <tbody>
    <tr>
        <td width="30%" style="font-weight: bold">Material</td>
        <td width="8%" style="font-weight: bold">Estado</td>
        <td width="8%" style="font-weight: bold">Fecha</td>
    </tr>

    @foreach($arrayRequiDetalle as $info)

        <tr>
            <td width="8%" style="font-weight: bold">{{ $info->nommaterial }}</td>
            <td width="8%" style="font-weight: bold">{{ $info->estado }}</td>
            <td width="8%" style="font-weight: bold">{{ $info->fechaestado }}</td>
        </tr>

    @endforeach

    </tbody>

</table>


<br>
<br>
<!-- partida de mano de obra -->
<div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-primary" onclick="infoEstados()">Información</button>
</div>

<script>



</script>

</body>
</html>

