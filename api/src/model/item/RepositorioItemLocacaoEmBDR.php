<?php

class RepositorioItemLocacaoEmBDR extends RepositorioGenericoEmBDR implements RepositorioItemLocacao {
    public function __construct(private PDO $pdo){
        parent::__construct($pdo);
    }

    public function coletarComIdLocacao(int $idLocacao): array{
        try{
            $sql = "SELECT * FROM item_locacao WHERE locacao_id = :idLocacao";
            $ps = $this->executarComandoSql($sql, ["idLocacao" => $idLocacao]);

            $dadosItensLocacao = $ps->fetchAll();
            $itensLocacao = [];

            $repItem = (new RepositorioItemEmBDR($this->pdo));
            foreach($dadosItensLocacao as $il){
                $item = $repItem->coletarComId($il['item_id']);
                $itemLocacao = new ItemLocacao($il['id'], $item, $il['precoLocacao']);
                $itemLocacao->setSubtotal($il['subtotal']);

                $itensLocacao[] = $itemLocacao;
            }
        
            return $itensLocacao;
        }catch(DominioException $e){
            throw $e;
        }catch(Exception $e){
            throw new RepositorioException("Erro ao coletar item com id de locação.", $e->getCode());
        }
    }

    public function adicionar(ItemLocacao $itemLocacao, int $idLocacao){
        try{
            $comando = "INSERT INTO item_locacao(item_id, locacao_id, precoLocacao, subtotal) VALUES (:idItem, :idLocacao, :precoLocacao, :subtotal)";
            $dados = [
                "idItem"        => $itemLocacao->getItem()->getId(),
                "idLocacao"     => $idLocacao,
                "precoLocacao"  => $itemLocacao->getPrecoLocacao(),
                "subtotal"      => $itemLocacao->getSubtotal()
            ];

            $this->executarComandoSql($comando, $dados);
        }catch(Exception $e){
            throw new RepositorioException("Erro ao cadastrar item de locação. ", $e->getCode());
        }
    }
}