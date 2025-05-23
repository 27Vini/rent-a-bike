<?php

class TransacaoComPDO implements Transacao {

    public function __construct( private PDO $pdo ) {}

    public function iniciar(){
        $this->pdo->beginTransaction();
    }

    public function finalizar(){
        if($this->pdo->inTransaction()){
            $this->pdo->commit();
        }
    }

    public function desfazer(){
        if($this->pdo->inTransaction()){
            $this->pdo->rollBack();
        }
    }
}