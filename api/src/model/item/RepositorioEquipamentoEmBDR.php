<?php 

class RepositorioEquipamentoEmBDR extends RepositorioGenericoEmBDR {
    public function adicionar(Equipamento $equip) : void {
        $comando = "INSERT INTO equipamento (idItem) VALUES (:idItem)";
        $this->executarComandoSql($comando, ["idItem" => $equip->getIdItem()]);

        $equip->setId($this->ultimoIdAdicionado());
    }

    public function coletarComId(int $id) : null | Equipamento {
        $comando = "SELECT e.*, i.* FROM equipamento e JOIN item  i ON item.id = e.idItem WHERE id = :id LIMIT 1";
        $ps = $this->executarComandoSql($comando, ["id" => $id]);

        $item = $ps->fetchObject(Equipamento::class); 
        return $item ?: null;
    }
}