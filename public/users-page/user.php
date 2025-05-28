<?php
include "../../dbConnection.php";

if (! isset($_COOKIE["email"])) { // tentando acessar a página do usuário sem estar logado
    header("Location: error.php");
}

function totalList($type){ // mostra a *quantidade* de itens cadastrados em uma tabela
    global $mysqli;

    $allowedTables = ["employee", "budget"];

    if(in_array($type, $allowedTables)){
        $sql_code = " SELECT count(*) FROM $type";
        $sql_query = $mysqli->query($sql_code) or die($mysqli->error);
        $result = $sql_query->fetch_row()[0];
        match($type){
            "employee" => $text = "<p><strong><span id=\"total-employes\">$result</span></strong></p>",
            "budget"   => $text = "<p><strong><span id=\"total-budgets\">$result</span></strong></p>",
            default    => $text = "\nERRO: nenhuma tabela válida selecionada\n",
        };

        echo $text;
    }else{
        echo "ERRO";
    }   
}

function showlist($type){
    global $mysqli;
    $allowedTables = ["employee", "budget"];

    if(in_array($type, $allowedTables)){
        $sql_code = "SELECT * FROM $type";
        $sql_query = $mysqli->query($sql_code);

        $amount = $sql_query->num_rows;

        if($amount <= 0){ // não há nenhum dado cadastrado na tabela selecionada
            match($type){
                "employee" => $text = "<p>Nenhum Funcionário Cadastrado no Momento</p>",
                "budget"   => $text = " <p>Nenhum Orçamento Cadastrado no Momento</p>",
                default    => $text = "\nERRO: nenhuma tabela válida selecionada\n"
            };

            echo $text;

        }else{ // existem dados cadastrados na tabela selecionada -> enviar para página web
            echo "<table>";
            switch($type){
                case "employee":{
                    echo "
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>Nascimento</th>
                            <th>Email</th>
                            <th>Gênero</th>
                            <th>Telefone</th>
                            <th>Cargo</th>
                            <th>Ingresso</th>
                            <th>Área</th>
                        </tr>
                    ";
                    while($dados = $sql_query->fetch_assoc()){ // adiciona os dados da tabela e manda para a página web
                        $dados["genderEmpl"] = match($dados["genderEmpl"]) {
                            "M" => "Masculino",
                            "F" => "Feminino",
                            "O" => "Outro",
                            default => $dados["genderEmpl"]
                        };
                        echo "
                            <tr>
                                <td>{$dados["idEmpl"]}</td>
                                <td>{$dados["nameEmpl"]}</td>
                                <td>{$dados["bDayEmpl"]}</td>
                                <td>{$dados["emailEmpl"]}</td>
                                <td>{$dados["genderEmpl"]}</td>
                                <td>{$dados["numberEmpl"]}</td>
                                <td>{$dados["emplPos"]}</td>
                                <td>{$dados["entryDate"]}</td>
                                <td>{$dados["areaEmpl"]}</td>
                            </tr>
                        ";
                    }
                    echo "</table>";
                    break;
                }
                case "budget":{
                    echo "
                        <tr>
                            <th>Id</th>
                            <th>Número</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Custo</th>
                            <th>Cliente</th>
                        </tr>
                    ";
                    $padrao = numfmt_create("pt-BR", style: NumberFormatter::CURRENCY);

                    while($dados = $sql_query->fetch_assoc()){ // adiciona os dados da tabela e manda para a página web
                        $realValue = numfmt_format_currency($padrao, $dados["budgetValue"], "BRL");
                        $realCost = numfmt_format_currency($padrao, $dados["budgetCost"], "BRL");
                        echo "
                            <tr>
                                <td>{$dados["idBudget"]}</td>
                                <td>{$dados["numBudget"]}</td>
                                <td>{$dados["descBudget"]}</td>
                                <td>{$realValue}</td>
                                <td>{$realCost}</td>
                                <td>{$dados["budgetClient"]}</td>
                            </tr>
                        ";
                    }
                    echo "</table>";
                    break;
                }
                default:{
                    echo "\nERRO: tabela selecionada não faz parte das válidas\n";
                    break;
                }
            }
        }
    }
}

function searchList($type, $filter){ // exibe o resultado da busca por um filtro escrito
    global $mysqli;

    $allowedTables = ["employee", "budget"];

    if(in_array($type, $allowedTables)){ // evitar SQL injection
        match($type){
            "employee" => $pesquisa = $_GET["searchEmpl"],
            "budget"   => $pesquisa = $_GET["searchBudget"],
            default    => $pesquisa = "\nERRO: tabela selecionada não está no intervalo de validade\n"
        };

        $stmt = $mysqli->prepare("
            SELECT * FROM $type 
            WHERE $filter LIKE ?
        ");

        $searchTerm = "%{$pesquisa}%"; // permite pesquisas sem o nome completo -> interpolação de strings
        $stmt->bind_param("s",$searchTerm);
        $stmt->execute();

        $sql_query = $stmt->get_result();

        $amount = $sql_query->num_rows;

        switch($amount){
            case 0: { // nenhum item com o filtro digitado foi encontrado
                match($type){
                    "employee" => $text = " <p>Nenhum Funcionário Encontrado com o Nome: <strong>\"$pesquisa\"</strong></p>",
                    "budget"   => $text = " <p>Nenhum Orçamento Encontrado com o Número: <strong> \"$pesquisa\"</strong></p>",
                    default    => $text = " <p>Erro</p>"
                };

                echo $text;

                break;
            }
            default: { // encontrou-se itens com o filtro digitado
                switch($type){
                    case "employee":{
                        echo "
                            <p>
                                <strong>
                                    <span class=\"highlight-word\">
                                        Funcionário(s) Encontrado(s) com o filtro:
                                    </span>\"$pesquisa\"
                                </strong>
                            </p>
                            <table>
                                <tr>
                                    <th>id</th>
                                    <th>Nome</th>
                                    <th>Nascimento</th>
                                    <th>Email</th>
                                    <th>Gênero</th>
                                    <th>Telefone</th>
                                    <th>Cargo</th>
                                    <th>Ingresso</th>
                                    <th>Área</th>
                                </tr>
                        ";
                        while ($dados = $sql_query->fetch_assoc()) { // retornando os valores encontrados 
                            $dados["genderEmpl"] = match($dados["genderEmpl"]) {
                                "M" => "Masculino",
                                "F" => "Feminino",
                                "O" => "Outro",
                                default => $dados["genderEmpl"]
                            }; 
                            echo "
                                <tr>
                                    <td>{$dados["idEmpl"]}</td>
                                    <td>{$dados["nameEmpl"]}</td>
                                    <td>{$dados["bDayEmpl"]}</td>
                                    <td>{$dados["emailEmpl"]}</td>
                                    <td>{$dados["genderEmpl"]}</td>
                                    <td>{$dados["numberEmpl"]}</td>
                                    <td>{$dados["emplPos"]}</td>
                                    <td>{$dados["entryDate"]}</td>
                                    <td>{$dados["areaEmpl"]}</td>
                                </tr>
                            ";
                        }
                        echo "</table>";
                        $stmt->close();

                       

                        break;
                    }
                    case "budget":{
                        echo "
                            <p>
                                <strong>
                                    <span class=\"highlight-word\">
                                        Orçamento(s) Encontrado(s) com o filtro:
                                    </span>\"$pesquisa\"
                                </strong>
                            </p>
                            <table>
                                <tr>
                                    <th>id</th>
                                    <th>Número</th>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                    <th>Custo</th>
                                    <th>Cliente</th>
                                </tr>
                        ";
                        $padrao = numfmt_create("pt-BR", style: NumberFormatter::CURRENCY);

                        while($dados = $sql_query->fetch_assoc()){ // adiciona os dados da tabela e manda para a página web
                            $realValue = numfmt_format_currency($padrao, $dados["budgetValue"], "BRL");
                            $realCost = numfmt_format_currency($padrao, $dados["budgetCost"], "BRL");
                            echo "
                                <tr>
                                    <td>{$dados["idBudget"]}</td>
                                    <td>{$dados["numBudget"]}</td>
                                    <td>{$dados["descBudget"]}</td>
                                    <td>{$realValue}</td>
                                    <td>{$realCost}</td>
                                    <td>{$dados["budgetClient"]}</td>
                                </tr>
                            ";
                        }
                        echo "</table>";
                        $stmt->close();
                        break;
                    }
                }
                // Removed header redirects to avoid "headers already sent" error.
                // To know which tab the user was on before submitting the form,
                // you can use a hidden input in your form to store the current tab/section.
                // Example: <input type="hidden" name="currentTab" value="employee">
                // Then, access it here with $_POST['currentTab'] or $_GET['currentTab'].
                // You can use this value to control which section to show after processing.
                break;
            }
        }
    }
}

function addToList($type){ // adiciona um novo item a lista selecionada
    global $mysqli;

    $allowedTables = ["employee", "budget"];
    
    if(in_array($type, $allowedTables)){ // tipo válido
        $stmt = $mysqli->prepare("SELECT * FROM $type WHERE ? = ?"); // evitar SQL injection
        match($type){
            "employee" => $stmt->bind_param("ss",$_POST["emailEmpl"], $_POST['eEmail']),
            "budget"   => $stmt->bind_param("si",$_POST["numBudget"], $_POST['bNum']),
        };

        $stmt->execute();
        $sql_query = $stmt->get_result();

        $amount = $sql_query->num_rows;

        switch($amount){
            case 0: { // adicionar novo funcionário
                switch($type){
                    case "employee":{
                        $currentDate = date("Y-m-d");
                        $minBDate = date("Y-m-d", strtotime("-16 years"));

                        $bd = $_POST["eBDay"];
                        $entry = $_POST["joinDate"];

                        if($bd >= $minBDate){ // Data de nascimento inválida
                            return "invalidBDate";
                        }else if($entry > $currentDate){ // Data de ingresso inválida
                            return "invalidEntryDate";
                        }else{ // nada de anormal
                            $stmt = $mysqli->prepare("
                                INSERT INTO $type (nameEmpl, bDayEmpl, emailEmpl, genderEmpl, numberEmpl, emplPos, entryDate, areaEmpl) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                            ");

                            $stmt->bind_param(
                                "ssssssss",
                                $_POST["eName"],
                                $_POST["eBDay"],
                                $_POST["eEmail"],
                                $_POST["eGender"],
                                $_POST["eNum"],
                                $_POST["position"],
                                $_POST["joinDate"],
                                $_POST["eArea"],
                            );

                            if($stmt->execute()){ // funcionário inserido no Banco de Dados
                                header("location: user.php?status=insert");
                                $stmt->close();
                            }else{ // funcionário não inserido no Banco de Dados
                                echo "<p>Erro ao Adicionar: " . $mysqli->error . "</p>";
                            }
                        }    
                        break;
                    }
                    case "budget":{
                        $stmt = $mysqli->prepare("
                            INSERT INTO $type (numBudget, descBudget, budgetValue, budgetCost, budgetClient) 
                            VALUES (?, ?, ?, ?, ?)
                        ");
                        $stmt->bind_param(
                            "isdds",
                            $_POST["bNum"],
                            $_POST["bDesc"],
                            $_POST["bValue"],
                            $_POST["bCost"],
                            $_POST["bClient"]
                        );

                        global $insert;
                        if($stmt->execute()){ // funcionário inserido no Banco de Dados
                            $insert = true;
                            header("location: user.php");
                            $stmt->close();
                        }else { // funcionário não inserido no Banco de Dados
                            echo "<p>Erro ao Adicionar: " . $mysqli->error . "</p>";
                        }
                        break;
                    }
                }
                break;
            }
            default: { // já existe um item com os dados inseridos
                return "alredyExist";
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { // verifica se o formulário do tipo POST foi enviado
    if (isset($_POST["bNum"])) {
        addToList("budget");
    }
    if (isset($_POST["eName"])) {
        addToList("employee");
    }
}

function filterList($type){ // filtra a lista de dados com base no filtro selecionado
    global $mysqli;

    $allowedTables = ["employee", "budget"];

    if(in_array($type, $allowedTables)){ // tipo válido 
        switch($type){
            case "employee": {
                $allowedFilters = ["idEmpl", "nameEmpl", "areaEmpl", "emplPos", "bDayEmpl"];
                $filter = $mysqli->real_escape_string($_GET["selectFilterEmpl"]);
                if(in_array($filter, $allowedFilters)){ // evitar sql injection
                    $sql_code = $mysqli->prepare("
                        SELECT * FROM $type 
                        ORDER BY $filter ASC
                    ");

                    $sql_code->execute();
                    $sql_query = $sql_code->get_result();

                    match($filter){
                        "idEmpl"   => $filter = "Id",
                        "nameEmpl" => $filter ="Nome",
                        "areaEmpl" => $filter ="Área de Atuação",
                        "emplPos"  => $filter ="Cargo",
                        "bDayEmpl" => $filter ="Idade"
                    };
                    echo "
                        <p><strong><span class=\"highlight-word\">Funcionários Filtrados por </span>\"$filter</strong>\"</p>
                        <table>
                            <tr>
                                <th>id</th>
                                <th>Nome</th>
                                <th>Nascimento</th>
                                <th>Email</th>
                                <th>Gênero</th>
                                <th>Telefone</th>
                                <th>Cargo</th>
                                <th>Ingresso</th>
                                <th>Área</th>
                            </tr>
                    ";
                    while ($dados = $sql_query->fetch_assoc()) {
                        $dados["genderEmpl"] = match($dados["genderEmpl"]) {
                            "M" => "Masculino",
                            "F" => "Feminino",
                            "O" => "Outro",
                            default => $dados["genderEmpl"]
                        }; 
                        echo "
                            <tr>
                                <td>{$dados["idEmpl"]}</td>
                                <td>{$dados["nameEmpl"]}</td>
                                <td>{$dados["bDayEmpl"]}</td>
                                <td>{$dados["emailEmpl"]} </td>
                                <td>{$dados["genderEmpl"]}</td>
                                <td>{$dados["numberEmpl"]}</td>
                                <td>{$dados["emplPos"]} </td>
                                <td>{$dados["entryDate"]} </td>
                                <td>{$dados["areaEmpl"]} </td>
                            </tr>";
                    }
                    echo "</table>";
                    $sql_code->close();
                }
                break;
            }
            case "budget": {
                $filter = $mysqli->real_escape_string($_GET["selectFilterBudget"]);
                $allowedFilters = ["numBudget", "budgetValue", "budgetCost", "budgetClient"];
                if(in_array($filter, $allowedFilters)){ // evitar sql injection
                    $sql_code = $mysqli->prepare("
                        SELECT * FROM $type 
                        ORDER BY $filter ASC
                    ");

                    $sql_code->execute();
                    $sql_query = $sql_code->get_result();

                     match($filter){
                        "numBudget"     => $filter = "Número",
                        "budgetValue"   => $filter ="Valor",
                        "budgetCost"    => $filter ="Custo",
                        "budgetClient"  => $filter ="Cliente",
                    };

                    echo "
                       <p><strong><span class=\"highlight-word\">Orçamentos Filtrados por </span>\"$filter</strong>\"</p>
                        <table>
                            <tr>
                                <th>id</th>
                                <th>Número</th>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Custo</th>
                                <th>Cliente</th>
                            </tr>   
                    ";
                    $padrao = numfmt_create("pt-BR", style: NumberFormatter::CURRENCY);
                    while ($dados = $sql_query->fetch_assoc()) {
                        $realValue = numfmt_format_currency($padrao, $dados["budgetValue"], "BRL");
                        $realCost = numfmt_format_currency($padrao, $dados["budgetCost"], "BRL");
                        echo "
                            <tr>
                                <td>{$dados["idBudget"]}</td>
                                <td>{$dados["numBudget"]}</td>
                                <td>{$dados["descBudget"]}</td>
                                <td>{$realValue}</td>
                                <td>{$realCost}</td>
                                <td>{$dados["budgetClient"]}</td>
                            </tr>
                        ";
                    }
                    echo "</table>";
                    $sql_code->close();
                }
                break;
            }
        }
    }
}

function verifyTab(){ // verificar qual aba o usuário está acessando
    if (isset($_GET["currentTab"])){ // verifica se a aba atual foi enviada
        $currentTab = $_GET["currentTab"];
        switch ($currentTab) {
            case "employee":
                return "employee";
            case "budget":
                return "budget";
            default:
                return "employee"; // padrão para evitar erros
        }
        
    }else{
        return "employee"; // padrão para evitar erros
    }
}

?>

<script>
    //script para controlar a exibição das abas ao enviar um formulário
    document.addEventListener("DOMContentLoaded", function() {
        let currentTab = "<?php echo verifyTab(); ?>";

        // esconde todas as sessões e remove a classe "selected" de todas as abas
        document.querySelectorAll(".right-section").forEach((section) => {
            section.classList.remove("show-div");
            section.classList.add("hidden-div");
        });
        document.querySelectorAll("#options-list li").forEach((el) => el.classList.remove("selected"));

        // mostra a sessão e adiciona a classe "selected" à aba correspondente
        let currentSection = document.querySelector(`.right-section.${currentTab}`);
        let currentTabLi = document.querySelector(`#options-list li#${currentTab}`);
        if (currentSection) {
            currentSection.classList.remove("hidden-div");
            currentSection.classList.add("show-div");
        }
        if (currentTabLi) {
            currentTabLi.classList.add("selected");
        }

        // adiciona o evento de clique a cada aba
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
    });
    //script para controlar a exibição das abas ao enviar um formulário
</script>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../icon/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="../styles/user.css">
    <link rel="stylesheet" href="../styles/general.css">
    <link rel="stylesheet" href="../styles/dark-mode.css">

    <script src="../scripts/dark-mode.js" defer></script>

    <script src="../scripts/user.js" defer></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <title>Projeto Zeus - Área do Usuário</title>

</head>

<body>
    <main>
        <aside id="left-content"> <!--Left on Desktop > Top on Mobile -->
            <div id="aside-top">
                <img src="../general-images/church-symbol.png" alt="church-symbol" id="church-symbol">
                <button class="theme-switch-button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="dark-mode-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="hidden light-mode-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                </button>
            </div>

            <div id="aside-bottom">
                <ul id="options-list">
                    <li id="employee" class="selected">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        Funcionários
                    </li>

                    <li id="budget">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                        </svg>
                        Orçamento
                    </li>
                    <li id="stock">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.242 5.992h12m-12 6.003H20.24m-12 5.999h12M4.117 7.495v-3.75H2.99m1.125 3.75H2.99m1.125 0H5.24m-1.92 2.577a1.125 1.125 0 1 1 1.591 1.59l-1.83 1.83h2.16M2.99 15.745h1.125a1.125 1.125 0 0 1 0 2.25H3.74m0-.002h.375a1.125 1.125 0 0 1 0 2.25H2.99" />
                        </svg>
                        Estoques
                    </li>
                    <li id="notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        Notificações
                    </li>
                </ul>
                <a href="../login-page/logout.php" class="button-submit">
                    <button style="width: 150px; margin: 0 auto;margin-top: 1em;">Sair</button>
                </a>
            </div>

        </aside>

        <!--Funcionários-->
        <section id="right-content">
            <section class="right-section employee show-div">
                <nav class="section-header">
                    <div>
                        <h1>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                            Todos os <span class="highlight-word">Funcionários</span>
                        </h1>

                        <p>Visualizar, pesquisar e adicionar novos funcionários</p>
                    </div>

                    <div class="user-data">
                        <img src="<?= htmlspecialchars($_COOKIE['picture']) ?>" alt="Foto de perfil">
                        <div class="user-data-text">
                            <p id="username-display"><?php echo $_COOKIE["username"]?></p>
                            <strong>
                                <p><?php echo $_COOKIE["area"]?></p>
                            </strong>
                        </div>
                        <a href="settings.php">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                    <path fill-rule="evenodd" d="M7.84 1.804A1 1 0 0 1 8.82 1h2.36a1 1 0 0 1 .98.804l.331 1.652a6.993 6.993 0 0 1 1.929 1.115l1.598-.54a1 1 0 0 1 1.186.447l1.18 2.044a1 1 0 0 1-.205 1.251l-1.267 1.113a7.047 7.047 0 0 1 0 2.228l1.267 1.113a1 1 0 0 1 .206 1.25l-1.18 2.045a1 1 0 0 1-1.187.447l-1.598-.54a6.993 6.993 0 0 1-1.929 1.115l-.33 1.652a1 1 0 0 1-.98.804H8.82a1 1 0 0 1-.98-.804l-.331-1.652a6.993 6.993 0 0 1-1.929-1.115l-1.598.54a1 1 0 0 1-1.186-.447l-1.18-2.044a1 1 0 0 1 .205-1.251l1.267-1.114a7.05 7.05 0 0 1 0-2.227L1.821 7.773a1 1 0 0 1-.206-1.25l1.18-2.045a1 1 0 0 1 1.187-.447l1.598.54A6.992 6.992 0 0 1 7.51 3.456l.33-1.652ZM10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </nav>

                <div class="section-top">
                    <div class="total">
                        <?php totalList("employee"); // exibe o total de funcionários?>
                        <p>Total de Funcionários</p>
                    </div>  
    
                    <form action ="<?php echo $_SERVER["PHP_SELF"]?>" method="get">
                        <div class="search-bar">
                            <label for="isearch-budget">Pesquisar Funcionário pelo Nome</label>
                            <input type="search" name="searchEmpl" id="isearch-employee" class="input-control" placeholder="Pressione Enter para Pesquisar">
                        </div>
                        <input type="hidden" name="currentTab" value="employee">
                    </form>

                    <div class="filter-bar">
                        <form action ="<?php echo $_SERVER["PHP_SELF"]?>" method="get">
                            <label for="iselect">Filtrar Funcionários</label>
                            <select name="selectFilterEmpl" id="iselect" class="input-control">
                                <optgroup label="Crescente">
                                    <option value="idEmpl">Id</option>
                                    <option value="nameEmpl">Nome</option>
                                    <option value="areaEmpl">Área de Atuação</option>
                                    <option value="emplPos">Cargo</option>
                                    <option value="bDayEmpl">Idade</option>
                                </optgroup>

                            </select>
                            <div class="button-submit" style="margin-top: 1em;">
                                <button class="filter-button ">Filtrar</button>
                            </div>
                            <input type="hidden" name="currentTab" value="employee">
                        </form>
                    </div>

                    <div class="button-submit">
                        <button class="add-employee-button">Adicionar Funcionário</button>
                    </div>
                </div>

                <div class="section-bottom">

                    <?php
                        if(isset($_POST["eName"])){ //  se algo estiver digitado no input "eName"
                            $result = addToList("employee");

                            switch($result){
                                case "alredyExist":{
                                    echo "
                                        <span class=\"error-text\">
                                            <p>Erro: <strong>Endereço de Email já Existente</strong></p>
                                            <p>Funcionário não inserido</p>
                                        </span>
                                    ";
                                    break;
                                }
                                case "invalidBDate":{
                                    echo "
                                        <span class=\"error-text\">
                                            <p>
                                                Erro:  <strong>Data de Nascimento</strong>. Precisa ser menor que a data atual e maior de 16 anos
                                            </p>
                                            <p>Funcionário não inserido</p>
                                        </span>
                                    ";
                                    break;
                                }
                                case "invalidEntryDate":{
                                    echo "
                                        <span class=\"error-text\">
                                            <p>Erro: <strong>Data de Ingresso</strong>. Precisa ser menor que a data atual</p>
                                            <p>Funcionário não inserido</p>
                                        </span>
                                    ";
                                    break;
                                }
                            }
                        }

                        if (isset($_GET["searchEmpl"])){ // // exibe todos os Funcionários com o nome digitado
                            searchList(type: "employee", filter: "nameEmpl");
                        }
                        echo "<h2><span class=\"highlight-word\">Todos os Funcionários</span></h2>";
                        if (isset($_GET["selectFilterEmpl"])) { // exibe todos os Funcionários filtrados
                            filterList(type: "employee"); 
                        }else{
                            showList(type: "employee"); // exibe todos os Funcionários
                        }
                    ?>

                </div>

            </section>
            <!--Funcionários-->

            <!--Funcionários Adicionar-->
            <section class="right-section addEmployee hidden-div" >
                <nav class="section-header">
                    <div>
                        <h1>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                            </svg>
                            <span class="highlight-word">Adicionar</span> Novo Funcionário
                        </h1>

                        <p>
                            Criar um novo Funcionário dentro do Banco de Dados
                        </p>
                    </div>

                    <div class="user-data">
                        <img src="<?= htmlspecialchars($_COOKIE['picture']) ?>" alt="Foto de perfil">
                        <div class="user-data-text">
                            <p id="username-display"><?php echo $_COOKIE["username"]?></p>
                            <strong>
                                <p><?php echo $_COOKIE["area"]?></p>
                            </strong>
                        </div>
                        <a href="settings.php">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                    <path fill-rule="evenodd" d="M7.84 1.804A1 1 0 0 1 8.82 1h2.36a1 1 0 0 1 .98.804l.331 1.652a6.993 6.993 0 0 1 1.929 1.115l1.598-.54a1 1 0 0 1 1.186.447l1.18 2.044a1 1 0 0 1-.205 1.251l-1.267 1.113a7.047 7.047 0 0 1 0 2.228l1.267 1.113a1 1 0 0 1 .206 1.25l-1.18 2.045a1 1 0 0 1-1.187.447l-1.598-.54a6.993 6.993 0 0 1-1.929 1.115l-.33 1.652a1 1 0 0 1-.98.804H8.82a1 1 0 0 1-.98-.804l-.331-1.652a6.993 6.993 0 0 1-1.929-1.115l-1.598.54a1 1 0 0 1-1.186-.447l-1.18-2.044a1 1 0 0 1 .205-1.251l1.267-1.114a7.05 7.05 0 0 1 0-2.227L1.821 7.773a1 1 0 0 1-.206-1.25l1.18-2.045a1 1 0 0 1 1.187-.447l1.598.54A6.992 6.992 0 0 1 7.51 3.456l.33-1.652ZM10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </nav>

                <div class="right-section-back-button back-employee">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                    <p>Voltar</p>
                </div>

                <form action ="<?php echo $_SERVER["PHP_SELF"]?>" method="post" autocomplete="on" class="section-bottom">
                    <header>
                        <p>Preencha o formulário abaixo para adicionar um <span class="highlight-word">Novo Funcionário</span></p>
                    </header>

                    <div class="right-section-forms">
                        <div class="forms-item">
                            <label for="iEName">Nome</label>
                            <input type="text" name="eName" id="iEName" class="input-control" required placeholder="Primeiro e Último Nome" maxlength="30">
                        </div>

                        <div class="forms-item">
                            <label for="iEBday">Data de Nascimento</label>
                            <input type="date" name="eBDay" id="iEBday" class="input-control" required>
                        </div>

                        <div class="forms-item">
                            <label for="ieEmail">Email</label>
                            <input type="email" name="eEmail" id="ieEmail" class="input-control" required placeholder="exemplo@gmail.com">
                        </div>

                        <div class="forms-item">
                            <label for="inum">Número de Telefone</label>
                            <input type="text" name="eNum" id="iENum" class="input-control" required placeholder="(XX) X XXXX-XXXX">
                        </div>

                        <div class="forms-item">
                            <label for="iEGender">Gênero</label>
                            <select name="eGender" id="iEGender" class="input-control">
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>
                                <option value="O">Outros</option>
                            </select>
                        </div>

                        <div class="forms-item">
                            <label for="iJoinDate">Data de Ingresso</label>
                            <input type="date" name="joinDate" id="iJoinDate" class="input-control">
                        </div>

                        <div class="forms-item">
                            <label for="iPos">Cargo</label>
                            <select name="position" id="iPos" class="input-control">
                                <option value="RH">Recursos Humanos</option>
                                <option value="Operações">Operações</option>
                                <option value="Gerente Projetos">Gerente de Projetos</option>
                                <option value="SAC">SAC</option>
                                <option value="Infraestrutura">Infraestrutura </option>
                                <option value="Segurança">Segurança </option>
                            </select>
                        </div>

                        <div class="forms-item">
                            <label for="iarea">Área</label>
                            <select name="eArea" id="iarea" class="input-control">
                                <option value="Gerencia">Gerencia</option>
                                <option value="Projetos">Projetos </option>
                                <option value="RH">RH</option>
                                <option value="Comercial">Comercial</option>
                            </select>
                        </div>

                        <div class="forms-item">
                            <label for="ipassword">Senha</label>
                            <input type="password" name="password" id="ipassword" class="input-control"  placeholder="• • • • • • •" maxlength="30" required>
                        </div>

                    </div>

                    <?php 
                        $status = $_GET["status"] ?? null;
                        if($status === "insert"){
                            echo "<span class=\"sucess-text\"><p>Funcionário com email <strong>\"{$_POST["eEmail"]}\"</strong> inserido no Banco de Dados com sucesso</p></span>";
                        }
                    ?>

                    <div class="button-submit">
                        <button>Enviar</button>
                    </div>
                </form>
            </section>
            <!--Funcionários Adicionar-->

            <!--Orçamentos-->
            <section class="right-section budget hidden-div">
                <nav class="section-header">
                    <div>
                        <h1>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span class="highlight-word">Orçamentos</span>
                        </h1>

                        <p>Visualizar, criar e enviar solicitações de orçamentos</p>
                    </div>

                    <div class="user-data">
                        <img src="<?= htmlspecialchars($_COOKIE['picture']) ?>" alt="Foto de perfil">
                        <div class="user-data-text">
                            <p id="username-display"><?php echo $_COOKIE["username"]?></p>
                            <strong>
                                <p><?php echo $_COOKIE["area"]?></p>
                            </strong>
                        </div>
                        <a href="settings.php">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                    <path fill-rule="evenodd" d="M7.84 1.804A1 1 0 0 1 8.82 1h2.36a1 1 0 0 1 .98.804l.331 1.652a6.993 6.993 0 0 1 1.929 1.115l1.598-.54a1 1 0 0 1 1.186.447l1.18 2.044a1 1 0 0 1-.205 1.251l-1.267 1.113a7.047 7.047 0 0 1 0 2.228l1.267 1.113a1 1 0 0 1 .206 1.25l-1.18 2.045a1 1 0 0 1-1.187.447l-1.598-.54a6.993 6.993 0 0 1-1.929 1.115l-.33 1.652a1 1 0 0 1-.98.804H8.82a1 1 0 0 1-.98-.804l-.331-1.652a6.993 6.993 0 0 1-1.929-1.115l-1.598.54a1 1 0 0 1-1.186-.447l-1.18-2.044a1 1 0 0 1 .205-1.251l1.267-1.114a7.05 7.05 0 0 1 0-2.227L1.821 7.773a1 1 0 0 1-.206-1.25l1.18-2.045a1 1 0 0 1 1.187-.447l1.598.54A6.992 6.992 0 0 1 7.51 3.456l.33-1.652ZM10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>

                </nav>

                <div class="section-top">
                    <div class="total">
                        <?php totalList(type: "budget");?>
                        <p>Total de Orçamentos</p>
                    </div>

                    <form action ="<?php echo $_SERVER["PHP_SELF"]?>" method="get">
                        <div class="search-bar">
                            <label for="isearch-budget">Pesquisa rápida de um Orçamento</label>
                            <input type="search" name="searchBudget" id="isearch-budget" class="input-control" placeholder="Número do Orçamento">
                        </div>

                        <input type="hidden" name="currentTab" value="budget">
                        
                    </form>

                    <div class="filter-bar">
                        <form action ="<?php echo $_SERVER["PHP_SELF"]?>" method="get">
                            <label for="iselectFilterBudget">Filtrar Orçamentos</label>
                            <select name="selectFilterBudget" id="iselectFilterBudget" class="input-control">
                                <option value="idBudget">Id</option>
                                <option value="numBudget">Número</option>
                                <option value="budgetValue">Valor(Crescente)</option>
                                <option value="budgetCost">Custo(Crescente)</option>
                                <option value="budgetClient">Cliente(A-Z)</option>
                            </select>
                            <div class="button-submit" style="margin-top: 1em;">
                                <button class="filter-button ">Filtrar</button>
                            </div>
                            
                            <input type="hidden" name="currentTab" value="budget">
                            
                        </form>
                        
                    </div>

                    <div>
                        <div class="button-submit budgetButton">
                            <button class="add-budget-button">Adicionar Orçamento</button>
                        </div>
                    </div>
                </div>

                <div class="section-bottom">
                        <?php
                            if (isset($_GET["searchBudget"])) { // exibe os Orçamentos com o número digitado no input
                                searchList(type: "budget", filter: "numBudget");
                            }

                            echo "<h2><span class=\"highlight-word\">Todos os Orçamentos</span></h2>";

                            if (isset($_GET["selectFilterBudget"])) { // filtra os Orçamentos
                                filterList(type: "budget");
                            }else{
                                showList(type: "budget"); // exibe todos os Orçamentos se nada for escrito
                            }
                        ?>
                </div>
            </section>
            <!--Orçamentos-->

            <!--Orçamentos - Adicionar-->
            <section class="right-section addBudget hidden-div">
                <nav class="section-header">
                    <div>
                        <h1>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                            </svg>

                            <span class="highlight-word">Adicionar</span> Novo Orçamento
                        </h1>

                        <p>
                            Adicione um novo <span class="highlight-word">Orçamento</span> dentro do Banco de Dados
                        </p>
                    </div>

                    <div class="user-data">
                        <img src="<?= htmlspecialchars($_COOKIE['picture']) ?>" alt="Foto de perfil">
                        <div class="user-data-text">
                            <p id="username-display"><?php echo $_COOKIE["username"]?></p>
                            <strong>
                                <p><?php echo $_COOKIE["area"]?></p>
                            </strong>
                        </div>
                        <a href="settings.php">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                    <path fill-rule="evenodd" d="M7.84 1.804A1 1 0 0 1 8.82 1h2.36a1 1 0 0 1 .98.804l.331 1.652a6.993 6.993 0 0 1 1.929 1.115l1.598-.54a1 1 0 0 1 1.186.447l1.18 2.044a1 1 0 0 1-.205 1.251l-1.267 1.113a7.047 7.047 0 0 1 0 2.228l1.267 1.113a1 1 0 0 1 .206 1.25l-1.18 2.045a1 1 0 0 1-1.187.447l-1.598-.54a6.993 6.993 0 0 1-1.929 1.115l-.33 1.652a1 1 0 0 1-.98.804H8.82a1 1 0 0 1-.98-.804l-.331-1.652a6.993 6.993 0 0 1-1.929-1.115l-1.598.54a1 1 0 0 1-1.186-.447l-1.18-2.044a1 1 0 0 1 .205-1.251l1.267-1.114a7.05 7.05 0 0 1 0-2.227L1.821 7.773a1 1 0 0 1-.206-1.25l1.18-2.045a1 1 0 0 1 1.187-.447l1.598.54A6.992 6.992 0 0 1 7.51 3.456l.33-1.652ZM10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>

                </nav>

                <div class="right-section-back-button back-budget">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                    <p>Voltar</p>
                </div>

                <form action ="<?php echo $_SERVER["PHP_SELF"]?>"  method="post" autocomplete="on" class="section-bottom">
                    <header>
                        <p>Preencha o formulário abaixo para adicionar um <span class="highlight-word">Novo Orçamento</span></p>
                    </header>

                    <div class="right-section-forms">
                        <div class="forms-item">
                            <label for="iBNum">Número Orçamento</label>
                            <input type="text" name="bNum" id="iBNum" class="input-control" placeholder="Insira o Número do Orçamento Aqui" required max="11">
                        </div>

                        <div class="forms-item">
                            <label for="iBdesc">Breve Descrição do Projeto</label>
                            <input type="text" name="bDesc" id="iBdesc" class="input-control" placeholder="Insira a Descrição" maxlength="50" required>
                        </div>

                        <div class="forms-item">
                            <label for="iBValue">Valor Estimado</label>
                            <input type="number" name="bValue" id="iBValue" class="input-control" placeholder="Valor em R$" step="0.01" required>
                        </div>

                        <div class="forms-item">
                            <label for="iBCost">Custos Previstos</label>
                            <input type="number" name="bCost" id="iBCost" class="input-control" placeholder="Valor em R$" step="0.001" required>
                        </div>

                        <div class="forms-item">
                            <label for="iBClient">Cliente</label>
                            <input type="text" name="bClient" id="iBClient" class="input-control" placeholder="Nome do Cliente" required>
                        </div>

                    </div>
                    <?php 
                        $status = $_GET["status"];

                        if($status === "insert"){
                            echo "
                                <span class=\"sucess-text\">
                                    <p>
                                        Orçamento de número 
                                        <strong>\" {$_POST["bNum"]}\"</strong> 
                                        inserido no Banco de Dados com sucesso
                                    </p>
                                </span>
                            ";
                        }
                    ?>
                    <div class="button-submit">
                        <button>Enviar</button>
                    </div>

                    <?php
                        if (isset($_POST["bNum"])) {
                            addToList("budget");
                        }
                    ?>

                </form>

            </section>
            <!--Orçamentos - Adicionar-->

            <!--Estoques-->
            <section class="right-section stock hidden-div">
                <nav class="section-header">
                    <div>
                        <h1>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.242 5.992h12m-12 6.003H20.24m-12 5.999h12M4.117 7.495v-3.75H2.99m1.125 3.75H2.99m1.125 0H5.24m-1.92 2.577a1.125 1.125 0 1 1 1.591 1.59l-1.83 1.83h2.16M2.99 15.745h1.125a1.125 1.125 0 0 1 0 2.25H3.74m0-.002h.375a1.125 1.125 0 0 1 0 2.25H2.99" />
                            </svg>
                            <span class="highlight-word">Estoques</span>
                        </h1>

                        <p>Visualizar o estoque de produtos</p>
                    </div>

                    <div class="user-data">
                        <img src="<?= htmlspecialchars($_COOKIE['picture']) ?>" alt="Foto de perfil">
                        <div class="user-data-text">
                            <p id="username-display"><?php echo $_COOKIE["username"]?></p>
                            <strong>
                                <p><?php echo $_COOKIE["area"]?></p>
                            </strong>
                        </div>
                        <a href="settings.php">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                    <path fill-rule="evenodd" d="M7.84 1.804A1 1 0 0 1 8.82 1h2.36a1 1 0 0 1 .98.804l.331 1.652a6.993 6.993 0 0 1 1.929 1.115l1.598-.54a1 1 0 0 1 1.186.447l1.18 2.044a1 1 0 0 1-.205 1.251l-1.267 1.113a7.047 7.047 0 0 1 0 2.228l1.267 1.113a1 1 0 0 1 .206 1.25l-1.18 2.045a1 1 0 0 1-1.187.447l-1.598-.54a6.993 6.993 0 0 1-1.929 1.115l-.33 1.652a1 1 0 0 1-.98.804H8.82a1 1 0 0 1-.98-.804l-.331-1.652a6.993 6.993 0 0 1-1.929-1.115l-1.598.54a1 1 0 0 1-1.186-.447l-1.18-2.044a1 1 0 0 1 .205-1.251l1.267-1.114a7.05 7.05 0 0 1 0-2.227L1.821 7.773a1 1 0 0 1-.206-1.25l1.18-2.045a1 1 0 0 1 1.187-.447l1.598.54A6.992 6.992 0 0 1 7.51 3.456l.33-1.652ZM10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>

                </nav>

                <div class="section-top">

                </div>

                <div class="section-bottom">

                </div>

            </section>
            <!--Estoques-->

            <!--Notificações-->
            <section class="right-section notifications hidden-div">
                <nav class="section-header">
                    <div>
                        <h1>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                            <span class="highlight-word">Notificações</span>
                        </h1>

                        <p>
                            Visualize suas notificações
                        </p>
                    </div>

                    <div class="user-data">
                        <img src="<?= htmlspecialchars($_COOKIE['picture']) ?>" alt="Foto de perfil">
                        <div class="user-data-text">
                            <p id="username-display"><?php echo $_COOKIE["username"]?></p>
                            <strong>
                                <p><?php echo $_COOKIE["area"]?></p>
                            </strong>
                        </div>
                        <a href="settings.php">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                    <path fill-rule="evenodd" d="M7.84 1.804A1 1 0 0 1 8.82 1h2.36a1 1 0 0 1 .98.804l.331 1.652a6.993 6.993 0 0 1 1.929 1.115l1.598-.54a1 1 0 0 1 1.186.447l1.18 2.044a1 1 0 0 1-.205 1.251l-1.267 1.113a7.047 7.047 0 0 1 0 2.228l1.267 1.113a1 1 0 0 1 .206 1.25l-1.18 2.045a1 1 0 0 1-1.187.447l-1.598-.54a6.993 6.993 0 0 1-1.929 1.115l-.33 1.652a1 1 0 0 1-.98.804H8.82a1 1 0 0 1-.98-.804l-.331-1.652a6.993 6.993 0 0 1-1.929-1.115l-1.598.54a1 1 0 0 1-1.186-.447l-1.18-2.044a1 1 0 0 1 .205-1.251l1.267-1.114a7.05 7.05 0 0 1 0-2.227L1.821 7.773a1 1 0 0 1-.206-1.25l1.18-2.045a1 1 0 0 1 1.187-.447l1.598.54A6.992 6.992 0 0 1 7.51 3.456l.33-1.652ZM10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>

                </nav>

                <div class="section-top">

                </div>

                <div class="section-bottom">

                </div>

            </section>

            <!--Notificações-->

        </section>
    </main>

</body>
</html>