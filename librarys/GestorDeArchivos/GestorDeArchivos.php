<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class GestorDeArchivos
{
    public function leer($rutaDeArchivo)
    {
        if (empty($rutaDeArchivo)
          ||!file_exists ($rutaDeArchivo)
           ) {
                return false;           
           }
        $archivo = fopen($rutaDeArchivo, 'r');
        $contenido = fread($archivo, filesize($rutaDeArchivo));
        $contenido = explode("\n", $contenido);
        fclose($archivo);
        return $contenido;
    }
    
    public function escrbir($rutaDeArchivo, $contenido)
    {
        $archivo = fopen($rutaDeArchivo, "w+");        
        if (!isset($archivo)) {
                $result = 0;
        }
        else if (!fwrite($archivo, $contenido)) {
                $result = 0;
        } else {
            $result = 1;
        }
        fclose($archivo);
        return $result;
    }

    public function agregar($rutaDeArchivo, $contenido)
    {
        $archivo = fopen($rutaDeArchivo, "a+");        
        if (!isset($archivo)) {
                $result = 0;
        }
        else if (!fwrite($archivo, $contenido)) {
                $result = 0;
        } else {
            $result = 1;
        }
        fclose($archivo);
        return $result;
    }
}