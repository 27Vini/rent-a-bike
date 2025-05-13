import { VisaoCliente } from "./visao-cliente";
import { GestorCliente } from "./gestor-cliente";

export class ControladoraCliente {
    private visao:VisaoCliente;
    private gestor:GestorCliente;

    constructor(visao:VisaoCliente){
        this.visao = visao;
        this.gestor = new GestorCliente();
    }

    async obterClientesComCodigoOuCpf(parametro:string) : Promise<any>{
        try{
            if(parametro == ''){
                this.visao.exibirAlerta("Digite um código ou cpf para consultar.");
                return;
            }

            const clientes = await this.gestor.coletarClientes(parametro);
            if(clientes.length == 0){
                this.visao.exibirAlerta("Nenhum cliente encontrado.");
                return;
            }

            return clientes;
        }catch(erro){
            this.visao.exibirAlerta(erro);
        }
    }
}