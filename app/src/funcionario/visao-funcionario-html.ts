import { VisaoFuncionario } from "./visao-funcionario";
import { ControladoraFuncionario } from "./controladora-funcionario";

export class VisaoFuncionarioHTML implements VisaoFuncionario{
    private controladora:ControladoraFuncionario;

    constructor(){
        this.controladora = new ControladoraFuncionario(this);
    }

    async listarFuncionarios(){
        const funcionarios = await this.controladora.obterFuncionarios();

        const select = document.querySelector("#funcionario");
        select!.innerHTML = funcionarios.map(f =>
            this.transformarEmOption(f)
        ).join('');
    }

    private transformarEmOption(func){
        return `
            <option value=${func.id}>${func.nome}</option>
        `;
    }

    exibirAlerta(mensagem: string) {
        alert(mensagem);
    }
}
