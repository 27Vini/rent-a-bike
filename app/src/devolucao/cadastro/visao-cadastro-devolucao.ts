export interface VisaoCadastroDevolucao{
    exibirMensagem(mensagem : string);
    coletarInputLocacao();
    exibirLocacoes(locacoes);
    coletarDataDevolucao();
    coletarIdLocacaoDoSelect() : number;
    coletarSubtotais() : number[];
    coletarValorFinal() : string | null;
    preencherValores({valorTotal, desconto, valorFinal})
}