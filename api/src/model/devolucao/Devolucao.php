<?php
require_once __DIR__.'/../../infra/util/validarId.php';
class Devolucao implements \JsonSerializable{
    private string | int $id;
    private Locacao $locacao;
    private DateTime $dataDeDevolucao;
    private float $valorPago;

    public function  __construct(string | int $id, Locacao $locacao, DateTime $data) {
        $this->id = $id;
        $this->locacao = $locacao;
        $this->dataDeDevolucao = $data;
    }

    public function getId() : int | string{
        return $this->id;
    }

    public function getLocacao(): Locacao{
        return $this->locacao;
    }

    public function getDataDeDevolucao(): DateTime{
        return $this->dataDeDevolucao;
    }

    public function getValorPago(): float{
        return $this->valorPago;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function setLocacao(Locacao $locacao): void{
        $this->locacao = $locacao;
    }

    public function setDataDeDevolucao(DateTime $dataDeDevolucao): void{
        $this->dataDeDevolucao = $dataDeDevolucao;
    }

    public function setValorPago(float $valorPago): void{
        $this->valorPago = $valorPago;
    }

    /**
	 * Valida dados do devolução.
	 * @return array<string>
	 */
    public function validar(): array {
    $problemas = validarId($this->id);

    if ($this->dataDeDevolucao > new DateTime()) {
        array_push($problemas, "A data de devolução deve ser inferior ou igual a data atual.");
    }

    if($this->dataDeDevolucao < $this->locacao->getEntrada()){
        array_push($problemas, "A data de devolução não pode ser menor do que a data de locação.");
    }

    if ($this->valorPago <= 0.0) {
        array_push($problemas, "O valor pago deve ser maior que 0.");
    }

    return $problemas;
}


    /**
     * Calcula valor a ser pago
     * @return float
     */
    public function calcularValorASerPago(): float{
        $total = 0;
        $horasCorridas = $this->calcularHorasCorridas();
        /**
         * @var ItemLocacao $item
         */
        foreach($this->locacao->getItensLocacao() as $item){
            $total += $item->calculaSubtotal($horasCorridas);
        }
        $desconto = $this->calculaDesconto($total, $horasCorridas);
        return $total - $desconto;
    }

    /**
     * Calcula horas corridas da devolução
     * @return int
     */
    public function calcularHorasCorridas(): int{
        $dataLocacao = $this->locacao->getEntrada();

        $diff = $this->dataDeDevolucao->diff($dataLocacao);
        $horas = $diff->h;
        $horas += $diff->days * 24;
    
        $numeroDeHoras = $this->locacao->getNumeroDeHoras();

        $horasCorridas = 0;
        if($horas == 0){
            $horas = 1;
        }if ($horas >= $numeroDeHoras && $horas <= $numeroDeHoras + 0.25) {
            $horasCorridas = $numeroDeHoras;
        } else {
            $horasCorridas = ceil($horas);
        }
        return intval($horasCorridas);
    }

    /**
     * Calcula desconto
     * @param float $total
     * @param int $horasCorridas
     * @return float
     */
    public function calculaDesconto(float $total, int $horasCorridas): float{
        if ($horasCorridas > 2) {
            $desconto = round($total * 0.1, 2);
            return $desconto;
        }
        return 0.0;
    }

     /**
      * Serializa em JSON para manuseio da API
      * @return array{dataDeDevolucao: string, id: int|string, locacao: Locacao|null, valorPago: float}
      */
     public function jsonSerialize(): mixed {
        return [
            'id'                => $this->id,
            'locacao'           => $this->locacao,
            'dataDeDevolucao'   => $this->dataDeDevolucao->format('Y-m-d H:i:s'),
            'valorPago'         => $this->valorPago
        ];
    }
}
