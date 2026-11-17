<?php

class Modelo
{
    private string $tabla;

    public function __construct(string $tabla)
    {
        $this->tabla = $tabla;
    }

    function recuperar(string $id)
    {
        $query = new Query("
            SELECT *
            FROM {$this->tabla}
            WHERE id = '{$id}';
        ");

        return $query->recuperar();
    }

    function eliminar(string $id)
    {
        $query = new Query("
            DELETE FROM {$this->tabla}
            WHERE id = {$id};
        ");

        return $query->total;
    }

    function modificar(array $registro)
    {
        $id = $registro['id'];

        $query = new Query("
            UPDATE {$this->tabla} 
            SET
                nombre = '" . $registro['nombre'] . "',
                apellido1 = '" . $registro['apellido1'] . "',
                apellido2 = '" . $registro['apellido2'] . "',
                fecha_nacimiento = '" . $registro['fecha_nacimiento'] . "',
                email = '" . $registro['email'] . "',
                fecha_modi = NOW(),
                ip_modi = '" . $_SERVER['SERVER_ADDR'] . "',
            WHERE id = '{$id}'
        ");

        return $query->total;
    }

    function obtener_total_registros()
    {
        $query = new Query("
                    SELECT count(*) as total_registros
                    FROM usuarios
                ");

        $registro = $query->recuperar();
        $total_registros = $registro['total_registros'];

        return $total_registros;
    }

    function obtener_pagina_listado(string $pagina, string $total_paginas)
    {
        $query = new Query("
            SELECT *
            FROM usuarios
            ORDER BY fecha_alta DESC
            LIMIT " . $total_paginas . "
            OFFSET " . ($pagina * $total_paginas) . "
            ;
        ");
    }
}
