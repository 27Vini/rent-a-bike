import { ControladoraCadastroLocacao } from "./controladora-cadastro-locacao";
import { VisaoCadastroLocacao } from "./visao-cadastro-locacao";
import { Money } from "ts-money";
import { sel } from "./seletores-cadastro-locacao";

export class VisaoCadastroLocacaoHTML implements VisaoCadastroLocacao{
    private controladora:ControladoraCadastroLocacao;

    constructor(){
        this.controladora = new ControladoraCadastroLocacao(this);
    }

    iniciar(){
        document.addEventListener('DOMContentLoaded', this.preencherSelectFuncionario.bind(this));

        document.querySelector(sel.botaoBuscarCliente)?.addEventListener("click", this.pesquisarCliente.bind(this))
        document.querySelector(sel.botaoBuscarItem)?.addEventListener("click", this.pesquisarItem.bind(this))
        document.querySelector(sel.inputHoras)?.addEventListener("blur", this.aoDigitarHora.bind(this));

        document.querySelector(sel.botaoCadastrar)!.addEventListener("click", this.cadastrar.bind(this));
    }

    coletarDados() : {funcionario, cliente, horas}{
        return {
            funcionario : document.querySelector<HTMLInputElement>(sel.selectFuncionario)!.value,
            cliente     : document.querySelector<HTMLInputElement>(sel.inputCliente)!.dataset.id,
            horas       : this.coletarHoras()
        }
    }

    coletarHoras(){
        const input = document.querySelector<HTMLInputElement>(sel.inputHoras)!;
        return Number(input.value);
    }

    private cadastrar(){
        this.controladora.cadastrar();
    }

    private aoDigitarHora(){
        if(this.coletarHoras() > 0){
            document.querySelector<HTMLInputElement>(sel.inputCodigoItem)!.disabled = false;
            document.querySelector<HTMLButtonElement>(sel.botaoBuscarItem)!.disabled = false;

            this.atualizarDados();
        } else {
            document.querySelector<HTMLInputElement>(sel.inputCodigoItem)!.disabled = true;
            document.querySelector<HTMLButtonElement>(sel.botaoBuscarItem)!.disabled = true;
            // document.querySelector(sel.campoEntrega )?.setAttribute("hidden", "hidden");
        }
    }

    /** SELECT DE FUNCIONÁRIOS */
    private async preencherSelectFuncionario(){
        const funcionarios = await this.controladora.coletarFuncionarios();
        const select = document.querySelector(sel.selectFuncionario);

        select!.innerHTML = funcionarios.map(f =>
            this.transformarEmOption({value:f.id, option:f.nome})
        ).join('');
    }

    private transformarEmOption({value, option}) {
        return `<option value=${value}>${option}</option>`
    }

    /** PESQUISA DE CLIENTES */
    private async pesquisarCliente(){
        const codigoCpf = document.querySelector<HTMLInputElement>(sel.inputCliente)!.value;
        const cliente = await this.controladora.coletarClienteComCodigoOuCpf(codigoCpf);

        this.exibirCliente({id:cliente.id, nome:cliente.nome, foto:cliente.foto});
    }

    private exibirCliente({id, nome, foto}){
        const inputCliente = document.querySelector<HTMLInputElement>(sel.inputCliente)!;
        inputCliente.dataset.id = id;

        const ul = document.querySelector(sel.listaCliente);
        ul!.innerHTML = '';

        const li = document.createElement('li');
        li.innerHTML = `<img src="${foto}" alt="${nome}" width='40px' /> ${nome}`;

        ul!.appendChild(li);
        ul!.removeAttribute("hidden");
    }

    /** PESQUISA DE ITEM */
    private async pesquisarItem(){
        const codigo = document.querySelector<HTMLInputElement>(sel.inputCodigoItem)!.value;
        await this.controladora.coletarItemComCodigo(codigo)
    }

    private removerItem(e){
        e.preventDefault();
        const botao = e.target.parentNode;
        const codigoItem = botao.dataset.itemId;
    
        this.controladora.removerItemComCodigo(codigoItem);

        const linha = botao.parentNode.parentNode;
        linha.remove();
    }

    exibirItem({descricao, disponibilidade, avarias, valorPorHora}){
        const ul = document.querySelector(sel.listaItem);
        ul!.innerHTML = '';

        let disponivel = disponibilidade ? 'disponível' : 'indisponível';
        let condicao = avarias == '' ? '' : `- ${avarias}`;
        let valorItem = Money.fromDecimal(valorPorHora, 'BRL');

        const li = document.createElement('li');
        li.innerHTML = `${descricao} - R$${valorItem}/h ${condicao} - <strong>${disponivel}</strong>`

        ul!.appendChild(li);
        ul!.removeAttribute("hidden");
        this.atualizarDados();
    }

    /** TABELA DE ITENS */
    atualizarDados(){
        this.controladora.atualizarDados();
    }

    construirTabela({itens, valores}){
        const tbody = document.querySelector(sel.tabelaItens)!;
        const tresumo = document.querySelector(sel.tabelaResumo)!;

        tbody.innerHTML = itens.map(i => 
            this.criarLinha(i)
        ).join('');

        document.querySelectorAll<HTMLElement>(sel.botaoRemoverItem)!.forEach((e) => e.onclick = this.removerItem.bind(this));

        tresumo.querySelector(sel.campoValorTotal)!.innerHTML = valores.valorTotal.toString();
        tresumo.querySelector(sel.campoDesconto)!.innerHTML = valores.valorDesconto.toString();
        tresumo.querySelector(sel.campoValorFinal)!.innerHTML = valores.valorFinal.toString();
    }

    private criarLinha(item){
        let subtotal = Money.fromDecimal(item.subtotal, 'BRL');

        return `
            <tr>
                <td>${item.codigo}</td>
                <td>${item.descricao}</td>
                <td>R$${subtotal}</td>
                <td><a data-item-id="${item.codigo}" class="remover-item" alt="Remover item da locação"><img src=".../../../styles/images/remover.png" class='icon'/></a></td>
            </tr>
        `
    }   

    exibirDataHoraEntrega(entrega:Date){
        document.querySelector(sel.campoEntregaSpan)!.innerHTML = entrega.toLocaleString();
        // document.querySelector(sel.campoEntrega)?.removeAttribute("hidden");
    }

    exibirMensagens(mensagens: string[]) {
        document.querySelector<HTMLOutputElement>(sel.output)!.innerHTML = mensagens.join('\n');
    }
}

let visao = new VisaoCadastroLocacaoHTML();
visao.iniciar();