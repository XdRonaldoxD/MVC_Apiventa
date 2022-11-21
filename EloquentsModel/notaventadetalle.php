<?php
use Illuminate\Database\Eloquent\Model;
class notaventadetalle extends Model
{
    protected $table = "nota_venta_detalle";
    protected $primaryKey = 'id_nota_venta_detalle';

    protected $fillable = [
        'id_nota_venta_detalle',
        'id_nota_venta',
        'id_producto',
        'fechacreacion_venta_detalle',
        'valor_venta',
        'cantidad_venta_detalle',
        'porcentadescuento_negocio',
        'orden_venta_detalle',
    ];

    public $timestamps = false;
}
