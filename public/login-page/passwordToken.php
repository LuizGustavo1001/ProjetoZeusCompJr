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
        $email->Username = "testemailsluiz@gmail.com"; 
        $email->Password = "aemd afyi ofth dauh"; 

        $email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $email->Port = 587; 

        $email->setFrom("testemailsluiz@gmail.com", "Equipe de Suporte"); // remetente
        $email->addAddress($emailReciever); // destinatário
        $email->isHTML(true); 
        $email->CharSet = "UTF-8"; // suportar caracteres especiais 

        $email->Subject = "Token de Recuperação de Senha"; 
        $email->Body = "<h1>Empresa Projeto Zeus</h1>"; 
        $email->Body .= "<p>Seu Token de Verificação de Email para Redefinir de Senha: <strong><h2>$token</h2></strong></p>";
        $email->Body .= "<p>Insira o Token acima na área destinada no site abaixo</p>";
        $email->Body .= "
            <p>O Token é válido por apenas <strong>1 Hora</strong>, após esse tempo é necessário solicitar outro</p>
        ";
        $email->Body .= "<p>Atenciosamente, <strong>Equipe Projeto Zeus</strong></p>";
        $email->Body .= "<p>Este é um email automático, não responda.</p>";
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

    //verificação dos tokens que já estão inválidos inseridos no Banco de Dados
    $currentTime = date("H:i");
    $currentDate = date("Y-m-d");

    $stmt = $mysqli->prepare("DELETE FROM rescuepassword WHERE dayLimit < ? OR (dayLimit = ? AND hourLimit > ?)");

    $stmt->bind_param("sss", $currentDate, $currentDate, $currentTime);
    $stmt->execute();
    $stmt->close();
    //verificação dos tokens que já estão invalidos inseridos no Banco de Dados

}
