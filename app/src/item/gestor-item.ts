import { API } from "../../infra/api";

export class GestorItem {
    async coletarItens(codigo:string){
        const response = await fetch(API + "itens?codigo=" + codigo);

        if(!response.ok)
            throw new Error("Erro ao obter itens.");

        return response.json();
    }
}