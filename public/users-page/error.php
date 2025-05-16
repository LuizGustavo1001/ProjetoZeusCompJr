<?php 
    include "../../dbConnection.php"; // incluir o arquivo de conexão com o banco de dados 
    if(! isset($_SESSION)){
        session_start(); // iniciar a sessão 
    }

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../icon/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="../styles/other-pages.css">
    <link rel="stylesheet" href="../styles/general.css">
    <link rel="stylesheet" href="../styles/dark-mode.css">

    <script src="../dark-mode.js" defer></script>
    

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">

    <title>Projeto Zeus - Login</title>


    <style>
        .right-content-img{
            background:url(images/error-bg.jpg) bottom left;
        }

        svg{
            width: 40px;
        }

    </style>

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
                    <div class="content-bottom-text" style="text-align: center; ">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                        </svg>
                        <p>
                            Infelizmente Você Foi Bloqueado
                        </p>
                        <h1 style="margin: 0 auto;">Faça seu <span class="highlight-word">login</span> primeiro</h1>
                        <h1> para acessar a página do Usuário</h1>
                        <a href="../login-page/login.php" class="button-submit">
                            <button  style="margin-top: 2em;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                    <path fill-rule="evenodd" d="M17 4.25A2.25 2.25 0 0 0 14.75 2h-5.5A2.25 2.25 0 0 0 7 4.25v2a.75.75 0 0 0 1.5 0v-2a.75.75 0 0 1 .75-.75h5.5a.75.75 0 0 1 .75.75v11.5a.75.75 0 0 1-.75.75h-5.5a.75.75 0 0 1-.75-.75v-2a.75.75 0 0 0-1.5 0v2A2.25 2.25 0 0 0 9.25 18h5.5A2.25 2.25 0 0 0 17 15.75V4.25Z" clip-rule="evenodd" />
                                    <path fill-rule="evenodd" d="M1 10a.75.75 0 0 1 .75-.75h9.546l-1.048-.943a.75.75 0 1 1 1.004-1.114l2.5 2.25a.75.75 0 0 1 0 1.114l-2.5 2.25a.75.75 0 1 1-1.004-1.114l1.048-.943H1.75A.75.75 0 0 1 1 10Z" clip-rule="evenodd" />
                                </svg>
                                Login
                            </button>
                        </a>
                    </div>
                </div>

            </div>
            <div class="right-content-img"></div>
    </main>
    
</body>
</html>