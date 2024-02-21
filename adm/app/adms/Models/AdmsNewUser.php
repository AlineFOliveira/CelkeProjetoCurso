<?php

namespace App\adms\Models;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class AdmsNewUser
{
    private array|null $data;
    private $resultBd;
    private $result;
    /** @var bool Recebe o email do remetente*/
    private bool $fromEmail;

    /** @var string Recebe o primeiro nome do usuário*/
    private string $firstName;

    private string $url;

    /** @var array Recebe os dados do conteúdo do email*/
    private array $emailData;

    function getResult()
    {
        return $this->result;
    }

    public function create(array $data = null)
    {
        $this->data = $data;
        //var_dump($this->data);

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        if ($valEmptyField->getResult()) { 
            $this->valInput();
           
        }else{
            $_SESSION['msg'] = "<p>Erro: Usuário não cadastrado com sucesso!</p>";
            $this->result = false;
        }
    }

    private function valInput(): void
    {
        $valEmail = new \App\adms\Models\helper\AdmsValEmail();// Tá chamando o helper de validar email
        $valEmail->validateEmail($this->data['email']);

        $valEmailSingle = new \App\adms\Models\helper\AdmsValEmailSingle();
        $valEmailSingle->validateEmailSingle($this->data['email']);

        $valPassword = new \App\adms\Models\helper\AdmsValPassword();
        $valPassword->validatePassword($this->data['password']);

        $valUserSingleLogin = new \App\adms\Models\helper\AdmsValUserSingleLogin();
        $valUserSingleLogin->validateUserSingleLogin($this->data['email']);

        if(($valEmail->getResult()) and ($valEmailSingle->getResult()) and ($valPassword->getResult()) and ($valUserSingleLogin->getResult())){//se é true
            $this->add();
        }else{
            $this->result = false;
        }
    }

    private function add(): void
    {
        $this->data['password'] = password_hash($this->data['password'], PASSWORD_DEFAULT);
            $this->data['user'] = $this->data['email'];
            $this->data['conf_email'] = password_hash($this->data['password'] . date("Y-m-d H:i:s"), PASSWORD_DEFAULT); 
            $this->data['created'] = date("Y-m-d H:i:s");
            //var_dump($this->data);

            $createUser = new \App\adms\Models\helper\AdmsCreate();
            $createUser->exeCreate("adms_users", $this->data);//nome da tabela e os dados

            if($createUser->getResult()){
                //$_SESSION['msg'] = "<p style='color:green;'>Usuário cadastrado com sucesso!";
                //$this->result = true;
                $this->sendEmail();
            }else{
                $_SESSION['msg'] = "<p style='color:#f00;'>Erro: Usuário não cadastrado com sucesso!</p>";
                $this->result = false;
            }
    }

    private function sendEmail():void
    {
        $this->contentEmailHtml();
        $this->contentEmailText();
        $sendEmail = new \App\adms\Models\helper\AdmsSendEmail();
        $sendEmail->sendEmail($this->emailData, 2);

        if($sendEmail->getResult()){
            $this->fromEmail = $sendEmail->getFromEmail();
            $_SESSION['msg'] = "<p style='color:green;'>Usuário cadastrado com sucesso. Acesse a sua caixa de email para confirmar o email!</p>";
            $this->result = true;
        }else{
            $_SESSION['msg'] = "<p style='color:#f00;'>Usuário cadastrado com sucesso. Houve erro ao enviar o email de confirmação, entre em contato com {$this->fromEmail} para mais informações!!</p>";
            $this->result = true;
        }

    }

    private function contentEmailHtml(): void
    {
        $name = explode(" ", $this->data['name']);
        $this->firstName = $name[0];

        $this->emailData['toEmail'] = $this->data['email'];
        $this->emailData['toName'] = $this->data['name'];
        $this->emailData['subject'] = "Confirmar sua conta";

        $this->url = URL . "conf-email/index?key=" . $this->data['conf_email'];

        $this->emailData['contentHTML'] = "Prezado(a) {$this->firstName}<br><br>";
        $this->emailData['contentHTML'] .= "Agradecemos a sua solicitação de cadastro em nosso site. <br><br>";
        $this->emailData['contentHTML'] .= "Para que possamos liberar o seu cadastro em nosso sistema, solicitamos a confirmação do e-mail clicando no link abaixo: <br><br>";
        $this->emailData['contentHTML'] .= "<a href='{$this->url}'>{$this->url}</a><br>";
    }

    private function contentEmailText(): void
    {
        $url = URL . "conf-email/index?key=" . $this->data['conf_email'];
        $this->emailData['contentText'] = "Prezado(a) {$this->firstName}\n\n";
        $this->emailData['contentText'] .= "Agradecemos a sua solicitação de cadastro em nosso site. \n\n";
        $this->emailData['contentText'] .= "Para que possamos liberar o seu cadastro em nosso sistema, solicitamos a confirmação do e-mail clicando no link abaixo: \n\n";
        $this->emailData['contentText'] .= $this->url. "\n\n";
    }

    
    
}
