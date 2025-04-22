<?php

class Locacao{
    private string | int $id;
    private string | int $itemId;
    private string | int $clienteId;
    private string | int $funcionarioId;
    private DateTime $entrada;
    private int $numeroDeHoras;
    private float $desconto;
    private float $valorTotal;
    private DateTime $previsaoDeEntrega;

    public function __construct(string|int $id, string|int $itemId, string|int $clienteId, string|int $funcionarioId, DateTime $entrada, int $numeroDeHoras, float $desconto, float $valorTotal, DateTime $previsaoDeEntrega){
        $this->id = $id;
        $this->itemId = $itemId;
        $this->clienteId = $clienteId;
        $this->funcionarioId = $funcionarioId;
        $this->entrada = $entrada;
        $this->numeroDeHoras = $numeroDeHoras;
        $this->desconto = $desconto;
        $this->valorTotal = $valorTotal;
        $this->previsaoDeEntrega = $previsaoDeEntrega;
    }

    public function getId(): string|int {
        return $this->id;
    }

    public function setId(string|int $id): void {
        $this->id = $id;
    }

    public function getItemId(): string|int {
        return $this->itemId;
    }

    public function setItemId(string|int $itemId): void {
        $this->itemId = $itemId;
    }

    public function getClienteId(): string|int {
        return $this->clienteId;
    }

    public function setClienteId(string|int $clienteId): void {
        $this->clienteId = $clienteId;
    }

    public function getFuncionarioId(): string|int {
        return $this->funcionarioId;
    }

    public function setFuncionarioId(string|int $funcionarioId): void {
        $this->funcionarioId = $funcionarioId;
    }

    public function getEntrada(): DateTime {
        return $this->entrada;
    }

    public function setEntrada(DateTime $entrada): void {
        $this->entrada = $entrada;
    }

    public function getNumeroDeHoras(): int {
        return $this->numeroDeHoras;
    }

    public function setNumeroDeHoras(int $numeroDeHoras): void {
        $this->numeroDeHoras = $numeroDeHoras;
    }

    public function getDesconto(): float {
        return $this->desconto;
    }

    public function setDesconto(float $desconto): void {
        $this->desconto = $desconto;
    }

    public function getValorTotal(): float {
        return $this->valorTotal;
    }

    public function setValorTotal(float $valorTotal): void {
        $this->valorTotal = $valorTotal;
    }

    public function getPrevisaoDeEntrega(): DateTime {
        return $this->previsaoDeEntrega;
    }

    public function setPrevisaoDeEntrega(DateTime $previsaoDeEntrega): void {
        $this->previsaoDeEntrega = $previsaoDeEntrega;
    }


    public function validar(): array{
        $problemas = [];
        $ids = [$this->id, $this->clienteId, $this->funcionarioId, $this->itemId];
        foreach($ids as $id){
            if($problemas = validarId($id)){
                break;
            }
        }

        if($this->entrada > new DateTime()->format("Y-m-d H:i:s")){
            array_push($problemas, "A data de entrada não pode ser posterior a atual.");
        }

        if($this->numeroDeHoras < 0){
            array_push($problemas, "O número de horas deve ser maior que 0.");
        }

        if($this->desconto < 0.0){
            array_push($problemas, "O desconto não pode ser menor que 0.");
        }

        if($this->valorTotal < 0.0){
            array_push($problemas,"Valor total não pode ser menor que 0.");
        }

        if($this->previsaoDeEntrega < $this->entrada){
            array_push($problemas, "A previsão de entrega deve ser posterior a data de entrada.");
        }

        return $problemas;
    }
}