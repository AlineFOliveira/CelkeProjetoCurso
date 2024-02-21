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
class AdmsDeleteGroupsPages
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
    public function deleteGroupsPages(int $id): void
    {
        $this->id = (int) $id;

        if (($this->viewGroup()) and ($this->checkGroupUsed())) {
            $deleteColor = new \App\adms\Models\helper\AdmsDelete();
            $deleteColor->exeDelete("adms_groups_pgs", "WHERE id =:id", "id={$this->id}");

            if ($deleteColor->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Grupo de página apagado com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Grupo de página não apagado com sucesso!</p>";
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
    private function viewGroup(): bool
    {

        $viewGroup = new \App\adms\Models\helper\AdmsRead();
        $viewGroup->fullRead(
            "SELECT id
                            FROM adms_groups_pgs                         
                            WHERE id=:id
                            LIMIT :limit",
            "id={$this->id}&limit=1"
        );

        $this->resultBd = $viewGroup->getResult();
        if ($this->resultBd) {
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Grupo de página não encontrado!</p>";
            return false;
        }
    }

    /**
     * Metodo verifica se tem situação cadastrados usando a cor a ser excluida, caso tenha a exclusão não é permitida
     * O resultado da pesquisa é enviada para a função deleteColor
     * @return boolean
     */
    private function checkGroupUsed(): bool
    {
        $viewGroupUsed = new \App\adms\Models\helper\AdmsRead();
        $viewGroupUsed->fullRead("SELECT id FROM adms_pages WHERE adms_groups_pgs_id  =:adms_groups_pgs_id  LIMIT :limit", "adms_groups_pgs_id={$this->id}&limit=1");
        if ($viewGroupUsed->getResult()) {
            $_SESSION['msg'] = "<p class='alert-warning'>Erro: O Grupo de página não pode ser apagado, há páginas cadastradas com esse nível!</p>";
            return false;
        } else {
            return true;
        }
    }
}
