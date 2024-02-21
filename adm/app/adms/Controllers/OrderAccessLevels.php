<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da pagina alterar ordem do nivel de acesso
 * @author Cesar <cesar@celke.com.br>
 */
class OrderAccessLevels
{
    /** @var array|string|null $pag Recebe o numero da pagina */
    private array|string|null $pag;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo alterar ordem do nivel de acesso  
     * Recebe como parametro o ID que sera usado para pesquisar as informacoes no banco de dados e instancia a MODELS AdmsOrderAccessLevels
     * Apos editar ou erro redireciona para o listar nivel de acesso
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->pag = filter_input(INPUT_GET, "pag", FILTER_SANITIZE_NUMBER_INT);
        var_dump($this->pag);

        if ((!empty($id)) and (!empty($this->pag))) {
            $this->id = (int) $id;

            $viewAccessLevel = new \App\adms\Models\AdmsOrderAccessLevels();
            $viewAccessLevel->orderAccessLevels($this->id);
            if ($viewAccessLevel->getResult()) {
                $urlRedirect = URL . "list-access-levels/index/{$this->pag}";
                header("Location: $urlRedirect");
            } else {
                $urlRedirect = URL . "list-access-levels/index/{$this->pag}";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acesso não encontrado!</p>";
            $urlRedirect = URL . "list-access-levels/index";
            header("Location: $urlRedirect");
        }
    }
}
