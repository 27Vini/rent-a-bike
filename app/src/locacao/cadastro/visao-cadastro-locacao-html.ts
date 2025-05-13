import { ControladoraCadastroLocacao } from "./controladora-cadastro-locacao";
import { VisaoCadastroLocacao } from "./visao-cadastro-locacao";
import { VisaoFuncionarioHTML} from "../../funcionario/visao-funcionario-html";
import { VisaoClienteHTML } from "../../cliente/visao-cliente-html";
import { VisaoItemHTML } from "../../item/visao-item-html";
import { carregarDoLocalStorage, salvarEmLocalStorage } from "../../../infra/util/gestor-localstorage";
import { Money } from "ts-money";

export class VisaoCadastroLocacaoHTML implements VisaoCadastroLocacao{
    private controladora:ControladoraCadastroLocacao;
    private visaoCliente:VisaoClienteHTML;
    private visaoItem:VisaoItemHTML;

    constructor(){
        this.visaoItem = new VisaoItemHTML();
        this.visaoCliente = new VisaoClienteHTML();
        this.controladora = new ControladoraCadastroLocacao(this);
        this.controladora.atualizarDadosLocacao();
    }

    iniciar(){
        document.addEventListener('DOMContentLoaded', this.preencherSelectFuncionario.bind(this));

        document.querySelector("#pesquisar-cliente")?.addEventListener("click", this.pesquisarCliente.bind(this))
        document.querySelector("#pesquisar-item")?.addEventListener("click", this.pesquisarItem.bind(this))
        document.querySelector("#horas")?.addEventListener("blur", this.controladora.atualizarDadosLocacao.bind(this.controladora));

        document.querySelector("#cadastrar")!.addEventListener("click", this.cadastrar.bind(this));
    }

    private cadastrar(){
        this.controladora.cadastrar();
    }

    /** PESQUISA E OBTENÇÃO DE DADOS */

    coletarDados() : {funcionario, cliente, itens, horas}{
        return {
            funcionario : document.querySelector<HTMLInputElement>("#funcionario")!.value,
            cliente     : this.obterDoLocalStorage("cliente"),
            itens       : this.obterDoLocalStorage("itens"),
            horas       : this.coletarHoras()
        }
    }

    coletarHoras(){
        const input = document.querySelector<HTMLInputElement>("#horas")!;
        return Number(input.value);
    }

    obterDoLocalStorage(chave:string){
        return carregarDoLocalStorage(chave);
    }

    salvarNoLocalStorage(chave:string, dados:[]){
        salvarEmLocalStorage(chave, dados);
    }

    private async preencherSelectFuncionario(){
        const visaoFuncionario = new VisaoFuncionarioHTML();
        await visaoFuncionario.listarFuncionarios();
    }

    private async pesquisarCliente(){
        const codigoCpf = document.querySelector<HTMLInputElement>("#cliente")!.value;
        await this.visaoCliente.clienteComCodigoOuCpf(codigoCpf);
    }

    private async pesquisarItem(){
        const codigo = document.querySelector<HTMLInputElement>("#item")!.value;
        await this.visaoItem.itemComCodigo(codigo);

        this.controladora.atualizarDadosLocacao();
    }

    /** EXIBIÇÃO DE DADOS E MENSAGENS */

    exibirValorTotal(valorTotal:number){
        const tfoot = document.querySelector<HTMLElement>('table tfoot td .valor-total');
        tfoot!.innerHTML = Money.fromDecimal(valorTotal, 'BRL').toString();
    }

    exibirValorDesconto(desconto:number){
        const tfoot = document.querySelector<HTMLElement>('table tfoot td .valor-desconto');
        tfoot!.innerHTML = Money.fromDecimal(desconto, 'BRL').toString();
    }

    exibirValorFinal(valorFinal:number){
        const tfoot = document.querySelector<HTMLElement>('table tfoot td .valor-final');
        tfoot!.innerHTML = Money.fromDecimal(valorFinal, 'BRL').toString();
    }

    exibirDataHoraEntrega(entrega:Date){
        document.querySelector("#entrega span")!.innerHTML = entrega.toLocaleString();
        document.querySelector("#entrega")?.removeAttribute("hidden");
    }

    exibirMensagens(mensagens: string[]) {
        //SUBSTITUIR ESSE ALERT NO FUTURO
        alert(mensagens.join(''));
    }

    atualizarTabela(){
        const table = document.querySelector('table tbody');
        const itens = carregarDoLocalStorage("itens");
        
        table!.innerHTML = itens.map(i => 
            this.criarLinha(i)
        ).join('')
    }

    private criarLinha(item){
        let subtotal = item.subtotal ? Money.fromDecimal(item.subtotal, 'BRL') : 0.00;

        return `
            <tr>
                <td>${item.codigo}</td>
                <td>${item.descricao}</td>
                <td>R$${subtotal}</td>
            </tr>
        `
    }
}

let visao = new VisaoCadastroLocacaoHTML();
visao.iniciar();