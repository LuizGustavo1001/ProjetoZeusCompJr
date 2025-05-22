
<?php
include "../../dbConnection.php";

if(! isset($_SESSION)){
    session_start(); // iniciar a sessão 
}

if(! isset($_SESSION["emailReciever"])){ // entrando na página sem solicitar um token
    header("location: password.php");
}else{
    $emailReciever = $_SESSION["email"];

    if(isset($_GET["token"])){

        $stmt = $mysqli->prepare("
            SELECT rescueToken FROM rescuepassword 
            WHERE rescueToken = ?
        ");
        $stmt->bind_param("s", $_SESSION["token"]);
        $stmt->execute();

        $amount = $stmt->get_result()->num_rows;

        switch($amount){
            case 0: { // token não consta no Banco de Dados
                $token404 = true;
                break;
            }
            default:{ // token consta no Banco de Dados
                // remover o token do banco de dados 
                $stmtDelete = $mysqli->prepare("
                    DELETE FROM rescuepassword 
                    WHERE rescueToken = ?
                ");
                $stmtDelete->bind_param("s", $_GET["token"]);
                $stmtDelete->execute();
                $stmtDelete->close();

                header("location: newPassword.php");
                break;
            }
        }
        $stmt->close();
    }
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
                background: url(images/rescuePassword.png) no-repeat center center;
                background-size: cover;
                transition: all var(--transition-time);

            }
            .dark-mode .right-content-img{
                background:url(images/dm-rescuePassword.png) no-repeat center center;
                background-size: cover;
                
            }
        }
    </style>
    <title>Projeto Zeus - Recuperar Senha</title>
</head>
<body>
    <main>
        <!--Logo and Dark/light mode Switch-->
            <div class="content">
                <div class="content-header">
                    <a href="../login-page/login.php">
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
                <div class="content-bottom">
                    <div class="content-bottom-text">
                        <p>Recuperação de Senha</p>
                        <h1>Código para <span class="highlight-word">Verificação de Email</span></h1>
                        <p>
                            Insira o Código enviado para o Email <span class="highlight-word"><?php echo $emailReciever?></span> abaixo para alterar sua senha
                        </p>
                    </div>
                    <div class="content-bottom-forms">
                        <form action ="<?php echo $_SERVER["PHP_SELF"]?>" method="get" autocomplete="on">
                            <div class="forms-item">
                                <label for="itoken">Token de Recuperação</label>
                                <input type="text" name="token" id="itoken" class="input-control" maxlength="6" minlength="6" placeholder="Digite o Token de Recuperação Aqui" required>
                            </div>
                            <div class="button-submit">
                                <button type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                  </svg>
                                  Enviar
                                </button>
                                <?php
                                if(isset($token404)){
                                    echo "
                                        <span class=\"error-text\">
                                            <p>Erro: Token Inserido <strong>não corresponde ao enviado ao seu Email</strong></p>
                                            <p>Tente Novamente</p>
                                            <p><a href=\"../sign-up-page/sign-up.php\">Cadastre-se Aqui</a></p>
                                        </span>
                                        ";
                                }
                                ?>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="right-content-img"></div>
    </main>
    
</body>
</html>