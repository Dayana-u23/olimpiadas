
const modal = document.getElementById('modalLoginJefe');
const overlay = document.getElementById('modalOverlay');
const btnCerrar = modal?.querySelector('.close-btn');
const formLoginJefe = document.getElementById('formLoginJefe');
const loginError = document.getElementById('loginError');
const loginSuccess = document.getElementById('loginSuccess');
const togglePassword = document.getElementById('togglePassword');
const inputPassword = document.getElementById('codigo');
const btnJefeAccion = document.getElementById('btnJefeAccion');
const jefeToggle = document.getElementById('jefeDropdownToggle');
const jefeMenu = document.getElementById('jefeDropdownMenu');

function abrirModal() {
  if (!modal || !overlay) return;
  modal.style.display = 'block';
  overlay.style.display = 'block';
  document.body.style.overflow = 'hidden';
  loginError.textContent = '';
  loginSuccess.textContent = '';
  formLoginJefe?.reset();
}

function cerrarModal() {
  if (!modal || !overlay) return;
  modal.style.display = 'none';
  overlay.style.display = 'none';
  document.body.style.overflow = '';
}


function actualizarMenu() {
  const loginLink = document.getElementById('menu-login-link');
  const clienteMenus = document.querySelectorAll('.cliente-only');
  const jefeMenus = document.querySelectorAll('.jefe-only');
  const tipoUsuario = localStorage.getItem('tipo_usuario');

  if (!loginLink) return;

  if (tipoUsuario === 'cliente') {
    loginLink.style.display = 'none';
    clienteMenus.forEach(el => el.style.display = 'block');
    jefeMenus.forEach(el => el.style.display = 'none');
  } else if (tipoUsuario === 'jefe') {
    loginLink.style.display = 'none';
    clienteMenus.forEach(el => el.style.display = 'none');
    jefeMenus.forEach(el => el.style.display = 'block');
  } else {
    loginLink.style.display = 'block';
    clienteMenus.forEach(el => el.style.display = 'none');
    jefeMenus.forEach(el => el.style.display = 'none');
  }
}

function configurarBotonJefe() {
  const tipoUsuario = localStorage.getItem('tipo_usuario');
  if (!btnJefeAccion) return;

  if (tipoUsuario === 'jefe') {
    btnJefeAccion.innerHTML = '<i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión';
    btnJefeAccion.onclick = () => {
      localStorage.clear();
      actualizarMenu();
      configurarBotonJefe();
      location.reload();
    };
  } else {
    btnJefeAccion.innerHTML = '<i class="bi bi-person-gear me-2"></i> Ingresar como Jefe de Ventas';
    btnJefeAccion.onclick = abrirModal;
  }
}

function setupLoginForm() {
  if (!formLoginJefe) return;

  formLoginJefe.addEventListener('submit', function(e) {
    e.preventDefault();
    if (!loginError || !loginSuccess) return;

    loginError.textContent = '';
    loginSuccess.textContent = '';

    const formData = new FormData(formLoginJefe);

    fetch('php/procesar_login_jefe.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        localStorage.setItem('tipo_usuario', 'jefe');
        localStorage.setItem('nombre_usuario', data.nombre);
        localStorage.setItem('id_empleado', data.id_empleado);

        loginSuccess.textContent = 'Se verificó correctamente. ¡Bienvenido, ' + data.nombre + '!';
        actualizarMenu();
        configurarBotonJefe();

        setTimeout(() => {
          cerrarModal();
          loginSuccess.textContent = '';
        }, 1500);
      } else {
        loginError.textContent = data.message || 'Error en el login';
      }
    })
    .catch(() => {
      loginError.textContent = 'Error al comunicarse con el servidor';
    });
  });
}

function setupModalEvents() {
  if (btnCerrar) btnCerrar.addEventListener('click', cerrarModal);
  if (overlay) overlay.addEventListener('click', cerrarModal);
  window.addEventListener('keydown', e => {
    if (e.key === 'Escape' && modal?.style.display === 'block') cerrarModal();
  });

  if (togglePassword) togglePassword.addEventListener('click', togglePasswordVisibility);
}

function setupJefeDropdown() {
  if (!jefeToggle || !jefeMenu) return;

  jefeToggle.addEventListener('click', e => {
    e.preventDefault();
    jefeMenu.classList.toggle('show');
  });

  document.addEventListener('click', e => {
    if (!jefeToggle.contains(e.target) && !jefeMenu.contains(e.target)) {
      jefeMenu.classList.remove('show');
    }
  });
}
document.addEventListener('DOMContentLoaded', () => {
  actualizarMenu();
  configurarBotonJefe();
  setupLoginForm();
  setupModalEvents();
  setupJefeDropdown();
});
document.addEventListener('DOMContentLoaded', () => {
  actualizarMenu(); 
});
document.addEventListener('DOMContentLoaded', function () {
  const tipoUsuario = localStorage.getItem('tipo_usuario');
  if (tipoUsuario === 'jefe') {
    document.querySelectorAll('.btn-agregar-carrito').forEach(btn => {
      btn.style.display = 'none';
    });
    document.querySelectorAll('.input-cantidad').forEach(input => {
      input.style.display = 'none';
    });
  }

  if (tipoUsuario === 'jefe') {
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
      btn.style.display = 'block';
      btn.addEventListener('click', function () {
        // ...
      });
    });
  }

});
