<?php

use Illuminate\Database\Eloquent\Model;

class Caja extends Model{
    protected $table = 'caja';
    protected $primaryKey = 'id_caja';
    protected $fillable = [
            'glosa_caja',
            'numero_caja',
            'folio_caja',
            'fechacreacion_caja',
            'fechacierre_caja',
            'estado_caja',
    ];
    public $timestamps = false;
}