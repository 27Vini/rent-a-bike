import { VisaoFuncionario } from "./visao-funcionario";
import { GestorFuncionario } from "./gestor-funcionario";

export class ControladoraFuncionario {
    private visao:VisaoFuncionario;
    private gestor:GestorFuncionario;

    constructor(visao:VisaoFuncionario){
        this.visao = visao;
        this.gestor = new GestorFuncionario();
    }

    async obterFuncionarios(){
        try{
            const funcionarios = await this.gestor.coletarFuncionarios();
            if(funcionarios.length == 0){
                this.visao.exibirAlerta("Nenhum funcionário cadastrado.");
                return;
            }

            return funcionarios;
        }catch(erro){
            this.visao.exibirAlerta("Erro ao obter funcionários.");
        }
    }
}