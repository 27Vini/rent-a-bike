import {API} from '../../infra/api.js'
import {Locacao} from '../locacao/locacao.js';
import {Devolucao} from '../devolucao/devolucao.js'
import {Money, Currencies} from 'ts-money'
import { ErrorDominio } from '../../infra/ErrorDominio.js';
import { ServicoDevolucao } from './servico-devolucao.js';
import { ItemLocacao } from '../item/item-locacao.js';
import {toZonedTime } from 'date-fns-tz';
import { DevolucaoGrafico } from './relatorio/DevolucaoGrafico.js'

export class GestorDevolucao{

    private locacaoesDoCliente : [];
    private locacaoEscolhida : Locacao | undefined;
    private horasCorridas : number = 0;

    async coletarDevolucoes(){
        const response = await fetch( API + 'devolucoes');
        const retorno = await response.json();
        
        if(!retorno.success){
            throw ErrorDominio.comProblemas([retorno.message]);
        }

        return retorno.data;
    }

    async coletarDevolucoesGrafico(dataInicial: string, dataFinal: string) : Promise<DevolucaoGrafico[]>{
        const reponse = await fetch(API + `devolucoes?dataInicial=${dataInicial}&dataFinal=${dataFinal}`);
        const retorno = await reponse.json();

        if(!retorno.success){
            throw ErrorDominio.comProblemas([retorno.message]);
        }
        if(retorno.data.length == 0){
            throw ErrorDominio.comProblemas(['Não há dados para essas datas.']);
        }
        return retorno.data;
    }

    async coletarFuncionariosCadastrados(){
        const response = await fetch(API + "funcionarios");
        const retorno = await response.json();

        if(!retorno.success)
            throw ErrorDominio.comProblemas(["Erro ao obter funcionários."+response.status]);

        return retorno.data;
    }

    async pesquisarLocacao(pesquisa : string) : Promise<any>{
        if(! /^\d+$/.test(pesquisa)){
            throw ErrorDominio.comProblemas([ "O campo de locação deve estar preenchido com apenas números." ] )
        }

        let parametro : string;
        parametro = `verificarAtivo=1`;
        if(pesquisa.length == 11){
            parametro += `&cpf=${pesquisa}`;
        }else{
            parametro += `&id=${pesquisa}`;
        }

        const response = await fetch( API + `locacoes?${parametro}`)
        const locacoes = await response.json();
        if(!locacoes.success){
            throw ErrorDominio.comProblemas([locacoes.message]);
        }
        this.horasCorridas = 0;
        this.locacaoesDoCliente = locacoes.data;
        if(locacoes.data.length == 1){
            const locacao = locacoes.data[0];
            this.locacaoEscolhida = new Locacao(locacao.id, locacao.cliente.id, locacao.funcionario.id, locacao.itensLocacao, locacao.entrada, locacao.horas, 0, locacao.valorTotal, locacao.previsaoDeEntrega);
            return locacao
        }
        return locacoes.data;
    }

    private criarDevolucao(dataDeDevolucao, valorPago, idFuncionario) : Devolucao{
        const dataDevolucaoReal = dataDeDevolucao ? new Date(dataDeDevolucao) : undefined
        const devolucao = new Devolucao(10, dataDevolucaoReal, valorPago, this.locacaoEscolhida?.id, idFuncionario);
        const problemas : string [] = devolucao.validar();
        if(problemas.length > 0){
            throw ErrorDominio.comProblemas(problemas);
        }
        const dataBrasileira = toZonedTime(dataDevolucaoReal!, 'America/Sao_Paulo');
        devolucao.setDataDeDevolucao(dataBrasileira);
        return devolucao
    }

    async salvarDevolucao(dataDevolucao, valorPago, idFuncionario){
        const devolucao = this.criarDevolucao(dataDevolucao, valorPago, idFuncionario);
        const response = await fetch( API + 'devolucoes', {method : 'POST', headers : {'Content-Type': 'application/json'}, body : JSON.stringify(devolucao)})

        const retorno = await response.json();
        if(!retorno.success){
            throw ErrorDominio.comProblemas([retorno.message]);
        }
    }

    getLocacacaoDoGestorPeloId(id : number) : Locacao | undefined{
        const locacao = this.locacaoesDoCliente.find( l => l.id == id);
        this.locacaoEscolhida = locacao;
        return locacao;
    }

    calcularValores(subtotais : number []) : {valorTotal, desconto, valorFinal}{
        return ServicoDevolucao.calcularValores(subtotais, this.horasCorridas);
        // const valorTotal = this.calcularValorTotal(subtotais);
        // const desconto = this.calcularDesconto(valorTotal);
        // const valorFinal = this.calcularValorFinal(valorTotal, desconto);
        // return {valorTotal, desconto, valorFinal};
    }

    calcularSubtotal(item : ItemLocacao, devolucao) : Money{
        const horasCorridas = this.calcularHorasCorridas(devolucao);
        const valorPorHora = new Money(item.precoLocacao * 100, Currencies.BRL);
        return valorPorHora.multiply(horasCorridas);
    }

    public calcularHorasCorridas(devolucao){
        if(this.horasCorridas != 0 ){
            return this.horasCorridas;
        }
        const dataLocacao = new Date(this.locacaoEscolhida!.entrada)
        const dataDevolucao = new Date(devolucao)
        const diferencaEmMilissegundos = dataDevolucao.getTime() - dataLocacao.getTime();
        const horas = diferencaEmMilissegundos / (1000 * 60 * 60);

        if (horas >= this.locacaoEscolhida!.numeroDeHoras && horas <= this.locacaoEscolhida!.numeroDeHoras + 0.25) {
            this.horasCorridas = this.locacaoEscolhida!.numeroDeHoras;
        }else if(horas < this.locacaoEscolhida!.numeroDeHoras){
            this.horasCorridas = Math.floor(horas) == 0 ? 1 : Math.floor(horas);
        }
        else {
            this.horasCorridas = Math.ceil(horas);
        }
        return this.horasCorridas;
    }
}