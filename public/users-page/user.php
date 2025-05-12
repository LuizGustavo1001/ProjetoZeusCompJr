<?php 
    include "../../dbConnection.php";

    if(! isset($_SESSION)){
        session_start(); // iniciar a sessão 
    }
    
    if (! isset($_SESSION["email"])) { // tentando acessar a página do usuário sem estar logado
        header("Location: error.php");
    }

    function totalList($type){
        global $mysqli;
        $sql_code = " SELECT count(*) FROM $type";
        $sql_query = $mysqli->query($sql_code) or die($mysqli->error);
        
        if($type == "funcionario"){ // employee
            $totalEmpl = $sql_query->fetch_row()[0];
            echo "<p><strong><span id=\"total-employes\">$totalEmpl</span></strong></p>";
        }else{ // budget
            $totalBudgets = $sql_query->fetch_row()[0];
            echo "<p><strong><span id=\"total-budget\">$totalBudgets</span></strong></p>";
        }
    }
    
    function showList($type){ // mostra todos os itens cadastrados de um tabela
        global $mysqli;
        if($type == "funcionario"){
?>
            <h2><span class="highlight-word">Todos os Funcionários </span></h2>
<?php
        }else if($type == "orcamento"){ // exibe todos os orçamentos
?>
            <h2> <span class="highlight-word">Todos os Orçamentos</span> </h2>
<?php
        }
        $sql_code = "SELECT * FROM $type";
        $sql_query = $mysqli->query($sql_code) or die($mysqli->error);

        if($sql_query->num_rows == 0){
            if($type == "funcionario"){
?>
                <p>Nenhum Funcionário Cadastrado no Momento</p>
<?php       
            }else if($type == "orcamento"){
?>
                <p>Nenhum Orçamento Cadastrado no Momento</p>
<?php     
            }
        }else{
?>
            <table class= "search-result">
<?php
            if($type == "funcionario"){
?>
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
<?php
            }else if($type == "orcamento"){
?>
                <tr>
                    <th>id</th> 
                    <th>Número</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Custo</th>
                    <th>Cliente</th>
                </tr> 
<?php
            }
            if($type == "funcionario"){
                
                while($dados = $sql_query->fetch_assoc()){ // adiciona o funcionário ao DB na tabela Funcionario
                    if($dados["genero"] == "M"){
                        $dados["genero"] = "Masculino";
                    }else if($dados["genero"] == "F"){
                        $dados["genero"] = "Feminino";
                    }else{
                        $dados["genero"] = "Outro";
                    }
?>
                    <tr class="func-tr">
                        <td><?php echo $dados["idFunc"] ?></td>
                        <td><?php echo $dados["nomeFunc"] ?></td>
                        <td><?php echo $dados["dataNasc"] ?></td>
                        <td><?php echo $dados["emailFunc"] ?></td>
                        <td><?php echo $dados["genero"] ?></td>
                        <td><?php echo $dados["telefone"] ?></td>
                        <td><?php echo $dados["cargo"] ?></td>
                        <td><?php echo $dados["dataI"] ?></td>
                        <td><?php echo $dados["areaFunc"] ?></td>
                    </tr>
<?php
                }
            }else if($type == "orcamento"){
                    while($dados = $sql_query->fetch_assoc()){ // adiciona o orçamento ao DB na Tabela Orçamento
                    $padrao = numfmt_create("pt-BR", style: NumberFormatter::CURRENCY);
?>                  
                    <tr class="func-tr">
                        <td><?php echo $dados["idOrc"] ?></td>
                        <td><?php echo $dados["numOrc"] ?></td>
                        <td><?php echo $dados["descProj"] ?></td>
                        <td>
                            <?php 
                                echo numfmt_format_currency($padrao, $dados["valorOrc"], "BRL")
                            ?>
                        </td>
                        <td>
                            <?php 
                                echo  numfmt_format_currency($padrao, $dados["custoOrc"], "BRL")
                            ?>
                        </td>
                        <td><?php echo $dados["cliente"] ?></td>
                    </tr>
<?php
                }
            }
?>
            </table>
<?php
        }
    }
    function searchList($type, $filter){ // exibe o resultado da busca
        global $mysqli;
        if($type == "funcionario"){
            $pesquisa = $mysqli->real_escape_string($_GET["searchEmpl"]);
        }else if($type = "orcamento"){
            $pesquisa = $mysqli->real_escape_string($_GET["searchBudget"]);
        }
        $sql_code = 
        "
        SELECT * FROM $type
        WHERE $filter LIKE '%$pesquisa%'
        ";

        $sql_query = $mysqli->query($sql_code) or die($mysqli->error);

        if($sql_query->num_rows == 0){ // nenhum funcionario com o nome desejado foi encontrado
            if($type == "funcionario"){
?>
                <p>Nenhum Funcionário Encontrado com o Nome: <strong>"<?php echo $pesquisa?>"</strong></p>
<?php
            }else if($type == "orcamento"){
?>
                <p>Nenhum Orçamento Encontrado com o Número: <strong> "<?php echo $pesquisa?>"</strong></p>
<?php
            }
        }else{ // funcionario(s) encontrados(s)
            if($type == "funcionario"){
?>          
                <p>
                    <strong>
                        <span class="highlight-word">
                            Funcionário(s) Encontrado(s) com o filtro: 
                        </span>"<?php echo $pesquisa?>"
                    </strong> 
                </p>

                <table class="search-result">
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
<?php
                while($dados = $sql_query->fetch_assoc()){
                    if($dados["genero"] == "M"){
                        $dados["genero"] = "Masculino";
                    }else if($dados["genero"] == "F"){
                        $dados["genero"] = "Feminino";
                    }else{
                        $dados["genero"] = "Outro";
                    }
?>
                    <tr>
                        <td><?php echo $dados["idFunc"] ?></td>
                        <td><?php echo $dados["nomeFunc"] ?></td>
                        <td><?php echo $dados["dataNasc"] ?></td>
                        <td><?php echo $dados["emailFunc"] ?></td>
                        <td><?php echo $dados["genero"] ?></td>
                        <td><?php echo $dados["telefone"] ?></td>
                        <td><?php echo $dados["cargo"] ?></td>
                        <td><?php echo $dados["dataI"] ?></td>
                        <td><?php echo $dados["areaFunc"] ?></td>
                    </tr>
<?php
                }
?>
                </table>
<?php
            }else if($type == "orcamento"){
?>          
                <p class="highlight-word"><strong>Orçamento(s) Encontrado(s)!</strong></p>
                <table class="search-result">
                    <tr>
                        <th>id</th> 
                        <th>Número</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Custo</th>
                        <th>Cliente</th>
                    </tr> 
<?php
                while($dados = $sql_query->fetch_assoc()){
                $padrao = numfmt_create("pt-BR", style: NumberFormatter::CURRENCY);
?>
                    <tr class="func-tr">
                        <td><?php echo $dados["idOrc"] ?></td>
                        <td><?php echo $dados["numOrc"] ?></td>
                        <td><?php echo $dados["descProj"] ?></td>
                        <td><?php  echo  numfmt_format_currency($padrao, $dados["valorOrc"], "BRL")?></td>
                        <td><?php  echo  numfmt_format_currency($padrao, $dados["custoOrc"], "BRL")?></td>
                    </tr>
<?php
                }
?>
                </table>
<?php 
            }
        }
    }

    function addToList($type){
        global $mysqli;

        if($type == "funcionario"){
            $stmt = $mysqli->prepare("
                INSERT INTO funcionario (nomeFunc, dataNasc, emailFunc, genero, telefone, cargo, dataI, areaFunc) VALUES
                (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param("ssssssss", // quantidade e tipo de dados escritos
                $_POST["eName"],
                $_POST["eBDay"],
                $_POST["eEmail"],
                $_POST["eGender"],
                $_POST["eNum"],
                $_POST["position"],
                $_POST["joinDate"],
                $_POST["eArea"]
            );

        }else if($type == "orcamento"){

            $stmt = $mysqli->prepare("
                INSERT INTO orcamento (numOrc, descProj, valorOrc, custoOrc, cliente) VALUES
                (?, ?, ?, ?, ?)
            ");

            $stmt->bind_param("ssdds",
                $_POST["bNum"],
                $_POST["bDesc"],
                $_POST["bValue"],
                $_POST["bCost"],
                $_POST["bClient"]
            );

        }
        if($stmt->execute()){
            header("Location: user.php?success=1");
        }else{
            echo "<p>Erro ao Adicionar: " . $mysqli->error . "</p>";
        }

        if ($stmt) {
            $stmt->close();
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["bNum"], $_POST["bDesc"], $_POST["bValue"], $_POST["bCost"], $_POST["bClient"])) {
            addToList("orcamento");
        }
        if (isset($_POST["eName"], $_POST["eBDay"], $_POST["eEmail"], $_POST["eGender"], $_POST["position"], $_POST["joinDate"], $_POST["eArea"])) {
            addToList("funcionario");
        }
    }
    function filterList($type){
        global $mysqli;
        if($type == "funcionario"){
             $filter = $mysqli->real_escape_string($_GET["selectFilterEmpl"]);
        }else if($type == "orcamento"){
             $filter = $mysqli->real_escape_string($_GET["selectFilterBudget"]);
        }

        $sql_code = 
        "
        SELECT * FROM $type
        ORDER BY $filter asc
        ";

        $sql_query = $mysqli->query($sql_code) or die($mysqli->error);

        if($type == "funcionario"){
?>          
            <p class="highlight-word"><strong>Funcionários Filtrados por <?php echo $filter?></strong></p>
            <table class="search-result">
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
<?php
            while($dados = $sql_query->fetch_assoc()){
                if($dados["genero"] == "M"){
                    $dados["genero"] = "Masculino";
                }else if($dados["genero"] == "F"){
                    $dados["genero"] = "Feminino";
                }else{
                    $dados["genero"] = "Outro";
                }
?>
                <tr>
                    <td><?php echo $dados["idFunc"] ?></td>
                    <td><?php echo $dados["nomeFunc"] ?></td>
                    <td><?php echo $dados["dataNasc"] ?></td>
                    <td><?php echo $dados["emailFunc"] ?></td>
                    <td><?php echo $dados["genero"] ?></td>
                    <td><?php echo $dados["telefone"] ?></td>
                    <td><?php echo $dados["cargo"] ?></td>
                    <td><?php echo $dados["dataI"] ?></td>
                    <td><?php echo $dados["areaFunc"] ?></td>
                </tr>
<?php
            }
?>
            </table>
<?php
        }else if($type == "orcamento"){
?>          
            <p class="highlight-word"><strong>Orçamento(s) Filtrados por <?php echo $filter?> </strong></p>
            <table class="search-result">
                <tr>
                    <th>id</th> 
                    <th>Número</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Custo</th>
                    <th>Cliente</th>
                </tr> 
<?php
            while($dados = $sql_query->fetch_assoc()){
            $padrao = numfmt_create("pt-BR", style: NumberFormatter::CURRENCY);
?>
                <tr class="func-tr">
                    <td><?php echo $dados["idOrc"] ?></td>
                    <td><?php echo $dados["numOrc"] ?></td>
                    <td><?php echo $dados["descProj"] ?></td>
                    <td><?php  echo  numfmt_format_currency($padrao, $dados["valorOrc"], "BRL")?></td>
                    <td><?php  echo  numfmt_format_currency($padrao, $dados["custoOrc"], "BRL")?></td>
                </tr>
<?php
            }
?>
            </table>
<?php 

        }
    }
?>


<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
        <link rel="shortcut icon" href="../icon/favicon.ico" type="image/x-icon">
    
        <link rel="stylesheet" href="users-style.css">
        <link rel="stylesheet" href="../general-styles.css">
        <link rel="stylesheet" href="../dark-mode.css">

        <script src="../dark-mode.js" defer></script>
        
        <script src="user.js" defer></script>
    
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
                    <li>
                        <a href="../login-page/logout.php" class="button-submit"><button>Sair</button></a>
                    </li>
                </ul>
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
                        <img src="../general-images/user-icon.png" alt="user-img">
                        <p id="username-display"><?php echo $_SESSION["username"]?></p>
                    </div>

                </nav>

                <div class="section-top">
                    <div class="total">
                        <?php 
                            $type = "funcionario";
                            totalList($type);
                        ?>

                        <p>Total de Funcionários</p>
                    </div>

                    <form action="" method="get">
                        <div class="search-bar">
                            <label for="isearch">Pesquisar Funcionário pelo Nome</label>
                            <input type="search" name="searchEmpl" id="isearch" class="input-control" placeholder="Pressione Enter para Pesquisar">
                        </div>
                    </form>
                    

                    <div class="filter-bar" >
                            <form action="" method="get" >
                                <label for="iselect">Filtrar Funcionários</label>
                                <select name="selectFilterEmpl" id="iselect" class="input-control">
                                    <optgroup label="Crescente">
                                        <option value="idFunc">Id</option>
                                        <option value="nomeFunc">Nome</option>
                                        <option value="areaFunc">Área de Atuação</option>
                                        <option value="cargo">Cargo</option>
                                    </optgroup>
                                    
                                </select>
                                <button class="filter-button">Filtrar</button>
                            </form>
                    </div>

                    <div class="button-submit">
                        <button class="add-employee-button">Adicionar Funcionário</button>
                    </div>
                </div>

                <div class="section-bottom">
                    <?php 
                        $type = "funcionario";
                        if(isset($_GET["selectFilterEmpl"])){ // filtra os Orçamentos
                            filterList($type);
                        }
                        if(isset($_GET["searchEmpl"])){ // // exibe todos os Orçamentos com o nome digitado
                            $filter = "nomeFunc";
                            searchList($type, $filter);
                        }
                        showList($type);
                    ?>
                </div>

            </section>
    <!--Funcionários-->

    <!--Funcionários Adicionar-->
            <section class="right-section addEmployee hidden-div">
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
                        <img src="../general-images/user-icon.png" alt="user-img">
                        <p id="username-display"><?php echo $_SESSION["username"]?></p>
                    </div>


                </nav>

                <div class="right-section-back-button back-employee">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                    <p>Voltar</p>
                </div>

                <form action="" method="post" autocomplete="on" class="section-bottom">
                    <header>
                        <p>Preencha o formulário abaixo para adicionar um <span class="highlight-word">Novo Funcionário</span></p>
                    </header>

                    <div class="right-section-forms">
                        <div class="forms-item">
                            <label for="iEName">Nome</label>
                            <input type="text" name="eName" id="iEName" class="input-control" required placeholder="Primeiro e Último Nome">
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
                        
                    </div>

                    <div class="button-submit">
                        <button>Enviar</button>
                    </div>

                    <?php 
                        if(
                            isset($_POST["EName"]) and 
                            isset($_POST["EBday"]) and
                            isset($_POST["eEmail"]) and
                            isset($_POST["ENum"]) and
                            isset($_POST["EGender"]) and
                            isset($_POST["joinDate"]) and
                            isset($_POST["position"]) and
                            isset($_POST["eArea"])
                        ){
                            $type = "funcionario";
                            addToList($type);
                        }
                    ?>

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
                        <img src="../general-images/user-icon.png" alt="user-img">
                        <p id="username-display"><?php echo $_SESSION["username"]?></p>
                    </div>

                </nav>

                <div class="section-top">
                    <div class="total">
                        <?php 
                            $type = "orcamento";
                            totalList($type);
                        ?>
                        <p>Total de Orçamentos</p>
                    </div>

                    <form action="" method="get">
                        <div class="search-bar">
                            <label for="isearch">Pesquisa rápida de um Orçamento</label>
                            <input type="search" name="searchBudget" id="isearch" class="input-control" placeholder="Número do Orçamento">
                        </div>
                    </form>

                    <div class="filter-bar">
                        <form action="" method="get">
                            <label for="iselect">Filtrar Orçamentos</label>
                            <select name="select" id="iselect" class="input-control">
                                <option value="idOrc">Id</option>
                                <option value="valorOrc">Valor(Crescente)</option>
                                <option value="custoOrc">Custo(Crescente)</option>
                            </select>
                            <button class="filter-button">Filtrar</button>
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
                        $type = "orcamento";
                        if(isset($_GET["selectFilterBudget"])){ // filtra os funcionarios
                            filterList($type);
                        }
                        if(isset($_GET["searchBudget"])){ // exibe todos os Funcionários se nada for escrito
                            $filter = "numOrc";
                            searchList($type, $filter);

                        }
                        showList($type);
                        
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
                        <img src="../general-images/user-icon.png" alt="user-img">
                        <p id="username-display"><?php echo $_SESSION["username"]?></p>
                    </div>

                </nav>

                <div class="right-section-back-button back-budget">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                    <p>Voltar</p>
                </div>

                <form action="" method="post" autocomplete="on" class="section-bottom">
                    <header>
                        <p>Preencha o formulário abaixo para adicionar um <span class="highlight-word">Novo Orçamento</span></p>
                    </header>

                    <div class="right-section-forms">
                        <div class="forms-item">
                            <label for="iBNum">Número Orçamento</label>
                            <input type="text" name="bNum" id="iBNum" class="input-control" placeholder="Insira o Número do Orçamento Aqui">
                        </div>

                        <div class="forms-item">
                            <label for="iBdesc">Breve Descrição do Projeto</label>
                            <input type="text" name="bDesc" id="iBdesc" class="input-control" required placeholder="Insira a Descrição" maxlength="50">
                        </div>

                        <div class="forms-item">
                            <label for="iBValue">Valor Estimado</label>
                            <input type="number" name="bValue"id="iBValue" class="input-control" required placeholder="Valor em R$" step="0.01">
                        </div>

                        <div class="forms-item">
                            <label for="iBCost">Custos Previstos</label>
                            <input type="number" name="bCost" id="iBCost" class="input-control" required placeholder="Valor em R$" step="0.001">
                        </div>

                        <div class="forms-item">
                           <label for="iBClient">Cliente</label>
                        <input type="text" name="bClient" id="iBClient" class="input-control" required placeholder="Nome do Cliente">
                        </div>

                    </div>

                    <div class="button-submit">
                        <button>Enviar</button>
                    </div>

                    <?php 
                        if(
                            isset($_POST["bNum"]) and 
                            isset($_POST["bDesc"]) and
                            isset($_POST["bValue"]) and
                            isset($_POST["bCost"]) and
                            isset($_POST["bClient"])
                        ){
                            $type = "orcamento";
                            addToList($type);
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
                        <img src="../general-images/user-icon.png" alt="user-img">
                        <p id="username-display"> <?php echo $_SESSION["username"]?></p>
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
                        <img src="../general-images/user-icon.png" alt="user-img">
                        <p id="username-display"><?php echo $_SESSION["username"]?></p>
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
