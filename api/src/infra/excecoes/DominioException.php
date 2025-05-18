<?php

class DominioException extends RuntimeException{
    private array $problemas = [];

    public function getProblemas() : array{
        return $this->problemas;
    }

    public static function com(array $problemas) : DominioException{
        $e = new DominioException();
        $e->problemas = $problemas;
        return $e;
    }
}