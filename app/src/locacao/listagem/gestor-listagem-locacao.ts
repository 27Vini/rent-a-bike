import { API } from "../../../infra/api";

export class GestorListagemLocacao{
    public async coletarLocacoes() : Promise<any>{
        const response = await fetch(API + "locacoes");
        if(!response.ok)
            throw new Error("Erro ao obter as locações.");

        return response.json();
    }
}