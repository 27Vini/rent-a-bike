<?php

interface GerenteDeSessao{
    public function setFuncionario(Funcionario $funcionario): void;
    public function abrirSessao(): void;
    public function fecharSessao(): void;
}