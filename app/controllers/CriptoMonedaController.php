<?php
require_once './models/CriptoMoneda.php';
require_once './interfaces/IApiUsable.php';
require_once './models/CSV.php';

class CriptoMonedaController extends CriptoMoneda implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $nacionalidad = $parametros['nacionalidad'];

        $cripto = new CriptoMoneda();
        $cripto->nombre = $nombre;
        $cripto->precio = $precio;
        $cripto->nacionalidad = $nacionalidad;
        if(file_exists($_FILES["foto"]["tmp_name"]))
        {
            $cripto->foto = $this->MoverFoto($cripto->nombre);
        }
        $cripto->crearCriptoMoneda();

        $payload = json_encode(array("mensaje" => "CriptoMoneda creada con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $cripto = CriptoMoneda::obtenerCriptoMoneda($id);
        $payload = json_encode($cripto);
        if(!$cripto)
        {
            $payload = json_encode(array("Error" => "La criptomoneda con ese ID no existe."));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = CriptoMoneda::obtenerTodos();
        $payload = json_encode(array("Lista CriptoMonedas" => $lista));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $cripto = new CriptoMoneda();
        $cripto->id = $parametros['id'];
        $cripto->nombre = $parametros['nombre'];
        $cripto->precio = $parametros['precio'];
        $cripto->nacionalidad = $parametros['nacionalidad'];
        $cripto->baja = $parametros['baja'];
        $cripto->foto = $this->MoverFotoBackup($parametros['foto']);
        CriptoMoneda::modificarCriptoMoneda($cripto);
        $payload = json_encode(array("mensaje" => "CriptoMoneda modificada con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $criptoId = $args['id'];
        //$parametros = $request->getParsedBody();
        //$userId = $parametros['user'];
        CriptoMoneda::cargarLog(1, $criptoId);
        CriptoMoneda::borrarCriptoMoneda($criptoId);
        $payload = json_encode(array("mensaje" => "CriptoMoneda borrada con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosPorNacionalidad($request, $response, $args)
    {
        $nacionalidad = $args["nacionalidad"];
        $lista = CriptoMoneda::obtenerTodosPorNacionalidad($nacionalidad);
        if(count($lista) > 0)
        {
            $payload = json_encode(array("Lista CriptoMonedas de nacionalidad: $nacionalidad :" => $lista));
        }
        else
        {
            $payload = json_encode(array("Error" => "No hay criptomonedas de esa Nacionalidad"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function MoverFoto($nombre)
    {
        $carpeta = ".".DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR;
        if(!file_exists($carpeta))
        {
            mkdir($carpeta, 0777, true);
        }
        $nombreFoto = $carpeta."fotoCripto-".$nombre.".jpg";
        if(!file_exists($nombreFoto))
        {
            rename($_FILES["foto"]["tmp_name"], $nombreFoto);
        }
        return $nombreFoto;
    }

    private function MoverFotoBackup($nombre)
    {
        $carpeta = ".".DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR;
        $carpetaBackup = ".".DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR."Backup2023".DIRECTORY_SEPARATOR;
        $nombreFoto = $carpeta."fotoCripto-".$nombre.".jpg";
        if(file_exists($nombreFoto))
        {
            if(!file_exists($carpetaBackup))
            {
                mkdir($carpetaBackup, 0777, true);
            }
            copy($nombreFoto, $carpetaBackup."fotoCripto-".$nombre.".jpg");
        }
        else
        {
            echo "La foto que desea adjuntar no existe.";
        }
        return $nombreFoto;
    }

    public function ExportarCripto($request, $response, $args)
    {
        try
        {
            $archivo = CSV::ExportarCSV("cripto.csv");
            if(file_exists($archivo) && filesize($archivo) > 0)
            {
                $payload = json_encode(array("Archivo creado:" => $archivo));
            }
            else
            {
                $payload = json_encode(array("Error" => "Datos ingresados invalidos."));
            }
            $response->getBody()->write($payload);
        }
        catch(Exception $e)
        {
            echo $e;
        }
        finally
        {
            return $response->withHeader('Content-Type', 'text/csv');
        }    
    }

    public function Log($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $userId = $parametros['user'];
        $criptoId = $parametros['crypto'];
        CriptoMoneda::cargarLog($userId, $criptoId);
        $payload = json_encode(array("mensaje" => "CriptoMoneda borrada con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
