import { Cookie } from "./cookie";
import { APP } from '../../infra/app'
import { API } from '../../infra/api'

export class GestorAutenticador{
    coletarUsuarioDoCookie(): string | null{
        const nome : string | null = Cookie.obter('user_name');
        if(nome == '' || nome == null){
            location.href = APP;
        }

        return nome;
    }

    async deslogar(): Promise<void>{
        const response = await fetch(API + 'logout', { method: 'POST' , credentials : 'include'});
        console.log("resposta: " + response.status);
        location.href = APP;
    }
}