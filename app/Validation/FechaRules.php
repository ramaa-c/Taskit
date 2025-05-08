<?php

namespace App\Validation;

class FechaRules{

    public function after_today(string $str, string $fields, array $data): bool{

        if (empty($str)) {
            return true;
        }

        $fechaIngresada = strtotime($str);
        $hoy = strtotime(date('Y-m-d'));

        return $fechaIngresada > $hoy;
    }

    public function recordatorio_valido(string $fechaRecordatorio, string $fields, array $data): bool{

        if (empty($fechaRecordatorio)) {
            return true;
        }

        $recordatorio = strtotime($fechaRecordatorio);
        $hoy = strtotime(date('Y-m-d'));

        if (!isset($data['fecha_vencimiento']) || empty($data['fecha_vencimiento'])) {
            return false;
        }

        $vencimiento = strtotime($data['fecha_vencimiento']);

        return $recordatorio >= $hoy && $recordatorio <= $vencimiento;
    }

}
