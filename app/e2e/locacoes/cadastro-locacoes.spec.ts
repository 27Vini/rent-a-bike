import {test} from "@playwright/test";
import { TelaCadastroLocacao } from "./tela-cadastro-locacao";
import { sel } from "../../src/locacao/cadastro/seletores-cadastro-locacao";

test.describe('Cadastro de locações', () => {
    let tela:TelaCadastroLocacao;

    test.beforeEach(async ({page}) => {
        tela = new TelaCadastroLocacao(page);
        await tela.abrir();
    });

    test('cadastro realizado com sucesso', async () => {
        await tela.preencherFormCadastro('12345678900', 2, 'I0000009');
        await tela.esperarResposta('/itens')
        await tela.clicar(sel.botaoCadastrar);
        await tela.esperarResposta('/locacoes')

        await tela.deveExibirAMensagem("sucesso");
    });

    test.describe('dado não preenchido', () => {
        test('cliente não preenchido', async () => {
            await tela.preencherFormCadastro('', 2, 'I0000009');
            await tela.clicar(sel.botaoCadastrar);

            await tela.deveExibirAMensagem("Um cliente deve estar associado à locação.");
        });

        test('item não cadastrado', async () => {
            await tela.preencherFormCadastro('12345678900', 2, '');
            await tela.clicar(sel.botaoCadastrar);

            await tela.deveExibirAMensagem("Ao menos um item deve ser cadastrado na locaçao.");
        });
    })

    test.describe('busca de cliente', () => {
        test('pesquisar cliente deve retornar cliente correto', async () => {
            await tela.preencherCampo(sel.inputCliente, '98765432100');
            await tela.clicar(sel.botaoBuscarCliente);
            await tela.esperarResposta('/clientes')

            await tela.encontrarElementoNaTela(sel.listaCliente, 'Maria Oliveira');
        });

        test('pesquisar cliente não existente', async() => {
            await tela.preencherCampo(sel.inputCliente, '12345678920');
            await tela.clicar(sel.botaoBuscarCliente);

            await tela.deveExibirAMensagem('Cliente não encontrado');
        });
    });
    

    test.describe('busca de itens', () => {
        test.beforeEach(async () => {
            await tela.preencherCampo(sel.inputHoras, '2');
        });
        
        test('pesquisar item retorna item correto', async () => {
            await tela.preencherCampo(sel.inputCodigoItem, 'I0000009');
            await tela.clicar(sel.botaoBuscarItem);
            await tela.esperarResposta('/itens')

            await tela.encontrarElementoNaTela(sel.listaItem, 'Bomba de Ar');
        });

        test('pesquisar item não existente', async () => {
            await tela.preencherCampo(sel.inputCodigoItem, 'BIKE9011');
            await tela.clicar(sel.botaoBuscarItem);

            await tela.deveExibirAMensagem('Item não encontrado');
        });

        test('pesquisar item já adicionado', async () => {
            await tela.preencherCampo(sel.inputCodigoItem, 'I0000003');
            await tela.clicar(sel.botaoBuscarItem);
            await tela.esperarResposta('/itens');

            await tela.preencherCampo(sel.inputCodigoItem, 'I0000003');
            await tela.clicar(sel.botaoBuscarItem);
            await tela.esperarResposta('/itens');

            await tela.deveExibirAMensagem("já adicionado");
        });

        test('item adicionado aparece na tabela', async () => {
            await tela.preencherCampo(sel.inputCodigoItem, 'I0000009');
            await tela.clicar(sel.botaoBuscarItem);

            await tela.encontrarElementoNaTela(sel.tabelaItens, 'Bomba de Ar');
        });

        test('remoção de itens da locação', async () => {
            await tela.preencherCampo(sel.inputCodigoItem, 'I0000003');
            await tela.clicar(sel.botaoBuscarItem);
            await tela.esperarResposta('/itens');

            await tela.clicar(sel.botaoRemoverItem);
            await tela.naoDeveEstarNaTela(sel.tabelaItens, 'I0000003');

        });
    });

    // test('data e hora de entrega calculados corretamente', async () => {
    //     const dataImposta = new Date();
        
    //     const horas = 4;
    //     const dataEsperada = new Date();
    //     dataEsperada.setHours(dataImposta.getHours() + horas);

    //     await tela.preencherCampo(sel.inputHoras, horas.toString());
    //     await tela.encontrarElementoNaTela(sel.campoEntregaSpan, dataEsperada.toLocaleString());
    // });

    test('valores são calculados corretamente', async() => {
        await tela.preencherCampo(sel.inputHoras, '2');
        await tela.preencherCampo(sel.inputCodigoItem, 'I0000009');
        await tela.clicar(sel.botaoBuscarItem);

        await tela.preencherCampo(sel.inputCodigoItem, 'I0000008');
        await tela.clicar(sel.botaoBuscarItem);

        await tela.encontrarElementoNaTela(sel.campoValorFinal, '14.00');
    });
});