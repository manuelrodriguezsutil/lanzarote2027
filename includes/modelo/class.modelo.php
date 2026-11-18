<?php

class Modelo
{
    private string $tabla;

    public function __construct(string $tabla)
    {
        $this->tabla = $tabla;
    }

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

    function obtener_pagina_listado(string $pagina, string $total_paginas, string $orden = 'id')
    {
        $limite = (int) $total_paginas;
        $offset = (int) $pagina * $limite;

        $orden = $orden === 'id' ? 'id' : "{$orden}, id";

        $query = new Query("
            SELECT *
            FROM {$this->tabla}
            ORDER BY {$orden} ASC
            LIMIT {$limite}
            OFFSET {$offset};
        ");

        $registros = [];

        while ($registro = $query->recuperar()) {
            $registros[] = $registro;
        }

        return $registros;
    }
}
