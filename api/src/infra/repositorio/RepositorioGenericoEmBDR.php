<?php


abstract class RepositorioGenericoEmBDR { 
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function coletarTodos($sql, $class, $parametros): array{
        try{
            $ps = $this->pdo->prepare($sql);
            $ps->setFetchMode( PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);
            $ps->execute($parametros);
            return $ps->fetchAll();
        } catch( PDOException $e){
            throw new RepositorioException($e->getMessage(), $e->getCode());
        }
    }

    public function removerComId(int $id, $nomeDaTabela) : bool{
        try{
            $ps = $this->pdo->prepare("DELETE FROM $nomeDaTabela WHERE id= :id");
            $ps->execute(["id" => $id]);
            return $ps->rowCount() > 0;
        } catch(PDOException $e){
            throw new RepositorioException($e->getMessage(), $e->getCode());
        }
    }
    
    public function ultimoIdAdicionado() : int{
        return (int) $this->pdo->lastInsertId();   
    }

    public function executarComandoSql( string $sql, array $parametros = [] ): PDOStatement {
        try {
            $ps = $this->pdo->prepare($sql);
            $ps->execute($parametros);
            return $ps;
        } catch ( PDOException $e ) {
            throw new RepositorioException($e->getMessage(), $e->getCode());
        }
    }

    

}



?>