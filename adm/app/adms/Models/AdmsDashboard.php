<?php

namespace App\adms\Models;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Visualizar o usuário no banco de dados
 *
 * @author Celke
 */
class AdmsDashboard

{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;


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

    public function countUsers(): void
    {

        

        $countUsers = new \App\adms\Models\helper\AdmsRead();
        $countUsers->fullRead(
            "SELECT COUNT(id) AS qnt_users FROM adms_users"
        );

        

        //pçvar_dump($viewUser->getResult());
        $this->resultBd = $countUsers->getResult();
        
        if ($this->resultBd) {
            $this->result = true;
        } else {
            //$_SESSION['msg'] = "<p style='color: #f00'>Erro: Usuário não encontrado!</p>";
            $this->result = false;
        }
    }
}
