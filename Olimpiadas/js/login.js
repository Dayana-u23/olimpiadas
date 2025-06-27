document.addEventListener('DOMContentLoaded', function () {
        const loginLink = document.getElementById('menu-login-link');
        const tipoUsuario = localStorage.getItem('tipo_usuario');
        const nombreUsuario = localStorage.getItem('nombre_usuario');
    
        if (loginLink && tipoUsuario && nombreUsuario) {
          if (tipoUsuario === 'cliente') {
            loginLink.innerHTML = '<i class="bi bi-person-circle"></i> Mi cuenta (' + nombreUsuario + ')';
            loginLink.href = 'mi_cuenta.html';
          }
        }
      });

  document.addEventListener('DOMContentLoaded', function () {
    const loginLink = document.getElementById('menu-login-link');
    const clienteMenus = document.querySelectorAll('.cliente-only');
    const jefeMenus = document.querySelectorAll('.jefe-only');

    const tipoUsuario = localStorage.getItem('tipo_usuario');
    const nombreUsuario = localStorage.getItem('nombre_usuario');

    if (loginLink && tipoUsuario && nombreUsuario) {
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
    } else {
    
      loginLink.style.display = 'block';
      clienteMenus.forEach(el => el.style.display = 'none');
      jefeMenus.forEach(el => el.style.display = 'none');
    }
  });