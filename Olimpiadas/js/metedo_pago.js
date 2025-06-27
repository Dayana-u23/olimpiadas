document.addEventListener('DOMContentLoaded', () => {
  const metodoPago = document.getElementById('metodoPago');
  const formularioTarjeta = document.getElementById('formularioTarjeta');
  const finalizarCompra = document.getElementById('finalizarCompra');
  const montoTotal = document.getElementById('montoTotal');
  const pagoModal = document.getElementById('pagoModal');

  metodoPago.addEventListener('change', () => {
    formularioTarjeta.style.display = metodoPago.value === 'tarjeta' ? 'block' : 'none';
  });

  pagoModal.addEventListener('show.bs.modal', () => {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const total = carrito.reduce((acc, item) => acc + item.precio * (item.cantidad || 1), 0);
    montoTotal.textContent = `$ ${total.toLocaleString()}`;
  });

  finalizarCompra.addEventListener('click', () => {
    const metodo = metodoPago.value;
    if (!metodo) {
      alert('Por favor selecciona un método de pago');
      return;
    }

    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];

    if (carrito.length === 0) {
      alert('El carrito está vacío');
      return;
    }

    const productos = carrito.map(p => ({
      id_producto: parseInt(p.id_producto),
      descripcion: p.descripcion || '',
      precio: parseFloat(p.precio),
      cantidad: parseInt(p.cantidad) || 1
    }));

    const compra = {
      productos: productos,
      metodo_pago: metodo,
      fecha: new Date().toISOString(),
      id_cliente: parseInt(localStorage.getItem('id_cliente'))
    };

    fetch('php/procesar_compra.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(compra),
    })
    .then(async (res) => {
      const texto = await res.text();
      console.log('Respuesta del servidor:', texto);

      try {
        const json = JSON.parse(texto);

        if (json.ok) {
          localStorage.setItem('ultimaCompra', JSON.stringify(compra));
          localStorage.removeItem('carrito');
          window.location.href = `ver_compra.html?id_pedido=${json.compra.id_pedido}`;
        } else {
          alert('Error: ' + (json.msg || 'No se pudo procesar la compra'));
        }
      } catch (e) {
        alert('La respuesta no es un JSON válido:\n' + texto);
      }
    })
    .catch((error) => {
      console.error('Error de conexión:', error);
      alert('Error de conexión: ' + error.message);
    });
  });
});
