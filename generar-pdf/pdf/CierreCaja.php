<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Punto de Venta</title>

    <style>
        html {

            font-family: Arial, Helvetica, sans-serif;
        }

        .w-100 {
            width: 100%;
        }

        .w-80 {
            width: 80%;
        }

        .w-70 {
            width: 70%;
        }

        .w-60 {
            width: 60%;
        }

        .w-50 {
            width: 50%;
        }

        .w-40 {
            width: 40%;
        }

        .w-30 {
            width: 30%;
        }

        .w-20 {
            width: 20%;
        }

        .p-1 {
            padding: 8px;
        }

        .p-2 {
            padding: 16px;
        }

        p {
            margin: 0;
            font-size: 9px;
        }

        .m-0 {
            margin: 0;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mb {
            margin-bottom: 5px;
        }

        .fs-small {
            font-size: 11px;
        }

        .fs-large {
            font-size: 14px;
        }

        .fs-xlarge {
            font-size: 16px;
        }

        .text-capitalize {
            text-transform: capitalize;
        }

        hr {
            height: 0px;
            border-bottom: 0px;
            border-top: double 2px black;
            margin: 5px 0px;
        }





        .titulocabezera {
            background-color: rgba(0, 0, 0, 0.05);

        }

        img {
            position: absolute;
            width: 80px;
            height: 80px;
            margin: 10px;
        }

        .size {
            width: 100%;
            height: 365px;
        }

        html {
            margin: 10px 15px
        }



        .t-desglose td:last-child,
        .t-desglose th:last-child {

            border-right: 0.01em solid #ff000000;

        }


        .miTabla {

            border-left: 0.01em solid #000;
            border-right: 0;
            border-top: 0.01em solid #000;
            border-bottom: 0;
            border-collapse: collapse;
        }

        .miTabla td,
        .miTabla th {

            padding-left: 5px;
            padding-right: 5px;
            border-left: 0;
            border-right: 0.01em solid #000;
            border-top: 0;
            border-bottom: 0.01em solid #000;
        }
    </style>
</head>

<body>


    <div style="text-align: center;">
        <img style="width:120px; height:auto; margin:0px 0px 0px 70px" src="data:image/png;base64,<?=$imagen?>" alt="" />
    </div>
    <br><br>
    <div style="text-align: center;">
        <strong style="font-size: 15px;margin: 0px;">AHORROFARMA</strong> <br>
        <strong style="font-size: 9px;margin: 0px;">R.U.C.: 10468481940</strong><br>
        <strong style="font-size: 9px;margin: 0px;">DE:DORA YULITH REMIGIO ZELAYA</strong>
    </div>
    <br>



    <div style="text-align: center;">
        <p style="font-size: 9px;margin: 0px;">RB. LOS JARDINES MZ. C LT. 10</p>
    </div>
    <div style="text-align: center;">
        <p style="font-size: 9px;margin: 0px;">LIMA - SANTA MARIA - HUAURA</p>
    </div>

    <table class="w-100 fs-small">
        <tr>
            <td class="w-50">
                <p><strong> Vendedor:</strong> <?=$cierreCaja['nombre_usuario']?> <?=$cierreCaja['apellido_usuario']?></p>
            </td>

        </tr>
    </table>
    <table class="w-100 fs-small">
        <tr>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px"><strong> Fecha y Hora Inicio :</strong></p>

            </td>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px"><?= date('d/m/Y g:i A', strtotime($cierreCaja['apertura_caja_fechainicio'])) ?> </p>

            </td>

        </tr>
    </table>
    <table class="w-100 fs-small">
        <tr>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px"><strong> Fecha y Hora Fin :</strong></p>

            </td>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px"><?= date('d/m/Y g:i A', strtotime($cierreCaja['apertura_caja_fechafin'])) ?> </p>

            </td>

        </tr>
    </table>
    <table class="w-100 fs-small">
        <tr>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px"><strong>Cantidad Nota de Ventas:</strong></p>

            </td>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px"><?= $cierreCaja['apertura_caja_cantidad_ventas'] ?> </p>

            </td>

        </tr>
    </table>

    <table class="w-100 fs-small">
        <tr>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px"><strong>Monto Incial:</strong></p>

            </td>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px">S/<?= number_format($cierreCaja['apertura_caja_monto_inicial'],2)  ?> </p>

            </td>

        </tr>
    </table>

    <table class="w-100 fs-small">
        <tr>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px"><strong>Monto Final:</strong></p>

            </td>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px">S/<?= number_format($cierreCaja['apertura_caja_monto_final'],2)  ?> </p>

            </td>

        </tr>
    </table>

    <table class="w-100 fs-small">
        <tr>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px"><strong>Descuento total de Ventas:</strong></p>

            </td>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px">S/<?= number_format($cierreCaja['apertura_caja_descuento'],2)  ?> </p>

            </td>

        </tr>
    </table>

    <table class="w-100 fs-small">
        <tr>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px"><strong>Monto Total Ventas:</strong></p>

            </td>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px">S/<?= number_format($cierreCaja['apertura_caja_total_ventas'],2)  ?> </p>

            </td>

        </tr>
    </table>


    <div style="width: 100%">
        <img style="margin-left: 80px;" src="data:image/png;base64,<?=$codigoBarra?>" alt="" />

    </div>


</body>

</html>