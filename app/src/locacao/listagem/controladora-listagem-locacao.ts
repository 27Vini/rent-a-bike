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
            if(locacoes.length == 0)
                this.visao.exibirMensagem("Não há locações para serem exibidas.");
            
            return locacoes;
        }catch(error){
            this.visao.exibirMensagem(error.message)
        }
    }
}