import { GestorItem } from "./gestor-item";
import { VisaoItem } from "./visao-item";

export class ControladoraItem {
    private gestor:GestorItem;
    private visao:VisaoItem;

    constructor(visao:VisaoItem){
        this.visao = visao;
        this.gestor = new GestorItem();
    }

    async obterItensComCodigo(codigo:string) : Promise<any>{
        try{
            if(codigo == ''){
                this.visao.exibirAlerta("Digite um código do item para consultar.");
                return;
            }

            const itens = await this.gestor.coletarItens(codigo);
            if(itens.length == 0){
                this.visao.exibirAlerta("Nenhum item encontrado.");
                return;
            }

            return itens;
        }catch(erro){
            this.visao.exibirAlerta(erro);
        }
    }
}