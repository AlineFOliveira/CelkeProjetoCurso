<?php

namespace App\adms\Models;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}



class AdmsListSitsUsers
{

    private $resultBd;
    private $result = false;
    private int $page;
    private int $limitResult = 40;
    private string|null $resultPg;
    /** @var string|null $searchEmail Recebe o nome da situação */
    private string|null $searchSit;

    /** @var string|null $searchNameValue Recebe o nome da situação*/
    private string|null $searchSitValue;

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
    public function listSitsUsers(int $page = null): void
    {
        $listSitsUsers = new \App\adms\Models\helper\AdmsRead();

        $this->page = (int) $page ? $page : 1; //força a ser 1 e inteiro se for vazia
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-sits-users/index'); //envia a url para a constructor da help
        $pagination->condition($this->page, $this->limitResult); //envia o numero da página e o limite a ser apresentado
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_sits_users");
        $this->resultPg = $pagination->getResult();

        $listSitsUsers->fullRead("SELECT sit.id, sit.name, col.color
        FROM adms_sits_users AS sit 
        INNER JOIN adms_colors AS col ON sit.adms_color_id=col.id
        ORDER BY sit.id ASC LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");



        $this->resultBd = $listSitsUsers->getResult();

        if ($this->resultBd) {
            $this->result = true;
            //var_dump($this->resultBd);
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não encontrado!</p>";
            $this->result = false;
        }
    }

    public function listSearchSits(int $page = null, string|null $search_sit): void
    {
        $this->page = (int) $page ? $page : 1;
        $this->searchSit = trim($search_sit);

        $this->searchSitValue = "%" . $this->searchSit . "%";

        if (!empty($this->searchSit)) {
            $this->searchSitsUser();
        }
    }

    public function searchSitsUser(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-sits-users/index', "?search_sit={$this->searchSit}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                            FROM adms_sits_users 
                            WHERE name LIKE :search_sit", "search_sit={$this->searchSitValue}");
        $this->resultPg = $pagination->getResult();

        $listUsers = new \App\adms\Models\helper\AdmsRead();
        $listUsers->fullRead("SELECT sit.id, sit.name, col.color
        FROM adms_sits_users AS sit 
        INNER JOIN adms_colors AS col ON sit.adms_color_id=col.id
        WHERE sit.name LIKE :search_sit
        ORDER BY sit.id ASC LIMIT :limit OFFSET :offset", "search_sit={$this->searchSitValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listUsers->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma situação encontrada!</p>";
            $this->result = false;
        }
    }
}
