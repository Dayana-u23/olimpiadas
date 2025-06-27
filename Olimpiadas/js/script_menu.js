document.addEventListener('DOMContentLoaded', function () {
  const menuGeneral = document.getElementById('menu-general');
  const clienteMenus = document.querySelectorAll('.cliente-only');
  const jefeMenus = document.querySelectorAll('.jefe-only');
  const clienteMenuLink = document.getElementById('cliente-menu-link');

  const tipoUsuario = localStorage.getItem('tipo_usuario');
  const nombreUsuario = localStorage.getItem('nombre_usuario');

  if (tipoUsuario === 'cliente' && nombreUsuario) {
    if (menuGeneral) menuGeneral.style.display = 'none';
    clienteMenus.forEach(el => el.style.display = 'block');
    jefeMenus.forEach(el => el.style.display = 'none');

    if (clienteMenuLink) {
      clienteMenuLink.innerHTML = `<i class="bi bi-person-circle"></i> Mi cuenta (${nombreUsuario})`;
      clienteMenuLink.href = 'php/mi_cuenta.php';
    }
  } else if (tipoUsuario === 'jefe') {
    jefeMenus.forEach(el => el.style.display = 'block');
    clienteMenus.forEach(el => el.style.display = 'none');
    if (menuGeneral) menuGeneral.style.display = 'none';
  } else {
    if (menuGeneral) menuGeneral.style.display = 'block';
    clienteMenus.forEach(el => el.style.display = 'none');
    jefeMenus.forEach(el => el.style.display = 'none');
  }
});
