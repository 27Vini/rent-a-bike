import { API } from "../../infra/api";

export class GestorCliente{
    async coletarClientes(parametro:string){
        let response;
        if(parametro.length == 14){
            response = await fetch(API + "clientes?cpf=" + parametro);
        } else {
            response = await fetch(API + "clientes?codigo=" + parametro);
        }

        if(!response.ok)
            throw new Error("Erro ao obter clientes.");

        return response.json();
    }
}