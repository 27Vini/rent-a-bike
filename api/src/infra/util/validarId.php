<?php


function validarId($texto){
    $problemas = [];
    if($texto == null || !is_numeric($texto) || $texto <= 0){
        array_push($problemas, "O id deve ser um número maior que 0.");
    }
    return $problemas;
}