import { ControladoraCadastroLocacao } from "./controladora-cadastro-locacao";
import { VisaoCadastroLocacao } from "./visao-cadastro-locacao";
import { Money } from "ts-money";

export class VisaoCadastroLocacaoHTML implements VisaoCadastroLocacao{
    private controladora:ControladoraCadastroLocacao;

    constructor(){
        this.controladora = new ControladoraCadastroLocacao(this);
    }

    iniciar(){
        //preenche o select de funcionário ao abrir a página
        document.addEventListener('DOMContentLoaded', this.preencherSelectFuncionario.bind(this));

        document.querySelector("#pesquisar-cliente")?.addEventListener("click", this.pesquisarCliente.bind(this))
        document.querySelector("#pesquisar-item")?.addEventListener("click", this.pesquisarItem.bind(this))
        document.querySelector("#horas")?.addEventListener("blur", this.aoDigitarHora.bind(this));

        document.querySelector("#cadastrar")!.addEventListener("click", this.cadastrar.bind(this));
    }

    private cadastrar(){
        this.controladora.cadastrar();
    }

    /** PESQUISA E OBTENÇÃO DE DADOS */

    coletarDados() : {funcionario, cliente, horas}{
        return {
            funcionario : document.querySelector<HTMLInputElement>("#funcionario")!.value,
            cliente     : document.querySelector<HTMLInputElement>("#cliente")!.dataset.id,
            horas       : this.coletarHoras()
        }
    }

    coletarHoras(){
        const input = document.querySelector<HTMLInputElement>("#horas")!;
        return Number(input.value);
    }

    private aoDigitarHora(){
        if(this.coletarHoras() > 0){
            document.querySelector<HTMLInputElement>("#item")!.disabled = false;
            document.querySelector<HTMLButtonElement>("#pesquisar-item")!.disabled = false;

            this.atualizarDados();
        } else {
            document.querySelector<HTMLInputElement>("#item")!.disabled = true;
            document.querySelector<HTMLButtonElement>("#pesquisar-item")!.disabled = true;
            document.querySelector("#entrega")?.setAttribute("hidden", "hidden");
        }
    }

    /** SELECT DE FUNCIONÁRIOS */
    private async preencherSelectFuncionario(){
        const funcionarios = await this.controladora.coletarFuncionarios();
        const select = document.querySelector("#funcionario");
        select!.innerHTML = funcionarios.map(f =>
            this.transformarEmOption({value:f.id, option:f.nome})
        ).join('');
    }

    private transformarEmOption(e:{value, option}) {
        return `<option value=${e.value}>${e.option}</option>`
    }

    /** Pesquisa de clientes */
    private async pesquisarCliente(){
        const codigoCpf = document.querySelector<HTMLInputElement>("#cliente")!.value;
        const cliente = await this.controladora.coletarClienteComCodigoOuCpf(codigoCpf);

        this.exibirCliente({id:cliente.id, nome:cliente.nome, foto:cliente.foto});
    }

    private exibirCliente(cliente:{id, nome, foto}){
        const inputCliente = document.querySelector<HTMLInputElement>("#cliente")!;
        inputCliente.dataset.id = cliente.id;

        const ul = document.querySelector("#lista-clientes");
        ul!.innerHTML = '';

        const li = document.createElement('li');
        li.innerHTML = `<img src="${cliente.foto}" alt="${cliente.nome}" width='50px' /> ${cliente.nome}`;

        ul!.appendChild(li);
    }

    /** Pesquisa de Item */

    private async pesquisarItem(){
        const codigo = document.querySelector<HTMLInputElement>("#item")!.value;
        await this.controladora.coletarItemComCodigo(codigo)
    }

    exibirItem({descricao, disponibilidade, avarias, valorPorHora}){
        const ul = document.querySelector("#lista-itens");
        ul!.innerHTML = '';

        let disponivel = disponibilidade ? 'disponível' : 'indisponível';
        let condicao = avarias == '' ? '' : `- ${avarias}`;
        let valorItem = Money.fromDecimal(valorPorHora, 'BRL');

        const li = document.createElement('li');
        li.innerHTML = `${descricao} - R$${valorItem}/h ${condicao} - <strong>${disponivel}</strong>`

        ul!.appendChild(li);
        this.atualizarDados();
    }

    /** TABELA DE ITENS */
    atualizarDados(){
        this.controladora.atualizarDados();
    }

    construirTabela({itens, valores}){
        const tbody = document.querySelector("table tbody")!;
        const tfoot = document.querySelector("table tfoot")!;

        tbody.innerHTML = itens.map(i => 
            this.criarLinha(i)
        ).join('');

        tfoot.querySelector(".valor-total")!.innerHTML = valores.valorTotal.toString();
        tfoot.querySelector('.valor-desconto')!.innerHTML = valores.valorDesconto.toString();
        tfoot.querySelector('.valor-final')!.innerHTML = valores.valorFinal.toString();
    }

    private criarLinha(item){
        let subtotal = Money.fromDecimal(item.subtotal, 'BRL');

        return `
            <tr>
                <td>${item.codigo}</td>
                <td>${item.descricao}</td>
                <td>R$${subtotal}</td>
            </tr>
        `
    }   

    exibirDataHoraEntrega(entrega:Date){
        document.querySelector("#entrega span")!.innerHTML = entrega.toLocaleString();
        document.querySelector("#entrega")?.removeAttribute("hidden");
    }

    exibirMensagens(mensagens: string[]) {
        //SUBSTITUIR ESSE ALERT NO FUTURO
        alert(mensagens.join(''));
    }
}

let visao = new VisaoCadastroLocacaoHTML();
visao.iniciar();