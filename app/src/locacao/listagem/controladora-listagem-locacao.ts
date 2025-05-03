import { GestorListagemLocacao } from "./gestor-listagem-locacao";
import { VisaoLocacao } from "./visao-listagem-locacao";

export class ControladoraListagemLocacao{
    private gestor:GestorListagemLocacao;
    private visao:VisaoLocacao

    constructor(visao){
        this.visao = visao;
        this.gestor = new GestorListagemLocacao();
    }

    async obterLocacoes(){
        try{
            const locacoes = await this.gestor.coletarLocacoes();
            if(!locacoes)
                this.visao.exibirMensagem("Não há locações para serem exibidas.");
            
            return locacoes;
        }catch(error){
            this.visao.exibirMensagem(error.message)
        }
    }
}