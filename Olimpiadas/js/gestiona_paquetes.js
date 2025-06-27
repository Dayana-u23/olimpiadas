document.addEventListener('DOMContentLoaded', function () {
  const contenedor = document.querySelector('.container .row');
  let tipoUsuario = localStorage.getItem('tipo_usuario');

  // Escucha cambios en localStorage aunque estés en la misma pestaña
  window.addEventListener('storage', () => {
    tipoUsuario = localStorage.getItem('tipo_usuario');
    mostrarPaquetes();
  });

  mostrarPaquetes();

  if (tipoUsuario === 'jefe') {
    mostrarFormularioCarga();
  }

 window.eliminarPaquete = function (index) {
  const paquetes = JSON.parse(localStorage.getItem('paquetesTuristicos')) || [];
  if (confirm('¿Estás seguro de que deseas eliminar este paquete?')) {
    paquetes.splice(index, 1);
    localStorage.setItem('paquetesTuristicos', JSON.stringify(paquetes));
    mostrarPaquetes(); // <- Actualiza para todos
  }
};


  function mostrarPaquetes() {
    contenedor.innerHTML = '';
    const paquetes = JSON.parse(localStorage.getItem('paquetesTuristicos')) || [];

    paquetes.forEach((paquete, index) => {
      const col = document.createElement('div');
      col.className = 'col-md-4';

      col.innerHTML = `
        <div class="card shadow-sm">
          <img src="${paquete.imagen}" class="card-img-top" alt="${paquete.nombre}">
          <div class="card-body">
            <h5 class="card-title">${paquete.nombre}</h5>
            <p class="card-text">${paquete.descripcion}</p>
            <p class="fw-bold text-success">$ ${paquete.precio.toLocaleString()}</p>
            <a href="#" class="btn btn-add w-100" onclick="agregarAlCarrito('${paquete.nombre}', ${paquete.precio})">
              <i class="bi bi-cart-plus"></i> Añadir al carrito
            </a>
            ${tipoUsuario === 'jefe'
              ? `<button class="btn btn-danger mt-2 w-100" onclick="eliminarPaquete(${index})">Eliminar</button>`
              : ''}
          </div>
        </div>
      `;
      contenedor.appendChild(col);
    });
  }

document.addEventListener('DOMContentLoaded', function () {
  const contenedor = document.querySelector('.container .row');
  let tipoUsuario = localStorage.getItem('tipo_usuario'); // debe ser 'jefe' o 'cliente'

  // Muestra paquetes en la página
  function mostrarPaquetes() {
    contenedor.innerHTML = '';
    const paquetes = JSON.parse(localStorage.getItem('paquetesTuristicos')) || [];

    paquetes.forEach((paquete, index) => {
      const col = document.createElement('div');
      col.className = 'col-md-4';

      col.innerHTML = `
        <div class="card shadow-sm">
          <img src="${paquete.imagen}" class="card-img-top" alt="${paquete.nombre}">
          <div class="card-body">
            <h5 class="card-title">${paquete.nombre}</h5>
            <p class="card-text">${paquete.descripcion}</p>
            <p class="fw-bold text-success">$ ${paquete.precio.toLocaleString()}</p>
            <a href="#" class="btn btn-add w-100" onclick="agregarAlCarrito('${paquete.nombre}', ${paquete.precio})">
              <i class="bi bi-cart-plus"></i> Añadir al carrito
            </a>
            ${tipoUsuario === 'jefe'
              ? `<button class="btn btn-danger mt-2 w-100" onclick="eliminarPaquete(${index})">Eliminar</button>`
              : ''}
          </div>
        </div>
      `;
      contenedor.appendChild(col);
    });
  }

  // Función para eliminar paquete
  window.eliminarPaquete = function (index) {
    const paquetes = JSON.parse(localStorage.getItem('paquetesTuristicos')) || [];
    if (confirm('¿Estás seguro de que deseas eliminar este paquete?')) {
      paquetes.splice(index, 1);
      localStorage.setItem('paquetesTuristicos', JSON.stringify(paquetes));
      mostrarPaquetes();
    }
  };

  // Si es jefe, mostrar formulario para agregar paquetes (opcional)
  function mostrarFormularioCarga() {
    const form = document.createElement('div');
    form.className = 'container my-4';
    form.innerHTML = `
      <h3 class="text-success">Cargar nuevo paquete</h3>
      <div class="row g-3">
        <div class="col-md-3"><input id="nuevoNombre" class="form-control" placeholder="Nombre"></div>
        <div class="col-md-3"><input id="nuevaDescripcion" class="form-control" placeholder="Descripción"></div>
        <div class="col-md-2"><input id="nuevoPrecio" type="number" class="form-control" placeholder="Precio"></div>
        <div class="col-md-3"><input id="nuevaImagen" class="form-control" placeholder="URL Imagen"></div>
        <div class="col-md-1"><button id="btnGuardarPaquete" class="btn btn-success w-100">+</button></div>
      </div>
    `;
    document.querySelector('.container').prepend(form);

    document.getElementById('btnGuardarPaquete').addEventListener('click', () => {
      const nombre = document.getElementById('nuevoNombre').value.trim();
      const descripcion = document.getElementById('nuevaDescripcion').value.trim();
      const precio = parseFloat(document.getElementById('nuevoPrecio').value);
      const imagen = document.getElementById('nuevaImagen').value.trim();

      if (!nombre || !descripcion || isNaN(precio) || !imagen) {
        alert('Completa todos los campos correctamente');
        return;
      }

      const paquetes = JSON.parse(localStorage.getItem('paquetesTuristicos')) || [];
      paquetes.push({ nombre, descripcion, precio, imagen });
      localStorage.setItem('paquetesTuristicos', JSON.stringify(paquetes));
      mostrarPaquetes();
    });
  }

  // Mostrar formulario solo si es jefe
  if (tipoUsuario === 'jefe') {
    mostrarFormularioCarga();
  }

  // Inicialmente mostrar paquetes
  mostrarPaquetes();

  // Escuchar cambios en localStorage (para sincronización en varias pestañas)
  window.addEventListener('storage', (e) => {
    if (e.key === 'paquetesTuristicos') {
      mostrarPaquetes();
    }
    if (e.key === 'tipo_usuario') {
      tipoUsuario = localStorage.getItem('tipo_usuario');
      mostrarPaquetes();
    }
  });
});

// Función para agregar al carrito, compartida
function agregarAlCarrito(nombre, precio) {
  const paquete = { nombre, precio };
  let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
  carrito.push(paquete);
  localStorage.setItem('carrito', JSON.stringify(carrito));
  alert('Paquete agregado al carrito.');
}

});

function agregarAlCarrito(nombre, precio) {
  const paquete = { nombre, precio };
  let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
  carrito.push(paquete);
  localStorage.setItem('carrito', JSON.stringify(carrito));
  alert('Paquete agregado al carrito.');
}
