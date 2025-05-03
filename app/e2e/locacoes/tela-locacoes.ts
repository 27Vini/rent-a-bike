import{expect, Page} from '@playwright/test';

export class TelaLocacoes {
    constructor(private page:Page){}

    async abrir(){
        await this.page.goto('http://localhost:5173');
    }

    async irPara(seletor){
        await this.page.click(seletor)
    }

    async verificarLink(url:string){
        await expect(this.page).toHaveURL(url);
    }

    async deveExibirUmaLocacao(id:number){
        let seletor = this.page.locator("tbody");
        seletor.waitFor();

        await expect(seletor).toContainText(id.toString())
    }

}