<?php

namespace App\adms\Models;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}



class AdmsListSitsPages
{

    private $resultBd;
    private $result = false;
    private int $page;
    private int $limitResult = 40;
    private string|null $resultPg;
    /** @var string|null $searchEmail Recebe o nome da situação */
    private string|null $searchSitPg;

    /** @var string|null $searchNameValue Recebe o nome da situação*/
    private string|null $searchSitPgValue;

    function getResult(): bool
    {
        return $this->result;
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
    public function listSitsPages(int $page = null): void
    {
        $listSitsPages = new \App\adms\Models\helper\AdmsRead();

        $this->page = (int) $page ? $page : 1; //força a ser 1 e inteiro se for vazia
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-sits-pages/index'); //envia a url para a constructor da help
        $pagination->condition($this->page, $this->limitResult); //envia o numero da página e o limite a ser apresentado
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_sits_pgs");
        $this->resultPg = $pagination->getResult();

        $listSitsPages->fullRead("SELECT sitpg.id, sitpg.name, col.color
        FROM adms_sits_pgs AS sitpg
        INNER JOIN adms_colors AS col ON sitpg.adms_color_id=col.id
        ORDER BY sitpg.id ASC LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");



        $this->resultBd = $listSitsPages->getResult();

        if ($this->resultBd) {
            $this->result = true;
            //var_dump($this->resultBd);
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não encontrado!</p>";
            $this->result = false;
        }
    }

    public function listSearchSits(int $page = null, string|null $search_sit_pag): void
    {
        $this->page = (int) $page ? $page : 1;
        $this->searchSitPg = trim($search_sit_pag);

        $this->searchSitPgValue = "%" . $this->searchSitPg . "%";

        if (!empty($this->searchSitPg)) {
            $this->searchSitsUser();
        }
    }

    public function searchSitsUser(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-sits-pages/index', "?search_sit_pag={$this->searchSitPg}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                            FROM adms_sits_pgs 
                            WHERE name LIKE :search_sit_pg", "search_sit_pg={$this->searchSitPgValue}");
        $this->resultPg = $pagination->getResult();

        $listSitsUsers = new \App\adms\Models\helper\AdmsRead();
        $listSitsUsers->fullRead("SELECT sitpg.id, sitpg.name, col.color
        FROM adms_sits_pgs AS sitpg 
        INNER JOIN adms_colors AS col ON sitpg.adms_color_id=col.id
        WHERE sitpg.name LIKE :search_sit_pg
        ORDER BY sitpg.id ASC LIMIT :limit OFFSET :offset", "search_sit_pg={$this->searchSitPgValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listSitsUsers->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma situação encontrada!</p>";
            $this->result = false;
        }
    }
}
