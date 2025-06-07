export interface VisaoCadastroLocacao{
    coletarDados() : {funcionario, cliente, horas};
    coletarHoras();
    coletarCliente();
    coletarCodigoItem();

    exibirFuncionarios(funcionarios);
    exibirCliente({id, nome, foto});
    exibirItem({codigo, descricao, disponibilidade, avarias, valorPorHora});
    construirTabela({itens, valores});
    exibirDataHoraEntrega(entrega:Date);
    exibirMensagens(mensagens : string[], erro:boolean);

    limparTelaCadastro();
}