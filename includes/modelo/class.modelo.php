<?php

class Modelo
{
    private string $tabla;

    public function __construct(string $tabla)
    {
        $this->tabla = $tabla;
    }

    //Escapa un valor para poder meterlo de forma segura dentro de una consulta
    private function escapar(?string $valor)
    {
        return BBDD::getInstancia()->getConexion()->real_escape_string($valor ?? '');
    }

    function recuperar(string $id)
    {
        $id = $this->escapar($id);

        $query = new Query("
            SELECT *
            FROM {$this->tabla}
            WHERE id = '{$id}';
        ");

        return $query->recuperar();
    }

    //Recibe un array campo => valor y lo inserta en la tabla. Devuelve el id del nuevo registro
    function insertar(array $registro)
    {
        $valores = [];
        foreach ($registro as $valor)
            $valores[] = "'" . $this->escapar($valor) . "'";

        $query = new Query("
            INSERT INTO {$this->tabla}
            (" . implode(', ', array_keys($registro)) . ")
            VALUES
            (" . implode(', ', $valores) . ")
        ");

        return $query->getLastInsertId();
    }

    function eliminar(string $id)
    {
        $id = $this->escapar($id);

        $query = new Query("
            DELETE FROM {$this->tabla}
            WHERE id = '{$id}';
        ");

        return $query->total;
    }

    //Recibe un array campo => valor (con el id incluido) y actualiza ese registro
    function modificar(array $registro)
    {
        $id = $this->escapar($registro['id']);
        unset($registro['id']);

        $asignaciones = [];
        foreach ($registro as $campo => $valor)
            $asignaciones[] = "{$campo} = '" . $this->escapar($valor) . "'";

        $query = new Query("
            UPDATE {$this->tabla}
            SET " . implode(', ', $asignaciones) . "
            WHERE id = '{$id}'
        ");

        return $query->total;
    }

    function obtener_total_registros()
    {
        $query = new Query("
                    SELECT count(*) as total_registros
                    FROM {$this->tabla}
                ");

        $registro = $query->recuperar();
        $total_registros = $registro['total_registros'];

        return $total_registros;
    }

    //Devuelve un array con los registros de la página indicada
    function obtener_pagina_listado(string $pagina, string $total_paginas, string $orden = 'id')
    {
        $query = new Query("
            SELECT *
            FROM {$this->tabla}
            ORDER BY {$orden}
            LIMIT " . (int) $total_paginas . "
            OFFSET " . ((int) $pagina * (int) $total_paginas) . "
            ;
        ");

        $registros = [];
        while ($registro = $query->recuperar())
            $registros[] = $registro;

        return $registros;
    }
}
