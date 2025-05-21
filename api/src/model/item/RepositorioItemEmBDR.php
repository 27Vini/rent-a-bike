<?php

class RepositorioItemEmBDR extends RepositorioGenericoEmBDR implements RepositorioItem{
    public function __construct(PDO $pdo){
        parent::__construct($pdo);
    }
    
    public function adicionar(Item $item) : void{
        try{
            $comando = "INSERT INTO item (codigo, descricao, modelo, fabricante, valorPorHora, avarias, disponibilidade, tipo) VALUES (:codigo, :descricao, :modelo, :fabricante, :valorPorHora, :avarias, :disponibilidade, :tipo)";

            $parametros = [
                "codigo"            => $item->getCodigo(),
                "descricao"         => $item->getDescricao(),
                "modelo"            => $item->getModelo(),
                "fabricante"        => $item->getFabricante(),
                "valorPorHora"      => $item->getValorPorHora(),
                "avarias"           => $item->getAvarias(),
                "disponibilidade"   => $item->getDisponibilidade(),
                "tipo"              => $item->getTipo()
            ];

            $this->executarComandoSql($comando, $parametros);
            $item->setId($this->ultimoIdAdicionado());
        }catch(Exception $e){
            throw new RepositorioException("Erro ao adicionar item.", $e->getCode());
        }
    }

    public function coletarComId(int $id) : Item {
        try{
            $comando = "SELECT * FROM item WHERE id = :id LIMIT 1";
            $ps = $this->executarComandoSql($comando, ["id" => $id]);

            if($ps->rowCount() == 0)
                throw new DominioException('Item não encontrado.');

            $dadosItem = $ps->fetch();
            return $this->transformarEmItem($dadosItem);
        }catch(DominioException $e){
            throw $e;
        }catch(Exception $e){
            throw new RepositorioException("Erro ao obter item com id.", $e->getCode());
        }
    } 

    public function coletarComCodigo(string $codigo) : Item {
        try{
            $comando = "SELECT * FROM item WHERE codigo = :codigo LIMIT 1";
            $ps = $this->executarComandoSql($comando, ["codigo" => $codigo]);

            if($ps->rowCount() == 0)
                throw new DominioException('Item não encontrado.');

            $dadosItem = $ps->fetch();
            return $this->transformarEmItem($dadosItem);
        }catch(DominioException $e){
            throw $e;
        }catch(Exception $e){
            throw new RepositorioException("Erro ao obter item com código.", $e->getCode());
        }
    }
    
    private function transformarEmItem(array $dadosItem) : Item {
        return new Item($dadosItem['id'], $dadosItem['codigo'], $dadosItem['descricao'], $dadosItem['modelo'], $dadosItem['fabricante'], $dadosItem['valorPorHora'], $dadosItem['avarias'], $dadosItem['disponibilidade'], $dadosItem['tipo']);
    }   
}