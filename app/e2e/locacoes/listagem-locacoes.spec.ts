import { expect, test} from "@playwright/test";
import { TelaLocacoes } from "./tela-locacoes";

test.describe('Listagem de locações', () => {
    let tela:TelaLocacoes;

    test.beforeEach(async ({page}) => {
        tela = new TelaLocacoes(page);
        await tela.abrir()
    })

    test('abrir página correta de locações', async() => {
        await tela.irPara("#locacoes");
        await tela.verificarLink("http://localhost:5173/app/pages/listagem-locacoes.html");
    })

    test('verificar listagem de locações registradas', async() => {
        await tela.irPara(".pages #locacoes");
        await tela.deveExibirUmaLocacao(1);
    })

    test('não há itens na tabela', async() =>{
        //TO-DO
    })

    test('botão cadastrar altera a url', async() => {
        await tela.irPara("#locacoes");
        await tela.irPara("#cadastrar-locacao");
        await tela.verificarLink("http://localhost:5173/app/pages/cadastrar-locacao.html")
    })
})