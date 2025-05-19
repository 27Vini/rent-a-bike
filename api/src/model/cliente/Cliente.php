<?php


class Cliente implements \JsonSerializable{
    private int|string $id;
    private string $codigo;
    private string $cpf;
    private string $nome;

    private $foto;
	private const MIN_NOME = 2;
    private const MAX_NOME = 100;
    private const MSG_NOME = "O nome deve ter entre ".self::MIN_NOME." e ".self::MAX_NOME." caracteres.";

	private const TAM_CPF = 11;
	private const MSG_CPF = "O cpf deve ter ".self::TAM_CPF . " caracteres sem caracteres especiais.";
	private const TAM_CODIGO = 8;
	private const MSG_CODIGO = "O código deve ter ".self::TAM_CODIGO." caracteres.";

    public function __construct(int|string $id, string $codigo, string $cpf, string $nome, $foto){
		$this->id = $id;
		$this->codigo = $codigo;
		$this->cpf = $cpf;
		$this->nome = $nome;
		$this->foto = $foto;
	}

	public function getId(){
		return $this->id;
	}

	public function setId(string|int $id){
		$this->id = $id;
	}

	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo(string $codigo){
		$this->codigo = $codigo;
	}

	public function getCpf(){
		return $this->cpf;
	}

	public function setCpf(string $cpf){
		$this->cpf = $cpf;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setNome(string $nome){
		$this->nome = $nome;
	}

	public function getFoto(){
		return $this->foto;
	}

	public function setFoto($foto){
		$this->foto = $foto;
	}


	public function validar(): array{
		$problemas = [];
		$problemas = validarId($this->id);
		$tamNome = mb_strlen($this->nome);
		$tamCpf = mb_strlen($this->cpf);
		$tamCodigo = mb_strlen($this->codigo);

		if($tamNome < self::MIN_NOME || $tamNome > self::MAX_NOME){
			array_push($problemas, self::MSG_NOME);
		}
		if($tamCpf != self::TAM_CPF){
			array_push($problemas, self::MSG_CPF);
		}
		if($tamCodigo != self::TAM_CODIGO){
			array_push($problemas, self::MSG_CODIGO);
		}
		return $problemas;
	}

	public function jsonSerialize(): mixed {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'cpf' => $this->cpf,
            'nome' => $this->nome,
            'foto' => $this->foto
        ];
    }

}