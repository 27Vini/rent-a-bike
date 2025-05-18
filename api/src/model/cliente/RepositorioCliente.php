<?php

interface RepositorioCliente {
    /**
     * @param int id
     * @return Cliente | null
     */
    public function coletarComId(int $id) : Cliente | null;
}