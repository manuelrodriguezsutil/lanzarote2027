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

    private array $errores = [
        'nombre'           => '',
        'apellido1'        => '',
        'apellido2'        => '',
        'fecha_nacimiento' => '',
        'email'            => '',
    ];

    private string $mensaje = '';

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

    private function datos_formulario(array $registro)
    {
        return [
            'nombre'           => $registro['nombre'],
            'apellido1'        => $registro['apellido1'],
            'apellido2'        => $registro['apellido2'],
            'fecha_nacimiento' => $registro['fecha_nacimiento'],
            'email'            => $registro['email'],
        ];
    }


    function insertar(array $registro)
    {
        $datos = $this->datos_formulario($registro);

        $datos['fecha_alta'] = date("Y-m-d H:i:s");
        $datos['ip_alta']    = $_SERVER['SERVER_ADDR'];
        $datos['fecha_modi'] = date("Y-m-d H:i:s");
        $datos['ip_modi']    = $_SERVER['SERVER_ADDR'];

        return $this->modelo->insertar($datos);
    }


    function eliminar(string $id)
    {
        return $this->modelo->eliminar($id);
    }


    function modificar(array $registro)
    {
        $datos = $this->datos_formulario($registro);

        $datos['id']         = $registro['id'];
        $datos['fecha_modi'] = date("Y-m-d H:i:s");
        $datos['ip_modi']    = $_SERVER['SERVER_ADDR'];

        return $this->modelo->modificar($datos);
    }

    function validar(array $registro)
    {
        $texto_salida = '';
        $errores = false;
        $mensaje = '';

        if (strlen($registro['nombre']) < 3) {
            $this->errores['nombre'] = 'error';
            $texto_salida = '<p>- El nombre debe tener al menos 3 caracteres.</p>';
            $errores = true;
        }

        if (strlen($registro['apellido1']) < 3) {
            $this->errores['apellido1'] = 'error';
            $texto_salida .= '<p>- El primer apellido debe tener al menos 3 caracteres.</p>';
            $errores = true;
        }

        if (strlen($registro['apellido2']) < 3) {
            $this->errores['apellido2'] = 'error';
            $texto_salida .= '<p>- El segundo apellido debe tener al menos 3 caracteres.</p>';
            $errores = true;
        }

        if (!fecha_valida($registro['fecha_nacimiento'])) {
            $this->errores['fecha_nacimiento'] = 'error';
            $texto_salida .= '<p>- La fecha de nacimiento no tiene un formato válido.</p>';
            $errores = true;
        }

        if (!filter_var($registro['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errores['email'] = 'error';
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

        $this->mensaje = $mensaje;

        return !$errores;
    }

    function formulario(string $oper, string $pagina = '')
    {
        $url_form = '/usuarios/' . $oper;

        switch ($oper) {
            case 'baja':
                $operacion = 'Eliminación';
                $id = $pagina;

            case 'modi':
                $operacion = empty($operacion) ? 'Modificación' : $operacion;
                $id = $pagina;
                $url_form .= '/' . $id;

            case 'alta':
                $operacion = empty($operacion) ? 'Alta' : $operacion;
        }

        define("OPERACION", $operacion);

        $boton_alta = "<input type=\"submit\" class=\"theme-primary btn-solid btn-styled\" value=\"" . OPERACION . " de usuario\" />";

        $disabled = $oper == 'baja' ? " readonly=\"readonly\" " : '';

        if ($_POST['paso'] == '1') {
            if ($this->validar($_POST)) {
                $boton_alta = '';
                $disabled = " disabled=\"disabled\" ";

                switch ($oper) {
                    case 'alta':
                        $this->insertar($_POST);
                        break;

                    case 'modi':
                        $this->modificar($_POST);
                        break;

                    case 'baja':
                        // echo "Aquí tendremos que eliminar";

                        $this->eliminar($_POST['id']);

                        ob_clean();
                        header("Location: /usuarios/listado/0");
                        exit;

                        break;
                }
            }
        } elseif (!empty($id)) {
            $registro = $this->recuperar($id);

            $_POST['id']               = $registro['id'];
            $_POST['nombre']           = $registro['nombre'];
            $_POST['apellido1']        = $registro['apellido1'];
            $_POST['apellido2']        = $registro['apellido2'];
            $_POST['email']            = $registro['email'];
            $_POST['fecha_nacimiento'] = $registro['fecha_nacimiento'];
        }

        return "
        <div class=\"container\" style=\"padding: 50px;\">
            <a href=\"/usuarios/listado/0\" class=\"btn-solid theme-info mb-5\">
                Volver <i class=\"bi bi-arrow-return-left\"></i>
            </a>

            <h1>" . OPERACION . " de usuarios</h1>

            {$this->mensaje}

            <form action=\"{$url_form}\" method=\"POST\">
                <input type=\"hidden\" name=\"paso\" value=\"1\" />
                <input type=\"hidden\" name=\"id\" value=\"{$_POST['id']}\" />

                <div class=\"form-field mb-4 {$this->errores['nombre']}\">
                    <label for=\"nombreID\" class=\"form-label\">Nombre</label>
                    <input {$disabled} type=\"text\" name=\"nombre\" class=\"form-control\" id=\"nombreID\" value=\"{$_POST['nombre']}\" placeholder=\"Introduce tu nombre\">
                </div>

                <div class=\"form-field mb-4 {$this->errores['apellido1']}\">
                    <label for=\"apellido1ID\" class=\"form-label\">Primer apellido</label>
                    <input {$disabled} type=\"text\" name=\"apellido1\" class=\"form-control\" id=\"apellido1ID\" value=\"{$_POST['apellido1']}\" placeholder=\"Introduce tu primer apellido\">
                </div>

                <div class=\"form-field mb-4 {$this->errores['apellido2']}\">
                    <label for=\"apellido2ID\" class=\"form-label\">Segundo apellido</label>
                    <input {$disabled} type=\"text\" name=\"apellido2\" class=\"form-control\" id=\"apellido2ID\" value=\"{$_POST['apellido2']}\" placeholder=\"Introduce tu segundo apellido\">
                </div>

                <div class=\"form-field mb-4 {$this->errores['fecha_nacimiento']}\">
                    <label for=\"fecha_nacimientoID\" class=\"form-label\">Fecha nacimiento</label>
                    <input {$disabled} type=\"date\" name=\"fecha_nacimiento\" class=\"form-control\" id=\"fecha_nacimientoID\" value=\"{$_POST['fecha_nacimiento']}\" placeholder=\"nombre@dominio.com\">
                </div>

                <div class=\"form-field mb-4 {$this->errores['email']}\">
                    <label for=\"emailID\" class=\"form-label\">Email</label>
                    <input {$disabled} type=\"email\" name=\"email\" class=\"form-control\" id=\"emailID\" value=\"{$_POST['email']}\" placeholder=\"nombre@dominio.com\">
                </div>

                {$boton_alta}
            </form>
        </div>
        ";
    }

    function listado(string $pagina)
    {
        $total_registros = $this->modelo->obtener_total_registros();
        $registros = $this->modelo->obtener_pagina_listado($pagina, TOTAL_PAGINAS_LISTADO);

        $salida = "
        <div class=\"container\" style=\"padding: 50px;\">
            <a href=\"/usuarios/alta\" class=\"theme-primary btn-solid btn-styled\">
                Alta de Usuario <i class=\"bi bi-plus-lg\"></i>
            </a>

            <table style=\"margin-top:15px\" class=\"table\">
                <thead>
                    <tr>
                        <th scope=\"col\">#</th>
                        <th scope=\"col\">ID</th>
                        <th scope=\"col\">Nombre</th>
                        <th scope=\"col\">Apellido1</th>
                        <th scope=\"col\">Apellido2</th>
                        <th scope=\"col\">Fecha Nacimiento</th>
                        <th scope=\"col\">E-mail</th>
                    </tr>
                </thead>
                <tbody>
        ";

        foreach ($registros as $registro) {
            $modificacion = "<a class=\"btn-solid theme-primary\" href=\"/usuarios/modi/{$registro['id']}\"><i class=\"bi bi-pencil-square\"></i></a>";
            $baja = "<a class=\"btn-solid theme-danger\" href=\"/usuarios/baja/{$registro['id']}\"><i class=\"bi bi-trash\"></i></a>";

            $salida .= "
                    <tr>
                        <td>{$modificacion} {$baja}</td>
                        <td>" . $registro['id'] . "</td>
                        <td>" . $registro['nombre'] . "</td>
                        <td>" . $registro['apellido1'] . "</td>
                        <td>" . $registro['apellido2'] . "</td>
                        <td>" . formato_DDMMAAAA($registro['fecha_nacimiento']) . "</td>
                        <td>" . $registro['email'] . "</td>
                    </tr>
            ";
        }

        $url_anterior = '';

        if ($pagina != "0") {
            $pagina_anterior = $pagina - 1;
            $url_anterior = "<li class=\"page-item\"><a class=\"page-link\" href=\"/usuarios/listado/{$pagina_anterior}\">Anterior</a></li>";
        }

        $url_siguiente = '';

        if ($total_registros > (($pagina + 1) * TOTAL_PAGINAS_LISTADO)) {
            $pagina_siguiente = $pagina + 1;
            $url_siguiente = "<li class=\"page-item\"><a class=\"page-link\" href=\"/usuarios/listado/{$pagina_siguiente}\">Siguiente</a></li>";
        }

        $salida .= "
                </tbody>
            </table>

            <nav aria-label=\"Page navigation example\">
                <ul class=\"pagination\">
                    {$url_anterior}
                    {$url_siguiente}
                </ul>
            </nav>
        </div>
        ";

        return $salida;
    }
}
