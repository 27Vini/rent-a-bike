export interface VisaoCadastroDevolucao{
    exibirFuncionarios(funcionarios);
    exibirMulta(multa);
    exibirMensagens(mensagem : string[], erro:boolean);
    coletarInputLocacao();
    coletarIdFuncionario();
    exibirLocacoes(locacoes);
    coletarDataDevolucao();
    coletarIdLocacaoDoSelect() : number;
    coletarSubtotais() : number[];
    coletarValorFinal() : string | null;
    limparForm() : void;
    preencherValores({valorTotal, desconto, valorFinal});
    coletarDadosAvaria();
    atualizarValorFinal(valorFinal);
}