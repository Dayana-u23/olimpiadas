document.addEventListener('DOMContentLoaded', function () {
  const tipoUsuario = localStorage.getItem('tipo_usuario');
  if (tipoUsuario !== 'jefe') return;

  document.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.style.display = 'block';

    btn.addEventListener('click', () => {
      const idProducto = btn.getAttribute('data-id-producto');

      fetch('php/eliminar_paquetes.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_producto: idProducto }),
      })
      .then(res => {
        if (!res.ok) throw new Error('Respuesta no OK del servidor');
        return res.json();
      })
      .then(data => {
        if (data.success) {
          const tarjeta = btn.closest('.paquete');
          if (tarjeta) tarjeta.remove();
          alert('Producto eliminado correctamente.');
        } else {
          alert('Error al eliminar producto: ' + data.error);
        }
      })
      .catch(error => {
        console.error('Error en la comunicación:', error);
        alert('Error en la comunicación con el servidor.');
      });
    });
  });
});
