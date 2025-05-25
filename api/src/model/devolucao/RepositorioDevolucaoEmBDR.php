<?php

class RepositorioDevolucaoEmBDR extends RepositorioGenericoEmBDR implements RepositorioDevolucao{

    public function __construct(PDO $pdo, private RepositorioLocacao $repositorioLocacao){
        parent::__construct($pdo);
    }

    /**
     * Coleta todas as devoluções
     * @throws \RepositorioException
     * @return Devolucao[]
     */
    public function coletarTodos(): array{
        try{
            $sql = "SELECT d.* FROM devolucao d
                    JOIN locacao l on l.id=d.locacao_id
                    ORDER BY d.data_de_devolucao DESC";
            
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

    /**
     * Adiciona devolução ao banco
     * @param Devolucao $devolucao
     * @throws \RepositorioException
     * @throws \Exception
     * @return void
     */
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

    /**
     * Coleta devolução com o ID.
     * @param int | string $id
     * @throws \DominioException
     * @throws \RepositorioException
     * @return Devolucao
     */
    public function coletarComId(int | string $id): Devolucao{
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

    /**
     * Transforma um array de dados em um objeto de Devolução
     * @param array<string,string> $dadosDevolucao
     * @param RepositorioLocacao $repositorioLocacao
     * @throws \DominioException
     * @return Devolucao
     */
    private function transformarEmDevolucao(array $dadosDevolucao, RepositorioLocacao $repositorioLocacao){
        try{
            $locacao = $repositorioLocacao->coletarComParametros(['id' => $dadosDevolucao['locacao_id']]);
            $devolucao = new Devolucao($dadosDevolucao['id'], $locacao[0], new DateTime($dadosDevolucao['data_de_devolucao']));
            $devolucao->setValorPago((float) $dadosDevolucao['valor_pago']);

            return $devolucao;
        }catch(Throwable $e){
            throw new DominioException("Erro ao instanciar nova devolução.", $e->getCode());
        }
    }
}