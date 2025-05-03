import { VisaoCliente } from "./visao-cliente";
import { ControladoraCliente } from "./controladora-cliente";

export class VisaoClienteHTML implements VisaoCliente {
    private controladora:ControladoraCliente;

    constructor(){
        this.controladora = new ControladoraCliente(this);
    }

    async listarClienteComCodigoOuCpf(parametro:string){
        const clientes = await this.controladora.obterClientesComCodigoOuCpf(parametro);
        let ul = document.querySelector("#lista-clientes");
        ul!.innerHTML = clientes.map(c => 
            this.transformarEmLI(c)
        ).join('')
    }

    private transformarEmLI(c){
        return `
            <li>
                <img src=""/> ${c.nome} <button data-cliente-id='${c.id}'>+</button>
            </li>
        `
    }

    exibirAlerta(mensagem: string) {
        alert(mensagem);
    }
}