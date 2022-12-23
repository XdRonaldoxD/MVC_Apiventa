<?php
use Illuminate\Database\Eloquent\Model;
class NotificacionMercadoPago extends Model
{
    protected $table = "notificacion_mercadopago";
    protected $primaryKey = 'id_notificacion';

    protected $fillable = [
        'id_notificacion_mercadopago',
        'json_notificacion_mercadopago',
        'fecha_creacion_notificacion_mercadopago'
    ];

    public $timestamps = false;
}
