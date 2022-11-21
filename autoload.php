<?php


function autocarga($classname)
{

    if (!strpos($classname, 'PDOConnection') ) {
        include "Controllers/" . $classname . '.php';
    }else{
        include  $classname;

    }
 
    spl_autoload_register("autocarga");

}