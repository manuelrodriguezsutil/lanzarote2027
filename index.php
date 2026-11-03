<?php
require_once "includes/general.php";

echo Template::header('Lanzarote');

switch ($_GET['seccion']) {
    case 'usuarios':
        // cargamos la sección usuarios
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
