<?php
$nombre = $_POST['nombre'];
$sueldo_semanal = floatval($_POST['sueldo']);
$fecha_inicio = new DateTime($_POST['inicio']);
$fecha_termino = new DateTime($_POST['termino']);
$despido = $_POST['despido'];

// Antigüedad total
$dias_antiguedad = $fecha_inicio->diff($fecha_termino)->days;
$anios_antiguedad = floor($dias_antiguedad / 365);

// Sueldo diario
$sueldo_diario = $sueldo_semanal / 7;

// Función con tabla oficial de vacaciones (reforma 2023)
function diasVacaciones($anios) {
    if ($anios == 1) return 12;
    if ($anios == 2) return 14;
    if ($anios == 3) return 16;
    if ($anios == 4) return 18;
    if ($anios == 5) return 20;
    if ($anios >= 6 && $anios <= 10) return 22;
    if ($anios >= 11 && $anios <= 15) return 24;
    if ($anios >= 16 && $anios <= 20) return 26;
    if ($anios >= 21 && $anios <= 25) return 28;
    if ($anios >= 26 && $anios <= 30) return 30;
    if ($anios >= 31 && $anios <= 35) return 32;
    if ($anios >= 36 && $anios <= 40) return 34;
    return 12; // valor mínimo por defecto
}

// Aguinaldo proporcional (solo desde 1 de enero hasta fecha de término)
$inicio_ejercicio = new DateTime($fecha_termino->format('Y')."-01-01");
$dias_en_ejercicio = $inicio_ejercicio->diff($fecha_termino)->days + 1;
$aguinaldo = ($sueldo_diario * 15) * ($dias_en_ejercicio / 365);
$detalle_aguinaldo = "Aguinaldo = (Sueldo diario $sueldo_diario × 15 días) × ($dias_en_ejercicio / 365)";

// Vacaciones (último año de antigüedad o proporcional)
$dias_vacaciones_tabla = diasVacaciones($anios_antiguedad);
$dias_trabajados_ultimo_anio = min($dias_antiguedad, 365);
$vacaciones = $dias_vacaciones_tabla * ($dias_trabajados_ultimo_anio / 365) * $sueldo_diario;
$detalle_vacaciones = "Vacaciones = Días tabla $dias_vacaciones_tabla × ($dias_trabajados_ultimo_anio / 365) × Sueldo diario $sueldo_diario";

// Prima vacacional (25%)
$prima_vacacional = $vacaciones * 0.25;
$detalle_prima = "Prima vacacional = Vacaciones $vacaciones × 25%";

// Indemnización (3 meses de sueldo si despido injustificado)
$indemnizacion = ($despido === 'si') ? ($sueldo_semanal * 4 * 3) : 0;
$detalle_indemnizacion = ($despido === 'si') 
    ? "Indemnización = Sueldo semanal $sueldo_semanal × 4 semanas × 3 meses"
    : "No aplica (no hubo despido injustificado)";

// Prima de antigüedad (12 días por año si aplica, con tope de 2 SMG)
$prima_antiguedad = ($anios_antiguedad >= 15) ? ($sueldo_diario * 12 * $anios_antiguedad) : 0;
$detalle_antiguedad = ($anios_antiguedad >= 15) 
    ? "Prima antigüedad = Sueldo diario $sueldo_diario × 12 días × $anios_antiguedad años"
    : "No aplica (menos de 15 años de servicio)";

// Total
$total = $aguinaldo + $vacaciones + $prima_vacacional + $indemnizacion + $prima_antiguedad;

echo json_encode([
  'nombre' => $nombre,
  'aguinaldo' => number_format($aguinaldo, 2),
  'detalle_aguinaldo' => $detalle_aguinaldo,
  'vacaciones' => number_format($vacaciones, 2),
  'detalle_vacaciones' => $detalle_vacaciones,
  'prima_vacacional' => number_format($prima_vacacional, 2),
  'detalle_prima' => $detalle_prima,
  'indemnizacion' => number_format($indemnizacion, 2),
  'detalle_indemnizacion' => $detalle_indemnizacion,
  'prima_antiguedad' => number_format($prima_antiguedad, 2),
  'detalle_antiguedad' => $detalle_antiguedad,
  'total' => number_format($total, 2)
]);
?>
