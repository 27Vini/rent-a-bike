<?php

class GerenteDeSessaoEmSession implements GerenteDeSessao{
    public function abrirSessao(): void{
        session_set_cookie_params(['httponly' => true ] );
        session_name( 'sid' );
        session_start();
    }

    public function setFuncionario(Funcionario $funcionario): void{
        $_SESSION['funcionario'] = $funcionario;
    }

    public function fecharSessao(): void{
        $_SESSION['funcionario'] = null;
        session_destroy();
    }
}