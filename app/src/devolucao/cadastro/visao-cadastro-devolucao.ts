export interface VisaoCadastroDevolucao{
    exibirMensagens(mensagem : string[]);
    coletarInputLocacao();
    exibirLocacoes(locacoes);
    coletarDataDevolucao();
    coletarIdLocacaoDoSelect() : number;
    coletarSubtotais() : number[];
    coletarValorFinal() : string | null;
    limparForm() : void;
    preencherValores({valorTotal, desconto, valorFinal})
}