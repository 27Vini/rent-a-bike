import { ControladoraListagemDevolucao } from "./controladora-listagem-devolucao.js";
import { VisaoListagemDevolucao } from "./visao-listagem-devolucao.js";
import { Money } from "ts-money";

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

    exibirMensagens(mensagens: string[], erro:boolean) {
        const classErro = "alert";
        const classSucesso = "success";

        const output = document.querySelector<HTMLOutputElement>("output")!;
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

    desenharDevolucao(d){
        const dataDevolucao = new Date(d.dataDeDevolucao);
        return `
            <tr>
                <td>${d.id}</td>
                <td>${d.locacao.id}</td>
                <td>${dataDevolucao.toLocaleString()}</td>
                <td>${d.locacao.cliente.nome}</td>
                <td>R$${Money.fromDecimal(d.valorPago, 'BRL')}</td>
            </tr>
        `
    }

}

const visao = new VisaoListagemDevolucaoHTML();
visao.iniciar()