import { ErrorDominio } from "../../../infra/ErrorDominio";
import { GestorLocacao } from "../gestor-locacao";
import { VisaoLocacao } from "./visao-listagem-locacao";

export class ControladoraListagemLocacao{
    private gestor:GestorLocacao;
    private visao:VisaoLocacao

    constructor(visao){
        this.visao = visao;
        this.gestor = new GestorLocacao();
    }

    async obterLocacoes(){
        try{
            const locacoes = await this.gestor.coletarLocacoes();
            return locacoes;
        }catch(error){
            if(error instanceof ErrorDominio)
                this.visao.exibirMensagens(error.getProblemas());
            else 
                this.visao.exibirMensagens([error.message]);
        }
    }
}