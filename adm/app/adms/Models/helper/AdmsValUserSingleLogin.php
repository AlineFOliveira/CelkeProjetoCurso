<?php

namespace App\adms\Models\helper;
if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}


class AdmsValUserSingleLogin
{
    private string $user;
    private bool|null $edit;
    private int|null $id;
    private bool $result;
    private array|null $resultBd;

    function getResult(): bool
    {
        return $this->result;
    }

    public function validateUserSingleLogin(string $user, bool|null $edit = null, int|null $id = null): void
    {
        $this->user = $user;
        $this->edit = $edit;
        $this->id = $id;

        $valUserSingle = new \App\adms\Models\helper\AdmsRead();

        if (($this->edit == true) and (!empty($this->id))) { //se o usuario quer editar dados já existentes
            $valUserSingle->fullRead("SELECT id FROM adms_users WHERE user = :user id <> :id LIMIT :limit", "user={$this->user}&id={$this->id}&limit=1");
        } else {
            $valUserSingle->fullRead("SELECT id FROM adms_users WHERE user = :user LIMIT :limit", "user={$this->user}&limit=1");
        }

        $this->resultBd = $valUserSingle->getResult();
        if (!$this->resultBd) { //se for diferente de true, ou seja, se a pesquisa no banco feito pelo admsRead não teve resultados
            $this->result = true;
        } else { //se já existe algum usuário com aquele email
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Este email já está cadastrado!</p>";
            $this->result = false;
        }
    }
}
