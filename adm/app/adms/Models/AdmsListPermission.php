<?php

namespace App\adms\Models;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}



class AdmsListPermission
{

    private $resultBd;
    private $resultBdLevel;
    private $result = false;
    private int $page;
    private int $level;
    private int $limitResult = 40;
    private string|null $resultPg;
    /** @var string|null $searchLvl Recebe o nome da situação */
    private string|null $searchLvl;

    /** @var string|null $searchLvlValue Recebe o nome da situação*/
    private string|null $searchLvlValue;

    function getResult(): bool
    {
        return $this->result;
    }

    function getResultBdLevel(): array|null
    {
        return $this->resultBdLevel;
    }

    function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    function getResultPg(): string|null
    {
        return $this->resultPg;
    }

    //vai receber o numero da página enviada pela controller
    public function listPermissions(int $page = null, int $level = null): void
    {
        $listPermission = new \App\adms\Models\helper\AdmsRead();

        $this->page = (int) $page ? $page : 1; //força a ser 1 e inteiro se for vazia
        $this->level = (int) $level;

        if ($this->viewAccessLevels()) {
            $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-permission/index', "?level={$this->level}"); //envia a url para a constructor da help
            $pagination->condition($this->page, $this->limitResult); //envia o numero da página e o limite a ser apresentado
            $pagination->pagination(
                "SELECT COUNT(lev_pag.id) AS num_result 
                FROM adms_levels_pages AS lev_pag
                LEFT JOIN adms_pages AS pag ON pag.id=adms_page_id
                WHERE lev_pag.adms_access_level_id=:adms_access_level_id
                AND (((SELECT permission FROM adms_levels_pages WHERE adms_page_id=lev_pag.adms_page_id AND adms_access_level_id={$_SESSION['adms_access_level_id']}) = 1) OR (publish = 1))",
                "adms_access_level_id={$this->level}"
            );
            $this->resultPg = $pagination->getResult();

            $listPermission->fullRead("SELECT lev_pag.id, lev_pag.permission, lev_pag.order_level_page, lev_pag.print_menu, lev_pag.adms_access_level_id, lev_pag.adms_page_id, pag.name_page
            FROM adms_levels_pages AS lev_pag
            LEFT JOIN adms_pages AS pag ON pag.id=lev_pag.adms_page_id
            INNER JOIN adms_access_levels AS lev ON lev.id=lev_pag.adms_access_level_id
            WHERE lev_pag.adms_access_level_id=:adms_access_level_id
            AND lev.order_levels >=:order_levels
            AND (((SELECT permission FROM adms_levels_pages WHERE adms_page_id = lev_pag.adms_page_id AND adms_access_level_id = {$_SESSION['adms_access_level_id']}) = 1) OR (publish = 1))
            ORDER BY lev_pag.order_level_page ASC LIMIT :limit OFFSET :offset", "adms_access_level_id={$this->level}&order_levels=".$_SESSION['order_levels']."&limit={$this->limitResult}&offset={$pagination->getOffset()}");



            $this->resultBd = $listPermission->getResult();
            if ($this->resultBd) {
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma permissão encontrada para o nível de acesso !</p>";
                $this->result = false;
            }
        } else {
            //$_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma permissão encontrada para o nível de acesso !</p>";
            $this->result = false;
        }
    }

    private function viewAccessLevels(): bool
    {

        $viewAccessLevels = new \App\adms\Models\helper\AdmsRead();
        $viewAccessLevels->fullRead(
            "SELECT name
        FROM adms_access_levels 
        WHERE id=:id AND order_levels >=:order_levels
        LIMIT :limit",
            "id={$this->level}&order_levels=" . $_SESSION['order_levels'] . "&limit=1"
        );

        // var_dump($viewUser->getResult());
        $this->resultBdLevel = $viewAccessLevels->getResult();

        if ($this->resultBdLevel) {
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acesso não encontrado!</p>";
            return false;
        }
    }
}
