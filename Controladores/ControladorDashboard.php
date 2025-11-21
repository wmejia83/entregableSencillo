<?php
// Controladores/ControladorDashboard.php

require_once "Modelos/ModeloDashboard.php";

class ControladorDashboard
{
    /**
     * Devuelve el resumen principal del dashboard.
     */
    public static function obtenerResumen(): array
    {
        return ModeloDashboard::obtenerResumen();
    }

    /**
     * Devuelve los últimos productos para mostrar en la vista.
     */
    public static function ultimosProductos(int $limit = 5): array
    {
        return ModeloDashboard::obtenerUltimosProductos($limit);
    }

    /**
     * Devuelve los últimos usuarios creados.
     */
    public static function ultimosUsuarios(int $limit = 5): array
    {
        return ModeloDashboard::obtenerUltimosUsuarios($limit);
    }
}
