<?php


class RepositorioFuncionarioEmBDR extends RepositorioGenericoEmBDR implements RepositorioFuncionario{
    public function __construct(PDO $pdo){
        parent::__construct($pdo);
    }

    public function adicionar(Funcionario $funcionario) : void{
        $comando = "INSERT INTO funcionario (nome) VALUES (:nome)";
        $this->executarComandoSql($comando, ["nome" => $funcionario->getNome()]);

        $funcionario->setId($this->ultimoIdAdicionado());
    }

    public function coletarComId(int $id) : null | Funcionario {
        $comando = "SELECT id, nome FROM funcionario WHERE id = :id LIMIT 1";
        $ps = $this->executarComandoSql($comando, ["id" => $id]);

        $funcionario = $ps->fetchObject(Funcionario::class); 
        return $funcionario ?: null;
    }
}