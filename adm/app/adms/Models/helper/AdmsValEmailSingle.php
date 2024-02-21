<?php

namespace App\adms\Models\helper;
if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}



class AdmsValEmailSingle
{
    private string $email;
    private bool|null $edit;
    private int|null $id;
    private bool $result;
    private array|null $resultBd;

    function getResult(): bool
    {
        return $this->result;
    }

    public function validateEmailSingle(string $email, bool|null $edit = null, int|null $id = null): void
    {
        $this->email = $email;
        $this->edit = $edit;
        $this->id = $id;

        $valEmailSingle = new \App\adms\Models\helper\AdmsRead();

        if (($this->edit == true) and (!empty($this->id))) { //se o usuario quer editar dados já existentes
            $valEmailSingle->fullRead("SELECT id FROM adms_users WHERE (email=:email OR user=:user) AND id <> :id LIMIT :limit", "email={$this->email}&user={$this->email}&id={$this->id}&limit=1");
        } else {
            $valEmailSingle->fullRead("SELECT id FROM adms_users WHERE email=:email LIMIT :limit", "email={$this->email}&limit=1");
        }

        $this->resultBd = $valEmailSingle->getResult();
        if (!$this->resultBd) { //se for diferente de true, ou seja, se a pesquisa no banco feito pelo admsRead não teve resultados
            $this->result = true;
        } else { //se já existe algum usuário com aquele email
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Este email já está cadastrado!</p>";
            $this->result = false;
        }
    }
}
