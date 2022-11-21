<?php

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = "cliente";
    protected $primaryKey = 'id_cliente';
    protected $fillable = [
        'id_cliente',
        'tipo_documento_cliente',
        'dni_cliente',
        'ruc_cliente',
        'nombre_cliente',
        'apellidopaterno_cliente',
        'apellidomaterno_cliente',
        'e_mail_cliente',
        'telefono_cliente',
        'celular_cliente',
        'direccion_cliente',
        'comentario_cliente',
        'vigente_cliente',
    ];
    public $timestamps = false;
}
