import { ControladoraListagemLocacao } from "./controladora-listagem-locacao";
import { VisaoLocacao } from "./visao-listagem-locacao";

export class VisaoListagemLocacaoHTML implements VisaoLocacao{
    private controladora:ControladoraListagemLocacao;

    constructor(){
        this.controladora = new ControladoraListagemLocacao(this);
    }

    iniciar(){
        document.addEventListener('DOMContentLoaded', this.listarLocacoes.bind(this));
    }

    async listarLocacoes(){
        const locacoes = await this.controladora.obterLocacoes();
        document.querySelector('tbody')!.innerHTML = locacoes.map(
            e => this.desenharLinhaLocacao(e)
        ).join('')
    }

    desenharLinhaLocacao(e){
        return `
            <tr>
                <td>${e.id}</td>
                <td>${e.entrada}</td>
                <td>${e.numeroDeHoras}</td>
                <td>${e.previsaoEntrega}</td>
                <td>${e.cliente.id}</td>
            </tr>
        `
    }

    exibirMensagem(mensagem: string) {
        alert(mensagem)
    }
}

const visaoListagem = new VisaoListagemLocacaoHTML();
visaoListagem.iniciar();