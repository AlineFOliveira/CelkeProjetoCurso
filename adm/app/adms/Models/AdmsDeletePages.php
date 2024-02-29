<?php

namespace App\adms\Models;

if (!defined('C8L6K7E')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Apagar o usuário no banco de dados
 *
 * @author Celke
 */
class AdmsDeletePages
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var string $delDirectory Recebe o endereço para apagar o diretório */
    private string $delDirectory;

    /** @var string $delImg Recebe o endereço para apagar a imagem */
    private string $delImg;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    public function deletePages(int $id): void
    {
        $this->id = (int) $id;

        if ($this->viewPages()) {

            /* $deleteLevelPages = new \App\adms\Models\helper\AdmsDelete();
            $deleteLevelPages->exeDelete("adms_levels_pages", "WHERE adms_page_id =:adms_page_id", "adms_page_id={$this->id}"); */

            $deletePages = new \App\adms\Models\helper\AdmsDelete();
            $deletePages->exeDelete("adms_pages", "WHERE id =:id", "id={$this->id}");

            if ($deletePages->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Página apagada com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Página não apagada com sucesso!</p>";
                $this->result = false;
            }
        } else {
            $this->result = false;
        }
    }

    private function viewPages(): bool
    {

        $viewPage = new \App\adms\Models\helper\AdmsRead();
        $viewPage->fullRead(
            "SELECT id
                            FROM adms_pages                      
                            WHERE id=:id
                            LIMIT :limit",
            "id={$this->id}&limit=1"
        );

        $this->resultBd = $viewPage->getResult();
        if (!empty($this->resultBd) && isset($this->resultBd[0]['id'])) {
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Página não encontrado!</p>";
            return false;
        }
    }

}
