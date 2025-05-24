import { ErrorDominio } from "../../../infra/ErrorDominio";
import { GestorLocacao } from "../gestor-locacao";
import { VisaoCadastroLocacao } from "./visao-cadastro-locacao";

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
            await this.gestor.salvarLocacao({funcionario: dados.funcionario, cliente:dados.cliente, horas:dados.horas});

            this.visao.exibirMensagens(['Locação salva com sucesso!'], false);
            this.visao.limparTelaCadastro();
        }catch(erro){
            if(erro instanceof ErrorDominio)
                this.visao.exibirMensagens(erro.getProblemas(), true);
            else 
                this.visao.exibirMensagens([erro.message], true);
        }
    }

    async coletarFuncionarios(){
        try{
            const funcionarios = this.gestor.coletarFuncionariosCadastrados();
            return funcionarios;
        }catch(erro){
            if(erro instanceof ErrorDominio)
                this.visao.exibirMensagens(erro.getProblemas(), true);
            else 
                this.visao.exibirMensagens([erro.message], true);
        }
    }

    async coletarClienteComCodigoOuCpf(codigoCpf:string){
        try{
            const cliente = await this.gestor.coletarClienteComCodigoOuCpf(codigoCpf);
            return cliente;
        }catch(erro){
            if(erro instanceof ErrorDominio)
                this.visao.exibirMensagens(erro.getProblemas(), true);
            else 
                this.visao.exibirMensagens([erro.message], true);
        }
    }

    async coletarItemComCodigo(codigo:string) {
        try{
            const item = await this.gestor.coletarItemComCodigo(codigo);
            this.visao.exibirItem({codigo:item.codigo, descricao:item.descricao, disponibilidade:item.disponibilidade, avarias:item.avarias, valorPorHora:item.valorPorHora});

        }catch(erro){
            if(erro instanceof ErrorDominio)
                this.visao.exibirMensagens(erro.getProblemas(), true);
            else 
                this.visao.exibirMensagens([erro.message], true);
        }
    }

    removerItemComCodigo(codigo:string){
        try{
            this.gestor.removerItemComCodigo(codigo);
            this.visao.exibirMensagens(["Item de código "+codigo+" removido com sucesso."], false);
        }catch(erro){
            this.visao.exibirMensagens([erro.message], true);
        }
    }

    atualizarDados(){
        try{
            const horas = this.visao.coletarHoras();
            const dados = this.gestor.calcularLocacao(horas);

            this.visao.construirTabela({itens:dados.dadosItens, valores:dados.valores});
            this.visao.exibirDataHoraEntrega(dados.entrega);
        }catch(erro){
            if(erro instanceof ErrorDominio)
                this.visao.exibirMensagens(erro.getProblemas(), true);
            else 
                this.visao.exibirMensagens([erro.message], true);
        }
    }
}