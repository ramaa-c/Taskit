<?php

namespace App\Validation;

class FechaRules {

    public function after_today(string $str, ?string $fields = null, array $data = []): bool {
        if (empty($str)) {
            return true;
        }

        $fechaIngresada = strtotime($str);
        $hoy = strtotime(date('Y-m-d'));

        return $fechaIngresada > $hoy;
    }

    public function recordatorio_valido(string $fechaRecordatorio, ?string $fields = null, array $data = []): bool {
        if (empty($fechaRecordatorio)) {
            return true;
        }

        $fechaVencimiento = $data['fecha_vencimiento'] ?? $_POST['fecha_vencimiento'] ?? null;

        if (empty($fechaVencimiento)) {
            return false;
        }

        $recordatorio = strtotime($fechaRecordatorio);
        $vencimiento = strtotime($fechaVencimiento);
        $hoy = strtotime(date('Y-m-d'));

        return $recordatorio >= $hoy && $recordatorio <= $vencimiento;
    }

}

