<?php

use Illuminate\Database\Eloquent\Model;

class Folio extends Model{
    protected $table = 'folio';
    protected $primaryKey = 'id_folio';
    protected $fillable = [
        'id_folio',
        'glosa_folio',
        'numero_folio',
        'numerosii_folio',
    ];
    public $timestamps = false;
}