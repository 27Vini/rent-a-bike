import {test} from '@playwright/test';
import { TelaCadastroDevolucao } from './TelaCadastroDevolucao';
import { sel } from '../../src/devolucao/cadastro/seletores-cadastro-devolucao';

test.describe('Cadastro de Devoluções', async () =>{
    let tela : TelaCadastroDevolucao

    test.beforeEach( async ({page}) => {
        tela = new TelaCadastroDevolucao(page)
        await tela.abrir()
    })

    test('Cadastro é efetuado com sucesso com ID' , async () => {
        await tela.preencherDados({locacao : '2', data : new Date()});
        await tela.clicar(sel.devolverBtn);
    })

    test('Cadastro é efetuado com sucesso com CPF', async () => {
        await tela.preencherDados({locacao : '98765432100', data : new Date()})
        await tela.clicar(sel.devolverBtn);
        await tela.esperarResposta('/devolucoes')
        await tela.deveExibirMensagem('Cadastrado')
    })

    test('Cadastro sem campo preenchido retorna mensagem de erro', async ()=> {
        await tela.clicar(sel.devolverBtn);
        await tela.deveExibirMensagem('deve ser informada')
    })

    test('Data posterior a atual deve retornar mensagem com erro', async () =>{
        const data = new Date();
        data.setHours(data.getHours() + 3);
        await tela.preencherDados({locacao : '2', data : data});
        await tela.clicar(sel.devolverBtn);
        await tela.deveExibirMensagem('A data de devolução deve ser menor ou igual a atual.');
    })

    test('As locações de um cliente devem ser preenchidas dentro do select', async () => {
        await tela.exibirLocacoesDoCliente(new Date(), '98765432100');
    })

    test('A locação pesquisada pelo ID deve ser apresentada corretamente', async () => {
        await tela.exibirLocacaoComId(new Date(), '2')
    })

    test('Verifica itens da tabela', async () => {
        await tela.preencherDados({locacao : '2', data: new Date()});
        await tela.tabelaDeveConter(["I003", "I004"])
    })
})