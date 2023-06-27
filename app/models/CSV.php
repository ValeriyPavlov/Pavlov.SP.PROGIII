<?php

class CSV
{
    public static function ExportarCSV($path)
    {
        $listaProductos = CriptoMoneda::obtenerTodos();
        $file = fopen($path, "w");
        foreach($listaProductos as $producto)
        {
            $separado= implode(",", (array)$producto);  
            if($file)
            {
                fwrite($file, $separado.",\r\n"); 
            }                           
        }
        fclose($file);  
        return $path;     
    }
}
?>