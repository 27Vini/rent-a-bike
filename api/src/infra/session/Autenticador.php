<?php

class Autenticador{
    private GerenteDeSessao $gerenteSessao;
    
    public function __construct(GerenteDeSessao $gerenteSessao){
        $this->gerenteSessao = $gerenteSessao;
    }

    /**
     * Método que autentica um usúario pelo JSON enviado.
     * @param PDO $pdo
     * @param string $json
     * @return void
     */
    public function autenticarFuncionario(PDO $pdo, string $json): void{
        $gestorFuncionario = criarGestorDeFuncionario($pdo, $this);
    
        $funcionario = $gestorFuncionario->logar(json_decode($json, true));
        $this->gerenteSessao->setFuncionario($funcionario);
    }

    public function abrirSessao(): void{
        if (session_status() !== PHP_SESSION_ACTIVE){
            $this->gerenteSessao->abrirSessao();
        }
    }

    public function fecharSessao(): void{
        try{
            $this->verificarSeUsuarioEstaLogado();
            $this->gerenteSessao->fecharSessao();
        }catch(DominioException $e){
            throw $e;
        }
    }

    /**
     * Verifica se tem um usuário logado.
     * @return void
     */
    public function verificarSeUsuarioEstaLogado(): void{
        if(!isset($_SESSION['funcionario']) || $_SESSION['funcionario'] == null){
            throw new DominioException("Usuário não autenticado.");
        }
    }

    /**
     * Retorna o funcionário logado no sistema no momento 
     * @return Funcionario
     */
    public function obterFuncionarioLogado() : Funcionario {
        return $_SESSION['funcionario'];
    }
}