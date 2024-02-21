<?php

namespace App\adms\Models;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Alterar a ordem do nivel de acesso no banco de dados
 *
 * @author Celke
 */
class AdmsOrderGroupsPages
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;
    /** @var array|null $resultBdPrev Recebe os registros do banco de dados */
    private array|null $resultBdPrev;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;
    private array|null $data;
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

    public function orderGroupsPages(int $id): void
    {
        $this->id = (int)$id;

        $viewGroupsPages = new \App\adms\Models\helper\AdmsRead();
        $viewGroupsPages->fullRead(
            "SELECT id, order_group_pg
            FROM adms_groups_pgs
            WHERE id=:id
            LIMIT :limit",
            "id={$this->id}&limit=1"
        );

        // var_dump($viewUser->getResult());
        $this->resultBd = $viewGroupsPages->getResult();
        if ($this->resultBd) {
            $this->result = true;
            $this->viewPrevGroupsPages();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: grupo de página não encontrado!</p>";
            $this->result = false;
        }
    }

    private function viewPrevGroupsPages(): void
    {
        var_dump($this->resultBd[0]['order_group_pg']);
        $prevGroupsPages = new \App\adms\Models\helper\AdmsRead();
        $prevGroupsPages->fullRead("SELECT id, order_group_pg
        FROM adms_groups_pgs
        WHERE order_group_pg <:order_group_pg
        ORDER BY order_group_pg DESC
        LIMIT :limit", 
        "order_group_pg={$this->resultBd[0]['order_group_pg']}&limit=1");
        $this->resultBdPrev = $prevGroupsPages->getResult();
        if ($this->resultBdPrev){
            $this->result = true;
            $this->editMoveDown();
            $this->result = true;

        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Grupo de página não encontrado!</p>";
            $this->result = false;
        }
    }

    private function editMoveDown(): void
    {
        $this->data['order_group_pg'] = $this->resultBd[0]['order_group_pg'];
        $this->data['modified'] = date("Y-m-d H:i:s");

        $moveDown = new \App\adms\Models\helper\AdmsUpdate();
        $moveDown->exeUpdate("adms_groups_pgs", $this->data, "WHERE id=:id", "id={$this->resultBdPrev[0]['id']}");
        if($moveDown->getResult()){
            $this->editMoveUp();
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Ordem do grupo de página não editada!</p>";
            $this->result = false;
        }
    }

    private function editMoveUp(): void
    {
        $this->data['order_group_pg'] = $this->resultBdPrev[0]['order_group_pg'];
        $this->data['modified'] = date("Y-m-d H:i:s");

        $moveUp = new \App\adms\Models\helper\AdmsUpdate();
        $moveUp->exeUpdate("adms_groups_pgs", $this->data, "WHERE id=:id", "id={$this->resultBd[0]['id']}");

        if($moveUp->getResult()){
            $_SESSION['msg'] = "<p class='alert-success'> Ordem do grupo de página editada com sucesso!</p>";
            $this->result = true;
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Ordem do grupo de página não editada!</p>";
            $this->result = false;
        }
    
    }
}
