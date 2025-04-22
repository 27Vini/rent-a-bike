<?php


class RepositorioFuncionarioEmBDR extends RepositorioGenericoEmBDR{

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