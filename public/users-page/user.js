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

/* Entrar na aba de adicionar funcionários*/
var emplLocal = document.querySelector('.employee');
var emplButton = document.querySelector('.add-employee-button');

var emplAddLocal = document.querySelector('.addEmployee');

emplButton.addEventListener("click", ()=>{
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
    budgetLocal.classList.remove('show-div');
    budgetLocal.classList.add('hidden-div');

    budgetAddLocal.classList.add('show-div');
    budgetAddLocal.classList.remove('hidden-div')

});
/* Entrar na aba de adicionar orçamento*/


/*Botão para voltar para a aba de funcionários*/
var backEmpl = document.querySelector('.back-employee');

backEmpl.addEventListener("click", () => {
    emplLocal.classList.remove('hidden-div');
    emplLocal.classList.add('show-div');

    emplAddLocal.classList.add('hidden-div');
    emplAddLocal.classList.remove('show-div');

})
/*Botão para voltar para a aba de orçamentos*/


/*Botão para voltar para a aba de orçamentos*/
var backBudget = document.querySelector('.back-budget');

backBudget.addEventListener("click", () => {
    budgetLocal.classList.remove('hidden-div');
    budgetLocal.classList.add('show-div');

    budgetAddLocal.classList.add('hidden-div');
    budgetAddLocal.classList.remove('show-div');

})
/*Botão para voltar para a aba de orçamentos*/


function submitEmpl(event){
    event.preventDefault();

    let nomeFunc = document.querySelector("#inome").value;
    

    let dataNascimento = document.querySelector("#inascimento").value;

    let emailFunc = document.querySelector("#iemail").value;
    
    let telefoneFunc = document.querySelector("#inum").value;

    let generoFunc = document.querySelector('input[name="genero"]:checked');

    let dataIngresso = document.querySelector('#idate').value;

    let cargoFunc = document.querySelector('#icargo').value;

    let areaFunc = document.querySelector('#iarea').value;

    alert
    (
        `
        Nome: ${nomeFunc}
        Data de Nascimento: ${dataNascimento}
        Email: ${emailFunc}
        Telefone: ${telefoneFunc}
        Gênero: ${generoFunc}
        Data de Ingresso: ${dataIngresso}
        Cargo: ${cargoFunc}
        Área: ${areaFunc}
        `
    )

}