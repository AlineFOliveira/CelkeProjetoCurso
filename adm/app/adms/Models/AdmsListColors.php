<?php

namespace App\adms\Models;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}



class AdmsListColors
{

    private $resultBd;
    private $result = false;
    private int $page;
    private int $limitResult = 40;
    private string|null $resultPg;
    /** @var string|null $searchName Recebe o nome da cor*/
    private string|null $searchName;

    /** @var string|null $searchEmail Recebe o hexadecimal*/
    private string|null $searchHex;

    /** @var string|null $searchNameValue Recebe o nome do usuario */
    private string|null $searchNameValue;

    /** @var string|null $searchHexValue Recebe o hexadecimal */
    private string|null $searchHexValue;

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
    public function listColors(int $page = null): void
    {
        $listColors = new \App\adms\Models\helper\AdmsRead();

        $this->page = (int) $page ? $page : 1; //força a ser 1 e inteiro se for vazia
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-colors/index'); //envia a url para a constructor da help
        $pagination->condition($this->page, $this->limitResult); //envia o numero da página e o limite a ser apresentado
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_colors");
        $this->resultPg = $pagination->getResult();

        $listColors->fullRead("SELECT id, name, color
        FROM adms_colors
        ORDER BY id DESC LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");



        $this->resultBd = $listColors->getResult();

        if ($this->resultBd) {
            $this->result = true;
            //var_dump($this->resultBd);
        } else {
            $_SESSION['msg'] = "<p style='color:#f00;'>Erro: Não encontrado!</p>";
            $this->result = false;
        }
    }

    public function listSearchColors(int $page = null, string|null $search_name, string|null $search_hex): void
    {
        $this->page = (int) $page ? $page : 1;
        $this->searchName = trim($search_name);
        $this->searchHex = trim($search_hex);
        $this->searchHex = str_replace("#", "", $this->searchHex);

        $this->searchNameValue = "%" . $this->searchName . "%";
        $this->searchHexValue = "%" . $this->searchHex . "%";
        
        

        if ((!empty($this->searchName)) and ((!empty($this->searchHex)) )) {
            $this->searchColNameHex();
        } elseif ((!empty($this->searchName)) and (empty($this->searchHex))) {
            $this->searchColName();
        } elseif ((empty($this->searchName)) and (!empty($this->searchHex))) {
            $this->searchColHex();
        } else {
            $this->searchColNameHex();
        }
    }

    public function searchColName(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-colors/index', "?search_name={$this->searchName}&search_hex={$this->searchHex}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                            FROM adms_colors
                            WHERE name LIKE :search_name", "search_name={$this->searchNameValue}");
        $this->resultPg = $pagination->getResult();

        $listColors = new \App\adms\Models\helper\AdmsRead();
        $listColors->fullRead("SELECT id, name, color
                FROM adms_colors
                WHERE name LIKE :search_name
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset", "search_name={$this->searchNameValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listColors->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma cor encontrada!</p>";
            $this->result = false;
        }
    }

    public function searchColHex(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-colors/index', "?search_name={$this->searchName}&search_hex={$this->searchHex}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                            FROM adms_colors
                            WHERE color LIKE :search_hex", "search_hex={$this->searchHexValue}");
        $this->resultPg = $pagination->getResult();

        $listColors = new \App\adms\Models\helper\AdmsRead();
        $listColors->fullRead("SELECT id, name , color
                FROM adms_colors
                WHERE color LIKE :search_hex
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset", "search_hex={$this->searchHexValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listColors->getResult();
        var_dump($this->searchHexValue);
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma cor encontrado!</p>";
            $this->result = false;
        }
    }

    public function searchColNameHex(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-colors/index', "?search_name={$this->searchName}&search_hex={$this->searchHex}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
        FROM adms_colors
        WHERE name LIKE :search_name OR color LIKE :search_hex", "search_name={$this->searchNameValue}&search_hex={$this->searchHexValue}");
        $this->resultPg = $pagination->getResult();
        
        $listColors = new \App\adms\Models\helper\AdmsRead();
        $listColors->fullRead("SELECT id, name , color
        FROM adms_colors
        WHERE name LIKE :search_name OR color LIKE :search_hex
        ORDER BY id DESC
        LIMIT :limit OFFSET :offset", "search_name={$this->searchNameValue}&search_hex={$this->searchHexValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");
        
        $this->resultBd = $listColors->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma cor encontrada!</p>";
            $this->result = false;
        }
    }
}
