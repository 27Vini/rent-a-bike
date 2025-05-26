<?php

class GestorDevolucao{
    public function __construct(private RepositorioDevolucao $repositorioDevolucao, private RepositorioLocacao $repositorioLocacao, private RepositorioFuncionario $repositorioFuncionario ,private Transacao $transacao){

    }

    /**
     * Salva uma devolução
     * @param array<string,string> $dados
     * @return void
     */
    public function salvarDevolucao(array $dados): void{
        $dataDeDevolucaoString = htmlspecialchars($dados["dataDeDevolucao"] ?? '');
        $locacaoId = htmlspecialchars( $dados["locacao"] ?? '' );
        if(!$dataDeDevolucaoString || !$locacaoId){
            throw new DominioException("Locação ou data de devolução não foram enviados.");
        }
        try{
            $this->transacao->iniciar();
            $locacao = $this->repositorioLocacao->coletarComParametros(['id' => $locacaoId, 'verificarAtivo' => '1']);
            if($locacao == null){
                throw new DominioException("Locação não encontrada com id " . $locacaoId);
            }
            $funcionario = $this->repositorioFuncionario->coletarComId(intval($dados['funcionario']));
            if($funcionario == null){
                throw new DominioException("Funcionário não encontrado com id " . $funcionario);
            }
    
            $devolucao = $this->instanciarDevolucao($locacao[0], $dataDeDevolucaoString, $funcionario);
        
    
            $this->repositorioDevolucao->adicionar($devolucao);
            $this->transacao->finalizar();
        } catch(Exception $e){
            $this->transacao->desfazer();
            throw $e;
        }
    }

    /**
     * Coleta todas as devoluções
     * @return array<Devolucao>
     */
    public function coletarDevolucoes():array{
        try{
            return $this->repositorioDevolucao->coletarTodos();
        }catch(Exception $e){
            throw $e;
        }
    }

    /**
     * Instaciar devolução.
     * @throws DominioException
     * @param Locacao $locacao
     * @param Funcionario $funcionario
     * @param string $dataDeDevolucaoString
     * @return Devolucao
     */
    private function instanciarDevolucao(Locacao $locacao, string $dataDeDevolucaoString, Funcionario $funcionario): Devolucao{
        $dataDeDevolucao = $this->transformarData($dataDeDevolucaoString);
        $devolucao = new Devolucao('1', $locacao, $dataDeDevolucao, $funcionario);

        $valorASerPago = $devolucao->calcularValorASerPago();
    
        $devolucao->setValorPago($valorASerPago);
        $problemas = $devolucao->validar();
        if(!empty($problemas)){
            throw new DominioException(implode('\n', $problemas));
        }
        return $devolucao;
    }

    /**
     * Transforma data em string para DateTime
     * @param string $data
     * @return DateTime
     */
    private function transformarData(string $data): DateTime{
        $dataDevolucao = new DateTime($data);
        $dataDevolucao->setTimezone(new DateTimeZone('America/Sao_Paulo'));
        //error_log('ALOOO'.$dataDevolucao->format('Y-m-d H:i:s'));
        $dataFormatada = $dataDevolucao->format('Y-m-d H:i:s');
        return new DateTime($dataFormatada);
    }
}