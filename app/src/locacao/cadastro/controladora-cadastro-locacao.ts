import { GestorLocacao } from "../gestor-locacao";
import { VisaoCadastroLocacao } from "./visao-cadastro-locacao";

export class ControladoraCadastroLocacao{
    private visao:VisaoCadastroLocacao;
    private gestor:GestorLocacao;

    constructor(visao:VisaoCadastroLocacao){
        this.visao = visao;
        this.gestor = new GestorLocacao();
    }
}