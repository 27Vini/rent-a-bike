import { ControladoraItem } from './controladora-item.js';
import {VisaoItem} from './visao-item.js';
import { Money } from 'ts-money'
import { carregarDoLocalStorage, salvarEmLocalStorage } from '../../infra/util/gestor-localstorage.js';

export class VisaoItemHTML implements VisaoItem{
    private controladora:ControladoraItem;

    constructor(){
        this.controladora = new ControladoraItem(this);
    }

    async itemComCodigo(codigo:string){
        const itens = await this.controladora.obterItensComCodigo(codigo);
        let item = itens[0];

        let ul = document.querySelector("#lista-itens");
        ul!.innerHTML = this.transformarEmLI(item);

        if(!item.disponibilidade)
            return;

        this.salvarEmLocalStorage(item);
    }
    
    private transformarEmLI(i){
        let disponibilidade = i.disponibilidade ? 'disponível' : 'indisponível';
        let avarias = i.avarias == '' ? '' : `- ${i.avarias}`;
        let valorPorHora = Money.fromDecimal(i.valorPorHora, 'BRL');

        return `
            <li>
                ${i.descricao} - R$${valorPorHora}/h ${avarias} - <strong>${disponibilidade}</strong>
            </li>
        `
    }

    private salvarEmLocalStorage(item:Object){
        const itensSalvos:object[] = carregarDoLocalStorage("itens");

        let itemSalvo = itensSalvos.find(i => i.id == item.id);
        if(itemSalvo){
            this.exibirAlerta("item já adicionado.");
            return;
        }

        itensSalvos.push(item);
        salvarEmLocalStorage("itens", itensSalvos);
    }

    exibirAlerta(mensagem: string) {
        alert(mensagem);
    }
}