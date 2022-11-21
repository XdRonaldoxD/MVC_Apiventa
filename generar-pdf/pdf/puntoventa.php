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
        .w-15 {
            width: 15%;
        }
        .w-10 {
            width: 10%;
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

        table {
            table-layout: fixed;
            width: 100%;
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


    <br>
    <table style="text-align:center;" class="t-desglose miTabla fs-small w-100">
        <thead>
            <tr>
                <th class="w-20" style="padding: 0px;">
                    <p> CODIGO </p>
                </th>
                <th class="w-30" style="padding: 0px;">
                    <p> DESCRIPCION
                    </p>
                </th>

                <th class="w-10" style="padding: 0px;">
                    <p> CANT.
                    </p>
                </th>
                <th class="w-15" style="padding: 0px;">
                    <p> VALOR</p>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0 ;
            foreach($venta_detalle as $ventas){
            ?>
            <tr>
                <td style="padding: 0px;">
                    <p><?=$ventas['codigo_producto']?></p>
                </td>
                <td style="padding: 0px;">
                    <p><?=$ventas['glosa_producto']?> </p>
                </td>
                <td style="padding: 0px;">
                    <p><?=$ventas['cantidad_venta_detalle']?></p>
                </td>
                <td style="padding: 0px;">
                    <p>S/.<?=number_format($ventas['valor_venta'],2)?></p>
                    <?php $total += $ventas['valor_venta'] ?>
                </td>
            </tr>
            <?php
            }
             ?>
     

        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="font-size: 9px;padding:0px"> <strong>TOTAL</strong> S/<?=number_format($total,2)?> </td>
            </tr>
        </tfoot>

    </table>

    <br>
    <table class="w-100 fs-small">
        <?php if (!empty($venta['descuento_negocio_venta'])) {
        ?>
            <tr>
                <td class="w-50">
                    <p> <strong>DESCUENTO</strong></p>

                </td>
                <td class="w-50">
                    <p> S/<?=number_format($venta['descuento_negocio_venta'],2)?></p>
                </td>
            </tr>
        <?php
        } ?>

        <tr>
            <td class="w-50">
                <p> <strong> TOTAL </strong></p>

            </td>
            <td class="w-50">
                <p> S/<?=number_format($venta['valor_venta'],2)?></p>
            </td>
        </tr>
        <tr>
            <td class="w-50">
                <p>EFECTIVO</p>

            </td>
            <td class="w-50">
                <p> S/<?=number_format($venta['pagocliente_venta'],2)?></p>
            </td>
        </tr>
        <tr>
            <td class="w-50">
                <p> VUELTO</p>

            </td>
            <td class="w-50">
                <p> S/<?= number_format($venta['cambiocliente_venta'], 2) ?></p>
            </td>
        </tr>
    </table>


    <table class="w-100 fs-small">
        <tr>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px">Fecha y Hora de Venta:</p>

            </td>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px"><?= date('d/m/Y', strtotime($venta['fecha_creacion_venta'])) ?> <?= date('g:i A', strtotime($venta['hora_creacion_venta'])) ?></p>

            </td>

        </tr>
    </table>
    <table class="w-100 fs-small">
        <tr>
            <td class="w-100">
                <p style="font-size: 9px;padding:0px">Cantidad de Producto:<?= count($venta_detalle) ?></p>
            </td>
        </tr>
    </table>

    <div style="text-align: center;">
        <b style="font-family: 'Constantia', serif;">!Gracias por su Compra!</b>
    </div>
    <div style="width: 100%">
        <img style="margin-left: 80px;" src="data:image/png;base64,<?=$codigoBarra?>" alt="" />

    </div>


</body>

</html>