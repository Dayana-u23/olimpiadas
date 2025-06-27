function agregarAlCarrito(nombre, precio, id_producto, cantidad = 1) {
  const producto = {
    id_producto: id_producto,
    nombre: nombre,
    precio: parseFloat(precio),
    cantidad: parseInt(cantidad)
  };

  let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

  // Verificar si el producto ya está en el carrito
  const index = carrito.findIndex(p => p.id_producto === id_producto);
  if (index >= 0) {
    carrito[index].cantidad += producto.cantidad;
  } else {
    carrito.push(producto);
  }

  localStorage.setItem('carrito', JSON.stringify(carrito));
  actualizarContador();
  alert(`${nombre} agregado al carrito (${producto.cantidad})`);
}

function actualizarContador() {
  const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
  const contador = document.getElementById('contador-carrito');
  if (contador) {
    // Mostrar la suma total de todas las cantidades
    const totalCantidad = carrito.reduce((acc, item) => acc + item.cantidad, 0);
    contador.textContent = totalCantidad;
  }
}

function mostrarCarrito() {
  const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
  const contenedor = document.getElementById('carrito-contenido');
  contenedor.innerHTML = '';

  if (carrito.length === 0) {
    contenedor.innerHTML = '<p class="text-center text-muted">El carrito está vacío.</p>';
    document.getElementById('total').textContent = '';
    return;
  }

  carrito.forEach((item, index) => {
    const row = document.createElement('div');
    row.classList.add('row', 'mb-3', 'align-items-center', 'border-bottom', 'pb-2');

    row.innerHTML = `
      <div class="col-md-4">${item.nombre}</div>
      <div class="col-md-2">Cant: ${item.cantidad}</div>
      <div class="col-md-3 text-success">$ ${(item.precio * item.cantidad).toLocaleString()}</div>
      <div class="col-md-3 text-end">
        <button class="btn btn-remove btn-sm" onclick="eliminarPaquete(${index})">
          <i class="bi bi-trash"></i> Quitar
        </button>
      </div>
    `;

    contenedor.appendChild(row);
  });

  // Calcular total considerando cantidad
  const total = carrito.reduce((acc, item) => acc + item.precio * item.cantidad, 0);
  document.getElementById('total').textContent = `Total: $ ${total.toLocaleString()}`;
}

function eliminarPaquete(index) {
  let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
  carrito.splice(index, 1);
  localStorage.setItem('carrito', JSON.stringify(carrito));
  mostrarCarrito();
  actualizarContador();
}

document.addEventListener('DOMContentLoaded', () => {
  mostrarCarrito();
  actualizarContador();

  // Asignar evento a botones de agregar al carrito, si están en esta página
  document.querySelectorAll('.btn-agregar-carrito').forEach(btn => {
    btn.addEventListener('click', () => {
      const card = btn.closest('.card-body');
      const nombre = btn.dataset.nombre;
      const precio = btn.dataset.precio;
      const id_producto = parseInt(btn.dataset.id);
      const cantidadInput = card.querySelector('.input-cantidad');
      const cantidad = cantidadInput ? parseInt(cantidadInput.value) : 1;

      if (cantidad < 1 || isNaN(cantidad)) {
        alert('Cantidad inválida');
        return;
      }

      agregarAlCarrito(nombre, precio, id_producto, cantidad);
    });
  });
  document.querySelectorAll('.btn-agregar-carrito').forEach(boton => {
  boton.addEventListener('click', function () {
    const id = parseInt(this.dataset.id);
    const nombre = this.dataset.nombre;
    const precio = parseFloat(this.dataset.precio);
    const inputCantidad = this.closest('.card-body').querySelector('.input-cantidad');
    const cantidad = parseInt(inputCantidad.value) || 1;

    console.log('Agregar al carrito:', { nombre, precio, id, cantidad });

    if (cantidad < 1) {
      alert('La cantidad debe ser al menos 1');
      return;
    }

    agregarAlCarrito(nombre, precio, id, cantidad);
  });
});

});
