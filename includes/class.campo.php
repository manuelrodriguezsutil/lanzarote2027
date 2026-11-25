<?php

class Campo
{
    private static ?Campo $instancia = null;
    private static $val = array();

    private function __construct()
    {
        self::cargarCampos($_GET);
        self::cargarCampos($_POST);
        self::cargarCampos($_COOKIE);
        self::cargarCampos($_SESSION ?? []);
    }

    private function cargarCampos(array $datos)
    {
        foreach ($datos as $clave => $valor) {
            if (self::comprobar_inyeccion($valor)) {
                self::$val[$clave] = $valor;
            }
        }
    }

    public static function getInstancia(): Campo
    {
        if (self::$instancia == null)
            self::$instancia = new Campo();

        return self::$instancia;
    }

    static function val(string $clave, $set_value = '')
    {
        empty($set_value) ? self::getInstancia() : self::$val[$clave] = $set_value;
        return self::$val[$clave];
    }

    public function __clone() {}


    public static function comprobar_inyeccion(string $valor)
    {
        if (preg_match('/\b(SELECT|UPDATE|DELETE|DROP TABLE)\b\s+FROM\b/i', $valor) || preg_match('/\bINSERT\sINTO\b/i', $valor))
            die;

        return True;
    }
}
