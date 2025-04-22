<?php

class RepositorioItemEmBDR extends RepositorioGenericoEmBDR {
    public function __construct(PDO $pdo){
        parent::__construct($pdo);
    }
    
    public function adicionar(Item $item) : void{
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
    }

    public function coletarComId(int $id) : null | Item {
        $comando = "SELECT * FROM item WHERE id = :id LIMIT 1";
        $ps = $this->executarComandoSql($comando, ["id" => $id]);

        $item = $ps->fetchObject(Item::class); 
        return $item ?: null;
    } 

    public function coletarComCodigo(string $codigo) : null | Item {
        $comando = "SELECT * FROM item WHERE codigo = :codigo LIMIT 1";
        $ps = $this->executarComandoSql($comando, ["codigo" => $codigo]);

        $item = $ps->fetchObject(Item::class); 
        return $item ?: null;
    } 
}