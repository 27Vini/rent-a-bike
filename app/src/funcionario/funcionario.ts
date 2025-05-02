export class Funcionario{
    static readonly TAM_MIN_NOME = 2;
    static readonly TAM_MAX_NOME = 100;

    constructor(
        public readonly id:number,
        public readonly nome:string
    ){}

    validar() : string[]{
        const erros:string[] = [];

        if(this.nome.length < Funcionario.TAM_MIN_NOME || this.nome.length > Funcionario.TAM_MAX_NOME)
            erros.push("Nome do funcionario deve ter entre "+Funcionario.TAM_MIN_NOME+" e "+Funcionario.TAM_MAX_NOME);

        return erros;
    }
}