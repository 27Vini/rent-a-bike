<?php 

class Hashador{
    function gerarSal(): string {
        return bin2hex( random_bytes( 15 ) );
    }
    private function adicionarPimenta( string $texto ): string {
        return '3493peyfkl,nc' . $texto . '2-0857rodiycjkxcv';
    }

    private function adicionarSal( string $texto, string $sal ): string {
        return $sal . $texto;
    }

    private function meuHash( string $texto ): string {
        return hash( 'sha512', $texto );
    }

    function criarSenha(string $senha, string $sal): string{
        return $this->meuHash( $this->adicionarSal( $this->adicionarPimenta( $senha ), $sal ) );
    }

}
