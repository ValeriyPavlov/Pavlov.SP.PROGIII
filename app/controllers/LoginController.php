<?php
require_once "./models/AuthJWT.php";
require_once "./models/Usuario.php"; 

class LoginController extends AuthJWT
{
    public function GenerarToken($request, $response)
    {
        $datosPost = $request->getParsedBody();
        $datosBD = Usuario::obtenerUsuario($datosPost["mail"]);
        $claveHasheada = md5($datosPost["clave"]);

        if($datosBD != null && $claveHasheada == $datosBD->clave && $datosBD->fechaBaja < 1)
        {
            $datos = array('id'=> $datosBD->id, 'mail' => $datosBD->mail, "tipo"=> $datosBD->tipo);
            $token = AuthJWT::NuevoToken($datos);
            $payload = json_encode(array('Se ha logeado como:' => $datosBD->tipo, 'Token' => $token));
            $response->getBody()->write($payload);
        }
        else
        {
            $response->getBody()->write(json_encode(array("Error" => "El usuario o la contraseña no coinciden.")));
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function Deslogear($request, $response)
    {
        $response->getBody()->write("Cerro su cuenta con exito!");
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function Test($request, $response)
    {
        $response->getBody()->write("Entro al Test!");
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>