<?php

class GestorLocacao {
    public function __construct(private RepositorioLocacao $repositorioLocacao, private RepositorioCliente $repositorioCliente, private RepositorioFuncionario $repositorioFuncionario, private Transacao $transacao){}

    public function salvarLocacao(array $dadosLocacao){
       try{
            $this->transacao->iniciar();
            $cliente = $this->repositorioCliente->coletarComId((int)$dadosLocacao['cliente']);
            $funcionario = $this->repositorioFuncionario->coletarComId((int)$dadosLocacao['funcionario']);
            
            $itensLocacao = [];
            foreach($dadosLocacao['itens'] as $itemLocacao){
                $item = $this->transformarEmItem($itemLocacao['item']);
                $itemLocacao = $this->transformarEmItemLocacao($itemLocacao, $item, $dadosLocacao['numeroDeHoras']);
                $itensLocacao[] = $itemLocacao;
            }   
            
            $locacao = new Locacao(0, $itensLocacao, $cliente, $funcionario, new DateTime(), $dadosLocacao['numeroDeHoras']);

            $problemas = $locacao->validar();
            if(count($problemas) > 0){
                throw new DominioException(implode('\n', $problemas));
            }

            $this->repositorioLocacao->adicionar($locacao);
            $this->transacao->finalizar();
        }catch(DominioException $e){
            $this->transacao->desfazer();
            throw $e;
        } catch(Exception $e){
            $this->transacao->desfazer();
            throw $e;
        }
    }

    private function transformarEmItem(array $dadosItem) : Item {
        $item = new Item($dadosItem['id'], $dadosItem['codigo'], $dadosItem['descricao'], $dadosItem['modelo'], $dadosItem['fabricante'], $dadosItem['valorPorHora'], $dadosItem['avarias'], $dadosItem['disponibilidade'], $dadosItem['tipo']);

        $problemas = $item->validar();
        if(count($problemas) > 0){
            throw new DominioException(implode('\n', $problemas));
        }

        return $item;
    }

    private function transformarEmItemLocacao(array $dadosItemLocacao, Item $item, int $horas) : ItemLocacao{
        $itemLocacao = new ItemLocacao(0, $item, $dadosItemLocacao['precoLocacao']);
        $itemLocacao->calculaSubtotal($horas);

        $problemas = $itemLocacao->validar();
        if(count($problemas)){
            throw new DominioException(implode('\n', $problemas));
        }

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
        try{
            foreach($parametros as &$p){
                $p = htmlspecialchars($p);
            }
            return $this->repositorioLocacao->coletarComParametros($parametros);
        }catch(Exception $e){
            throw $e;
        }
    }
}