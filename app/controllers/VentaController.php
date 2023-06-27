<?php
require_once './models/Venta.php';
require_once './models/Usuario.php';
require_once './models/CriptoMoneda.php';
require_once './interfaces/IApiUsable.php';

class VentaController extends Venta implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $header = $request->getHeaderLine(("Authorization"));
        $token = trim(explode("Bearer", $header)[1]);
        $data = AuthJWT::ObtenerData($token);

        $idCripto = $parametros['idCripto'];
        $cantidad = $parametros['cantidad'];

        $venta = new Venta();
        $venta->idUsuario = $data->id;
        $venta->idCripto = $idCripto;
        $venta->cantidad = $cantidad;
        $fecha = new DateTime(date("d-m-Y"));
        $venta->fechaCompra = date_format($fecha, 'Y-m-d');
        $usuario = Usuario::obtenerUsuarioPorId($venta->idUsuario);
        $nombreUser = (explode("@", $usuario->mail))[0];
        $cripto = CriptoMoneda::obtenerCriptoMoneda($venta->idCripto);

        if(file_exists($_FILES["foto"]["tmp_name"]))
        {
            $venta->foto = $this->MoverFoto($cripto->nombre, $nombreUser, $venta->fechaCompra);
        }
        $venta->crearVenta();

        $payload = json_encode(array("mensaje" => "Venta creada con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $venta = Venta::obtenerVenta($id);
        $payload = json_encode($venta);
        if(!$venta)
        {
            $payload = json_encode(array("Error" => "La venta con ese ID no existe."));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Venta::obtenerTodos();
        $payload = json_encode(array("Lista Ventas" => $lista));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosPorNacionalidad($request, $response, $args)
    {
        $nacionalidad = $args['nacionalidad'];
        $lista = Venta::obtenerTodosNacionalidad($nacionalidad);
        if(count($lista) > 0)
        {
            $payload = json_encode(array("Lista Ventas de nacionalidad $nacionalidad entre 10 y 13 de Julio:" => $lista));
        }
        else
        {
            $payload = json_encode(array("Error" => "No existen Ventas de la nacionalidad $nacionalidad realizadas entre 10 y 13 de Julio."));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosPorNombre($request, $response, $args)
    {
        $nombre = $args['nombre'];
        $lista = Venta::obtenerTodosNombre($nombre);
        if(count($lista) > 0)
        {
            $payload = json_encode(array("Lista de usuarios que compraron la moneda $nombre:" => $lista));
        }
        else
        {
            $payload = json_encode(array("Error" => "No hay usuarios que hayan realizado compras de la moneda $nombre."));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $venta = new Venta();
        $venta->id = $parametros['id'];
        $venta->cantidad = $parametros['cantidad'];
        Venta::modificarVenta($venta);
        $payload = json_encode(array("mensaje" => "Venta modificada con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $venta = $parametros['id'];
        Venta::borrarVenta($venta);
        $payload = json_encode(array("mensaje" => "Venta borrada con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function MoverFoto($nombre, $cliente, $fecha)
    {
        $carpeta = ".".DIRECTORY_SEPARATOR."FotosCripto2023".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR;
        if(!file_exists($carpeta))
        {
            mkdir($carpeta, 0777, true);
        }
        $nombreFoto = $carpeta.$nombre.'-'.$cliente.'-'.$fecha.".jpg";
        if(!file_exists($nombreFoto))
        {
            rename($_FILES["foto"]["tmp_name"], $nombreFoto);
        }
        return $nombreFoto;
    }
}
