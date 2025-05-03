import { VisaoCadastroLocacao } from "./visao-cadastro-locacao";
import {VisaoFuncionarioHTML} from "../../funcionario/visao-funcionario-html";
import { ControladoraCadastroLocacao } from "./controladora-cadastro-locacao";
import { VisaoClienteHTML } from "../../cliente/visao-cliente-html";

export class VisaoCadastroLocacaoHTML implements VisaoCadastroLocacao{
    private controladora:ControladoraCadastroLocacao;

    constructor(){
        this.controladora = new ControladoraCadastroLocacao(this);
    }

    iniciar(){
        document.addEventListener('DOMContentLoaded', this.preencherSelectFuncionario.bind(this));
        document.querySelector("#cadastrar")!.addEventListener("click", this.cadastrar.bind(this));
        document.querySelector("#pesquisar-cliente")?.addEventListener("click", this.pesquisarCliente.bind(this))
        document.querySelector("#pesquisar-item")?.addEventListener("click", this.pesquisarItem.bind(this))
    }

    private async preencherSelectFuncionario(){
        const visaoFuncionario = new VisaoFuncionarioHTML();
        await visaoFuncionario.listarFuncionarios();
    }

    private cadastrar(){

    }

    private async pesquisarCliente(){
        const visaoCliente = new VisaoClienteHTML();
        const codigoCpf = document.querySelector<HTMLInputElement>("#cliente")!.value;
        await visaoCliente.listarClienteComCodigoOuCpf(codigoCpf);

        document.querySelectorAll("#lista-clientes li button").forEach(e => e.addEventListener('click', this.salvarCliente.bind(this)))
    }

    private salvarCliente(e){
        console.log(e.target);
        e.preventDefault();
        e.target.innerHTML = "✅";
    }

    private pesquisarItem(){

    }

    coletarDados(dados: { funcionario: any; cliente: any; itens: any; horas: any; }) {
        
    }

    exibirMensagem(mensagem: string) {
        
    }
}

let visao = new VisaoCadastroLocacaoHTML();
visao.iniciar();