<?php

namespace App\adms\Models;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Editar a senha do usuário no banco de dados
 *
 * @author Celke
 */
class AdmsEditUsersPassword
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    private array|null $data;
    private array|null $dataExitVal;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /**
     * @return bool Retorna os detalhes do registro
     */
    function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    public function viewUser(int $id): void
    {
        $this->id = $id;


        $viewUser = new \App\adms\Models\helper\AdmsRead();
        $viewUser->fullRead(
            "SELECT usr.id 
            FROM adms_users AS usr
            INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
            WHERE usr.id=:id AND lev.order_levels >:order_levels
            LIMIT :limit",
            "id={$this->id}&order_levels=".$_SESSION['order_levels']."&limit=1"
        );

        //pçvar_dump($viewUser->getResult());
        $this->resultBd = $viewUser->getResult();

        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado b!</p>";
            $this->result = false;
        }
    }

    public function update(array $data = null): void
    {
        $this->data = $data;

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        if ($valEmptyField->getResult()) {
            $this->valInput();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não cadastrado com sucesso!</p>";
            $this->result = false;
        }
    }

    private function valInput(): void
    {
        $valPassword = new \App\adms\Models\helper\AdmsValPassword(); // Tá chamando o helper de validar email
        $valPassword->validatePassword($this->data['password']);



        if ($valPassword->getResult()) { //se é true
            $this->edit();
        } else {
            $this->result = false;
        }
    }

    private function edit(): void
    {
        $this->data['password'] = password_hash($this->data['password'], PASSWORD_DEFAULT);
        $this->data['modified'] = date("Y-m-d H:i:s");

        $upUser = new \App\adms\Models\helper\AdmsUpdate();
        $upUser->exeUpdate("adms_users", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upUser->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>A senha do usuário foi editada com sucesso!</p><br>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: A senha do usuário não foi editada com sucesso!</p>";
            $this->result = false;
        }
    }
}
