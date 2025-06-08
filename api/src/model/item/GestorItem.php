<?php

class GestorItem{
    public function __construct(private RepositorioItem $repositorioItem){}

    /**
     * Coleta um item com o código informado
     * @param string $codigo
     * @return Item
     * @throws Exception
     */
    public function coletarComCodigo(string $codigo) : Item{
        try{
            $item = $this->repositorioItem->coletarComCodigo($codigo);

            return $item;
        }catch(Exception $e){
            throw $e;
        }
    }

    /**
     * Coleta dados dos itens para o relatório de Itens Alugados
     * @param array<string,string> $parametros
     * @return array<ItemRelatorioDTO>
     * @throws Exception
     */
    public function coletarItensParaRelatorio(array $parametros) : array {
        $dataInicial = !empty($parametros['dataInicial']) ? (new DateTime($parametros['dataInicial'])) : new DateTime('first day of this month');
        $dataFinal = !empty($parametros['dataFinal']) ? (new DateTime($parametros['dataFinal'])) : new DateTime('last day of this month');

        if($dataInicial > new DateTime())
            throw new DominioException("Data inicial não deve ser maior do que a data atual");
        if($dataFinal < $dataInicial)
            throw new DominioException("Data final não deve ser menor do que a data inicial");

        try{
            $dataInicialString = $dataInicial->format("Y-m-d H:i:s");
            $dataFinalString = $dataFinal->format("Y-m-d H:i:s");

            $dadosRelatorio = $this->repositorioItem->coletarDadosParaRelatorio($dataInicialString, $dataFinalString);
            $itens = $dadosRelatorio['itens'];
            $totalLocacoes = $dadosRelatorio['totalLocacoes'];

            $dadosItensRelatorio = [];
            if(count($itens) > 10){
                for($i = 0; $i < 10; $i++){
                    $dadosItensRelatorio[] = $this->gerarItemRelatorioDTO($itens[$i]['qtdVezesAlugado'], $totalLocacoes, $itens[$i]['descricao']);
                }

                $arrayOutros = array_slice($itens, 10);
                $somatorioOutros = array_sum(array_column($arrayOutros, 'qtdVezesAlugado'));
                $dadosItensRelatorio[] = $this->gerarItemRelatorioDTO((int)$somatorioOutros, $totalLocacoes, "Outros");
            } else {                
                foreach($itens as $item){
                    $dadosItensRelatorio[] = $this->gerarItemRelatorioDTO($item['qtdVezesAlugado'], $totalLocacoes, $item['descricao']);
                }
            }

            return $dadosItensRelatorio;
        }catch(Exception $e){
            throw $e;
        }
    }

    /**
     * Retorna um objeto de ItemRelatorioDTO
     * @param string|int $qtdVezesAlugado
     * @param string $qtdTotalLocacoes
     * @param string $descricao
     * @return ItemRelatorioDTO
     */
    private function gerarItemRelatorioDTO(string|int $qtdVezesAlugado, string $qtdTotalLocacoes, string $descricao) : ItemRelatorioDTO{
        $porcentagem = intval($qtdVezesAlugado)/intval($qtdTotalLocacoes);
        return new ItemRelatorioDTO(intval($qtdVezesAlugado), $descricao, floatval($porcentagem));
    }
}