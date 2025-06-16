import { VisaoCadastroDevolucao } from "./visao-cadastro-devolucao.js";
import {GestorDevolucao} from '../gestor-devolucao.js';
import { Item } from "../../item/item.js";
import { ErrorDominio } from "../../../infra/ErrorDominio.js";
import { ItemLocacao } from "../../item/item-locacao.js";

export class ControladoraCadastroDevolucao {
    private visao : VisaoCadastroDevolucao
    private gestor : GestorDevolucao

    public constructor(visao : VisaoCadastroDevolucao){
        this.visao = visao;
        this.gestor = new GestorDevolucao()
    }

    async coletarFuncionarios(){
        try{
            const funcionarios = await this.gestor.coletarFuncionariosCadastrados();
            this.visao.exibirFuncionarios(funcionarios);
        }catch(erro){
            if(erro instanceof ErrorDominio)
                this.visao.exibirMensagens(erro.getProblemas(), true);
            else 
                this.visao.exibirMensagens([erro.message], true);
        }
    }

    async pesquisarLocacao(){
        const pesquisa = this.visao.coletarInputLocacao();
        try{
            const locacoes = await this.gestor.pesquisarLocacao(pesquisa);
            this.visao.exibirLocacoes(locacoes);
        }catch( error ){
            if(error instanceof ErrorDominio)
                this.visao.exibirMensagens(error.getProblemas(), true);
            else
                this.visao.exibirMensagens([ error.message ], true);
            
            return
        }
    }

    calcularValores(){
        const subtotais : number[] = this.visao.coletarSubtotais();
        const {valorTotal, desconto, valorFinal} = this.gestor.calcularValores(subtotais);
        this.visao.preencherValores({valorTotal, desconto, valorFinal})
    }

    calcularSubtotal(item : ItemLocacao){
        const devolucao = this.visao.coletarDataDevolucao();
        return this.gestor.calcularSubtotal(item, devolucao);
    }

    procurarLocacaoDoSelecionada(){
        const id = this.visao.coletarIdLocacaoDoSelect();
        const locacao = this.gestor.getLocacacaoDoGestorPeloId(id);
        this.visao.exibirLocacoes(locacao);
    }

    registrarAvaria(){
        try{
            const dadosAvaria = this.visao.coletarDadosAvaria();
            this.gestor.registrarAvaria(dadosAvaria);
            this.visao.exibirMensagens(["Avaria do item registrada com sucesso!"], false);
        }catch(error){
            if(error instanceof ErrorDominio)
                this.visao.exibirMensagens(error.getProblemas(), true);
            else
                this.visao.exibirMensagens([ error.message ], true);
        }
    } 

    async enviarDados(){
        try{
            const valorFinal = this.visao.coletarValorFinal();
            const idFuncionario = this.visao.coletarIdFuncionario();
            await this.gestor.salvarDevolucao(this.visao.coletarDataDevolucao(), valorFinal, idFuncionario);
            this.visao.limparForm();
            this.visao.exibirMensagens(['Devolvido com sucesso.'], false);
        }catch( error ){
            if(error instanceof ErrorDominio)
                this.visao.exibirMensagens(error.getProblemas(), true);
            else
                this.visao.exibirMensagens([ error.message ], true);
        }
    }


}