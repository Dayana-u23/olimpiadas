const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});
function toggleMenu(event) {
    event.stopPropagation();
    var navbar = document.getElementById("navbar");
    var hamburger = document.querySelector(".hamburger");
    navbar.classList.toggle("active");
    hamburger.classList.toggle("active"); 
}  

