export interface VisaoCadastroLocacao{
    coletarDados() : {funcionario, cliente, horas};
    coletarHoras();

    exibirItem({codigo, descricao, disponibilidade, avarias, valorPorHora});
    construirTabela({itens, valores});
    exibirDataHoraEntrega(entrega:Date);
    exibirMensagens(mensagens : string[]);
}