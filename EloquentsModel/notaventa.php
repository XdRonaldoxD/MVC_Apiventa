<?php
use Illuminate\Database\Eloquent\Model;
class notaventa extends Model
{
    protected $table = "nota_venta";
    protected $primaryKey = 'id_nota_venta';
    protected $fillable = [
        'id_nota_venta',
        'id_usuario',
        'id_apertura_caja',
        'numero_venta',
        'valor_venta',
        'descuento_negocio_venta',
        'porcentajedescuento_venta',
        'pagocliente_venta',
        'cambiocliente_venta',
        'fecha_creacion_venta',
        'hora_creacion_venta',
        'path_nota_venta'
    ];
    public $timestamps = false;
}
