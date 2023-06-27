<?php

class Venta
{
    public $id;
    public $idUsuario;
    public $idCripto;
    public $cantidad;
    public $fechaCompra;
    public $foto;
    public $fechaBaja;

    public function crearVenta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO ventas (idUsuario, idCripto, cantidad, fechaCompra, foto) VALUES (:idUsuario, :idCripto, :cantidad, :fechaCompra, :foto)");
        $consulta->bindValue(':idUsuario', $this->idUsuario, PDO::PARAM_INT);
        $consulta->bindValue(':idCripto', $this->idCripto, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $fechaCompra = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':fechaCompra', date_format($fechaCompra, 'Y-m-d'));
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ventas");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

    public static function obtenerTodosNacionalidad($nacionalidad)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ventas INNER JOIN cripto_monedas ON ventas.idCripto = cripto_monedas.id WHERE cripto_monedas.nacionalidad = :nacionalidad AND ventas.fechaCompra < '2023-07-13' AND ventas.fechaCompra > '2023-07-10'");
        $consulta->bindValue(':nacionalidad', $nacionalidad, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'stdClass');
    }

    public static function obtenerTodosNombre($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT usuarios.id, usuarios.mail, usuarios.tipo FROM usuarios JOIN ventas ON usuarios.id = ventas.idUsuario JOIN cripto_monedas ON cripto_monedas.id = ventas.idCripto WHERE cripto_monedas.nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'stdClass');
    }

    public static function obtenerVenta($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ventas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Venta');
    }

    public static function modificarVenta($venta)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE ventas SET cantidad = :cantidad WHERE id = :id");
        $consulta->bindValue(':cantidad', $venta->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':id', $venta->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarVenta($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE ventas SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d'));
        $consulta->execute();
    }

    public function __toString()
    {
        return "$this->id, $this->idUsuario, $this->idCripto, $this->cantidad, $this->fechaCompra, $this->fechaBaja, $this->foto";
    }

}
?>