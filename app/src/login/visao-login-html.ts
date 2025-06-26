import { ControladoraLogin } from "./controladora-login";
import { sel } from "./seletores-login";
import { VisaoLogin } from "./visao-login";
import DOMPurify from "dompurify";

export class VisaoLoginHTML implements VisaoLogin{

    controladora : ControladoraLogin;

    constructor(){
        this.controladora = new ControladoraLogin(this);
    }

    iniciar(){
        document.querySelector(sel.botaoLogar)?.addEventListener('click', (event) => {
            event.preventDefault();
            this.controladora.logar();
        });
    }


    coletarDados(): { login: string; senha: string; } {
        const login = DOMPurify.sanitize(document.querySelector<HTMLInputElement>(sel.campoUsuario)!.value);
        const senha = DOMPurify.sanitize(document.querySelector<HTMLInputElement>(sel.campoSenha)!.value);
        return {login, senha};
    }

    exibirMensagens(mensagens: string[], erro: boolean) {
        const classErro = "alert";
        const classSucesso = "success";

        const output = document.querySelector<HTMLOutputElement>(sel.output)!;
        if(erro == true){
            output.classList.add(classErro);
        }else{
            output.classList.add(classSucesso);
        }

        output.innerHTML = mensagens.join('\n');        
        output.removeAttribute('hidden');

        setTimeout(() => {
            output.setAttribute('hidden', '');
        }, 5000); 
    }

}


const visao = new VisaoLoginHTML();
visao.iniciar();