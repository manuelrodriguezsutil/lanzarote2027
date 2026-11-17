<?php

class Usuario
{
    private Modelo $modelo;

    private string $nombre;
    private string $apellido1;
    private string $apellido2;
    private string $fecha_nacimiento;
    private string $email;
    private string $fecha_alta;
    private string $ip_alta;
    private string $fecha_modi;
    private string $ip_modi;

    function __construct()
    {
        $this->modelo = new Modelo('usuarios');
    }

    function recuperar(string $id)
    {
        $registro = $this->modelo->recuperar($id);

        $this->nombre = $registro['nombre'];
        $this->apellido1 = $registro['apellido1'];
        $this->apellido2 = $registro['apellido2'];
        $this->fecha_nacimiento = $registro['fecha_nacimiento'];
        $this->email = $registro['email'];
        $this->fecha_alta = $registro['fecha_alta'];
        $this->ip_alta = $registro['ip_alta'];
        $this->fecha_modi = $registro['fecha_modi'];
        $this->ip_modi = $registro['ip_modi'];

        return $registro;
    }


    function insertar(array $registro)
    {

        $query = new Query("
            INSERT INTO usuarios
            (
                nombre,    
                apellido1, 
                apellido2, 
                fecha_nacimiento, 
                email, 
                fecha_alta,   
                ip_alta,      
                fecha_modi,   
                ip_modi,      
            )
            VALUES
            (
                '" . $registro['nombre']           . "'
                ,'" . $registro['apellido1']        . "'
                ,'" . $registro['apellido2']        . "'
                ,'" . $registro['fecha_nacimiento'] . "'
                ,'" . $registro['email']            . "'
                ,now()
                ,'" . $_SERVER['SERVER_ADDR']    . "'
                ,now()
                ,'" . $_SERVER['SERVER_ADDR']    . "'
                
            )
        
        ");

        return $query->total;
    }


    function eliminar(string $id)
    {
        return $this->modelo->eliminar($id);
    }


    function modificar(array $registro)
    {
        $registro['fecha_modi'] = date("Y-m-d H:i:s");
        $registro['ip_modi'] = $_SERVER['SERVER_ADDR'];

        return $this->modelo->modificar($registro);
    }

    function validar(array $registro)
    {
        $texto_salida = '';
        $errores = false;
        $mensaje = '';

        if (strlen($_POST['nombre']) < 3) {
            $_error_nombre = 'error';
            $texto_salida = '<p>- El nombre debe tener al menos 3 caracteres.</p>';
            $errores = true;
        }

        if (strlen($_POST['apellido1']) < 3) {
            $_error_apellido1 = 'error';
            $texto_salida .= '<p>- El primer apellido debe tener al menos 3 caracteres.</p>';
            $errores = true;
        }

        if (strlen($_POST['apellido2']) < 3) {
            $_error_apellido2 = 'error';
            $texto_salida .= '<p>- El segundo apellido debe tener al menos 3 caracteres.</p>';
            $errores = true;
        }

        if (!fecha_valida($_POST['fecha_nacimiento'])) {
            $_error_fecha_nacimiento = 'error';
            $texto_salida .= '<p>- La fecha de nacimiento no tiene un formato válido.</p>';
            $errores = true;
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_error_email = 'error';
            $texto_salida .= '<p>- El E-mail no tiene un formato válido.</p>';
            $errores = true;
        }

        if ($errores) {
            $mensaje = "
                <div class=\"info_errores mb-5 mt-5\" role=\"alert\">
                    {$texto_salida}
                </div>
            ";
        } else {
            $mensaje  = "
                <div class=\"alert theme-success  mb-5 mt-5\" role=\"alert\">
                    <p>Operación realizada con éxito</p>
                </div>
            ";
        }

        return $mensaje;
    }

    function listado() {}
}
