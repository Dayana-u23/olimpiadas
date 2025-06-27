
  document.addEventListener('DOMContentLoaded', function () {
    // Simulamos las ventas que están guardadas (reemplazá esto por tus datos reales)
    // Ejemplo de estructura de ventas:
    // localStorage.setItem('ventas', JSON.stringify([
    //   { fecha: '24/06/2025', producto: 'Tour a Cataratas del Iguazú', cantidad: 2, precioUnitario: 30000 },
    //   { fecha: '22/06/2025', producto: 'City Tour por Buenos Aires', cantidad: 5, precioUnitario: 4000 },
    //   // ...
    // ]));

    // Obtener ventas guardadas o arreglo vacío
    const ventas = JSON.parse(localStorage.getItem('ventas')) || [];

    let totalVentas = 0;
    let totalProductos = 0;
    const comisionPorcentaje = 0.10; // 10% de comisión
    let comisiones = 0;

    // Referencias a elementos para mostrar datos
    const totalVentasElem = document.querySelector('.card.border-success .card-text');
    const productosVendidosElem = document.querySelector('.card.border-info .card-text');
    const comisionesElem = document.querySelector('.card.border-warning .card-text');
    const tablaCuerpo = document.querySelector('table tbody');

    // Vaciar la tabla antes de llenar
    tablaCuerpo.innerHTML = '';

    ventas.forEach(venta => {
      const total = venta.cantidad * venta.precioUnitario;
      totalVentas += total;
      totalProductos += venta.cantidad;

      // Crear fila de la tabla
      const fila = document.createElement('tr');
      fila.innerHTML = `
        <td>${venta.fecha}</td>
        <td>${venta.producto}</td>
        <td>${venta.cantidad}</td>
        <td>$${venta.precioUnitario.toLocaleString()}</td>
        <td>$${total.toLocaleString()}</td>
      `;
      tablaCuerpo.appendChild(fila);
    });

    comisiones = totalVentas * comisionPorcentaje;

    // Mostrar totales en las tarjetas
    totalVentasElem.textContent = `$${totalVentas.toLocaleString()}`;
    productosVendidosElem.textContent = totalProductos;
    comisionesElem.textContent = `$${comisiones.toLocaleString()}`;
  });
function guardarVenta(venta) {
  let ventas = JSON.parse(localStorage.getItem('ventas')) || [];
  ventas.push(venta);
  localStorage.setItem('ventas', JSON.stringify(ventas));
}

// Ejemplo de venta guardada al comprar un paquete
guardarVenta({
  fecha: new Date().toLocaleDateString('es-AR'),
  producto: 'Tour a Cataratas del Iguazú',
  cantidad: 1,
  precioUnitario: 30000
});
document.getElementById('total-ventas').textContent = '$123.456';
