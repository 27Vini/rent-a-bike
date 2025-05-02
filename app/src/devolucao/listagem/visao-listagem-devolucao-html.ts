import { ControladoraListagemDevolucao } from "./controladora-listagem-devolucao.js";
import { VisaoListagemDevolucao } from "./visao-listagem-devolucao.js";

export class VisaoListagemDevolucaoHTML implements VisaoListagemDevolucao{

    private controladora : ControladoraListagemDevolucao;

    public constructor(){
        this.controladora = new ControladoraListagemDevolucao(this);
    }

    iniciar(){
        document.addEventListener('DOMContentLoaded', this.listarDevolucoes.bind(this) )
    }

    async listarDevolucoes(){
        const devolucoes = await this.controladora.obterDevolucoes()
        document.querySelector('tbody')!.innerHTML = devolucoes.map(e => this.desenharDevolucao(e)).join('')
    }

    exibirMensagem(mensagem: string): void {
        const output = document.querySelector('output');
        output!.innerText = mensagem;
    }

    desenharDevolucao(d){
        return `
            <tr>
                <td>${d.id}</td>
                <td>${d.locacao.id}</td>
                <td>${d.dataDeDevolucao}</td>
                <td>${d.locacao.cliente.nome}</td>
                <td>${d.valorPago}</td>
            </tr>
        `
    }

}

const visao = new VisaoListagemDevolucaoHTML();
visao.iniciar()