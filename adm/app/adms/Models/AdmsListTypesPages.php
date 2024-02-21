<?php

namespace App\adms\Models;
if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}



class AdmsListTypesPages
{

    private $resultBd;
    private $result = false;
    private int $page;
    private int $limitResult = 40;
    private string|null $resultPg;

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
    public function listTypesPages(int $page = null):void
    {
        $listTypesPages = new \App\adms\Models\helper\AdmsRead();

        $this->page = (int) $page ? $page : 1; //força a ser 1 e inteiro se for vazia
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL. 'list-types-pages/index');//envia a url para a constructor da help
        $pagination->condition($this->page, $this->limitResult);//envia o numero da página e o limite a ser apresentado
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_types_pgs");
        $this->resultPg = $pagination->getResult();

        $listTypesPages->fullRead("SELECT id, type, name, order_type_pg
        FROM adms_types_pgs 
        ORDER BY order_type_pg ASC LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");



        $this->resultBd = $listTypesPages->getResult();
        
        if($this->resultBd){
            $this->result = true;
            //var_dump($this->resultBd);
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não encontrado!</p>";
            $this->result = false;
        }
    }


    
    
}
