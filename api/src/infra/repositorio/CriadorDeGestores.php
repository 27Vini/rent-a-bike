<?php

function criarGestorDeLocacao(PDO $pdo): GestorLocacao{
    $repositorioCliente = new RepositorioClienteEmBDR($pdo);
    $repositorioFuncionario = new RepositorioFuncionarioEmBDR($pdo);
    $repositorioItemLocacao = new RepositorioItemLocacaoEmBDR($pdo);
    $repositorioLocacao = new RepositorioLocacaoEmBDR($pdo, $repositorioItemLocacao);
    $transacao = new TransacaoComPDO($pdo);
    
    return new GestorLocacao($repositorioLocacao, $repositorioCliente, $repositorioFuncionario, $transacao);
}

function criarGestorDeDevolucao(PDO $pdo): GestorDevolucao{
    $repositorioItemLocacao = new RepositorioItemLocacaoEmBDR($pdo);
    $repositorioLocacao = new RepositorioLocacaoEmBDR($pdo, $repositorioItemLocacao);
    $repositorioFuncionario = new RepositorioFuncionarioEmBDR($pdo);
    $repositorioDevolucao = new RepositorioDevolucaoEmBDR($pdo, $repositorioLocacao, $repositorioFuncionario);
    $transacao = new TransacaoComPDO($pdo);
    
    return new GestorDevolucao($repositorioDevolucao, $repositorioLocacao, $repositorioFuncionario, $transacao);
}


function criarGestorDeAvaria(PDO $pdo): GestorAvaria{
    $repositorioItem = new RepositorioItemEmBDR($pdo);
    $repositorioFuncionario = new RepositorioFuncionarioEmBDR($pdo);
    $repositorioAvaria = new RepositorioAvariaEmBDR($pdo, $repositorioItem, $repositorioFuncionario);
    $transacao = new TransacaoComPDO($pdo);
    
    return new GestorAvaria($repositorioAvaria, $repositorioItem, $repositorioFuncionario, $transacao);
}

function criarGestorDeFuncionario(PDO $pdo): GestorFuncionario{
    $repositorioFuncionario = new RepositorioFuncionarioEmBDR($pdo);
    
    return new GestorFuncionario($repositorioFuncionario);
}

function criarGestorDeCliente(PDO $pdo): GestorCliente{
    $repositorioCliente = new RepositorioClienteEmBDR($pdo);
    
    return new GestorCliente($repositorioCliente);
}

function criarGestorDeItem(PDO $pdo): GestorItem{
    $repositorioItem = new RepositorioItemEmBDR($pdo);
    
    return new GestorItem($repositorioItem);
}