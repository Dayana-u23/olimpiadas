const params = new URLSearchParams(window.location.search);
const idPedido = params.get('id_pedido');

if (!idPedido) {
  document.getElementById('detalleCompra').innerHTML = '<p>No se encontró el pedido.</p>';
} else {
  fetch(`php/get_pedido.php?id_pedido=${idPedido}`)
    .then(res => res.json())
    .then(data => {
      if (!data.ok) {
        document.getElementById('detalleCompra').innerHTML = `<p>Error: ${data.msg}</p>`;
        return;
      }
      // Aquí mostrar datos...
    })
    .catch(() => {
      document.getElementById('detalleCompra').innerHTML = '<p>Error al cargar los datos.</p>';
    });
}
