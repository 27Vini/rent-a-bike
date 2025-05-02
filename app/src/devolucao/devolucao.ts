import {Locacao} from '../locacao/locacao.js'

export class Devolucao {

    public constructor(
        public readonly id : number,
        public readonly dataDeDevolucao : Date,
        public readonly valorPago : number,
        public readonly locacao : Locacao
    ){       
    }


    validar() : string[]{
        const problemas: string[] = [];
        if(this.id == null || this.id <= 0){
            problemas.push("O id deve ser um número maior que 0");
        }      
        if (this.dataDeDevolucao > new Date()) {
            problemas.push("A data de devolução deve ser menor ou igual a atual.");
        }
        if(this.valorPago <= 0.0){
            problemas.push("O valor pago deve ser maior que 0.0");
        }

        return problemas
    }
}