import {Page, expect} from '@playwright/test'

export class TelaListagemDevolucao{
    constructor(private page : Page){}

    async abrir(){
        await this.page.goto('http://localhost:5173')
    }

    async irPara(seletor : string){
        await this.page.click(seletor)
    }

    async verificarUrl(url : string){
        await expect(this.page).toHaveURL(url)
    }

    async esperarResposta(endpoint : string){
        await this.page.waitForResponse(response => response.url().includes(endpoint));
    }

    async deveConterDevolucao( id : number){
        await this.irPara('#devolucoes')
        this.esperarResposta('/devolucoes')
        const tbody = this.page.locator('tbody')

        await expect(tbody).toContainText(id.toString());
    }



}