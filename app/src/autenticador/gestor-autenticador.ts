import { Cookie } from "./cookie";
import { APP, FORBIDDEN } from '../../infra/app'
import { API } from '../../infra/api'
import { ServicoAutenticador } from "./servico-autenticador";
import { ErrorNaoAutorizado } from "../../infra/ErrorNaoAutorizado";
import { ErrorForbidden } from "../../infra/ErrorForbidden";

export class GestorAutenticador{
    coletarUsuarioDoCookie(): string | null{
        const nome : string | null = Cookie.obter('user_name');
        if(nome == '' || nome == null){
            throw new ErrorNaoAutorizado();
        }

        return nome;
    }

    verificarPermissaoDeAcesso() {
        const cargoFuncionario = Cookie.obter('cargo');
        if(cargoFuncionario == null){
            throw new ErrorNaoAutorizado();
        }

        if(!ServicoAutenticador.verificarPermissao(cargoFuncionario!)){
            throw new ErrorForbidden();
        }
    }

    async deslogar(): Promise<void>{
        const response = await fetch(API + 'logout', { method: 'POST' , credentials : 'include'});
        console.log("resposta: " + response.status);
    }
}