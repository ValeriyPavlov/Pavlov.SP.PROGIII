<?php

class CriptoMoneda
{
    public $id;
    public $nombre;
    public $precio;
    public $nacionalidad;
    public $foto;
    public $baja;

    public function crearCriptoMoneda()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO cripto_monedas (nombre, precio, nacionalidad, foto, baja) VALUES (:nombre, :precio, :nacionalidad, :foto, :baja)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':nacionalidad', $this->nacionalidad, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':baja', False, PDO::PARAM_BOOL);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM cripto_monedas");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'CriptoMoneda');
    }

    public static function obtenerCriptoMoneda($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM cripto_monedas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('CriptoMoneda');
    }

    public static function modificarCriptoMoneda($criptoMoneda)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE cripto_monedas SET nombre = :nombre, precio = :precio, nacionalidad = :nacionalidad, foto = :foto, baja = :baja WHERE id = :id");
        $consulta->bindValue(':nombre', $criptoMoneda->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $criptoMoneda->precio, PDO::PARAM_INT);
        $consulta->bindValue(':nacionalidad', $criptoMoneda->nacionalidad, PDO::PARAM_STR);
        $consulta->bindValue(':baja', $criptoMoneda->baja, PDO::PARAM_BOOL);
        $consulta->bindValue(':foto', $criptoMoneda->foto, PDO::PARAM_STR);
        $consulta->bindValue(':id', $criptoMoneda->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarCriptoMoneda($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE cripto_monedas SET baja = :baja WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':baja', True, PDO::PARAM_BOOL);
        $consulta->execute();
    }

    public static function obtenerTodosPorNacionalidad($nacionalidad)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM cripto_monedas WHERE nacionalidad = :nacionalidad");
        $consulta->bindValue(':nacionalidad', $nacionalidad);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'CriptoMoneda');
    }

    public static function cargarLog($idUser, $idCripto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO logs (id_usuario, id_cripto, accion, fecha_accion) VALUES (:id_usuario, :id_cripto, :accion, :fecha_accion)");
        $consulta->bindValue(':id_usuario', $idUser);
        $consulta->bindValue(':id_cripto', $idCripto);
        $consulta->bindValue(':accion', "Borrar");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':fecha_accion', date_format($fecha, 'Y-m-d'));
        $consulta->execute();
    }
}

?>