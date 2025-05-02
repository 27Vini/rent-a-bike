import { VisaoListagemDevolucao } from "./visao-listagem-devolucao.js";
import {GestorListagemDevolucao} from './gestor-listagem-devolucao.js';

export class ControladoraListagemDevolucao{

    private visao : VisaoListagemDevolucao;
    private gestor : GestorListagemDevolucao

    public constructor(visao : VisaoListagemDevolucao){
        this.visao = visao;
        this.gestor = new GestorListagemDevolucao()
    }

    async obterDevolucoes(){
        try{
            const devolucoes = await this.gestor.coletarDevolucoes()
            return devolucoes;
        }catch(error){
            this.visao.exibirMensagem(error.message);
        }
    }

}