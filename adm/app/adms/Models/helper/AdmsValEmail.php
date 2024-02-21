<?php

namespace App\adms\Models\helper;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

// 

class AdmsValEmail
{
    private string $email;
    private bool $result;

    function getResult(): bool
    {
        return $this->result;
    }

    public function validateEmail(string $email): void
    {
        $this->email = $email;
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Email inválido!</p>";
            $this->result = false;
        }
    }
}
