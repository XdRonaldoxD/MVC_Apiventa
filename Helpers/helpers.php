<?php

use Carbon\Carbon;

class helpers
{

    public static function validar_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public static function validaRequerido($valor)
    {
        if (trim($valor) == '') {
            return false;
        } else {
            return true;
        }
    }
    public static  function validarEntero($valor, $opciones = null)
    {
        if (filter_var($valor, FILTER_VALIDATE_INT, $opciones) === FALSE) {
            return false;
        } else {
            return true;
        }
    }
    public static  function validaEmail($valor)
    {
        if (filter_var($valor, FILTER_VALIDATE_EMAIL) === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public static  function ValidarTipoParametros($elemento)
    {
        $var_type = null;
        switch ($elemento) {
            case is_bool($elemento):
                $var_type = PDO::PARAM_BOOL;
                break;
            case is_int($elemento):
                $var_type = PDO::PARAM_INT;
                break;
            case is_null($elemento):
                $var_type = PDO::PARAM_NULL;
                break;
            default:
                $var_type = PDO::PARAM_STR;
        }
        return $var_type;
    }

    public static function group_by($key, $data)
    {
        $result = array();
        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }
        return $result;
    }

    public static function nombreMes($fecha)
    {
      $miFecha = new Carbon($fecha, 'America/Lima');
      $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
      $mes = $meses[($miFecha->format('n')) - 1];
      return $mes;
    }

    public static function generate_string($input, $strength = 16) {
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
     
        return $random_string;
    }


    
}
