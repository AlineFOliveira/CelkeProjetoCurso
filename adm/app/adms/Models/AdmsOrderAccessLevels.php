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
class AdmsOrderAccessLevels
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

    public function orderAccessLevels(int $id): void
    {
        $this->id = (int)$id;

        $viewAccessLevels = new \App\adms\Models\helper\AdmsRead();
        $viewAccessLevels->fullRead(
            "SELECT id, order_levels
            FROM adms_access_levels
            WHERE id=:id AND order_levels >:order_levels
            LIMIT :limit",
            "id={$this->id}&order_levels=".$_SESSION['order_levels']."&limit=1"
        );

        // var_dump($viewUser->getResult());
        $this->resultBd = $viewAccessLevels->getResult();
        if ($this->resultBd) {
            $this->result = true;
            $this->viewPrevAccessLevel();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acesso não encontrado!</p>";
            $this->result = false;
        }
    }

    private function viewPrevAccessLevel(): void
    {
        var_dump($this->resultBd[0]['order_levels']);
        $prevAccessLevels = new \App\adms\Models\helper\AdmsRead();
        $prevAccessLevels->fullRead("SELECT id, order_levels
        FROM adms_access_levels
        WHERE order_levels <:order_levels
        AND order_levels >:order_levels_user
        ORDER BY order_levels DESC
        LIMIT :limit", 
        "order_levels={$this->resultBd[0]['order_levels']}&order_levels_user=" . $_SESSION['order_levels'] . "&limit=1");
        $this->resultBdPrev = $prevAccessLevels->getResult();
        if ($this->resultBdPrev){
            $this->result = true;
            $this->editMoveDown();
            $this->result = true;

        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acesso não encontrado!</p>";
            $this->result = false;
        }
    }

    private function editMoveDown(): void
    {
        $this->data['order_levels'] = $this->resultBd[0]['order_levels'];
        $this->data['modified'] = date("Y-m-d H:i:s");

        $moveDown = new \App\adms\Models\helper\AdmsUpdate();
        $moveDown->exeUpdate("adms_access_levels", $this->data, "WHERE id=:id", "id={$this->resultBdPrev[0]['id']}");
        if($moveDown->getResult()){
            $this->editMoveUp();
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Ordem do nível de acesso não editada!</p>";
            $this->result = false;
        }
    }

    private function editMoveUp(): void
    {
        $this->data['order_levels'] = $this->resultBdPrev[0]['order_levels'];
        $this->data['modified'] = date("Y-m-d H:i:s");

        $moveUp = new \App\adms\Models\helper\AdmsUpdate();
        $moveUp->exeUpdate("adms_access_levels", $this->data, "WHERE id=:id", "id={$this->resultBd[0]['id']}");

        if($moveUp->getResult()){
            $_SESSION['msg'] = "<p class='alert-success'> Ordem do nível de acesso editada com sucesso!</p>";
            $this->result = true;
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Ordem do nível de acesso não editada!</p>";
            $this->result = false;
        }
    
    }
}
