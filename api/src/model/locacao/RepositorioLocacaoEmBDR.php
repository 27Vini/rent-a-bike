<?php


class RepositorioLocacaoEmBDR extends RepositorioGenericoEmBDR{
    public function __construct(PDO $pdo){
        parent::__construct($pdo);
    }

    public function adicionar(Locacao $locacao) : void{
        $comando = "INSERT INTO locacao(entrada,numero_de_horas,desconto,valor_total,previsao_de_entrega, cliente_id, funcionario_id) VALUES (:entrada,:numero_de_horas,:desconto,:valor_total,:previsao_de_entrega, :cliente_id, :funcionario_id)";
        $this->executarComandoSql($comando, ["entrada" => $locacao->getEntrada(), "numero_de_horas" => $locacao->getNumeroDeHoras(),
                                                              "desconto" => $locacao->getDesconto(), "valor_total" => $locacao->getValorTotal(),
                                                              "previsao_de_entrega" => $locacao->getPrevisaoDeEntrega(), "cliente_id" => $locacao->getClienteId(),
                                                              "funcionario_id" => $locacao->getFuncionarioId()
                                                            ]);
    
        $locacao->setId($this->ultimoIdAdicionado());
    }

    public function coletarComId($id): null | Locacao{
        $comando = "SELECT * FROM locacao WHERE id=:id";
        $ps = $this->executarComandoSql($comando, ["id" => $id ]);
        $l = $ps->fetchObject(Locacao::class) ?: null;
        return $l;
    }
}