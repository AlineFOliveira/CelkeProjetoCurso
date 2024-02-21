<?php

namespace App\adms\Controllers;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
class SyncPagesLevels
{

    public function index(): void
    {
        $syncPagesLevels = new \App\adms\Models\AdmsSyncPagesLevels();
        $syncPagesLevels->syncPagesLevels();

        $urlRedirect = URL . "list-access-levels/index";
        header("Location: $urlRedirect");
    }
}
