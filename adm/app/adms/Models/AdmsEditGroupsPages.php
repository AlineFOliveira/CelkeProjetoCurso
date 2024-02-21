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
class AdmsEditGroupsPages
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

    public function viewGroupsPages(int $id): void
    {
        $this->id = (int) $id;
        //var_dump($this->id);


        $viewGroupPg = new \App\adms\Models\helper\AdmsRead();
        $viewGroupPg->fullRead(
            "SELECT id, name
            FROM adms_groups_pgs
            WHERE id=:id
            LIMIT :limit",
            "id={$this->id}&limit=1"
        );

        //var_dump($viewGroupPg->getResult());
        $this->resultBd = $viewGroupPg->getResult();

        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Grupo de página não encontrado!</p>";
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
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Grupo de página cadastrado com sucesso!</p>";
            $this->result = false;
        }
    }



    private function edit(): void
    {
        $this->data['modified'] = date("Y-m-d H:i:s");
        $upGroup = new \App\adms\Models\helper\AdmsUpdate();
        $upGroup->exeUpdate("adms_groups_pgs", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upGroup->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Grupo de página editado com sucesso!</p><br>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Grupo de página não editado com sucesso!</p>";
            $this->result = false;
        }
    }


}
