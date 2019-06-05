<?php
//lIMPIANDO EL TEXTO
function sanear_string($string){
 
    $string = trim($string);
 
    //Esta parte se encarga de eliminar cualquier caracter extraño

    if(strpos($string, "'") > 0){
    	return '"'.$string.'"';
    }else if(strpos($string, '"') > 0){
    	return "'".$string."'";
    }else{
    	return "'".$string."'";
    }
}

