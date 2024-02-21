<?php

namespace App\adms\Models;

if (!defined('C8L6K7E')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Apagar cor no banco de dados
 *
 * @author Celke
 */
class AdmsDeleteAccessLevels
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

    /**
     * Metodo recebe como parametro o ID do registro que será excluido
     * Chama as funções viewSit e checkColorUsed para fazer a confirmação do registro antes de excluir
     * @param integer $id
     * @return void
     */
    public function deleteAccessLvls(int $id): void
    {
        $this->id = (int) $id;

        if (($this->viewLevel()) and ($this->checkLevelUsed())) {
            $deleteColor = new \App\adms\Models\helper\AdmsDelete();
            $deleteColor->exeDelete("adms_access_levels", "WHERE id =:id", "id={$this->id}");

            if ($deleteColor->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Nível de acesso apagado com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acesso não apagado com sucesso!</p>";
                $this->result = false;
            }
        } else {
            $this->result = false;
        }
    }

    /**
     * Metodo verifica se a cor esta cadastrada na tabela e envia o resultado para a função deleteColor
     * @return boolean
     */
    private function viewLevel(): bool
    {

        $viewLevel = new \App\adms\Models\helper\AdmsRead();
        $viewLevel->fullRead(
            "SELECT id
                            FROM adms_access_levels                         
                            WHERE id=:id AND order_levels >:order_levels
                            LIMIT :limit",
            "id={$this->id}&order_levels=".$_SESSION['order_levels']."&limit=1"
        );

        $this->resultBd = $viewLevel->getResult();
        if ($this->resultBd) {
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acesso não encontrado!</p>";
            return false;
        }
    }

    /**
     * Metodo verifica se tem situação cadastrados usando a cor a ser excluida, caso tenha a exclusão não é permitida
     * O resultado da pesquisa é enviada para a função deleteColor
     * @return boolean
     */
    private function checkLevelUsed(): bool
    {
        $viewLevelUsed = new \App\adms\Models\helper\AdmsRead();
        $viewLevelUsed->fullRead("SELECT id FROM adms_access_levels WHERE adms_access_level_id =:adms_access_level_id LIMIT :limit", "adms_access_level_id={$this->id}&limit=1");
        if ($viewLevelUsed->getResult()) {
            $_SESSION['msg'] = "<p class='alert-warning'>Erro: O nível de acesso não pode ser apagado, há situação cadastrada com esse nível!</p>";
            return false;
        } else {
            return true;
        }
    }
}
