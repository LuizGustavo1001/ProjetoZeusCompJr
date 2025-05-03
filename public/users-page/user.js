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
var funcLocal = document.querySelector('.employe');
var funcButton = document.querySelector('.add-employe-button');

var funcAddLocal = document.querySelector('.addEmploye');

funcButton.addEventListener("click", ()=>{
    funcLocal.classList.remove('show-div');
    funcLocal.classList.add('hidden-div');

    funcAddLocal.classList.remove('hidden-div');
    funcAddLocal.classList.add('show-div');

    
});
/* Entrar na aba de adicionar funcionários*/

/* Entrar na aba de adicionar orçamento*/
var orcLocal = document.querySelector('.budget');
var orcButton = document.querySelector('.add-budget-button');

var orcAddLocal = document.querySelector('.addBudget');

orcButton.addEventListener("click", () =>{
    orcLocal.classList.remove('show-div');
    orcLocal.classList.add('hidden-div');

    orcAddLocal.classList.add('show-div');
    orcAddLocal.classList.remove('hidden-div')


});
/* Entrar na aba de adicionar orçamento*/

/*Botão para voltar para a aba de orçamentos*/
var voltarOrc = document.querySelector('.back-budget');

voltarOrc.addEventListener("click", () => {
    orcLocal.classList.remove('hidden-div');
    orcLocal.classList.add('show-div');

    orcAddLocal.classList.add('hidden-div');
    orcAddLocal.classList.remove('show-div');

})
/*Botão para voltar para a aba de orçamentos*/



function submitFunc(event){
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

/*
async function atualizarTotalFuncionarios(){
    try{
        const res = await fetch('/api/total-funcionarios');
        const data = await res.json();
        document.querySelector('#total-funcionarios').innerHTML = `${data.local}`;
    }catch{
        console.error('Erro ao buscar o numero total de funcionarios', err);
    }

}

atualizarTotalFuncionarios();

*/