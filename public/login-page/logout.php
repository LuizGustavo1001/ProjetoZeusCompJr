<?php 
    if(isset($_SESSION)){
        session_destroy(); // destruir a sessão
    }
    header("location: ../login-page/login.php"); // redirecionar para a página de login

?>