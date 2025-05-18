<?php 
include "../../dbConnection.php";

require "../mailLibrary/src/PHPMailer.php";
require "../mailLibrary/src/SMTP.php";
require "../mailLibrary/src/Exception.php";
// permitir o acesso as classes acima no arquivo php desejado

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if(! isset($_SESSION)){
    session_start(); // iniciar a sessão

}

if(! isset($_SESSION["emailReciever"])){ // entrando na página sem solicitar um token
    header("location: password.php");
}else{ // entrando na página com um token solicitado -> enviar email
    $token = bin2hex(random_bytes(3));

    $emailReciever = $_SESSION["emailReciever"];

    $email = new PHPMailer;

    try{
        $email->isSMTP();
        $email->Host = "smtp.gmail.com";
        $email->SMTPAuth = true;
        $email->Username = "gustavw1001@gmail.com";
        $email->Password = "wnvz eldg fbwg udzp";

        $email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $email->Port = 587;

        $email->setFrom("gustavw1001@gmail.com"); // remetente
        $email->addAddress($emailReciever); // destinatiario

        $email->isHTML(true);
        $email->Subject = "Token de Recuperacao de Senha";
        $email->Body = "<h1>Empresa Projeto Zeus</h1>";
        $email->Body .= "<p>Seu Token de Verificação de Email: <strong><h2>$token</h2></strong></p>";
        $email->Body .= "<p>Insira o Token acima na área destinada no site abaixo</p>";
        $email->AltBody = "Empresa Projeto Zeus\n Seu Token de Verificação de Email: $token";

        if($email->send()){ // email enviado com sucesso
            global $mysqli;

            date_default_timezone_set('America/Sao_Paulo'); // definir fuso horário local
            
            $stmt = $mysqli->prepare("
                INSERT INTO rescuepassword (rescueToken, dayLimit, hourLimit, emailReciever) 
                VALUES (?,?,?,?)
            ");

            $limitTime = date("H:i", strtotime("+1 hours"));
            $limitDay = date("Y-m-d");

            $stmt->bind_param("ssss", $token, $limitDay, $limitTime, $emailReciever);

            $stmt->execute();
            $stmt->close();
            $_SESSION["email"] = $emailReciever;
            $_SESSION["token"] = $token;

            header("location: rescuePassword.php");

        }else {
            echo "Email não enviado";
        }
    }catch(Exception $e){
        echo "Erro ao enviar o email: {$email->ErrorInfo}";
    }

    //verificação dos tokens que já estão invalidos inseridos no Banco de Dados
    $currentTime = date("H:i");
    $currentDate = date("Y-m-d");
    $stmt = $mysqli->prepare("
        SELECT rescueToken FROM rescuepassword 
        WHERE dayLimit < ? AND hourLimit < ?
    ");

    $stmt->bind_param("ss", $currentDate, $currentTime);

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $tokenToDelete = $row['rescueToken'];
        $stmtDelete = $mysqli->prepare("
            DELETE FROM rescuepassword 
            WHERE rescueToken = ?
        ");
        $stmtDelete->bind_param("s", $tokenToDelete);
        $stmtDelete->execute();

        $stmtDelete->close();
    }

    $stmt->close();

    //verificação dos tokens que já estão invalidos inseridos no Banco de Dados

}
