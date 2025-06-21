import { GestorAutenticador } from "./gestor-autenticador";
import { VisaoAutenticador } from "./visao-autenticador";

export class ControladoraAutenticador{
    private visao : VisaoAutenticador;
    private gestor : GestorAutenticador;

    constructor(visao : VisaoAutenticador){
        this.visao = visao;
        this.gestor = new GestorAutenticador();
    }

    gerenciarAutenticacao(){
        this.gestor.verificarPermissaoDeAcesso();

        this.visao.criarOHeader();
        const nome = this.gestor.coletarUsuarioDoCookie() || '';
        this.visao.mostrarNome(nome);
    }

    async sair(){
        await this.gestor.deslogar();
    }
}