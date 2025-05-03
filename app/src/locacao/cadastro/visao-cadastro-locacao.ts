export interface VisaoCadastroLocacao{
    exibirMensagem(mensagem : string);
    coletarDados(dados : {funcionario, cliente, itens, horas});
}