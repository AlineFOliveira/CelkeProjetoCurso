<?php

namespace App\adms\Models;

if(!defined('C8L6K7E')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Apagar o usuário no banco de dados
 *
 * @author Celke
 */
class AdmsDeleteSitsUsers
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;


    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    public function deleteSit(int $id): void
    {
        $this->id = (int) $id;

        if(($this->viewSit()) and ($this->checkStatusUsed())){
            $deleteUser = new \App\adms\Models\helper\AdmsDelete();
            $deleteUser->exeDelete("adms_sits_users", "WHERE id =:id", "id={$this->id}");
    
            if ($deleteUser->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Situação apagada com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Situação não apagada com sucesso!</p>";
                $this->result = false;
            }
        }else{
            $this->result = false;
        }
        
    }

    private function viewSit(): bool
    {

        $viewUser = new \App\adms\Models\helper\AdmsRead();
        $viewUser->fullRead(
            "SELECT id
                            FROM adms_sits_users                          
                            WHERE id=:id
                            LIMIT :limit",
            "id={$this->id}&limit=1"
        );

        $this->resultBd = $viewUser->getResult();
        if ($this->resultBd) {
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Situação não encontrada!</p>";
            return false;
        }
    }

    private function checkStatusUsed():bool
    {
        $viewUserAdd = new \App\adms\Models\helper\AdmsRead();
        $viewUserAdd->fullRead("SELECT id FROM adms_users WHERE adms_sits_user_id =:adms_sits_user_id LIMIT :limit", "adms_sits_user_id={$this->id}&limit=1");
        if($viewUserAdd->getResult()){
            $_SESSION['msg'] = "<p class='alert-warning'>Erro: Situação não pode ser apagada, há usuários cadastrados com essa situação!</p>";
            return false;
        }else{
            return true;
        }
    }

}
