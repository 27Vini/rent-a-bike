import { API } from "../../infra/api";
import { Item } from "../item/item";
import { Locacao } from "./locacao";
import { ErrorDominio } from "../../infra/ErrorDominio";
import { ItemLocacao } from "../item/item-locacao";
import { ServicoLocacao } from "./servico-locacao";

export class GestorLocacao{
    private itens : ItemLocacao[] = [];

    private setItem(objItem){
        if(this.itens.some(e => e.item.id == objItem.id)){
            throw ErrorDominio.comProblemas(["item já adicionado."]);
        }

        if(!objItem.disponibilidade)
            throw new Error("Item indisponível.");

        const item = new Item(
            objItem.id,
            objItem.codigo,
            objItem.descricao,
            objItem.modelo,
            objItem.fabricante,
            objItem.valorPorHora,
            objItem.avarias,
            objItem.disponibilidade,
            objItem.tipo
        );

        const itemLocacao = new ItemLocacao(
            0,
            item, 
            item.valorPorHora,
            0
        );

        this.itens.push(itemLocacao);
    }

    private atualizarSubtotalItens(horas:number){
        this.itens.map(item => item.calcularSubtotal(horas));
    }

    getItens() : ItemLocacao[]{
        return this.itens;
    }

    private getDadosPrincipaisItem(){
        let dados:Object[] = [];
        for(let i of this.itens){
            dados.push({codigo:i.item.codigo, descricao:i.item.descricao, subtotal:i.subtotal});
        }

        return dados;
    }

    calcularLocacao(horas:number) : {valores, entrega, dadosItens}{
        this.atualizarSubtotalItens(horas);
        const valoresAtualizados = ServicoLocacao.calcularValores(this.itens, horas);

        return {
            valores     : valoresAtualizados,
            entrega     : valoresAtualizados.entrega,
            dadosItens  : this.getDadosPrincipaisItem()
        };
    }

    public async salvarLocacao({funcionario, cliente, horas}) : Promise<void> {
        const locacao = new Locacao(
            100, 
            cliente, 
            funcionario, 
            this.itens,
            new Date(),
            horas,
            0,
            0,
            ServicoLocacao.calcularEntrega(horas)
        );

        const problemas = locacao.validar();
        if(problemas.length > 0)
            throw ErrorDominio.comProblemas(problemas);

        this.cadastrarLocacao(locacao);
    }

    private async cadastrarLocacao(locacao:Locacao){
        const response = await fetch(API + "locacoes", 
            {
                method:"POST",
                headers:{"Content-Type":"application/json"},
                body:JSON.stringify(locacao)
            }
        );

        if(!response.ok) {
            throw ErrorDominio.comProblemas(['Erro ao cadastrar locação. Status: '+ response.status]);
        }
    }

    public async coletarLocacoes() : Promise<any>{
        const response = await fetch(API + "locacoes");
        if(!response.ok)
            throw ErrorDominio.comProblemas(["Erro ao obter as locações."]);

        return response.json();
    }

    public async coletarItemComCodigo(codigo:string){
        if(codigo == '')
            throw ErrorDominio.comProblemas(["Digite um código válido."]);
        
        const response = await fetch(API + "itens?codigo=" + codigo);
        if(!response.ok)
            throw ErrorDominio.comProblemas(["Erro ao obter itens. "+response.status]);

        const result = await response.json();
        if(result.length == 0)
            throw ErrorDominio.comProblemas(["Nenhum item encontrado."]);


        this.setItem(result[0]);
        return result[0];
    }

    public async coletarFuncionariosCadastrados(){
        const response = await fetch(API + "funcionarios");

        if(!response.ok)
            throw ErrorDominio.comProblemas(["Erro ao obter funcionários."+response.status]);

        return response.json();
    }
    
    public async coletarClienteComCodigoOuCpf(codigoCpf:string){
        if(! /^\d+$/.test(codigoCpf)){
            throw ErrorDominio.comProblemas(["O campo de cliente não deve conter caracteres especiais."]);
        }

        let campo = '';
        if(codigoCpf.length == 11){
            campo = "?cpf="+codigoCpf;
        } else {
            campo = "?codigo="+codigoCpf;
        }

        const response = await fetch(API + "clientes"+campo);
        if(!response.ok)
            throw ErrorDominio.comProblemas(["Erro ao obter cliente."+response.status]);

        return response.json();
    }
}