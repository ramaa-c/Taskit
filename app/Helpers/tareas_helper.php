<?php

function orden_link($campo, $actual, $direccion, $etiqueta = null) {
    if (!in_array($campo, ['prioridad', 'estado', 'fecha_vencimiento', 'fecha_recordatorio', 'fecha_creacion'])) {
        return $etiqueta ?? ucfirst($campo);
    }

    $icon = '';
    if ($campo === $actual) {
        $icon = $direccion === 'asc' ? '↑' : '↓';
    }
    $url = "?orden=$campo&dir=" . ($campo === $actual ? ($direccion === 'asc' ? 'desc' : 'asc') : 'asc');
    return "<a href='$url'>" . ($etiqueta ?? ucfirst($campo)) . " $icon</a>";
}
