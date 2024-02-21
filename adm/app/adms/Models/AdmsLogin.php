<?php

namespace App\adms\Models;
if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}



class AdmsLogin
{
    private array|null $data;
    private $resultBd;
    private $result;

    function getResult()
    {
        return $this->result;
    }

    public function login(array $data = null)
    {
        $this->data = $data;
        //var_dump($this->data);

        $viewUser = new \App\adms\Models\helper\AdmsRead();
        //Retorna todas as colunas
        //$viewUser->exeRead("adms_users", "WHERE user =:user LIMIT :limit", "user={$this->data['user']}&limit=1");//Tabela, sql, paramentros?

        //retorna somente as colunas indicadas

        $viewUser->fullRead("SELECT usr.id, usr.name, usr.nickname, usr.email, usr.password, usr.image, usr.adms_sits_user_id, usr.adms_access_level_id,
        lev.order_levels
        FROM adms_users AS usr
        INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
        WHERE usr.user =:user 
        OR usr.email = :email LIMIT :limit", "user={$this->data['user']}&email={$this->data['user']}&limit=1");

        $this->resultBd = $viewUser->getResult();
        if ($this->resultBd) {
            $this->valEmailPerm();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário ou senha incorretos!</p>";
            $this->result = false;
        }
    }

    private function valEmailPerm(): void
    {
        if ($this->resultBd[0]['adms_sits_user_id'] == 1) {
            $this->valPassword();
        } elseif ($this->resultBd[0]['adms_sits_user_id'] == 3) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário confirmar o email, solicite novo link <a href='" . URL . "new-conf-email/index'>Clique aqui</a>!</p>";
            $this->result = false;
        } elseif ($this->resultBd[0]['adms_sits_user_id'] == 5) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Email descadastrado, entre em contato com a empresa!</p>";
            $this->result = false;
        } elseif ($this->resultBd[0]['adms_sits_user_id'] == 2) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Email inativo, entre em contato com a empresa!</p>";
            $this->result = false;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Email inativo, entre em contato com a empresa!</p>";
            $this->result = false;
        }
    }

    private function valPassword()
    {
        if (password_verify($this->data['password'], $this->resultBd[0]['password'])) { //compara se a senha é igual a criptografada
            $this->result = true;
            $_SESSION['user_id'] = $this->resultBd[0]['id'];
            $_SESSION['user_name'] = $this->resultBd[0]['name'];
            $_SESSION['user_nickname'] = $this->resultBd[0]['nickname'];
            $_SESSION['user_email'] = $this->resultBd[0]['email'];
            $_SESSION['user_image'] = $this->resultBd[0]['image'];
            $_SESSION['adms_access_level_id'] = $this->resultBd[0]['adms_access_level_id'];
            $_SESSION['order_levels'] = $this->resultBd[0]['order_levels'];
            /* echo $_SESSION['msg']; */
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário ou senha incorretos!</p>";
            /* echo $_SESSION['msg']; */
            $this->result = false;
        }
    }
}
