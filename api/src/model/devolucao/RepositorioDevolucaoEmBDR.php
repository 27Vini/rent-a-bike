<?php

class RepositorioDevolucaoEmBDR extends RepositorioGenericoEmBDR implements RepositorioDevolucao{

    public function __construct(private PDO $pdo){
        parent::__construct($pdo);
    }

    
    public function coletarTodos(): array{
        try{
            $sql = "SELECT d.* FROM devolucao d
                    JOIN locacao l on l.id=d.locacao_id";
            
            $ps = $this->executarComandoSql($sql);
            $dadosDevolucao = $ps->fetchAll();
            $devolucoes = [];
            $repositorioLocacao = new RepositorioLocacaoEmBDR($this->pdo);
            foreach($dadosDevolucao as $devolucao){
                $locacao = $repositorioLocacao->coletarComParametros(['id' => $dadosDevolucao['locacao_id']]);
                $devolucao = new Devolucao($dadosDevolucao['id'], $locacao[0], $dadosDevolucao['data_de_devolucao']);
                $devolucoes[] = $devolucao;
            }
    
            return $devolucoes;
        }catch( PDOException $e){
            throw new RepositorioException($e->getMessage(), $e->getCode());
        }

    }

    public function adicionar(Devolucao $devolucao) : void{
        try{
            $comando = "INSERT INTO devolucao (locacao_id,data_de_devolucao,valor_pago) VALUES (:locacao_id,:data_de_devolucao,:valor_pago)";
            $this->executarComandoSql($comando, ["locacao_id" => $devolucao->getLocacao()->getId(), "data_de_devolucao" => $devolucao->getDataDeDevolucao()->format('Y-m-d H:i:s'),
                                                                  "valor_pago" => $devolucao->getValorPago()
                                                                ]);
        
            $devolucao->setId($this->ultimoIdAdicionado());
        }catch( PDOException $e){
            throw new RepositorioException($e->getMessage(), $e->getCode());
        } catch(Throwable $e){
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function coletarComId($id): null | Devolucao{
        try{
            $comando = "SELECT * FROM devolucao WHERE id=:id";
            $ps = $this->executarComandoSql($comando, ["id" => $id ]);
            $c = $ps->fetchObject(Devolucao::class) ?: null;
            return $c;
        }catch( PDOException $e){
            throw new RepositorioException($e->getMessage(), $e->getCode());
        }
    }


}