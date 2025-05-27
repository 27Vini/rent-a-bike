import { ControladoraCadastroDevolucao } from "./controladora-cadastro-devolucao.js";
import { VisaoCadastroDevolucao } from "./visao-cadastro-devolucao.js";
import { sel } from './seletores-cadastro-devolucao.js';

export class VisaoCadastroDevolucaoHTML implements VisaoCadastroDevolucao{
    private controladora : ControladoraCadastroDevolucao;

    public constructor(){
        this.controladora = new ControladoraCadastroDevolucao(this);
    }

    iniciar(){
        document.addEventListener('DOMContentLoaded', this.preencherSelectFuncionario.bind(this));

        document.querySelector(sel.pesquisarLocacao)?.addEventListener('click', this.controladora.pesquisarLocacao.bind(this.controladora));
        document.querySelector(sel.devolverBtn)?.addEventListener('click', this.controladora.enviarDados.bind(this.controladora));
        document.querySelector(sel.selectLocacao)?.addEventListener('change', this.controladora.procurarLocacaoDoSelecionada.bind(this.controladora));
        document.querySelector(sel.devolucao)?.addEventListener('input', this.bloquearInputLocacao.bind(this));

        this.bloquearInputLocacao();
    }

    private async preencherSelectFuncionario(){
        const funcionarios = await this.controladora.coletarFuncionarios();
        const select = document.querySelector(sel.selectFuncionarios);

        select!.innerHTML = funcionarios.map(f =>
            this.transformarEmOption({value:f.id, option:f.nome})
        ).join('');
    }

    private transformarEmOption({value, option}) {
        return `<option value=${value}>${option}</option>`
    }

    bloquearInputLocacao(): void {
        const data = this.coletarDataDevolucao();
        const inputLocacao = document.querySelector<HTMLInputElement>(sel.locacaoInput);
        const botaoPesquisar = document.querySelector<HTMLButtonElement>(sel.pesquisarLocacao);
        if(!data || data == ''){
            inputLocacao!.disabled = true;
            botaoPesquisar!.disabled = true;
        }
        else{
            inputLocacao!.disabled = false;
            botaoPesquisar!.disabled = false;
        }
    }

    exibirMensagens(mensagens: string[], erro:boolean) {
        const classErro = "alert";
        const classSucesso = "success";

        const output = document.querySelector<HTMLOutputElement>(sel.output)!;
        if(erro == true){
            output.classList.add(classErro);
        }else{
            output.classList.add(classSucesso);
        }

        output.innerHTML = mensagens.join('\n');        
        output.removeAttribute('hidden');

        setTimeout(() => {
            output.setAttribute('hidden', '');
        }, 5000);    
    }

    coletarInputLocacao() {
        return document.querySelector<HTMLInputElement>(sel.locacaoInput)!.value;
    }

    coletarIdFuncionario() {
        return document.querySelector<HTMLInputElement>(sel.selectFuncionarios)!.value;
    }

    coletarValorFinal() : string | null{
        return document.querySelector(sel.valorFinal)!.textContent;
    }

    coletarIdLocacaoDoSelect(): number {
        return Number(document.querySelector<HTMLSelectElement>(sel.selectLocacao)!.value);
    }

    coletarSubtotais(): number[] {
        const valores : number[] = [];
        const trs = document.querySelectorAll('tbody tr');
        for(const tr of trs){
            const td = tr.querySelectorAll('td')[2];
            valores.push(Number(td.textContent));
        }
        return valores;
    }

    exibirLocacoes(locacoes) {
        document.querySelector<HTMLOutputElement>(sel.output)!.innerText = "";
        if(locacoes.length > 1){
            this.exibirLocacoesDoCliente(locacoes);
        }
        else{
            this.exibirLocacaoUnica(locacoes);
        }
    }
    
    exibirLocacoesDoCliente(locacaoes) {
        document.querySelector<HTMLSelectElement>(sel.locacaoOutput)!.hidden = true;
        const select = document.querySelector<HTMLSelectElement>(sel.selectLocacao)!
        select!.innerHTML = ""
        select?.removeAttribute("hidden")
        this.criarOptionPadrao(select);
        
        for(const locacao of locacaoes){
            const option = document.createElement('option');
            option.value = String(locacao.id);
            const apenasData = locacao.previsaoDeEntrega.substring(0, 10);
            option.innerText = `R$${locacao.valorTotal} ${apenasData}`;
            select!.append(option)
        }
    }

    criarOptionPadrao(select : HTMLSelectElement){
        const optionPadrao = document.createElement('option')
        optionPadrao.innerText = '--Selecione--'
        select?.append(optionPadrao);
    }

    exibirLocacaoUnica(locacao) {
        document.querySelector<HTMLOutputElement>(sel.selectLocacao)!.hidden = true;
        const div = document.querySelector<HTMLOutputElement>(sel.locacaoOutput)
        div?.removeAttribute('hidden')
        const data = (new Date(locacao.previsaoDeEntrega)).toLocaleString();
        const apenasData = data.substring(0, 10);
        div!.innerText = `Locação de valor R$${locacao.valorTotal} para entregar ${apenasData}`
        div!.dataset.id = String(locacao.id)
        this.desenharTabela(locacao);
    }

    desenharTabela(locacao){
        const tbody = document.querySelector('tbody')
        tbody!.innerHTML = ""
        for(const itemLoc of locacao.itensLocacao){
            const subtotal = this.controladora.calcularSubtotal(itemLoc);
            tbody!.innerHTML += `
                <tr>
                    <td>${itemLoc.item.codigo}</td>
                    <td>${itemLoc.item.descricao}</td>
                    <td>${subtotal}</td>
                </tr>
            ` 
        }
        this.controladora.calcularValores()
    }

    preencherValores({valorTotal, desconto, valorFinal}){
        document.querySelector<HTMLOutputElement>(sel.valorTotal)!.innerText = valorTotal.toString()
        document.querySelector<HTMLOutputElement>(sel.desconto)!.innerText = desconto.toString()
        document.querySelector<HTMLOutputElement>(sel.valorFinal)!.innerText = valorFinal.toString()
    }

    limparForm(): void {
        document.querySelector<HTMLInputElement>(sel.devolucao)!.value = ''
        document.querySelector<HTMLInputElement>(sel.locacaoInput)!.value = ''
        document.querySelector<HTMLOutputElement>(sel.locacaoOutput)!.hidden = true;
        document.querySelector<HTMLSelectElement>(sel.selectLocacao)!.hidden = true;
        document.querySelector('tbody')!.innerHTML = "";
    }

    coletarDataDevolucao() {
        return document.querySelector<HTMLInputElement>(sel.devolucao)!.value;
    }

}

const visao = new VisaoCadastroDevolucaoHTML();
visao.iniciar()