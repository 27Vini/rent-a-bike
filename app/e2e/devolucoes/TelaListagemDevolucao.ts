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

    async deveConterDevolucao( id : number){
        await this.irPara('#devolucoes')
        const tbody = this.page.locator('tbody')
        tbody.waitFor();

        await expect(tbody).toContainText(id.toString());
    }



}