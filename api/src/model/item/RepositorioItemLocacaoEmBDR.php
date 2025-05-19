<?php

class RepositorioItemLocacaoEmBDR extends RepositorioGenericoEmBDR implements RepositorioItemLocacao {
    public function __construct(private PDO $pdo){
        parent::__construct($pdo);
    }

    public function coletarComIdLocacao(int $idLocacao): array{
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
    }
}