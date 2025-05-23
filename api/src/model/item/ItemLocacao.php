<?php
require_once __DIR__.'/../../infra/util/validarId.php';

class ItemLocacao implements \JsonSerializable {
    private int   $id = 0;
    private Item  $item;
    private float $precoLocacao;
    private float $subtotal;

    public function __construct(int $id, Item $item, float $precoLocacao){
        $this->id = $id;
        $this->item = $item;
        $this->precoLocacao = $precoLocacao;
        $this->subtotal = 0.0;
    }

    public function calculaSubtotal(int $horas) : float{
        if($horas < 0)
            throw new DominioException("Horas devem ser maior do que 0.");

        $this->subtotal = $this->precoLocacao * $horas;
        return $this->subtotal;
    }

    public function setId(int $id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setItem(Item $item){
        $this->item = $item;
    }

    public function getItem(){
        return $this->item;
    }

    public function setPrecoLocacao(float $precoLocacao){
        $this->precoLocacao = $precoLocacao;
    }

    public function getPrecoLocacao(){
        return $this->precoLocacao;
    }

    public function setSubtotal(float $subtotal){
        $this->subtotal = $subtotal;
    }

    public function getSubtotal(){
        return $this->subtotal;
    }

    public function validar() : array {
        $problemas = [];
        $problemas = validarId($this->id);

        if($this->precoLocacao <= 0.0){
            array_push($problemas, "O preço da locação deve ser maior que 0.0 .");
        }

        if($this->subtotal <= 0.0){
            array_push($problemas, "O subtotal deve ser maior que 0.0 .");
        }

        return $problemas;
    }

    public function jsonSerialize(): mixed {
        return [
            'id'            => $this->id,
            'item'          => $this->item,
            'precoLocacao'  => $this->precoLocacao,
            'subtotal'      => $this->subtotal
        ];
    }
}