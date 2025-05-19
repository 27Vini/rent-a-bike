<?php

class GestorLocacao {
    public function __construct(private RepositorioLocacao $repositorioLocacao, private RepositorioCliente $repositorioCliente, private RepositorioFuncionario $repositorioFuncionario){
        
    }

    public function salvarLocacao(array $dadosLocacao){
        $cliente = $this->repositorioCliente->coletarComId((int)$dadosLocacao['cliente']);
        $funcionario = $this->repositorioFuncionario->coletarComId((int)$dadosLocacao['funcionario']);

        $itensLocacao = [];
        foreach($dadosLocacao['itensLocacao'] as $itemLocacao){
            $item = $this->transformarEmItem($itemLocacao['item']);
            $itemLocacao = $this->transformarEmItemLocacao($itemLocacao, $item);
            $itensLocacao[] = $itemLocacao;
        }

        $locacao = new Locacao(0, $itensLocacao, $cliente, $funcionario, new DateTime(), $dadosLocacao['horas']);
        $problemas = $locacao->validar();
        if(count($problemas) > 0)
            throw DominioException::com($problemas);

        $this->repositorioLocacao->adicionar($locacao);
    }

    private function transformarEmItem(array $dadosItem) : Item {
        $item = new Item($dadosItem['item']['id'], $dadosItem['item']['codigo'], $dadosItem['item']['descricao'], $dadosItem['item']['modelo'], $dadosItem['item']['fabricante'], $dadosItem['item']['valorPorHora'], $dadosItem['item']['avarias'], $dadosItem['item']['disponibilidade'], $dadosItem['item']['tipo']);

        $problemas = $item->validar();
        if(count($problemas) > 0)
            throw DominioException::com($problemas);

        return $item;
    }

    private function transformarEmItemLocacao(array $dadosItemLocacao, Item $item) : ItemLocacao{
        $itemLocacao = new ItemLocacao(0, $item, $dadosItemLocacao['precoLocacao']);
        $problemas = $itemLocacao->validar();

        if(count($problemas))
            throw DominioException::com($problemas);

        return $itemLocacao;
    }

    /**
     * Coleta todas as locações
     * @return array<Locacao>
     */
    public function coletarTodos() : array {
        $locacoes = [];
        $locacoes = $this->repositorioLocacao->coletarTodos();

        return $locacoes;
    }

    /**
     * Coletar com id ou array de parâmetros
     * @param string|array $parametros
     * @return void
     */
    public function coletarCom(array $parametros): array | Locacao{
        foreach($parametros as &$p){
            $p = htmlspecialchars($p);
        }
        return $this->repositorioLocacao->coletarComParametros($parametros);
    }
}