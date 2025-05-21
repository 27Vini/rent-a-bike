<?php

enum TipoItem: string {
    case BICICLETA = 'bicicleta';
    case EQUIPAMENTO = 'equipamento';

    private static function toArray() : array {
        return [
            self::BICICLETA->value,
            self::EQUIPAMENTO->value
        ];
    }


    public static function isValid(string $tipo) : bool {
        return in_array($tipo, self::toArray());
    }
}