let navbar = document.querySelector('.header .header-2 .flex .navbar');
let userBox = document.querySelector('.header .header-2 .flex .user-box');

document.querySelector('#user-btn').onclick = () =>{
    userBox.classList.toggle('active');
    navbar.classList.remove('active');
 }

window.onscroll = () =>{
   navbar.classList.remove('active');
   userBox.classList.remove('active');
}

