<?php

namespace App\adms\Models;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Cadastrar a situação no banco de dados
 *
 * @author Celke
 */
class AdmsAddTypesPages
{
    /** @var array|null $data Recebe as informações do formulário */
    private array|null $data;

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result;

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private null|array $resultBd;

    private array $listRegistryAdd;
    private array|null $dataExitVal;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /** 
     * Recebe os valores do formulário.
     * Instancia o helper "AdmsValEmptyField" para verificar se todos os campos estão preenchidos 
     * Verifica se todos os campos estão preenchidos e instancia o método "valInput" para validar os dados dos campos
     * Retorna FALSE quando algum campo está vazio
     * 
     * @param array $data Recebe as informações do formulário
     * 
     * @return void
     */
    public function create(array $data = null)
    {

        $this->data = $data;
        $this->dataExitVal['obs'] = $this->data['obs'];
        unset($this->data['obs']); 

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);

        if ($valEmptyField->getResult()) {
            $this->data['obs'] = $this->dataExitVal['obs'];
            $this->add();
        } else {
            $this->result = false;
        }
    }



    /** 
     * Cadastrar usuário no banco de dados
     * Retorna TRUE quando cadastrar o usuário com sucesso
     * Retorna FALSE quando não cadastrar o usuário
     * 
     * @return void
     */
    private function add(): void
    {
        if ($this->newOrderType()) {
            $this->data['created'] = date("Y-m-d H:i:s");

            $createGroupPg = new \App\adms\Models\helper\AdmsCreate();
            $createGroupPg->exeCreate("adms_types_pgs", $this->data);
            if ($createGroupPg->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Tipo de página cadastrado com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo de página não cadastrado com sucesso!</p>";
                $this->result = false;
            } 
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo de página não cadastrado com sucesso!</p>";
            $this->result = false;
        } 


         

        
    }


    private function newOrderType():bool
    {
        $orderLevel = new \App\adms\Models\helper\AdmsRead();
        $orderLevel->fullRead("SELECT order_type_pg FROM adms_types_pgs ORDER BY order_type_pg DESC");
        $this->resultBd = $orderLevel->getResult();
        if ($this->resultBd) {
            $this->data['order_type_pg'] = $this->resultBd[0]['order_type_pg'] + 1;
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo de página não cadastrado  com sucesso!</p>";
            return false;
        }
    }
}
