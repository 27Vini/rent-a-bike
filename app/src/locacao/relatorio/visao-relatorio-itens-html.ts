import { ControladoraRelatorioItens } from "./controladora-relatorio-itens";
import { VisaoRelatorioItens } from "./visao-relatorio-itens";
import { seletores } from "./seletores-relatorio-itens";
import { Chart } from 'chart.js/auto';
import DOMPurify from "dompurify";
import { PieController, ArcElement, Tooltip,Legend,Title } from 'chart.js';
Chart.register(PieController, ArcElement, Tooltip, Legend, Title);

export class VisaoRelatorioItensHTML implements VisaoRelatorioItens {
    private grafico: Chart | null = null;
    private controladora : ControladoraRelatorioItens;

    constructor(){
        this.controladora = new ControladoraRelatorioItens(this);
    }

    iniciar(){
        document.querySelector(seletores.botaoGerar)?.addEventListener("click", this.controladora.obterItensParaRelatorio.bind(this.controladora));
    }

    coletarDataInicial(): string {
        return DOMPurify.sanitize(document.querySelector<HTMLInputElement>(seletores.dataInicial)!.value)
    }

    coletarDataFinal(): string {
        return DOMPurify.sanitize(document.querySelector<HTMLInputElement>(seletores.dataFinal)!.value)
    }

    gerarRelatorio(itensRelatorio) {
        this.gerarGrafico(itensRelatorio);

        if(itensRelatorio.length == 11){
            const index = itensRelatorio.findIndex(item => item.descricao === 'Outros');
            itensRelatorio.splice(index, 1); 
        }
            
        this.gerarTabelaRanking(itensRelatorio);
    }

    private gerarGrafico(itensParaRelatorio) {
    const descricoes : string[] = [];
    const dados : string[] = [];
    const cores = ["#4CAF50", "#2196F3", "#FF9800",  "#795548",  "#9C27B0",  "#F44336",  "#00BCD4", "#FFEB3B",  "#3F51B5",  "#8BC34A",  "#E91E63"];

    for(const item of itensParaRelatorio) {
        const porcentagem = (item.porcentagem * 100).toFixed(1);

        descricoes.push(`${item.descricao} (${porcentagem}%)`); 
        dados.push(item.qtdVezesAlugado); 
    }

    if(this.grafico) {
        this.grafico.destroy();
    }

    const div = document.querySelector<HTMLCanvasElement>("canvas")!;
        this.grafico = new Chart(div, {
            type: "pie",
            data: {
                labels: descricoes,
                datasets: [{
                    backgroundColor: cores,
                    data: dados
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    title: {
                        display: true,
                        text: "Itens Mais Alugados"
                    }
                }
            }
        });
    }


    private gerarTabelaRanking(itensRanking:[]){
        const tabela = document.querySelector(seletores.tabelaRanking)!;
        tabela.innerHTML = itensRanking.map((item, i) => this.desenharLinhaRanking(item, i+1)).join('');
    }

    private desenharLinhaRanking(item, posicao){
        return `
            <tr>
                <td>${posicao}º</td>
                <td>${item.qtdVezesAlugado}</td>
                <td>${item.descricao}</td>
            </tr>
        `
    }

    exibirMensagens(mensagens: string[], erro: boolean) {
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
}

const visao = new VisaoRelatorioItensHTML();
visao.iniciar();