<?php
use Illuminate\Database\Eloquent\Model;
class aperturacaja extends Model
{
    protected $table = "apertura_caja";
    protected $primaryKey = 'id_apertura_caja';

    protected $fillable = [
        'id_apertura_caja',
        'id_caja',
        'id_usuario',
        'apertura_caja_fecha',
        'apertura_caja_fechainicio',
        'apertura_caja_fechafin',
        'apertura_caja_monto_inicial',
        'apertura_caja_monto_final',
        'apertura_caja_total_ventas',
        'apertura_caja_cantidad_ventas',
        'apertura_caja_descuento',
        'apertura_caja_estado',
        'apertura_caja_total_boleta',
        'apertura_caja_total_factura'
    ];

    public $timestamps = false;
}
