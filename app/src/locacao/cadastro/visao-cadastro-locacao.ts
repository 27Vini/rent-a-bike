export interface VisaoCadastroLocacao{
    coletarDados() : {funcionario, cliente, itens, horas};
    coletarHoras();
    obterDoLocalStorage(chave:string);
    salvarNoLocalStorage(chave:string, dados:[]);
    
    exibirValorTotal(valor:number);
    exibirValorDesconto(desconto:number);
    exibirValorFinal(valorFinal);
    exibirDataHoraEntrega(entrega:Date);
    exibirMensagens(mensagens : string[]);

    atualizarTabela();
}