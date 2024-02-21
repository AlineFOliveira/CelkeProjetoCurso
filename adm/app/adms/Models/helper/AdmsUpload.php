<?php

namespace App\adms\Models\helper;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}



class AdmsUpload
{

    private string $directory;
    private string $tmpName;
    private string $name;
    private bool $result;

    function getResult(): bool
    {
        return $this->result;
    }

    public function upload(string $directory, string $tmpName, string $name): void
    {
        $this->directory = $directory;
        $this->tmpName = $tmpName;
        $this->name = $name;

        if ($this->valDirectory()) {
            $this->uploadFile();
        } else {
            $this->result = false;
        }
    }

    private function valDirectory(): bool
    {
        if ((!file_exists($this->directory)) and (!is_dir($this->directory))) {
            mkdir($this->directory, 0755); //cria um diretório com o id do usuário
            if ((!file_exists($this->directory)) and (!is_dir($this->directory))) {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Upload da imagem não realizado com sucesso. Tente novamente!</p>";
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    private function uploadFile()
    {
        if (move_uploaded_file($this->tmpName, $this->directory . $this->name)) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Upload da imagem não realizado com sucesso. Tente novamente! </p>";
            $this->result = false;
        }
    }
}
