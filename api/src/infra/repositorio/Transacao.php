<?php

interface Transacao {

    public function iniciar();
    public function finalizar();
    public function desfazer();

}