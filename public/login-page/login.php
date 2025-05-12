<?php 
    include "../../dbConnection.php";
    if(isset($_POST["userEmail"])){ // se algo for escrito nos campos a função será executada
        $email = $mysqli->real_escape_string($_POST["userEmail"]);
        $password = $mysqli->real_escape_string($_POST["userPassword"]);

        $sql_code = 
        "
        SELECT * FROM usuario 
        WHERE userEmail = '$email' AND userPassword = '$password'
        ";
        $sql_query = $mysqli->query($sql_code) or die($mysqli->error); // executa o código sql

        if($sql_query->num_rows != 0){ // há algum usuário cadastrado com os dados enviados
            //criar sessão
            session_start();
            $user = $sql_query->fetch_assoc(); // pegar todos os dados do usuário do BD e armazenar em $usuario
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['userEmail'];
            header("location: ../users-page/user.php");
            exit;
        }else{ // nenhum usuário cadastrado com os dados enviados
            $erroLogin = true;
        }                 
    }else{
        session_start();
        if(isset($_SESSION['success_message'])){
            $cadastrado = true;
            $_SESSION['success_message'] = null; // limpa a mensagem de sucesso
        }
    }

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../icon/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="../general-styles.css">
    <link rel="stylesheet" href="../dark-mode.css">

    <script src="../dark-mode.js" defer></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">

    <title>Projeto Zeus - Login</title>

</head>

<body>
    <main>
        <!--Logo and Dark/light mode Switch-->
        <div class="content">
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
                </div>
                <div class="content-bottom-forms">

                    <form action="" method="post" autocomplete="on">
                        <div class="forms-item">
                            <label for="iemail">Email</label>
                            <input type="email" name="userEmail" id="iUserEmail" class="input-control" required
                                placeholder="Seu Email Institucional" maxlength="40">
                        </div>
                        <div class="forms-item">
                            <label for="isenha">Senha</label>
                            <input type="password" name="userPassword" id="iUserPassword" class="input-control" required
                                placeholder="• • • • • • •" maxlength="30">
                        </div>

                        <div id="forms-bottom">
                            <div class="forms-checkbox">
                                <input type="checkbox" name="lembrar" id="ilembrar" style="transform: scale(1.3);">
                                <label for="ilembrar">Lembrar Usuário</label>
                            </div>
                            <a href="password.html">Esqueci minha Senha</a>
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
                                </span>
                                ";
                            }
                            if(isset($cadastrado)){
                                echo "
                                <span class=\"sucess-text\">
                                    <p>Usuário cadastrado com sucesso</p>
                                    <p>Insira novamente seus dados acima para Entrar na Área do Usuário</p>
                                </span>
                               ";
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