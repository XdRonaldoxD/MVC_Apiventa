<?php
use Illuminate\Database\Eloquent\Model;
class Notificacion extends Model
{
    protected $table = "notificacion";
    protected $primaryKey = 'id_notificacion';

    protected $fillable = [
        'id_notificacion',
        'json_notificacion',
        'fecha_creacion_notificacion'
    ];

    public $timestamps = false;
}
