import { VisaoCliente } from "./visao-cliente";
import { ControladoraCliente } from "./controladora-cliente";
import { salvarEmLocalStorage } from "../../infra/util/gestor-localstorage";
export class VisaoClienteHTML implements VisaoCliente {
    private controladora:ControladoraCliente;

    constructor(){
        this.controladora = new ControladoraCliente(this);
    }

    async clienteComCodigoOuCpf(parametro:string){
        const clientes = await this.controladora.obterClientesComCodigoOuCpf(parametro);
        let cliente = clientes[0];
        
        let ul = document.querySelector("#lista-clientes");
        ul!.innerHTML = this.transformarEmLI(cliente);

        this.salvarEmLocalStorage(cliente);
    }

    private transformarEmLI(c){
        return `
            <li>
                <img src="${c.foto}" width="50px" height="50px"/> ${c.nome}
            </li>
        `
    }

    private salvarEmLocalStorage(cliente){
        salvarEmLocalStorage("cliente", cliente);
    }

    exibirAlerta(mensagem: string) {
        alert(mensagem);
    }
}