<?php
class Template
{
    function __construct($idioma = 'ES')
    {
        $this->$idioma = $idioma;
    }

    static function header(string $titulo, string $descripcion = '')
    {
        return "
        <!doctype html>
        <html lang=\"es\" data-bs-theme=\"light\">
        <head>
            <meta charset=\"utf-8\">
            <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
            <title>{$titulo}</title>
            <meta name=\"description\" content=\"{$descripcion}\">
            <!-- Bootstrap 6, bundled in assets/vendor/bootstrap6 (version in the JS bundle header). To load it from jsDelivr instead, replace both paths below with https://cdn.jsdelivr.net/npm/bootstrap@VERSION/dist/... -->
            <link rel=\"stylesheet\" href=\"assets/vendor/bootstrap6/css/bootstrap.min.css\">
            <script type=\"importmap\">{\"imports\": {\"bootstrap\": \"./assets/vendor/bootstrap6/js/bootstrap.bundle.min.js\"}}</script>
            <link rel=\"stylesheet\" href=\"assets/css/base.css\">
            <link rel=\"stylesheet\" href=\"assets/css/style.css\">
            <link rel=\"stylesheet\" href=\"assets/css/print.css\" media=\"print\">
        </head>
        <body>
        " . self::navegacion();
    }

    static function navegacion()
    {
        return "
        <a class=\"visually-hidden-focusable btn-solid theme-primary m-3\" href=\"#main\">Skip to main content</a>

        <header class=\"site-header no-print\">

        <div class=\"wrap head-in\">
            <a class=\"wordmark\" href=\"index.php\">Lanzarote</a>

            <nav class=\"head-nav\" aria-label=\"Main\">
                <a class=\"head-link is-active\" href=\"index.html#work\">Work</a>
                <a class=\"head-link is-active\" href=\"index.html#writing\">Writing</a>
                <a class=\"head-link\" href=\"project.html\">Case study</a>
                <a class=\"head-link\" href=\"index.html#contact\">Contact</a>
            </nav>

            <div class=\"d-flex align-items-center gap-3\">

            <button class=\"btn-icon\" type=\"button\" id=\"themeToggle\" aria-label=\"Toggle color mode\">
                <svg class=\"theme-icon-light\" width=\"18\" height=\"18\" viewBox=\"0 0 16 16\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"1.6\" stroke-linecap=\"round\" aria-hidden=\"true\"><circle cx=\"8\" cy=\"8\" r=\"3.25\"/><path d=\"M8 1v1.5M8 13.5V15M15 8h-1.5M2.5 8H1m11-4-1 1M5 11l-1 1m0-8 1 1m6 6 1 1\"/></svg>
                <svg class=\"theme-icon-dark\" width=\"18\" height=\"18\" viewBox=\"0 0 16 16\" fill=\"currentColor\" aria-hidden=\"true\"><path d=\"M6 .278a.77.77 0 0 1 .08.858 7.2 7.2 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277q.792-.001 1.533-.16a.79.79 0 0 1 .81.316.73.73 0 0 1-.031.893A8.35 8.35 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.75.75 0 0 1 6 .278\"/></svg>
            </button>

            <button class=\"btn-solid theme-primary btn-sm\" type=\"button\" data-print>Print CV</button>

            </div>
        </div>

        </header>
        ";
    }

    static function footer()
    {
        return "
        <footer class=\"site-footer no-print\">
            <div class=\"wrap\">
                <p class=\"fs-sm fg-3 mb-0\">
                &copy; <span data-year>2026</span> Marit Halvorsen &mdash; a free Bootstrap 6 template.
                The person, the roles and every project here are fictional.
                </p>
            </div>
        </footer>

        <script type=\"module\" src=\"assets/js/main.js\"></script>
        </body>
        ";
    }
}
