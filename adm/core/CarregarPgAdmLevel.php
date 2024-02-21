<?php

namespace Core;


if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
/**
 * Verifica se existe a classe
 */

class CarregarPgAdmLevel
{
    private string $urlController;
    private string $urlMetodo;
    private int|string $urlParameter;
    private string $classLoad;
    private $urlSlugController;
    private $urlSlugMetodo;

    private array|null $resultPage;
    private array|null $resultLevelPage;

    /**
     * Verificar se existe a classe
     * @param string $urlController Recebe da URL o nome da controller
     * @param string $urlMetodo Recebe da URL o método
     * @param string $urlParamentro Recebe da URL o parâmetro
     */

    public function loadPage(string|null $urlController, string|null $urlMetodo, string|null $urlParameter): void
    {
        $this->urlController = $urlController;
        $this->urlMetodo = $urlMetodo;
        $this->urlParameter = $urlParameter;
        //var_dump($this->urlController);
        $this->searchPage();

        //unset($_SESSION['user_id']);


    }

    private function searchPage(): void
    {
        $serchPage = new \App\adms\Models\helper\AdmsRead();
        $serchPage->fullRead("SELECT pag.id, pag.publish, 
        typ.type
        FROM adms_pages AS pag
        INNER JOIN adms_types_pgs AS typ ON typ.id=pag.adms_types_pgs_id 
        WHERE pag.controller =:controller AND pag.metodo =:metodo LIMIT :limit", "controller={$this->urlController}&metodo={$this->urlMetodo}&limit=1");
        $this->resultPage = $serchPage->getResult();
        if ($this->resultPage) {
            //var_dump($this->resultPage);
            if ($this->resultPage[0]['publish'] == 1) {
                $this->classLoad = "\\App\\".$this->resultPage[0]['type']."\\Controllers\\" . $this->urlController;
                $this->loadMetodo();
            } else {
                $this->verifyLogin();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Página não encontrada!</p>";
            $urlRedirect = URL . "login/index";
            header("Location: $urlRedirect");
        }
    }

    private function loadMetodo(): void
    {
        $classLoad = new $this->classLoad();
        if (method_exists($classLoad, $this->urlMetodo)) {
            $classLoad->{$this->urlMetodo}($this->urlParameter);
        } else {
            die("Erro - 004: Por favor tente novamente. Caso o problema persista, entre em contato o administrador ");
        }
    }

    /**
     * Verificar se o usuário está logado e carregar a página
     *
     * @return void
     */
    private function verifyLogin(): void
    {
        if ((isset($_SESSION['user_id'])) and (isset($_SESSION['user_name']))  and (isset($_SESSION['user_email'])) and (isset($_SESSION['adms_access_level_id'])) and (isset($_SESSION['order_levels']))) {
            $this->searchLevelPage();
            //$this->classLoad = "\\App\\adms\\Controllers\\" . $this->urlController;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Para acessar a página realize o login!</p>";
            $urlRedirect = URL . "login/index";
            header("Location: $urlRedirect");
        }
    }

    private function searchLevelPage(): void
    {
        $serchLevelPage = new \App\adms\Models\helper\AdmsRead();
        $serchLevelPage->fullRead("SELECT id, permission FROM adms_levels_pages 
        WHERE adms_page_id =:adms_page_id
        AND adms_access_level_id =:adms_access_level_id
        AND permission =:permission 
        LIMIT :limit", "adms_page_id={$this->resultPage[0]['id']}&adms_access_level_id=" . $_SESSION['adms_access_level_id'] . "&permission=1&limit=1");
        $this->resultLevelPage = $serchLevelPage->getResult();
        if ($this->resultLevelPage) {
            $this->classLoad = "\\App\\".$this->resultPage[0]['type']."\\Controllers\\" . $this->urlController;
            $this->loadMetodo();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário permissão para acessar a página!</p>";
            $urlRedirect = URL . "login/index";
            header("Location: $urlRedirect");
        }
    }
}
