import {test} from '@playwright/test'
import { TelaDevolucao } from './TelaDevolucao.js'

test.describe('Listagem de devoluções', async () => {

    let tela : TelaDevolucao

    test.beforeEach( async ({page}) => {
        tela = new TelaDevolucao(page)
        await tela.abrir()
    })

    test('Consegue ir para a página correta', async () => {
        await tela.irPara('#devolucoes')
        await tela.verificarUrl('http://localhost:5173/app/pages/listagem-devolucoes.html')
    })

    test('Lista ao menos uma devolução', async () => {
        await tela.deveConterDevolucao(1);
    })

    test('Clicar em cadastrar deve ir para a página' , async () => {
        await tela.irPara('#devolucoes')
        await tela.irPara('.register-btn')
        await tela.verificarUrl('http://localhost:5173/app/pages/cadastrar-devolucoes.html')
    })
})