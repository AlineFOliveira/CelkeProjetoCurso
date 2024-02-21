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
class AdmsOrderTypesPages
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

    public function orderTypesPages(int $id): void
    {
        $this->id = (int)$id;

        $viewTypesPages = new \App\adms\Models\helper\AdmsRead();
        $viewTypesPages->fullRead(
            "SELECT id, order_type_pg
            FROM adms_types_pgs
            WHERE id=:id
            LIMIT :limit",
            "id={$this->id}&limit=1"
        );

        // var_dump($viewUser->getResult());
        $this->resultBd = $viewTypesPages->getResult();
        if ($this->resultBd) {
            $this->result = true;
            $this->viewPrevTypesPages();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: tipo de página não encontrado!</p>";
            $this->result = false;
        }
    }

    private function viewPrevTypesPages(): void
    {
        var_dump($this->resultBd[0]['order_type_pg']);
        $prevTypesPages = new \App\adms\Models\helper\AdmsRead();
        $prevTypesPages->fullRead("SELECT id, order_type_pg
        FROM adms_types_pgs
        WHERE order_type_pg <:order_type_pg
        ORDER BY order_type_pg DESC
        LIMIT :limit", 
        "order_type_pg={$this->resultBd[0]['order_type_pg']}&limit=1");
        $this->resultBdPrev = $prevTypesPages->getResult();
        if ($this->resultBdPrev){
            $this->result = true;
            $this->editMoveDown();
            $this->result = true;

        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo de página não encontrado!</p>";
            $this->result = false;
        }
    }

    private function editMoveDown(): void
    {
        $this->data['order_type_pg'] = $this->resultBd[0]['order_type_pg'];
        $this->data['modified'] = date("Y-m-d H:i:s");

        $moveDown = new \App\adms\Models\helper\AdmsUpdate();
        $moveDown->exeUpdate("adms_types_pgs", $this->data, "WHERE id=:id", "id={$this->resultBdPrev[0]['id']}");
        if($moveDown->getResult()){
            $this->editMoveUp();
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Ordem do tipo de página não editada!</p>";
            $this->result = false;
        }
    }

    private function editMoveUp(): void
    {
        $this->data['order_type_pg'] = $this->resultBdPrev[0]['order_type_pg'];
        $this->data['modified'] = date("Y-m-d H:i:s");

        $moveUp = new \App\adms\Models\helper\AdmsUpdate();
        $moveUp->exeUpdate("adms_types_pgs", $this->data, "WHERE id=:id", "id={$this->resultBd[0]['id']}");

        if($moveUp->getResult()){
            $_SESSION['msg'] = "<p class='alert-success'> Ordem do tipo de página editada com sucesso!</p>";
            $this->result = true;
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Ordem do tipo de página não editada!</p>";
            $this->result = false;
        }
    
    }
}
