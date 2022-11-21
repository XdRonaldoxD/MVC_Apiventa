<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>
</head>
<style>
    body {
        margin: 0;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        font-size: 0.875rem;
        font-weight: normal;
        line-height: 1.5;
        color: #151b1e;
    }

    .table {
        display: table;
        width: 100%;
        max-width: 100%;
        margin-bottom: 0px;
        padding: 0px;
        background-color: transparent;
        border-collapse: collapse;

    }

    .table-bordered {
        border: 1px solid #c2cfd6;
    }

    thead {
        display: table-header-group;
        vertical-align: middle;
        border-color: inherit;
    }

    tr {
        display: table-row;
        vertical-align: inherit;
        border-color: inherit;
    }

    .table th,
    .table td {
        padding: 0.55rem;
        vertical-align: top;
        border-top: 1px solid #c2cfd6;
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #c2cfd6;
    }

    .table-bordered thead th,
    .table-bordered thead td {
        border-bottom-width: 2px;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #c2cfd6;
    }

    th,
    td {
        display: table-cell;

    }

    th {
        font-weight: bold;
        text-align: -internal-center;
        text-align: left;
    }

    tbody {
        display: table-row-group;
        vertical-align: middle;
        border-color: inherit;
    }

    tr {
        display: table-row;
        vertical-align: inherit;
        border-color: inherit;
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
</style>

<body>
    <h1 style="text-align: center;margin: 0px;">PRODUCTO DEL INVENTARIO ALMACENADO </h1>
    <?php
    if ($fechaInicio != null && $fechaFin != null) {
    ?>
        <h1 style="text-align: center;margin: 0px;"> <?=date('Y/m/d',strtotime( $fechaInicio))?>-<?=date('Y/m/d',strtotime($fechaFin))?></h1>
    <?php
    }
    ?>


    <?php foreach ($inventario as $key => $datos) {
    ?>
        <strong><?= $key ?></strong>
        <br>
        <table style="text-align:center;" class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th>Codigo Producto </th>
                    <th>Nombre Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalventa = 0;
                foreach ($datos as $key => $elemento) {
                    $totalProducto = $elemento['preciocosto_producto'];
                    $totalventa += $totalProducto;
                ?>
                    <tr>
                        <td><?=$elemento['codigo_producto']?></td>
                        <td><?=$elemento['glosa_producto']?></td>
                        <td><?=$elemento['stock_producto']?></td>
                        <td>S/<?=number_format($elemento['precioventa_producto'],2)?></td>
                        <td>
                            <?= "S/" . number_format($totalProducto, 2) ?>
                        </td>
                    </tr>
                <?php
                }
                ?>

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="font-size: 15px;"> <strong>Total:S/<?=number_format($totalventa,2)?></strong> </td>
                </tr>
            </tfoot>
        </table>
        <br>
    <?php
    } ?>


</body>

</html>