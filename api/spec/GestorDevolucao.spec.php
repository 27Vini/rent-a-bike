<?php

describe('Gestor Devolução', function(){

    beforeAll(function(){
        `cd ../g4 && pnpm run db`;
        $pdo = new PDO( 'mysql:dbname=g4;host=localhost;charset=utf8', 'root', '' );

        $repoLocacao = new RepositorioLocacaoEmBDR($pdo);
        $repo = new RepositorioDevolucaoEmBDR( $pdo, $repoLocacao);
        $this->gestor = new GestorDevolucao($repo, $repoLocacao);
    });

    it("Cadastra uma devolução válida", function(){
        $this->gestor->salvarDevolucao(["dataDeDevolucao"=> '2025-05-23 11:14:00', "locacao" => '3']);
        expect(1)->toBe(1);
    });

    it("Cadastrar devolução de uma locação já devolvida deve retornar erro", function(){
        expect(function (){
            $this->gestor->salvarDevolucao(["dataDeDevolucao"=> '2025-05-23 11:14:00', "locacao" => '3']);
        })->toThrow(new DominioException());
    });

    it("Cadastrar devolução com uma locação inexistente deve retornar erro", function(){
        expect(function(){
            $this->gestor->salvarDevolucao(["dataDeDevolucao"=> '2025-05-23 11:14:00', "locacao" => '100']);
        })->toThrow(new DominioException());
    });

    it("Retorna todas as devoluções corretamente", function(){
        $devolucoes = $this->gestor->coletarDevolucoes();
        expect($devolucoes)->toHaveLength(3);
    });
});