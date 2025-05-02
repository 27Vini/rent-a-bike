import { Cliente } from "../cliente/cliente";
import { Funcionario } from "../funcionario/funcionario";
import { ItemLocacao } from "../item/item-locacao";

export class Locacao{
    constructor(
        public readonly id:number, 
        public readonly cliente:Cliente,
        public readonly funcionario:Funcionario,
        public readonly item:ItemLocacao[],
        public readonly entrada:Date,
        public readonly numeroDeHoras:number, 
        public readonly desconto:number,
        public readonly valorTotal:number,
        public readonly previsaoEntrega:Date,
    ){}

    public validar() : string[] {
        const erros:string[] = [];

        if(this.numeroDeHoras <= 0)
            erros.push("Número de horas deve ser maior do que zero.");

        if(this.entrada > new Date())
            erros.push("A data de entrada não deve ser posterior a data atual.");

        if(this.desconto < 0)
            erros.push("O descontro não deve ser menor do que zero.");

        if(this.valorTotal < 0)
            erros.push("O valor total deve ser maior do que zero.");

        if(this.previsaoEntrega < this.entrada)
            erros.push("A hora de entrega deve ser posterior a hora de entrada.");

        return erros;
    }
}