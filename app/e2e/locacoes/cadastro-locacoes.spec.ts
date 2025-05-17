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
        await tela.preencherFormCadastro('12345678921', 2, 'BIKE9001');
        await tela.clicar(sel.botaoCadastrar);

        await tela.deveExibirAMensagem("sucesso");
    });

    test.describe('dado não preenchido', () => {
        test('cliente não preenchido', async () => {
            await tela.preencherFormCadastro('', 2, 'BIKE9001');
            await tela.clicar(sel.botaoCadastrar);

            await tela.deveExibirAMensagem("Um cliente deve estar associado à locação.");
        });

        test('item não cadastrado', async () => {
            await tela.preencherFormCadastro('12345678921', 2, '');
            await tela.clicar(sel.botaoCadastrar);

            await tela.deveExibirAMensagem("Ao menos um item deve ser cadastrado na locaçao.");
        });
    })

    test.describe('busca de cliente', () => {
        test('pesquisar cliente deve retornar cliente correto', async () => {
            await tela.preencherCampo(sel.inputCliente, '12345678921');
            await tela.clicar(sel.botaoBuscarCliente);

            await tela.encontrarElementoNaTela(sel.listaCliente, 'Carlos da Silva');
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
            await tela.preencherCampo(sel.inputCodigoItem, 'BIKE9001');
            await tela.clicar(sel.botaoBuscarItem);

            await tela.encontrarElementoNaTela(sel.listaItem, 'Bicicleta aro 29');
        });

        test('pesquisar item não existente', async () => {
            await tela.preencherCampo(sel.inputCodigoItem, 'BIKE9011');
            await tela.clicar(sel.botaoBuscarItem);

            await tela.deveExibirAMensagem('Nenhum item encontrado');
        });

        test('item adicionado aparece na tabela', async () => {
            await tela.preencherCampo(sel.inputCodigoItem, 'BIKE9001');
            await tela.clicar(sel.botaoBuscarItem);

            await tela.encontrarElementoNaTela(sel.tabelaItens, 'Bicicleta aro 29');
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
        await tela.preencherCampo(sel.inputCodigoItem, 'BIKE9001');
        await tela.clicar(sel.botaoBuscarItem);

        await tela.preencherCampo(sel.inputCodigoItem, 'EQP0002');
        await tela.clicar(sel.botaoBuscarItem);

        await tela.encontrarElementoNaTela(sel.campoValorFinal, '40.00');
    });
});