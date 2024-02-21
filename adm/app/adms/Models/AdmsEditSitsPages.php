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
class AdmsEditSitsPages
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    private array|null $data;
    private array|null $dataExitVal;
    private array $listRegistryAdd;

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

    public function viewSitPg(int $id): void
    {
        $this->id = (int) $id;
        //var_dump($this->id);


        $viewSit = new \App\adms\Models\helper\AdmsRead();
        $viewSit->fullRead(
            "SELECT id, name, adms_color_id
            FROM adms_sits_pgs
            WHERE id=:id
            LIMIT :limit",
            "id={$this->id}&limit=1"
        );


        //var_dump($viewSit->getResult());
        $this->resultBd = $viewSit->getResult();

        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Situação de página não encontrada!</p>";
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
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Situação de página não cadastrada com sucesso!</p>";
            $this->result = false;
        }
    }



    private function edit(): void
    {
        $this->data['modified'] = date("Y-m-d H:i:s");
        $upUser = new \App\adms\Models\helper\AdmsUpdate();
        $upUser->exeUpdate("adms_sits_pgs", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upUser->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Situação editada com sucesso!</p><br>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Situação não editada com sucesso!</p>";
            $this->result = false;
        }
    }

    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $list->fullRead("SELECT id id_col, name name_col FROM adms_colors ORDER BY name ASC");
        $registry['col'] = $list->getResult();

        $this->listRegistryAdd = ['col' => $registry['col']];

        return $this->listRegistryAdd;
    }
}
