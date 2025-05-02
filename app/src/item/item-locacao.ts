import { Locacao } from "../locacao/locacao.js";
import { Item } from "./item.js";

export class ItemLocacao{

    public constructor(
        public readonly id : number,
        public readonly locacao : Locacao,
        public readonly item : Item,
        public readonly precoLocacao : number,
        public readonly subtotal : number
    ){}

    validar() : string[] {
        const problemas: string[] = [];

        if(this.id == null || this.id <= 0){
            problemas.push("O item de locação deve ter um ID maior que 0.");
        }
        if(this.precoLocacao <= 0.0){
            problemas.push("O preço da locação deve ser maior que 0.0 .");
        }
        if(this.qtd <= 0){
            problemas.push("A quantidade deve ser maior que 0.");
        }
        if(this.subtotal <= 0.0){
            problemas.push("O subtotal deve ser maior que 0.0 .");
        }
        return problemas;
    }

}