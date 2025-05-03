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

        // let cliente;
        // if(parametro.length > 8){
        //     clientes.then(c => {
        //         cliente = c.find(c => c.cpf === parametro);
        //     })
        //     .catch(e => {
        //         throw new Error("Cliente não encontrado.")
        //     })
        // } else {
        //     clientes.then(c => {
        //         cliente = c.find(c => c.codigo === parametro);
        //     })
        //     .catch(e => {
        //         throw new Error("Cliente não encontrado.")
        //     })
        // }

        // return cliente;
    }
}