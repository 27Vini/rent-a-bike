import {API} from '../../../infra/api.js'

export class GestorListagemDevolucao{

    async coletarDevolucoes() : Promise<any>{
        const response = await fetch( API + 'devolucao');
        if(!response.ok){
            throw new Error("Não foi possível coletar os dados de devolução: " + response.status);
        }
        return response.json();
    }
}