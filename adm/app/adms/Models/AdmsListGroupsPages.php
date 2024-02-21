<?php

namespace App\adms\Models;
if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}



class AdmsListGroupsPages
{

    private $resultBd;
    private $result = false;
    private int $page;
    private int $limitResult = 40;
    private string|null $resultPg;
    /** @var string|null $searchGroupPg Recebe o nome do grupo */
    private string|null $searchGroupPg;

    /** @var string|null $searchGroupPgValue Recebe o nome do grupo*/
    private string|null $searchGroupPgValue;

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
    public function listGroupsPages(int $page = null):void
    {
        $listGroupsPages = new \App\adms\Models\helper\AdmsRead();

        $this->page = (int) $page ? $page : 1; //força a ser 1 e inteiro se for vazia
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL. 'list-groups-pages/index');//envia a url para a constructor da help
        $pagination->condition($this->page, $this->limitResult);//envia o numero da página e o limite a ser apresentado
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_groups_pgs");
        $this->resultPg = $pagination->getResult();

        $listGroupsPages->fullRead("SELECT id, name, order_group_pg
        FROM adms_groups_pgs 
        ORDER BY order_group_pg ASC LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");



        $this->resultBd = $listGroupsPages->getResult();
        
        if($this->resultBd){
            $this->result = true;
            //var_dump($this->resultBd);
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não encontrado!</p>";
            $this->result = false;
        }
    }

    public function listSearchGroups(int $page = null, string|null $search_sit_pag): void
    {
        $this->page = (int) $page ? $page : 1;
        $this->searchGroupPg = trim($search_sit_pag);

        $this->searchGroupPgValue = "%" . $this->searchGroupPg . "%";

        if (!empty($this->searchGroupPg)) {
            $this->searchGroup();
        }
    }

    public function searchGroup(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-sits-pages/index', "?search_group_pag={$this->searchGroupPg}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                            FROM adms_groups_pgs 
                            WHERE name LIKE :search_group_pg", "search_group_pg={$this->searchGroupPgValue}");
        $this->resultPg = $pagination->getResult();

        $listGroups = new \App\adms\Models\helper\AdmsRead();
        $listGroups->fullRead("SELECT id, name, order_group_pg
        FROM adms_groups_pgs 
        WHERE name LIKE :search_group_pg
        ORDER BY order_group_pg ASC LIMIT :limit OFFSET :offset", "search_group_pg={$this->searchGroupPgValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listGroups->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhum grupo encontrado!</p>";
            $this->result = false;
        }
    }
    
    
}
