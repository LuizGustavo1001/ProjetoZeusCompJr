var toggleButtonElement = document.querySelector('.theme-switch-button');
var lightModeIcon = document.querySelector('.light-mode-icon');
var darkModeIcon = document.querySelector('.dark-mode-icon');
var bodyElement = document.body;

toggleButtonElement.addEventListener('click', () =>{
    bodyElement.classList.toggle("dark-mode");
    darkModeIcon.classList.toggle("hidden");
    lightModeIcon.classList.toggle("hidden");

});