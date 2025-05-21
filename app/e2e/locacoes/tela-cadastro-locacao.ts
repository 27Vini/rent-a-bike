import{expect, Page} from '@playwright/test';
import{sel} from '../../src/locacao/cadastro/seletores-cadastro-locacao';

export class TelaCadastroLocacao{
    constructor(private page:Page){}

    async abrir(){
        await this.page.goto("http://localhost:5173/app/pages/cadastrar-locacao.html");
    }

    async clicar(elemento:string){
        await this.page.click(elemento);
    }

    async esperarResposta(endpoint : string){
        await this.page.waitForResponse(response => response.url().includes(endpoint));
    }

    async preencherCampo(campo:string, valor:string){
        await this.page.fill(campo, valor);
        await this.page.locator(campo).evaluate(el => el.blur());
    }

    async preencherFormCadastro(cpfCliente:string, qtdHoras:number, codigoItem:string){
        await this.preencherCampo(sel.inputCliente, cpfCliente);
        await this.clicar(sel.botaoBuscarCliente);
        //await this.esperarResposta('/clientes')

        await this.preencherCampo(sel.inputHoras, qtdHoras.toString());
        await this.page.locator(sel.inputHoras).evaluate(el => el.blur());

        await this.preencherCampo(sel.inputCodigoItem, codigoItem);
        await this.clicar(sel.botaoBuscarItem);
        //await this.esperarResposta('/itens')

    }

    async deveExibirAMensagem(mensagemEsperada:string){
        let seletor = this.page.locator(sel.output);
        seletor.waitFor();

        await expect(seletor).toContainText(mensagemEsperada);
    }

    async encontrarElementoNaTela(seletor:string, texto:string){
        let lista = this.page.locator(seletor);

        await expect(lista).toContainText(texto);
    }
}