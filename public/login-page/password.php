<?php 
    include "../../dbConnection.php";

    if(isset($_POST["email"])){
        $email = $_POST["email"];

        $stmt = $mysqli->prepare("
            SELECT emailEmpl FROM employee
            WHERE emailEmpl = ?
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $amount = $stmt->get_result()->num_rows;

        switch($amount){
            case 0:{ // nenhum usuário cadastrado com o email inserido
                $email404 = true;
                break;
            }
            case 1:{ // usuário com o email digitado foi encontrado
                session_start();
                $_SESSION["emailReciever"] = $email;
                header("location: passwordToken.php");
                exit();
            }
        }
        $stmt->close();
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
                background: url(images/forgotPassword.png) no-repeat center center;
                background-size: cover;
                transition: all var(--transition-time);

            }
            .dark-mode .right-content-img{
                background:url(images/dm-forgotPassword.png) no-repeat center center;
                background-size: cover;
                
            }
        }

    </style>

    <title>Projeto Zeus - Recuperar Senha</title>

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
    <!--Logo and Dark/light mode Switch-->


    <!--Forms-->
            <div class="content-bottom">
                <div class="content-bottom-text">
                    <p>Recuperação de Senha</p>
                    <h1>Esqueceu sua <span class="highlight-word">Senha</span>?</h1>
                    <p>
                        Insira o endereço de <span class="highlight-word">Email vinculado</span>
                        a esta conta para enviarmos um <span class="highlight-word">código de Recuperação</span>
                        para você alterá-la.
                    </p>
                </div>

            </div>
            <div class="content-bottom-forms">
                <form action ="<?php echo $_SERVER["PHP_SELF"]?>" method="post" autocomplete="on">
                    <div class="forms-item">
                        <label for="iemail">Endereço de Email</label>
                        <input type="email" name="email" id="iemail" class="input-control" placeholder="Digite aqui o email de recuperação" required>
                    </div>

                    <div class="button-submit">
                        <button type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                          </svg>
                          Enviar Código
                        </button>
                        <?php 
                        if(isset($email404)){
                            echo "
                                <span class=\"error-text\" >
                                    <p style=\"margin-top: 1em;\">Erro: Email Inserido <strong>não está cadastrado</strong></p>
                                    <p>Tente Novamente com outro endereço de email ou</p>
                                    <p style=\"margin-top: 1em;\"><a href=\"../sign-up-page/sign-up.php\">Cadastre-se Aqui</a></p>
                                </span>
                                ";
                        }
                        ?>
                    </div>

                </form>
            </div>
        </div>
        <!--Forms-->

        <div class="right-content-img"></div>

    </main>

</body>

</html>