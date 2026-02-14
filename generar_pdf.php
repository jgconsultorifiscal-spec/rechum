<?php
require 'vendor/autoload.php'; // Asegúrate de tener Dompdf instalado con Composer
use Dompdf\Dompdf;

$data = $_POST['resultados']; // JSON con los cálculos

// Logo (puedes usar una ruta local o URL pública)
$logo = "https://tusitio.com/logo.png"; 

// Construcción del HTML estilizado
$html = "
<html>
<head>
  <style>
    body { font-family: DejaVu Sans, sans-serif; color: #333; }
    .header { text-align: center; border-bottom: 2px solid #0d6efd; padding-bottom: 10px; margin-bottom: 20px; }
    .header img { height: 60px; }
    .header h2 { margin: 5px 0; color: #0d6efd; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th, td { border: 1px solid #999; padding: 8px; text-align: left; }
    th { background-color: #0d6efd; color: #fff; }
    .footer { text-align: center; font-size: 12px; margin-top: 30px; color: #666; }
    .total { font-size: 18px; font-weight: bold; color: #198754; text-align: right; }
  </style>
</head>
<body>
  <div class='header'>
    <img src='$logo' alt='Logo'>
    <h2>Finiquito Laboral</h2>
    <p>Trabajador: {$data['nombre']}</p>
    <p>Fecha de cálculo: ".date('d/m/Y')."</p>
  </div>

  <table>
    <thead>
      <tr>
        <th>Concepto</th>
        <th>Monto</th>
        <th>Detalle del cálculo</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>Aguinaldo proporcional</td><td>\${$data['aguinaldo']}</td><td>{$data['detalle_aguinaldo']}</td></tr>
      <tr><td>Vacaciones</td><td>\${$data['vacaciones']}</td><td>{$data['detalle_vacaciones']}</td></tr>
      <tr><td>Prima vacacional</td><td>\${$data['prima_vacacional']}</td><td>{$data['detalle_prima']}</td></tr>
      <tr><td>Indemnización</td><td>\${$data['indemnizacion']}</td><td>{$data['detalle_indemnizacion']}</td></tr>
      <tr><td>Prima de antigüedad</td><td>\${$data['prima_antiguedad']}</td><td>{$data['detalle_antiguedad']}</td></tr>
    </tbody>
  </table>

  <div class='total'>Total a pagar: \${$data['total']}</div>

  <div class='footer'>
    Documento generado automáticamente por el sistema de cálculo laboral.<br>
    © ".date('Y')." Tu Empresa. Todos los derechos reservados.
  </div>
</body>
</html>
";

// Generar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Enviar como binario para descarga
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="finiquito.pdf"');
echo $dompdf->output();
?>
