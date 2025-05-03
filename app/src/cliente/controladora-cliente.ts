import { VisaoCliente } from "./visao-cliente";
import { GestorCliente } from "./gestor-cliente";

export class ControladoraCliente {
    private visao:VisaoCliente;
    private gestor:GestorCliente;

    constructor(visao:VisaoCliente){
        this.visao = visao;
        this.gestor = new GestorCliente();
    }

    async obterClientesComCodigoOuCpf(parametro:string){
        try{
            const clientes = await this.gestor.coletarClientes(parametro);
            if(!clientes)
                this.visao.exibirAlerta("Nenhum cliente encontrado.");

            return clientes;
        }catch(erro){
            this.visao.exibirAlerta(erro);
        }
    }
}