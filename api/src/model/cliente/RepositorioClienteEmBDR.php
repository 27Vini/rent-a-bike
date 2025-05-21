<?php

class RepositorioClienteEmBDR extends RepositorioGenericoEmBDR implements RepositorioCliente{

    public function __construct(PDO $pdo){
        parent::__construct($pdo);
    }

    public function adicionar(Cliente $cliente) : void{
        try{
            $comando = "INSERT INTO cliente (codigo,nome,cpf,foto) VALUES (:codigo,:nome,:cpf,:foto)";
            $this->executarComandoSql($comando, [
                "nome"      => $cliente->getNome(), 
                "codigo"    => $cliente->getCodigo(),
                "cpf"       => $cliente->getCpf(), "foto" => $cliente->getFoto()
            ]);
        
            $cliente->setId($this->ultimoIdAdicionado());
        } catch(Exception $e){
            throw new RepositorioException("Erro ao cadastrar cliente.", $e->getCode());
        }
        
    }

    public function coletarComId($id): Cliente{
        try{
            $comando = "SELECT * FROM cliente WHERE id=:id";
            $ps = $this->executarComandoSql($comando, ["id" => $id ]);
            if($ps->rowCount() == 0)
                throw new DominioException("Cliente não encontrado.");

            $dadosCliente = $ps->fetch();
            return $this->transformarEmCliente($dadosCliente);
        }catch(DominioException $e){
            throw $e;
        }catch(Exception $e){
            throw new RepositorioException("Erro ao obter cliente com id.", $e->getCode());
        }
    }

    public function coletarComCodigoOuCpf($codigoCpf) : Cliente{
        try{
            $comando = 'SELECT * FROM cliente WHERE ';
            mb_strlen($codigoCpf) != 11 ? $comando .= "codigo=:codigoCpf" : $comando .= "cpf=:codigoCpf";

            $ps = $this->executarComandoSql($comando, ["codigoCpf" => $codigoCpf]);

            if($ps->rowCount() == 0)
                throw new DominioException("Cliente não encontrado.");

            $dadosCliente = $ps->fetch();
            return $this->transformarEmCliente($dadosCliente);
        }catch(DominioException $e){
            throw $e;
        }catch(Exception $e){
            throw new RepositorioException("Erro ao obter cliente com código/cpf.", $e->getMessage());
        }        
    }

    private function transformarEmCliente(array $dadosCliente) : Cliente{
        return new Cliente($dadosCliente['id'], $dadosCliente['codigo'], $dadosCliente['cpf'], $dadosCliente['nome'], $dadosCliente['foto']);
    }
    
}