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
class AdmsOrderPageMenu
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

    public function orderPageMenu(int $id): void
    {
        $this->id = (int)$id;

        $viewPageMenu = new \App\adms\Models\helper\AdmsRead();
        $viewPageMenu->fullRead(
            "SELECT lev_pag.id, lev_pag.order_level_page, lev_pag.adms_access_level_id
            FROM adms_levels_page lev_pag
            INNER JOIN adms_access_levels AS lev ON lev.id=lev_pag.adms_access_level_id
            WHERE lev_pag.id=:id AND lev.order_levels >=:order_levels
            LIMIT :limit",
            "id={$this->id}&order_levels=".$_SESSION['order_levels']."&limit=1"
        );

        // var_dump($viewUser->getResult());
        $this->resultBd = $viewPageMenu->getResult();
        if ($this->resultBd) {
            $this->result = true;
            $this->viewPrevPageMenu();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acesso não encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Recupera a ordem do item de menu
     *
     * @return void
     */
    private function viewPrevPageMenu(): void
    {
        //var_dump($this->resultBd[0]['order_levels']);
        $prevPageMenu = new \App\adms\Models\helper\AdmsRead();
        $prevPageMenu->fullRead("SELECT id, order_level_page
        FROM adms_levels_pages
        WHERE order_levels_page <:order_levels_page
        AND adms_access_level_id >:adms_access_level_id 
        ORDER BY order_level_page DESC
        LIMIT :limit", 
        "order_levels={$this->resultBd[0]['order_levels']}&adms_access_level_id={$this->resultBd[0]['adms_access_level_id']}&limit=1");
        $this->resultBdPrev = $prevPageMenu->getResult();
        if ($this->resultBdPrev){
            $this->result = true;
            $this->editMoveDown();
            $this->result = true;

        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Item de menu não encontrado!</p>";
            $this->result = false;
        }
    }

    private function editMoveDown(): void
    {
        $this->data['order_level_page'] = $this->resultBd[0]['order_level_page'];
        $this->data['modified'] = date("Y-m-d H:i:s");

        $moveDown = new \App\adms\Models\helper\AdmsUpdate();
        $moveDown->exeUpdate("adms_levels_pages", $this->data, "WHERE id=:id", "id={$this->resultBdPrev[0]['id']}");
        if($moveDown->getResult()){
            $this->editMoveUp();
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Ordem do item de menu não editada!</p>";
            $this->result = false;
        }
    }

    private function editMoveUp(): void
    {
        $this->data['order_level_page'] = $this->resultBdPrev[0]['order_level_page'];
        $this->data['modified'] = date("Y-m-d H:i:s");

        $moveUp = new \App\adms\Models\helper\AdmsUpdate();
        $moveUp->exeUpdate("adms_levels_pages", $this->data, "WHERE id=:id", "id={$this->resultBd[0]['id']}");

        if($moveUp->getResult()){
            $_SESSION['msg'] = "<p class='alert-success'> Ordem do item de menu editada com sucesso!</p>";
            $this->result = true;
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Ordem do item de menu não editada!</p>";
            $this->result = false;
        }
    
    }
}
