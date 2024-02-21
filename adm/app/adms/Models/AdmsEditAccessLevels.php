<?php

namespace App\adms\Models;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Editar o usuário no banco de dados
 *
 * @author Celke
 */
class AdmsEditAccessLevels
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

    public function viewAccessLvls(int $id): void
    {
        $this->id = (int) $id;
        //var_dump($this->id);


        $viewSit = new \App\adms\Models\helper\AdmsRead();
        $viewSit->fullRead(
            "SELECT id, name
            FROM adms_access_levels
            WHERE id=:id AND order_levels >:order_levels
            LIMIT :limit",
            "id={$this->id}&order_levels=".$_SESSION['order_levels']."&limit=1"
        );

        //var_dump($viewSit->getResult());
        $this->resultBd = $viewSit->getResult();

        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acesso não encontrado!</p>";
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
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acesso cadastrado com sucesso!</p>";
            $this->result = false;
        }
    }



    private function edit(): void
    {
        $this->data['modified'] = date("Y-m-d H:i:s");
        $upLevel = new \App\adms\Models\helper\AdmsUpdate();
        $upLevel->exeUpdate("adms_access_levels", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upLevel->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Nível de acesso editado com sucesso!</p><br>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acesso não editado com sucesso!</p>";
            $this->result = false;
        }
    }


}
