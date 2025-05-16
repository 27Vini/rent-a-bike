import {API} from '../../infra/api.js'
import { Item } from '../item/item.js';
import {Locacao} from '../locacao/locacao.js';
import {Devolucao} from '../devolucao/devolucao.js'
import {Money, Currencies} from 'ts-money'
import { ErrorDominio } from '../../infra/ErrorDominio.js';

export class GestorDevolucao{

    private locacaoesDoCliente : Locacao[];
    private locacaoEscolhida : Locacao | undefined;
    private horasCorridas : number = 0;

    async coletarDevolucoes() : Promise<any>{
        const response = await fetch( API + 'devolucao');
        if(!response.ok){
            throw new Error("Não foi possível coletar os dados de devolução: " + response.status);
        }
        return response.json();
    }

    async pesquisarLocacao(pesquisa : string) : Promise<any>{
        if(! /^\d+$/.test(pesquisa)){
            throw ErrorDominio.comProblemas([ "O campo de locação deve estar preenchido com apenas números." ] )
        }

        let parametro : string;
        if(pesquisa.length == 11){
            parametro = `cpf=${pesquisa}`;
        }else{
            parametro = `id=${pesquisa}`;
        }

        const response = await fetch( API + `locacoes?${parametro}`)
        if(!response.ok){
            throw ErrorDominio.comProblemas(["Não foi possível coletar os dados de locação: " + response.status]);
        }

        const locacoes = await response.json();
        this.locacaoesDoCliente = locacoes;
        if(locacoes.length == 1){
            const locacao = locacoes[0];
            this.locacaoEscolhida = locacao;
            return locacao
        }
        return locacoes;
    }

    private criarDevolucao(dataDeDevolucao, valorPago) : Devolucao{
        const dataDevolucaoReal = dataDeDevolucao ? new Date(dataDeDevolucao) : undefined
        const devolucao = new Devolucao(10, dataDevolucaoReal, valorPago, this.locacaoEscolhida!);
        const problemas : string [] = devolucao.validar();
        if(problemas.length > 0){
            throw ErrorDominio.comProblemas(problemas);
        }
        return devolucao
    }

    async salvarDevolucao(dataDevolucao, valorPago){
        const devolucao = this.criarDevolucao(dataDevolucao, valorPago);
        const response = await fetch( API + 'devolucao', {method : 'POST', headers : {'Content-Type': 'application/json'}, body : JSON.stringify(devolucao)})

        if(!response.ok){
            throw ErrorDominio.comProblemas(['Não foi enviar os dados de devolução.' + response.status]);
        }
    }

    getLocacacaoDoGestorPeloId(id : number) : Locacao | undefined{
        const locacao = this.locacaoesDoCliente.find( l => l.id == id);
        this.locacaoEscolhida = locacao;
        return locacao;
    }

    calcularValores(subtotais : number []) : {valorTotal, desconto, valorFinal}{
        const valorTotal = this.calcularValorTotal(subtotais);
        const desconto = this.calcularDesconto(valorTotal);
        const valorFinal = this.calcularValorFinal(valorTotal, desconto);
        return {valorTotal, desconto, valorFinal};
    }

    private calcularValorTotal(subtotais : number []) : Money{
        let valorTotal = new Money(0, Currencies.BRL)
        for(const subtotal of subtotais){
            valorTotal = valorTotal.add(new Money(subtotal * 100, Currencies.BRL));
        }
        return valorTotal;
    }

    private calcularDesconto(valorTotal : Money) : Money{
        if(this.horasCorridas > 2){
            const desconto = Math.round(valorTotal.amount * 0.1);
            return new Money(desconto, Currencies.BRL);
        }
        return new Money(0, Currencies.BRL);
    }

    private calcularValorFinal(valorTotal : Money , desconto : Money) :Money{
        return valorTotal.subtract(desconto);
    }

    calcularSubtotal(item : Item, devolucao) : Money{
        const horasCorridas = this.calcularHorasCorridas(devolucao);
        const valorPorHora = new Money(item.valorPorHora * 100, Currencies.BRL);
        return valorPorHora.multiply(horasCorridas);
    }

    private calcularHorasCorridas(devolucao){
        const dataLocacao = new Date(this.locacaoEscolhida!.entrada)
        const dataDevolucao = new Date(devolucao)
        const diferencaEmMilissegundos = dataDevolucao.getTime() - dataLocacao.getTime();
        const horas = diferencaEmMilissegundos / (1000 * 60 * 60);

        if (horas >= this.locacaoEscolhida!.numeroDeHoras && horas <= this.locacaoEscolhida!.numeroDeHoras + 0.25) {
            this.horasCorridas = this.locacaoEscolhida!.numeroDeHoras;
        }else {
            this.horasCorridas = Math.ceil(horas);
        }
        return this.horasCorridas;
    }
}