<?php

class RepositorioLocacaoEmBDR extends RepositorioGenericoEmBDR implements RepositorioLocacao{

    public function __construct(private PDO $pdo){
        parent::__construct($pdo);
    }

    public function adicionar(Locacao $locacao) : void{
        $comando = "INSERT INTO locacao(entrada,numero_de_horas,desconto,valor_total,previsao_de_entrega, cliente_id, funcionario_id) VALUES (:entrada,:numero_de_horas,:desconto,:valor_total,:previsao_de_entrega, :cliente_id, :funcionario_id)";
        $dados = [
            "entrada" => $locacao->getEntrada(), 
            "numero_de_horas" => $locacao->getNumeroDeHoras(), 
            "desconto" => $locacao->getDesconto(), 
            "valor_total" => $locacao->getValorTotal(),
            "previsao_de_entrega" => $locacao->getPrevisaoDeEntrega(), 
            "cliente_id" => $locacao->getCliente()->getId(),
            "funcionario_id" => $locacao->getFuncionario()->getId()
        ];

        $this->executarComandoSql($comando, $dados);
    
        $locacao->setId($this->ultimoIdAdicionado());
    }

    public function coletarComId($id): null | Locacao{
        $comando = "SELECT * FROM locacao WHERE id=:id";
        $ps = $this->executarComandoSql($comando, ["id" => $id ]);
        $l = $ps->fetchObject(Locacao::class) ?: null;
        return $l;
    }

    /**
     * @return array<Locacao>
     */
    public function coletarTodos() : array{
        try{
            $parametros = [];
            $sql = "SELECT l.id, l.entrada, l.numero_de_horas, l.desconto, l.valor_total, l.previsao_de_entrega, c.id as id_cliente, c.codigo as codigo_cliente, c.nome as nome_cliente, c.cpf, c.foto, f.id as id_funcionario, f.nome as nome_funcionario
            FROM locacao l 
            JOIN cliente c ON l.cliente_id = c.id 
            JOIN funcionario f ON l.funcionario_id = f.id";

            $ps = $this->executarComandoSql($sql, $parametros);
            $dadosLocacoes = $ps->fetchAll();

            $locacoes = [];
            $repositorioItemLocacaoBDR = (new RepositorioItemLocacaoEmBDR($this->pdo));
            foreach($dadosLocacoes as $dados){
                $cliente = new Cliente($dados['id_cliente'], $dados['codigo_cliente'], $dados['cpf'], $dados['nome'], $dados['foto']);
                $funcionario = new Funcionario($dados['id_funcionario'], $dados['nome_funcionario']);
                $itensLocacao = $repositorioItemLocacaoBDR->coletarComIdLocacao($dados['id']);

                $locacao = new Locacao($dados['id'], $itensLocacao, $cliente, $funcionario, DateTime::createFromFormat("Y-m-d H:i:s", $dados['entrada']), $dados['numero_de_horas']);
                $locacoes[] = $locacao;
            }

            return $locacoes;

        } catch( PDOException $e){
            throw new RepositorioException($e->getMessage(), $e->getCode());
        }
    }
}