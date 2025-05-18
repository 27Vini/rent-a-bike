<?php

interface RepositorioItemLocacao {
    /**
     * @param int $idLocacao
     * @return array<ItemLocacao>
     */
    public function coletarComIdLocacao(int $idLocacao) : array;
}