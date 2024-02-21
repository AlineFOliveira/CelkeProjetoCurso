<?php

namespace App\adms\Models\helper;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}



class AdmsValEmptyField
{
    private array|null $data;
    private bool $result;

    function getResult()
    {
        return $this->result;
    }

    public function valField(array $data = null)
    {

        $this->data = $data;
        $this->data = array_map('strip_tags', $this->data); //array map aplica uma função a cada elemento do array, tira a tentativa de usar tags
        $this->data = array_map('trim', $this->data); //remove espaços em branco

        if (in_array('', $this->data)) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário preencher todos os campos</p>";
            $this->result = false;
        } else {
            $this->result = true;
        }
    }
}
