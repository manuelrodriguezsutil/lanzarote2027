<?php
require_once "includes/general.php";

echo Template::header('Lanzarote');

switch ($_GET['seccion']) {
    case 'usuarios':
        $query = new Query("
                SELECT * 
                FROM usuarios
                LIMIT " . TOTAL_PAGINAS_LISTADO . "
                OFFSET " . ($_GET['pagina'] * TOTAL_PAGINAS_LISTADO) . ";
            ");

        echo "<div class=\"container\" style=\"padding: 50px;\">";
        echo "<h1>Listado de usuarios</h1> <br/>";
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
                    <td></td>
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
