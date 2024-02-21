<?php
 
    namespace App\adms\Models\helper;

    if(!defined('C8L6K7E')){
        header("Location: /");
        /* die('Erro: página não encontrada'); */
    }

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;



 class AdmsSendEmail
 {
    /** @var array Recebe as informações do conteúdo do email*/
    private array $data;

    /** @var array Recebe as credenciais do email*/
    private array $dataInfoEmail;

    private array|null $resultBd;
    /** @var bool Recebe true ou false*/
    private bool $result;

    /** @var bool Recebe o email do remetente*/
    private bool $fromEmail;

    /** @var int Recebe o id do email que será utilizado para enviar email*/
    private int $optionConfEmail;

    function getResult():bool
    {
        return $this->result;
    }

    function getFromEmail():string
    {
        return $this->fromEmail;
    }

    public function sendEmail(array $data, int $optionConfEmail): void
    {

        $this->optionConfEmail = $optionConfEmail;
        $this->data = $data;

        /* $this->data['toEmail'] = "aliferroli18@gmail.com";
        $this->data['toName'] = "Aline";
        $this->data['subject'] = "Confirma e-mail";
        $this->data['contentHTML'] = "Olá <b>Cesar</b><br><p>Cadastro realizado com sucesso!</p>";
        $this->data['contentText'] = "Olá Cesar \n\nCadastro realizado com sucesso"; */


        $this->infoPhpMailer();

    }

    private function infoPhpMailer(): void
    {
        $confEmail = new \App\adms\Models\helper\AdmsRead();
        $confEmail->fullRead("SELECT name, email, host, username, password, smtpsecure, port FROM adms_confs_emails WHERE id =:id LIMIT :limit", "id={$this->optionConfEmail}&limit=1");
        $this->resultBd = $confEmail->getResult();


        if($this->resultBd){
            $this->dataInfoEmail['host'] = $this->resultBd[0]['host'];
            $this->dataInfoEmail['fromEmail'] = $this->resultBd[0]['email'];
            $this->fromEmail = $this->dataInfoEmail['fromEmail'];
            $this->dataInfoEmail['fromName'] = $this->resultBd[0]['name'];
            $this->dataInfoEmail['username'] = $this->resultBd[0]['username'];
            $this->dataInfoEmail['password'] = $this->resultBd[0]['password'];
            $this->dataInfoEmail['smtpsecure'] = $this->resultBd[0]['smtpsecure'];
            $this->dataInfoEmail['port'] = $this->resultBd[0]['port'];

            $this->sendEmailPhpMailer();
        }else{
            $this->result = false;
            echo "Não deu";
        }
    }

    private function sendEmailPhpMailer(): void
    {
        $mail = new PHPMailer(true);//cria um objeto email

        try{

            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $this->dataInfoEmail['host'];                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;    
            $mail->AuthType = 'LOGIN';                               //Enable SMTP authentication
            $mail->Username   = $this->dataInfoEmail['username'];                     //SMTP username
            $mail->Password   = $this->dataInfoEmail['password'];                               //SMTP password
            $mail->SMTPSecure = $this->dataInfoEmail['smtpsecure'];            //Enable implicit TLS encryption
            $mail->Port       = $this->dataInfoEmail['port']; 

            $mail->setFrom($this->dataInfoEmail['fromEmail'], $this->dataInfoEmail['fromName']);
            $mail->addAddress($this->data['toEmail'] , $this->data['toName']);     //Add a recipient

            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $this->data['subject'];
            $mail->Body    = $this->data['contentHTML'];
            $mail->AltBody = $this->data['contentText'];

            $mail->send();

            $this->result = true;
        }catch (Exception $e){

            $this->result = false;
        }




    }

    
 }
 
 
 ?>