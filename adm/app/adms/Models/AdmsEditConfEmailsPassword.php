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
class AdmsEditConfEmailsPassword
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

    public function viewConfEmails(int $id): void
    {
        $this->id = $id;


        $viewConfEmails = new \App\adms\Models\helper\AdmsRead();
        $viewConfEmails->fullRead(
            "SELECT id 
            FROM adms_confs_emails 
            WHERE id=:id
            LIMIT :limit",
            "id={$this->id}&limit=1"
        );

        //pçvar_dump($viewConfEmails->getResult());
        $this->resultBd = $viewConfEmails->getResult();

        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Configuração de e-mail não encontrada!</p>";
            $this->result = false;
        }
    }

    public function update(array $data = null): void
    {
        $this->data = $data;

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        if ($valEmptyField->getResult()) {
            $this->edit();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Configuração de e-mail não com sucesso!</p>";
            $this->result = false;
        }
    }


    private function edit(): void
    {
        $this->data['modified'] = date("Y-m-d H:i:s");

        $upUser = new \App\adms\Models\helper\AdmsUpdate();
        $upUser->exeUpdate("adms_confs_emails", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upUser->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>A senha foi editada com sucesso!</p><br>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: A senha não foi editada com sucesso!</p>";
            $this->result = false;
        }
    }
}
