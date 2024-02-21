<?php

namespace App\adms\Models;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Recupera os nívels de acesso no BD
 *
 * @author Celke
 */
class AdmsSyncPagesLevels
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;
    /** @var array|null $resultBdLevels Recebe os registros do banco de dados */
    private array|null $resultBdLevels;
    /** @var array|null $resultBdPages Recebe os registros do banco de dados */
    private array|null $resultBdPages;
    /** @var array|null $resultBdLevelPage Recebe os registros do banco de dados */
    private array|null $resultBdLevelPage;
    /** @var array|null $dataLevelPage Recebe os registros do banco de dados */
    private array|null $dataLevelPage;
    /** @var array|null $resultBdLastOrder Recebe os registros do banco de dados */
    private array|null $resultBdLastOrder;
    /** @var int|string|null $levelId Recebe os id do registro */
    private int|string|null $levelId;
    /** @var int|string|null $pageId Recebe os id do registro */
    private int|string|null $pageId;
    /** @var int|string|null $publish Recebe tipo e permissão */
    private int|string|null $publish;



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

    public function syncPagesLevels(): void
    {

        $listLevels = new \App\adms\Models\helper\AdmsRead();
        $listLevels->fullRead(
            "SELECT id FROM adms_access_levels"
        );

        // var_dump($viewUser->getResult());
        $this->resultBdLevels = $listLevels->getResult();

        if ($this->resultBdLevels) {
            /* $this->result = true; */
            
            $this->listPages();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhum nível de acesso encontrado!</p>";
            $this->result = false;
        }
    }

    private function listPages(): void
    {

        $listPages = new \App\adms\Models\helper\AdmsRead();
        $listPages->fullRead(
            "SELECT id, publish FROM adms_pages"
        );

        // var_dump($viewUser->getResult());
        $this->resultBdPages = $listPages->getResult();

        if ($this->resultBdPages) {
            //$this->result = true;

            $this->readLevels();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma página encontrado!</p>";
            $this->result = false;
        }
    }

    private function readLevels(): void
    {
        foreach ($this->resultBdLevels as $level) {

            extract($level);
            //echo "Id do nivel: $id <br>";
            $this->levelId = $id;
            $this->readPages();
        }
    }

    private function readPages(): void
    {
        foreach ($this->resultBdPages as $page) {

            extract($page);
            //echo "Id da página: $id <br>";
            $this->pageId = $id;
            $this->publish = $publish;
            $this->searchLevelPage();
        }
    }

    private function searchLevelPage(): void
    {

        $listLevelPage = new \App\adms\Models\helper\AdmsRead();
        $listLevelPage->fullRead(
            "SELECT id FROM adms_levels_pages 
            WHERE adms_access_level_id=:adms_access_level_id AND adms_page_id=:adms_page_id",
            "adms_access_level_id={$this->levelId}&adms_page_id={$this->pageId}"
        );

        // var_dump($viewUser->getResult());
        $this->resultBdLevelPage = $listLevelPage->getResult();

        if ($this->resultBdLevelPage) {
            //$this->result = true;
            //echo "O nível de acesso tem cadastro para a página: {$this->pageId}<br>";
            $_SESSION['msg'] = "<p class='alert-success'>Todas as permissões estão sincronizadas!</p>";
        } else {
            //echo "O nível de acesso não tem cadastro para a página: {$this->pageId}<br>";
            //$_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma página encontrado!</p>";
            //$this->result = false;
            $this->addLevelPermission();
        }
    }

    private function addLevelPermission(): void
    {
        $this->searchLastOrder();
        $this->dataLevelPage['permission'] = (($this->levelId == 1) or ($this->publish == 1)) ? 1 : 2;
        $this->dataLevelPage['order_level_page'] = $this->resultBdLastOrder[0]['order_level_page'] + 1;
        $this->dataLevelPage['adms_access_level_id'] = $this->levelId;
        $this->dataLevelPage['adms_page_id'] = $this->pageId;
        $this->dataLevelPage['created'] = date("Y-m-d H:i:s");

        $addAccessLevel = new \App\adms\Models\helper\AdmsCreate();
        $addAccessLevel->exeCreate("adms_levels_pages", $this->dataLevelPage);

        if ($addAccessLevel->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Permissões sincronizadas com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Permissões não sincronizadas com sucesso!</p>";
            $this->result = false;
        }
    }

    private function searchLastOrder(): void
    {
        $viewLastOrder = new \App\adms\Models\helper\AdmsRead;
        $viewLastOrder->fullRead("SELECT order_level_page, adms_access_level_id
        FROM adms_levels_pages
        WHERE adms_access_level_id =:adms_access_level_id
        ORDER BY order_level_page DESC LIMIT :limit", "adms_access_level_id={$this->levelId}&limit=1");
        $this->resultBdLastOrder = $viewLastOrder->getResult();
        if (!$this->resultBdLastOrder) {
            $this->resultBdLastOrder[0]['order_level_page'] = 0;
        }
    }
}
