<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Calculadora Laboral</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #121212; /* negro profundo */
      color: #e0e0e0; /* gris claro para texto */
    }
    .navbar, footer {
      background-color: #000000; /* negro sólido */
    }
    .card {
      background-color: #1e1e1e; /* gris oscuro */
      border: none;
      color: #e0e0e0;
    }
    .btn-primary {
      background-color: #0d6efd; /* azul Bootstrap */
      border: none;
    }
    .btn-primary:hover {
      background-color: #0b5ed7;
    }
    .btn-success {
      background-color: #198754;
      border: none;
    }
    .btn-success:hover {
      background-color: #157347;
    }
  </style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Calculadora Laboral</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#" onclick="mostrar('horas')">Horas Extras</a></li>
        <li class="nav-item"><a class="nav-link" href="#" onclick="mostrar('finiquito')">Finiquito</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Contenido dinámico -->
<div class="container mt-4" id="contenido">
  <div class="card p-4 text-center">
    <h3>Bienvenido</h3>
    <p>Selecciona una opción en el menú para comenzar.</p>
  </div>
</div>

<!-- Footer -->
<footer class="text-white text-center py-3 mt-5">
  © 2026 Calculadora Laboral
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function mostrar(opcion){
  if(opcion === 'horas'){
    $('#contenido').html(`
      <div class="card p-4">
        <h4>Cálculo de Horas Extras</h4>
        <form id="formHoras">
          <div class="mb-3">
            <label for="salario" class="form-label">Salario diario del trabajador</label>
            <input type="number" step="0.01" class="form-control" id="salario" name="salario" required>
          </div>
          <div class="mb-3">
            <label for="horas" class="form-label">Horas extras trabajadas</label>
            <input type="number" class="form-control" id="horas" name="horas" required>
          </div>
          <button type="submit" class="btn btn-primary">Calcular</button>
        </form>
        <div id="resultado" class="mt-3"></div>
      </div>
    `);

    $('#formHoras').on('submit', function(e){
      e.preventDefault();
      $.ajax({
        url: 'calculo_horas.php',
        type: 'POST',
        data: { 
          horas: $('#horas').val(),
          salario: $('#salario').val()
        },
        success: function(res){
          let data = JSON.parse(res);
          $('#resultado').html(`
            <p><strong>Pago doble:</strong> $${data.doble}</p>
            <p><strong>Pago triple:</strong> $${data.triple}</p>
            <p><strong>Total:</strong> $${data.total}</p>
          `);
        }
      });
    });
  }
  else if(opcion === 'finiquito'){
    $('#contenido').html(`
      <div class="card p-4">
        <h4>Cálculo de Finiquito</h4>
        <form id="formFiniquito">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del trabajador</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
          </div>
          <div class="mb-3">
            <label for="sueldo" class="form-label">Sueldo semanal</label>
            <input type="number" step="0.01" class="form-control" id="sueldo" name="sueldo" required>
          </div>
          <div class="mb-3">
            <label for="inicio" class="form-label">Fecha de inicio</label>
            <input type="date" class="form-control" id="inicio" name="inicio" required>
          </div>
          <div class="mb-3">
            <label for="termino" class="form-label">Fecha de término</label>
            <input type="date" class="form-control" id="termino" name="termino" required>
          </div>
          <div class="mb-3">
            <label for="despido" class="form-label">¿Despido injustificado?</label>
            <select class="form-select" id="despido" name="despido">
              <option value="no">No</option>
              <option value="si">Sí</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Calcular</button>
        </form>
        <div id="resultadoFiniquito" class="mt-3"></div>
      </div>
    `);

    $('#formFiniquito').on('submit', function(e){
      e.preventDefault();
      $.ajax({
        url: 'calculo_finiquito.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(res){
          let data = JSON.parse(res);
          $('#resultadoFiniquito').html(`
            <div class="card bg-dark text-light p-4">
              <h5 class="card-title">Resultados para ${data.nombre}</h5>
              <div class="table-responsive">
                <table class="table table-dark table-striped table-bordered align-middle">
                  <thead>
                    <tr>
                      <th>Concepto</th>
                      <th>Monto</th>
                      <th>Detalle del cálculo</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr><td>Aguinaldo proporcional</td><td>$${data.aguinaldo}</td><td>${data.detalle_aguinaldo}</td></tr>
                    <tr><td>Vacaciones</td><td>$${data.vacaciones}</td><td>${data.detalle_vacaciones}</td></tr>
                    <tr><td>Prima vacacional</td><td>$${data.prima_vacacional}</td><td>${data.detalle_prima}</td></tr>
                    <tr><td>Indemnización</td><td>$${data.indemnizacion}</td><td>${data.detalle_indemnizacion}</td></tr>
                    <tr><td>Prima de antigüedad</td><td>$${data.prima_antiguedad}</td><td>${data.detalle_antiguedad}</td></tr>
                  </tbody>
                </table>
              </div>
              <div class="card-footer text-end">
                <h5>Total a pagar: <span class="text-success">$${data.total}</span></h5>
                <button id="btnPDF" class="btn btn-success mt-3">Descargar PDF</button>
              </div>
            </div>
          `);

          // Evento para generar PDF
          $('#btnPDF').on('click', function(){
          $.ajax({
            url: 'generar_pdf.php',
            type: 'POST',
            data: { resultados: data },
            xhrFields: { responseType: 'blob' },   // <-- esto ya lo tienes
            processData: true,                     // asegura que se envíen los datos
            success: function(blob){
              // Crear enlace temporal para descargar
              var link = document.createElement('a');
              var url = window.URL.createObjectURL(blob);
              link.href = url;
              link.download = "finiquito.pdf";
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
              window.URL.revokeObjectURL(url);
            }
          });
        });

        }
      });
    });
  }
}
</script>
</body>
</html>
