<?php
class Pelicula
{
    private Modelo $modelo;

    private string $id;
    private string $titulo;
    private string $director;
    private string $year;
    private string $genero;
    private string $duracion;

    function __construct()
    {
        $this->modelo = new Modelo('peliculas');
    }
}
