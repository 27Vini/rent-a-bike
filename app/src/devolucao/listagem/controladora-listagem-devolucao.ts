import { VisaoListagemDevolucao } from "./visao-listagem-devolucao.js";
import {GestorDevolucao} from '../gestor-devolucao.js';

export class ControladoraListagemDevolucao{

    private visao : VisaoListagemDevolucao;
    private gestor : GestorDevolucao

    public constructor(visao : VisaoListagemDevolucao){
        this.visao = visao;
        this.gestor = new GestorDevolucao()
    }

    async obterDevolucoes(){
        try{
            const devolucoes = await this.gestor.coletarDevolucoes()
            if(devolucoes.length == 0){
                this.visao.exibirMensagens(['Nenhuma devolução encontrada.'], true);
            }
            return devolucoes;
        }catch(error){
            this.visao.exibirMensagens([error.message], true);
        }
    }

}