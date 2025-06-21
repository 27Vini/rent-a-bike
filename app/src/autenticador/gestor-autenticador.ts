import { Cookie } from "./cookie";
import { APP, FORBIDDEN } from '../../infra/app'
import { API } from '../../infra/api'
import { ServicoAutenticador } from "./servico-autenticador";

export class GestorAutenticador{
    coletarUsuarioDoCookie(): string | null{
        const nome : string | null = Cookie.obter('user_name');
        if(nome == '' || nome == null){
            location.href = APP;
        }

        return nome;
    }

    verificarPermissaoDeAcesso(){
        const cargoFuncionario = Cookie.obter('cargo');
        if(cargoFuncionario == null){
            location.href = APP;
        }

        if(!ServicoAutenticador.verificarPermissao(cargoFuncionario!)){
            location.href = APP + FORBIDDEN;
        }
    }

    async deslogar(): Promise<void>{
        const response = await fetch(API + 'logout', { method: 'POST' , credentials : 'include'});
        console.log("resposta: " + response.status);
        location.href = APP;
    }
}