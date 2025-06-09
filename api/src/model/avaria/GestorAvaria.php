<?php

class GestorAvaria{

    public function __construct(private RepositorioAvaria $repositorioAvaria, private RepositorioItem $repositorioItem, private RepositorioFuncionario $repositorioFuncionario ,private Transacao $transacao){

    }

    /**
     * Salva uma avaria
     * @param array<string,string> $dados
     * @return void
     */
    public function salvarAvaria(array $dados): void{
        $lancamentoString = htmlspecialchars($dados["lancamento"] ?? '');
        $itemId = htmlspecialchars( $dados["item"] ?? '');
        $funcionarioId = htmlspecialchars($dados['funcionario'] ?? '');
        $descricao =  htmlspecialchars($dados['descricao'] ?? '');
        $foto = htmlspecialchars($dados['foto'] ?? '');
        $valor = htmlspecialchars($dados['valor'] ?? '');

        try{
            $this->transacao->iniciar();
            $funcionario = $this->repositorioFuncionario->coletarComId(intval($funcionarioId));
            if($funcionario == null){
                throw new DominioException("Funcionário não encontrado com id " . $funcionarioId);
            }
            $item = $this->repositorioItem->coletarComId(intval($itemId));
            if($item == null){
                throw new DominioException("Item não encontrado com id " . $itemId);
            }
    
            $avaria = $this->instanciarAvaria($funcionario, $item, $lancamentoString, $descricao, $foto, $valor);
        
    
            $this->repositorioAvaria->adicionar($avaria);
            $this->transacao->finalizar();
        } catch(Exception $e){
            $this->transacao->desfazer();
            throw $e;
        }
    }

    /**
     * Coleta todas as avarias
     * @return array<Avaria>
     */
    public function coletarAvarias():array{
        try{
            return $this->repositorioAvaria->coletarTodos();
        }catch(Exception $e){
            throw $e;
        }
    }

    /**
     * Instaciar avaria.
     * @throws DominioException
     * @param Funcionario $funcionario
     * @param Item $item
     * @param string $lancamentoString
     * @param string $descricao
     * @param string $foto
     * @param string $valor
     * @return Avaria
     */
    private function instanciarAvaria(Funcionario $funcionario, Item $item, string $lancamentoString, string $descricao, string $foto, string $valor): Avaria{
        $lancamento = $this->transformarData($lancamentoString);
        $avaria = new Avaria("1", $lancamento, $funcionario, $descricao, $foto, (float) $valor, $item);
        
        $problemas = $avaria->validar();
        if(!empty($problemas)){
            throw new DominioException(implode('\n', $problemas));
        }
        return $avaria;
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