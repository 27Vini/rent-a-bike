<?php

class RepositorioDevolucaoEmBDR extends RepositorioGenericoEmBDR{

    public function __construct(PDO $pdo){
        parent::__construct($pdo);
    }

    public function adicionar(Devolucao $devolucao) : void{
        $comando = "INSERT INTO devolucao (locacao_id,data_de_devolucao,valor_pago) VALUES (:locacao_id,:data_de_devolucao,:valo_pago)";
        $this->executarComandoSql($comando, ["locacao_id" => $devolucao->getLocacaoId(), "data_de_devolucao" => $devolucao->getDataDeDevolucao(),
                                                              "valor_pago" => $devolucao->getValorPago()
                                                            ]);
    
        $devolucao->setId($this->ultimoIdAdicionado());
    }

    public function coletarComId($id): null | Devolucao{
        $comando = "SELECT * FROM devolucao WHERE id=:id";
        $ps = $this->executarComandoSql($comando, ["id" => $id ]);
        $c = $ps->fetchObject(Devolucao::class) ?: null;
        return $c;
    }


}