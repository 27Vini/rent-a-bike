import { GestorLocacao } from "../gestor-locacao";
import { VisaoCadastroLocacao } from "./visao-cadastro-locacao";
import { ErrorLocacao } from "../../../infra/ErrorLocacao";

const PORCENTAGEM_DESCONTO = 0.1;
export class ControladoraCadastroLocacao{

    private visao:VisaoCadastroLocacao;
    private gestor:GestorLocacao;

    constructor(visao:VisaoCadastroLocacao){
        this.visao = visao;
        this.gestor = new GestorLocacao();
    }

    async cadastrar(){
        const dados = this.visao.coletarDados();
        try{
            const entrega = this.calcularEntrega(dados.horas);

            await this.gestor.salvarLocacao({
                funcionario: dados.funcionario,
                cliente: dados.cliente,
                itens: dados.itens,
                horas: dados.horas,
                entrega: entrega
            });

            this.visao.exibirMensagens(['Locação salva com sucesso!']);
        }catch(erro){
            this.visao.exibirMensagens([erro.message]);
        }
    }

    atualizarDadosLocacao(){
        const horas = this.visao.coletarHoras();

        this.atualizarSubtotal(horas);

        try{
            const entrega = this.calcularEntrega(horas);
            if(entrega instanceof Date)
                this.visao.exibirDataHoraEntrega(entrega);

            const total = this.calcularTotal();
            this.visao.exibirValorTotal(total);

            const desconto = this.calcularDesconto(total, horas);
            this.visao.exibirValorDesconto(desconto);

            this.visao.exibirValorFinal(this.calcularValorFinal(total, desconto));
        } catch(erro){
            this.visao.exibirMensagens(erro.message);
        }
        
        this.visao.atualizarTabela();
    }

    private atualizarSubtotal(horas:number){
        const itens = this.visao.obterDoLocalStorage("itens");
        const itensAtualizados = itens.map(i => {
            return { ...i, subtotal: i.valorPorHora * horas };
        });

        this.visao.salvarNoLocalStorage("itens", itensAtualizados);
    }

    /** Cálculos do sistema */

    private calcularEntrega(horas:number){
        if(horas < 0)
            throw ErrorLocacao.comProblemas(['Horas devem ser maior do que 0.']);

        //para que não seja exibida a data e hora sem horas setadas.
        if(horas == 0)
            return;

        const entrega = new Date();
        entrega.setHours(entrega.getHours() + horas);

        return entrega;
    }

    private calcularTotal(){
        const itens = this.visao.obterDoLocalStorage("itens");

        let total = 0;
        for(let item of itens){
            total += item.subtotal;
        }

        return total;
    }

    private calcularDesconto(total:number, horas:number){
        if(horas < 0)
            throw ErrorLocacao.comProblemas(["Horas de aluguel devem ser maiores do que zero."]);

        let desconto = 0;
        if(horas > 2){
            desconto = total * PORCENTAGEM_DESCONTO;
        }

        return desconto;
    }

    private calcularValorFinal(total, desconto){
        return Number(total-desconto);
    }
}