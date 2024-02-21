<?php

namespace App\adms\Models;
if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}



class AdmsListAccessLevels
{

    private $resultBd;
    private $result = false;
    private int $page;
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

    function getResultBd(): array|null
    {
        return $this->resultBd;
    }
    function getResultPg(): string|null
    {
        return $this->resultPg;
    }

    //vai receber o numero da página enviada pela controller
    public function listAccessLevels(int $page = null):void
    {
        $listAccessLevels = new \App\adms\Models\helper\AdmsRead();

        $this->page = (int) $page ? $page : 1; //força a ser 1 e inteiro se for vazia
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL. 'list-access-levels/index');//envia a url para a constructor da help
        $pagination->condition($this->page, $this->limitResult);//envia o numero da página e o limite a ser apresentado
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_access_levels WHERE order_levels >:order_levels", "order_levels=" . $_SESSION['order_levels']);
        $this->resultPg = $pagination->getResult();

        $listAccessLevels->fullRead("SELECT id, name, order_levels
        FROM adms_access_levels 
        WHERE order_levels >=:order_levels
        ORDER BY order_levels ASC LIMIT :limit OFFSET :offset", "order_levels=" . $_SESSION['order_levels']."&limit={$this->limitResult}&offset={$pagination->getOffset()}");



        $this->resultBd = $listAccessLevels->getResult();
        
        if($this->resultBd){
            $this->result = true;
            //var_dump($this->resultBd);
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não encontrado!</p>";
            $this->result = false;
        }
    }
    public function listSearchLvls(int $page = null, string|null $search_lvl): void
    {
        $this->page = (int) $page ? $page : 1;
        $this->searchLvl = trim($search_lvl);

        $this->searchLvlValue = "%" . $this->searchLvl . "%";

        if (!empty($this->searchLvl)) {
            $this->searchAccessLevel();
        }
    }

    public function searchAccessLevel(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-access-levels/index', "?search_lvl={$this->searchLvl}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                            FROM adms_access_levels 
                            WHERE name LIKE :search_lvl", "search_lvl={$this->searchLvlValue}");
        $this->resultPg = $pagination->getResult();

        $listLvls = new \App\adms\Models\helper\AdmsRead();
        $listLvls->fullRead("SELECT id, name, order_levels
        FROM adms_access_levels 
        WHERE name LIKE :search_lvl AND order_levels >:order_levels
        ORDER BY order_levels ASC LIMIT :limit OFFSET :offset", "order_levels=" . $_SESSION['order_levels']."&search_lvl={$this->searchLvlValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listLvls->getResult();
        

        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma página encontrada!</p>";
            $this->result = false;
        }
    }


    
    
}
