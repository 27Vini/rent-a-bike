<?php

class RepositorioDevolucaoEmBDR extends RepositorioGenericoEmBDR implements RepositorioDevolucao{

    public function __construct(private PDO $pdo, private RepositorioLocacao $repositorioLocacao){
        parent::__construct($pdo);
    }

    
    public function coletarTodos(): array{
        try{
            $sql = "SELECT d.* FROM devolucao d
                    JOIN locacao l on l.id=d.locacao_id";
            
            $ps = $this->executarComandoSql($sql);
            $dadosDevolucao = $ps->fetchAll();
            
            $devolucoes = [];
            foreach($dadosDevolucao as $devolucao){
                $devolucoes[] = $this->transformarEmDevolucao($devolucao, $this->repositorioLocacao);
            }
    
            return $devolucoes;
        }catch( PDOException $e){
            throw new RepositorioException("Erro ao coletar devoluções.", $e->getCode());
        }

    }

    public function adicionar(Devolucao $devolucao) : void{
        try{
            $comando = "INSERT INTO devolucao (locacao_id,data_de_devolucao,valor_pago) VALUES (:locacao_id,:data_de_devolucao,:valor_pago)";
            $this->executarComandoSql($comando, ["locacao_id" => $devolucao->getLocacao()->getId(), "data_de_devolucao" => $devolucao->getDataDeDevolucao()->format('Y-m-d H:i:s'),
                "valor_pago" => $devolucao->getValorPago()
            ]);
        
            $devolucao->setId($this->ultimoIdAdicionado());
            $this->repositorioLocacao->marcarComoDevolvida($devolucao->getLocacao());
        }catch( PDOException $e){
            throw new RepositorioException("Erro ao adicionar devolução.", $e->getCode());
        } catch(Throwable $e){
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function coletarComId($id): Devolucao{
        try{
            $comando = "SELECT * FROM devolucao WHERE id=:id";
            $ps = $this->executarComandoSql($comando, ["id" => $id ]);
            
            $dadosDevolucao = $ps->fetch();
            if(count($dadosDevolucao) == 0){
                throw new DominioException("Nenhuma devolução encontrada.");
            }

            return $this->transformarEmDevolucao($dadosDevolucao, $this->repositorioLocacao);
        }catch(DominioException $e){
            throw $e;
        }catch( PDOException $e){
            throw new RepositorioException("Erro ao obter devolução de id: ".$id, $e->getCode());
        }
    }

    private function transformarEmDevolucao(array $dadosDevolucao, RepositorioLocacao $repositorioLocacao){
        try{
            $locacao = $repositorioLocacao->coletarComParametros(['id' => $dadosDevolucao['locacao_id']]);
            $devolucao = new Devolucao($dadosDevolucao['id'], $locacao[0], new DateTime($dadosDevolucao['data_de_devolucao']));
            $devolucao->setValorPago($dadosDevolucao['valor_pago']);

            return $devolucao;
        }catch(Throwable $e){
            throw new DominioException("Erro ao instanciar nova devolução.", $e->getCode());
        }
    }
}