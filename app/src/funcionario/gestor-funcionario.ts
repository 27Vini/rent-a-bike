import { API } from "../../infra/api";

export class GestorFuncionario{
    async coletarFuncionarios() : Promise<any>{
        const response = await fetch(API + "funcionarios")
        if(!response.ok)
            throw new Error("Erro ao obter funcionários.");

        return response.json();
    }
}