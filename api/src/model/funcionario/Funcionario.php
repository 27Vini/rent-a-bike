<?php

class Funcionario implements \JsonSerializable{
    const TAM_MAX_NOME = 100;
    const TAM_MIN_NOME = 2;


    private int $id;
    private string $nome;

    public function __construct(int|null $id, string $nome){
        $this->id = $id;
        $this->nome = $nome;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function getId(): int{
        return $this->id;
    }

    public function setNome(string $nome): void{
        $this->nome = $nome;
    }

    public function getNome(): string{
        return $this->nome;
    }

    /**
     * Valida dados de funcionário
     * @return string[]
     */
    public function validar() : array{
        $problemas = [];
        if(mb_strlen($this->nome) > self::TAM_MAX_NOME || mb_strlen($this->nome) < self::TAM_MIN_NOME){
            $problemas[] = "O tamanho do nome deve estar entre ". self::TAM_MAX_NOME ." e ".self::TAM_MIN_NOME;
        }

        return $problemas;
    }

    /**
     * Serializa objeto para JSON para manuseio da API
     * @return array{id: int, nome: string}
     */
    public function jsonSerialize(): mixed {
        return [
            'id' => $this->id,
            'nome' => $this->nome
        ];
    }
}