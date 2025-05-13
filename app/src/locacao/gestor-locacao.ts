import { API } from "../../infra/api";
import { Locacao } from "./locacao";
import { ErrorLocacao } from "../../infra/ErrorLocacao";

export class GestorLocacao{
    public async salvarLocacao({funcionario, cliente, itens, horas, entrega}) : Promise<void> {
        const locacao = new Locacao(0, cliente, funcionario, itens, new Date(), horas, 0, 0, entrega);

        const problemas = locacao.validar();
        if(problemas.length > 0)
            throw ErrorLocacao.comProblemas(problemas);

        await this.cadastrarLocacao(locacao);
    }

    private async cadastrarLocacao(locacao:Locacao){
        const response = await fetch(API + "locacoes", 
            {
                method:"POST",
                headers:{"Content-Type":"application/json"},
                body:JSON.stringify(locacao)
            }
        );

        if(!response.ok) {
            throw ErrorLocacao.comProblemas(['Erro ao cadastrar locação. Status: '+ response.status]);
        }
    }

    public async coletarLocacoes() : Promise<any>{
        const response = await fetch(API + "locacoes");
        if(!response.ok)
            throw ErrorLocacao.comProblemas(["Erro ao obter as locações."]);

        return response.json();
    }
}