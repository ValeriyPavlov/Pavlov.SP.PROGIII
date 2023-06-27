<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $mail = $parametros['mail'];
        $tipo = $parametros['tipo'];
        $clave = $parametros['clave'];

        $usr = new Usuario();
        $usr->mail = $mail;
        $usr->tipo = $tipo;
        $usr->clave = $clave;
        $usr->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $usuario = Usuario::obtenerUsuarioPorId($id);
        $payload = json_encode($usuario);
        if(!$usuario)
        {
            $payload = json_encode(array("Error" => "El usuario con ese ID no existe."));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("Lista Usuarios" => $lista));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usr = new Usuario();
        $usr->id = $parametros['id'];
        $usr->mail = $parametros['mail'];
        $usr->clave = $parametros['clave'];
        Usuario::modificarUsuario($usr);
        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuarioId = $parametros['id'];
        Usuario::borrarUsuario($usuarioId);
        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
