function toggleSections(hideEl, showEl){
    hideEl.classList.remove('show-div');
    hideEl.classList.add('hidden-div');

    showEl.classList.add('show-div');
    showEl.classList.remove('hidden-div')

}

//trocar a aba selecionada no site
document.querySelectorAll("#options-list li").forEach((li) => {
    li.addEventListener("click", () => {
        document.querySelectorAll(".right-section").forEach((section) => {
            section.classList.remove("show-div");
            section.classList.add("hidden-div");
        });

        document.querySelector(`.right-section.${li.id}`).classList.remove("hidden-div");
        document.querySelector(`.right-section.${li.id}`).classList.add("show-div");

        document.querySelectorAll("#options-list li").forEach((el) => el.classList.remove("selected"));
        li.classList.add("selected");
    });
});
//trocar a aba selecionada no site

/* Entrar na aba de adicionar funcionários*/
var emplLocal = document.querySelector('.employee');
var emplButton = document.querySelector('.add-employee-button');

var emplAddLocal = document.querySelector('.addEmployee');

emplButton.addEventListener("click", ()=>{
    toggleSections(emplLocal, emplAddLocal);
    emplLocal.classList.remove('show-div');
    emplLocal.classList.add('hidden-div');

    emplAddLocal.classList.remove('hidden-div');
    emplAddLocal.classList.add('show-div');
    
});
/* Entrar na aba de adicionar funcionários*/

/* Entrar na aba de adicionar orçamento*/
var budgetLocal = document.querySelector('.budget');
var budgetButton = document.querySelector('.add-budget-button');

var budgetAddLocal = document.querySelector('.addBudget');

budgetButton.addEventListener("click", () =>{
    toggleSections(budgetLocal, budgetAddLocal);

});
/* Entrar na aba de adicionar orçamento*/


/*Botão para voltar para a aba de funcionários*/
var backEmpl = document.querySelector('.back-employee');

backEmpl.addEventListener("click", () => {
    toggleSections(emplAddLocal, emplLocal);

})
/*Botão para voltar para a aba de orçamentos*/


/*Botão para voltar para a aba de orçamentos*/
var backBudget = document.querySelector('.back-budget');

backBudget.addEventListener("click", () => {
    toggleSections(budgetAddLocal,budgetLocal);

})
/*Botão para voltar para a aba de orçamentos*/



