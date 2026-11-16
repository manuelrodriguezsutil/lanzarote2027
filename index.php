<?php
require_once "includes/general.php";

echo Template::header('Lanzarote');

switch ($_GET['seccion']) {
    case 'usuarios':
        $url_form = '/usuarios/alta';

        switch ($_GET['oper']) {
            case 'modi':
                $id = $_GET["pagina"];
                $url_form = '/usuarios/modi/' . $id;
            case 'alta':
                define("OPERACION", $_GET['oper'] == 'modi' ? 'Modificación' : 'Alta');

                $boton_alta = "<input type=\"submit\" class=\"theme-primary btn-solid btn-styled\" value=\"Alta de usuario\" />";

                $errores = false;
                $disabled = '';

                if ($_POST['paso'] == '1') {

                    $_texto_errores = '';

                    if (strlen($_POST['nombre']) < 3) {

                        $_error_nombre = 'error';
                        $_texto_errores = '<p>- El nombre debe tener al menos 3 caracteres.</p>';

                        $errores = true;
                    }

                    if (strlen($_POST['apellido1']) < 3) {

                        $_error_apellido1 = 'error';
                        $_texto_errores .= '<p>- El primer apellido debe tener al menos 3 caracteres.</p>';

                        $errores = true;
                    }

                    if (strlen($_POST['apellido2']) < 3) {

                        $_error_apellido2 = 'error';
                        $_texto_errores .= '<p>- El segundo apellido debe tener al menos 3 caracteres.</p>';

                        $errores = true;
                    }


                    if (!fecha_valida($_POST['fecha_nacimiento'])) {

                        $_error_fecha_nacimiento = 'error';
                        $_texto_errores .= '<p>- La fecha de nacimiento no tiene un formato válido.</p>';

                        $errores = true;
                    }

                    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

                        $_error_email = 'error';
                        $_texto_errores .= '<p>- El E-mail no tiene un formato válido.</p>';

                        $errores = true;
                    }

                    if ($errores) {
                        $mensajes_errores = "
                                <div class=\"info_errores mb-5 mt-5\" role=\"alert\">
                                {$_texto_errores}
                                </div>
                            
                            ";
                    } else {
                        $mensaje_exito = "
                                <div class=\"alert theme-success  mb-5 mt-5\" role=\"alert\">
                                    <p>Operación realizada con éxito</p>
                                </div>
                            ";
                        $boton_alta = '';

                        $disabled = " disabled=\"disabled\" ";

                        switch ($_GET['oper']) {
                            case 'alta':
                                $query = new Query("
                                        INSERT INTO usuarios
                                        (
                                            nombre    
                                            ,apellido1 
                                            ,apellido2 
                                            ,fecha_nacimiento 
                                            ,email 

                                            ,fecha_alta   
                                            ,ip_alta      
                                            ,fecha_modi   
                                            ,ip_modi      
                                        )
                                        VALUES
                                        (
                                            '" . $_POST['nombre']           . "'
                                            ,'" . $_POST['apellido1']        . "'
                                            ,'" . $_POST['apellido2']        . "'
                                            ,'" . $_POST['fecha_nacimiento'] . "'
                                            ,'" . $_POST['email']            . "'


                                            ,now()
                                            ,'" . $_SERVER['SERVER_ADDR']    . "'
                                            ,now()
                                            ,'" . $_SERVER['SERVER_ADDR']    . "'
                                            
                                        )
                                    
                                    ");
                                break;
                            case 'modi':
                                $query = new Query("
                                        UPDATE usuarios

                                        SET
                                             nombre             = '" . $_POST['nombre']           . "'
                                            ,apellido1          = '" . $_POST['apellido1']        . "'
                                            ,apellido2          = '" . $_POST['apellido2']        . "'
                                            ,fecha_nacimiento   = '" . $_POST['fecha_nacimiento'] . "'
                                            ,email              = '" . $_POST['email']            . "'

                                            ,fecha_modi         = now()
                                            ,ip_modi            = '" . $_SERVER['SERVER_ADDR']    . "'

                                        WHERE id = '{$id}'
                                    
                                    ");
                                break;
                        }
                    }
                }

                echo "
                <div class=\"container\" style=\"padding: 50px;\">
                    <a href=\"/usuarios/listado/0\" class=\"btn-solid theme-info mb-5\">Volver <i class=\"bi bi-arrow-return-left\"></i></a>

                    <h1>" . OPERACION . " de usuarios</h1>

                    {$mensajes_errores}
                    {$mensaje_exito}

                    <form action=\"{$url_form}\" method=\"POST\">

                        <input type=\"hidden\" name=\"paso\" value=\"1\" />

                        <input type=\"hidden\" name=\"id\" value=\"{$_POST['id']}\" />

                        <div class=\"form-field mb-4 {$_error_nombre}\">
                            <label for=\"nombreID\" class=\"form-label\">Nombre</label>
                            <input {$disabled} type=\"text\" name=\"nombre\" class=\"form-control\" id=\"nombreID\" value=\"{$_POST['nombre']}\" placeholder=\"Introduce tu nombre\">
                        </div>

                        <div class=\"form-field mb-4 {$_error_apellido1}\">
                            <label for=\"apellido1ID\" class=\"form-label\">Primer apellido</label>
                            <input {$disabled} type=\"text\" name=\"apellido1\" class=\"form-control\" id=\"apellido1ID\" value=\"{$_POST['apellido1']}\" placeholder=\"Introduce tu primer apellido\">
                        </div>

                        <div class=\"form-field mb-4 {$_error_apellido2}\">
                            <label for=\"apellido2ID\" class=\"form-label\">Segundo apellido</label>
                            <input {$disabled} type=\"text\" name=\"apellido2\" class=\"form-control\" id=\"apellido2ID\" value=\"{$_POST['apellido2']}\" value=\"{$_POST['nombre']}\" placeholder=\"Introduce tu segundo apellido\">
                        </div>

                        <div class=\"form-field mb-4 {$_error_fecha_nacimiento}\">
                            <label for=\"fecha_nacimientoID\" class=\"form-label\">Fecha nacimiento</label>
                            <input {$disabled} type=\"date\" name=\"fecha_nacimiento\" class=\"form-control\" id=\"fecha_nacimientoID\" value=\"{$_POST['fecha_nacimiento']}\" placeholder=\"nombre@dominio.com\">
                        </div>

                        <div class=\"form-field mb-4 {$_error_email}\">
                            <label for=\"emailID\" class=\"form-label\">Email</label>
                            <input {$disabled} type=\"email\" name=\"email\" class=\"form-control\" id=\"emailID\" value=\"{$_POST['email']}\" placeholder=\"nombre@dominio.com\">
                        </div>

                        {$boton_alta}

                    </form>
                </div>";
                break;

            default:
                $query = new Query("
                SELECT * 
                FROM usuarios
                LIMIT " . TOTAL_PAGINAS_LISTADO . "
                OFFSET " . ($_GET['pagina'] * TOTAL_PAGINAS_LISTADO) . ";
                ");

                echo "<div class=\"container\" style=\"padding: 50px;\">";
                echo "<h1>Listado de usuarios</h1> <br/>";
                echo "<a href=\"/usuarios/alta\" class=\"theme-primary btn-solid btn-styled\">
                        Alta de Usuario <i class=\"bi bi-plus-lg\"></i>
                    </a>";
                echo "<table class=\"table\">
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

                while ($registro = $query->recuperar()) {
                    echo "
                        <tr>
                            <td>
                                <a href=\"/usuarios/modi/{$registro['id']}\">
                                    <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-pencil-square\" viewBox=\"0 0 16 16\">
                                    <path d=\"M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z\"/>
                                    <path fill-rule=\"evenodd\" d=\"M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z\"/>
                                    </svg>
                                </a>
                            </td>
                            <td>" . $registro['id'] . "</td>
                            <td>" . $registro['nombre'] . "</td>
                            <td>" . $registro['apellido1'] . "</td>
                            <td>" . $registro['apellido2'] . "</td>
                            <td>" . $registro['fecha_nacimiento'] . "</td>
                            <td>" . $registro['email'] . "</td>
                        </tr>
                        ";
                }

                $url_anterior = '';
                if ($_GET['pagina'] != "0") {
                    $pagina_anterior = $_GET['pagina'] - 1;
                    $url_anterior = "<li class=\"page-item\"><a class=\"page-link\" href=\"/usuarios/listado/{$pagina_anterior}\">Anterior</a></li>";
                }

                $pagina_siguiente = $_GET['pagina'] + 1;
                $url_siguiente = "<li class=\"page-item\"><a class=\"page-link\" href=\"/usuarios/listado/{$pagina_siguiente}\">Siguiente</a></li>";
                if ($query->total < TOTAL_PAGINAS_LISTADO)
                    $url_siguiente = '';

                echo "
                        </tbody>
                    </table>
                    ";

                echo "
                    <nav aria-label=\"Page navigation example\">
                        <ul class=\"pagination\">
                            {$url_anterior}
                            {$url_siguiente}
                        </ul>
                    </nav>
                    ";

                echo "</div>";

                break;
        }

        break;

    default:
        echo "<div class=\"container\" style=\"padding: 50px;\">
                <div id=\"carouselStackedIndicators\" class=\"carousel slide\">
                    <div class=\"carousel-inner rounded-5\">
                        <div class=\"carousel-item active\">
                        <img src=\"/img/fondo1.jpeg\" class=\"d-block w-100\" alt=\"...\">
                        </div>
                        <div class=\"carousel-item\">
                        <img src=\"/img/fondo2.avif\" class=\"d-block w-100\" alt=\"...\">
                        </div>
                        <div class=\"carousel-item\">
                        <img src=\"/img/fondo3.avif\" class=\"d-block w-100\" alt=\"...\">
                        </div>
                    </div>
                    <div class=\"d-flex justify-content-between align-items-center\">
                        <div>
                        <button class=\"btn-icon btn-sm\" type=\"button\" data-bs-target=\"#carouselStackedIndicators\" data-bs-slide=\"prev\">
                            <span class=\"carousel-icon-prev\" aria-hidden=\"true\"></span>
                            <span class=\"visually-hidden\">Previous</span>
                        </button>
                        <button class=\"btn-icon btn-sm\" type=\"button\" data-bs-target=\"#carouselStackedIndicators\" data-bs-slide=\"next\">
                            <span class=\"carousel-icon-next\" aria-hidden=\"true\"></span>
                            <span class=\"visually-hidden\">Next</span>
                        </button>
                        </div>
                        <div class=\"carousel-indicators\">
                        <button type=\"button\" data-bs-target=\"#carouselStackedIndicators\" data-bs-slide-to=\"0\" class=\"active\" aria-current=\"true\" aria-label=\"Slide 1\"></button>
                        <button type=\"button\" data-bs-target=\"#carouselStackedIndicators\" data-bs-slide-to=\"1\" aria-label=\"Slide 2\"></button>
                        <button type=\"button\" data-bs-target=\"#carouselStackedIndicators\" data-bs-slide-to=\"2\" aria-label=\"Slide 3\"></button>
                        </div>
                    </div>
                </div>
            </div>";
        break;
}

echo Template::footer();
