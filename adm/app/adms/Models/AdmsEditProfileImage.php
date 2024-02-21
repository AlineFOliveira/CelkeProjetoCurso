<?php

namespace App\adms\Models;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Editar o perfil do usuario
 *
 * @author Celke
 */
class AdmsEditProfileImage
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    private array|null $data;

    private array|null $dataImage;

    private string $directory;

    private string $delImg;

    private string $nameImg;


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

    public function viewProfile(): bool
    {

        $viewUser = new \App\adms\Models\helper\AdmsRead();
        $viewUser->fullRead(
            "SELECT id, image
                            FROM adms_users
                            WHERE id=:id
                            LIMIT :limit",
            "id=" . $_SESSION['user_id'] . "&limit=1"
        );

        $this->resultBd = $viewUser->getResult();
        if ($this->resultBd) {
            $this->result = true;
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Perfil não encontrado!</p>";
            $this->result = false;
            return false;
        }
    }

    public function update(array $data = null): void
    {
        $this->data = $data;
        $this->dataImage = $this->data['new_image'];
        unset($this->data['new_image']);


        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        if ($valEmptyField->getResult()) {
            if (!empty($this->dataImage['name'])) {
                //$this->result = false;
                $this->valInput();
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário selecionar uma imagem</p>";
                $this->result = false;
            }
            //$this->result = false;

        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não cadastrado com sucesso!</p>";
            $this->result = false;
        }
    }

    private function valInput(): void
    {
        $valExtImg = new \App\adms\Models\helper\AdmsValExtImg();
        $valExtImg->validateExtImg($this->dataImage['type']);
        if (($this->viewProfile($this->data['id'])) and ($valExtImg->getResult())) {
            $this->upload();
        } else {
            $this->result = false;
        }
    }

    private function upload(): void
    {
        $slugImg = new \App\adms\Models\helper\AdmsSlug();
        $this->nameImg =  $slugImg->slug($this->dataImage['name']);
        

        $this->result = false;

        $this->directory = "app/adms/assets/image/users/" . $_SESSION['user_id'] . "/";


        $uploadImgRes = new \App\adms\Models\helper\AdmsUploadImgRes();
        $uploadImgRes->upload($this->dataImage, $this->directory, $this->nameImg, 300, 300);

        if($uploadImgRes->getResult()){
            $this->edit();
        }else{
           
            $this->result = false;
        }
        
    }

    private function edit(): void
    {
        $this->data['image'] = $this->nameImg;
        $this->data['modified'] = date("Y-m-d H:i:s");


        $upUser = new \App\adms\Models\helper\AdmsUpdate();
        $upUser->exeUpdate("adms_users", $this->data, "WHERE id=:id", "id=".$_SESSION['user_id']);

        if ($upUser->getResult()) {
            $_SESSION['user_image'] = $this->nameImg;
            $this->deletImage();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Imagem não editada com sucesso!</p>";
            $this->result = false;
        }
    }

    private function deletImage(): void
    {

        //apagar para substituir uma imagem já existente
        if (((!empty($this->resultBd[0]['image'])) or ($this->resultBd[0]['image'] != null)) and ($this->resultBd[0]['image'] != $this->nameImg)) {
            $this->delImg = "app/adms/assets/image/users/" . $this->data['id'] . "/" . $this->resultBd[0]['image'];
            if (file_exists($this->delImg)) {
                unlink($this->delImg);
            }
        }

        $_SESSION['msg'] = "<p class='alert-success'>Imagem editada com sucesso!</p>";
        $this->result = true;
    }

    
}
