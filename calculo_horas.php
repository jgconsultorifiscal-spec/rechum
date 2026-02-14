<?php
$horas = intval($_POST['horas']);
$salario_diario = 249; // salario mínimo general
$salario_hora = $salario_diario / 8;

$pago_doble = min($horas, 9) * ($salario_hora * 2);
$pago_triple = max($horas - 9, 0) * ($salario_hora * 3);
$total = $pago_doble + $pago_triple;

echo json_encode([
  'doble' => number_format($pago_doble, 2),
  'triple' => number_format($pago_triple, 2),
  'total' => number_format($total, 2)
]);
?>
