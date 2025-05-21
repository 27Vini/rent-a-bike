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

    public function coletarComId(int $id) : Funcionario {
        $comando = "SELECT id, nome FROM funcionario WHERE id = :id";
        $ps = $this->executarComandoSql($comando, ["id" => $id]);

        if($ps->rowCount() == 0)
            throw DominioException::com(['Funcionário não encontrado.']);

        $dadosFuncionario = $ps->fetch(PDO::FETCH_ASSOC);
        return new Funcionario($dadosFuncionario['id'], $dadosFuncionario['nome']);
    }

    public function coletarTodos() : array {
        $comando = "SELECT id, nome FROM funcionario";
        $ps = $this->executarComandoSql($comando, []);
        
        $dadosFuncionarios = $ps->fetchAll(PDO::FETCH_ASSOC);
        $funcionarios = [];
        foreach($dadosFuncionarios as $funcionario){
            $func = new Funcionario($funcionario['id'], $funcionario['nome']);
            $funcionarios[] = $func;
        }

        return $funcionarios;
    }
}