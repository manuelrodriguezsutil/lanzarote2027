<?php

class Usuario extends Modelo
{
    public string $tabla;

    function __construct()
    {
        $this->tabla = 'usuarios';
    }
}
