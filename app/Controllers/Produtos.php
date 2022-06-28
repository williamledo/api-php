<?php

  namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Exception;

  class Produtos extends ResourceController {

    private $produtoModel;
    private $token = '123456789abcdefghi';

    public function __construct()
    {
      $this->produtoModel = new \App\Models\ProdutosModel();
    }

    private function _validaToken() {

      return $this->request->getHeaderLine('token') == $this->token;

    }

    public function list() {

      $data = $this->produtoModel->findAll();
      return $this->response->setJSON($data);

    }
 
    public function create() {

      $reponse = [];

      if ($this->_validaToken() == true) {

        $newProduto['nome'] = $this->request->getPost('nome');
        $newProduto['valor'] = $this->request->getPost('valor');

        try {
          if($this->produtoModel->insert($newProduto)){
            $response = [
              'response' => 'success',
              'msg'      => 'Produto adicionado com sucesso'
            ];
          }
          else {
            $response = [
              'response' => 'error',
              'msg'      => 'Erro ao salvar o produto',
              'errors'   => $this->produtoModel->errors()
            ];
          }
        }
        catch(Exception $e) {
          $response = [
            'response' => 'error',
            'msg'      => 'Erro ao salvar o produto',
            'errors'   => [
              'exception' => $e->getMessage()
            ]
          ];
        }

      }
      else {
        $response = [
          'response' => 'error' ,
          'msg' => 'Token inválido',
        ];
      }

      return $this->response->setJSON($response);

    }

  }

