<?php 
include "../../dbConnection.php";

// Não é necessário iniciar a sessão, pois vamos usar apenas cookies

if (isset($_POST['userEmail'], $_POST['userPassword'])) {
    $email = $_POST['userEmail'];
    $password = $_POST['userPassword'];

    $rememberUser = isset($_POST["remember"]);

    $stmt = $mysqli->prepare('SELECT * FROM employee WHERE emailEmpl = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $amount = $result->num_rows;

    switch($amount){
        case 0:{ // nenhum funcionário cadastrados com as credenciais digitadas
             $erroLogin = true;
             break;
        }
        default:{ // existe algum funcionário com as credencias digitadas
            $data = $result->fetch_assoc(); // pegando os dados do resultado do sql_query e armazenando em um vetor 
            $storedPassword = $data['emplPassword']; // senha no banco de dados

            if($storedPassword == $password){ // Login com senha em texto puro no Banco de Dados -> migrar para hash
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $updateStmt = $mysqli->prepare("UPDATE employee SET emplPassword = ? WHERE emailEmpl = ?");
                $updateStmt->bind_param("ss", $newHash, $email);
                $updateStmt->execute();
                $updateStmt->close();
                $storedPassword = $newHash; // atualizar o valor da senha armazenada no Banco de Dados
            }

            if(password_verify($password, $storedPassword)){ // verificar se a senha hasheada é a mesma digitada pelo usuário
                if($rememberUser){ // ficar logado por mais de 1 dia
                    $time = time() + 2629800;

                }else{ // fica logado por 1 hora
                    $time = time() + 3600; 
                }

                setcookie('username', $data['nameEmpl'],       $time, "/");
                setcookie('email',    $data['emailEmpl'],      $time, "/");
                setcookie('cargo',    $data['emplPos'],        $time, "/");
                setcookie('id',       $data['idEmpl'],         $time, "/");
                setcookie('area',     $data['areaEmpl'],       $time, "/");
                setcookie('picture',  $data['profilePicPath'], $time, "/");
                setcookie('bday',     $data['bDayEmpl'],       $time, "/");
                setcookie('gender',   $data['genderEmpl'],     $time, "/");
                setcookie('number',   $data['numberEmpl'],     $time, "/");

                header("Location: ../users-page/user.php");
               
                exit();
            }else{
                $erroLogin = true; // possui uma função abaixo que utiliza essa variavel
            }
            break;
        }
    }
    $stmt->close(); // Fecha a declaração
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../icon/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="../styles/other-pages.css">
    <link rel="stylesheet" href="../styles/general.css">
    <link rel="stylesheet" href="../styles/dark-mode.css">


    <script src="../scripts/dark-mode.js" defer></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <style>
        @media(min-width: 1024px){
            .right-content-img{
                display: block;
                background: url(images/loginBg.png) no-repeat center center;
                background-size: cover;
                transition: all var(--transition-time);

            }
            .dark-mode .right-content-img{
                background:url(images/dm-loginBg.png)no-repeat center center;
                background-size: cover;
            }
        }
        
    </style>

    <title>Projeto Zeus - Login</title>

</head>

<body>
    <main>
        <div class="content">
        <!--Logo and Dark/light mode Switch-->
            <div class="content-header">
                <a href="login.php">
                    <img src="../general-images/church-symbol.png" alt="church-symbol" id="church-symbol">
                </a>

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
        <!--Logo and Dark/light Mode Switch-->

        <!--Forms-->
            <div class="content-bottom">
                <div class="content-bottom-text">
                    <p>Bem vindo de volta!</p>
                    <h1>Faça seu <span class="highlight-word">login</span> novamente</h1>
                    <?php 
                        if(isset($_COOKIE['registrado'])){
                            echo "
                                <span class=\"sucess-text\" style= \"text-align: left;\">
                                    <p>Usuário Registrado com sucesso, digite suas credenciais para entrar na conta</p>
                                </span>
                            ";
                            $_COOKIE['registrado'] = null; // resetar o valor 
                        }
                    ?>
                </div>
                <div class="content-bottom-forms">
                        
                    <form action ="<?php echo $_SERVER["PHP_SELF"]?>" method="post" autocomplete="on">
                        <div class="forms-item">
                            <label for="iUserEmail">Email</label>
                            <input type="email" name="userEmail" id="iUserEmail" class="input-control" required
                                placeholder="Seu Email Institucional" maxlength="40">
                        </div>
                        <div class="forms-item">
                            <label for="iUserPassword">Senha</label>
                            <input type="password" name="userPassword" id="iUserPassword" class="input-control" required
                                placeholder="• • • • • • •" maxlength="30">
                        </div>

                        <div id="forms-bottom">
                            <div class="forms-checkbox">
                                <input type="checkbox" name="remember" id="iremember" style="transform: scale(1.3);">
                                <label for="iremember">Lembrar Usuário</label>
                            </div>
                            <a href="password.php">Esqueci minha Senha</a>
                        </div>

                        <div class="button-submit">
                            <button type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                                </svg>
                                Entrar
                            </button>
                            <?php 
                                if(isset($erroLogin)){
                                    echo "
                                    <span class=\"error-text\">
                                        <p>Erro: Credencias Inseridas <strong>não estão cadastradas</strong></p>
                                        <p>Clique no Botão Abaixo para<strong> Cadastrar-se</strong></p>
                                        <p>ou Tente Novamente</p>
                                    </span>
                                    ";
                                    $erroLogin = null; // limpa a mensagem de erro
                                }
                                if(isset($cadastrado)){
                                    echo "
                                    <span class=\"sucess-text\">
                                        <p>Usuário cadastrado com sucesso</p>
                                        <p>Insira novamente seus dados acima para Entrar na Área do Usuário</p>
                                    </span>
                                ";
                                    $cadastrado = null; // limpa a mensagem de erro
                                }
                            ?>
                        </div>
                    </form>
                    <div id="create-account" style="padding-top: 2em; text-align: center;"  >
                        <a href="../sign-up-page/sign-up.php">Não está cadastrado ainda? Cadastre-se Aqui!</a>
                    </div>
                </div>
            </div>
        </div>
        <!--Forms-->

        <div class="right-content-img"></div>

    </main>


</body>

</html>