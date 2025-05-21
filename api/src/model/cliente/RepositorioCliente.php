<?php

interface RepositorioCliente {
    /**
     * Busca o cliente pelo Id informado
     * @param int id
     * @return Cliente
     */
    public function coletarComId(int $id) : Cliente;

    /**
     * Busca o cliente que possui o Código ou o CPF informado
     * @param string codigoOuCpf
     * @return Cliente
     */
    public function coletarComCodigoOuCpf($codigoCpf) : Cliente;
}