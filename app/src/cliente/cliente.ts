export class Cliente{
    static readonly TAM_MAX_NOME = 100;
    static readonly TAM_MIN_NOME = 2;
    static readonly TAM_CPF = 11;
    static readonly TAM_CODIGO = 8;

    constructor(
        public readonly id:number,
        public readonly codigo:string,
        public readonly nome:string,
        public readonly cpf:string,
        public readonly foto:Blob
    ){}

    validar() : string[] {
        const erros:string[] = []

        if(this.codigo == '')
            erros.push("Código não deve ser vazio ou nulo.");

        if(this.nome.length < Cliente.TAM_MIN_NOME || this.nome.length > Cliente.TAM_MAX_NOME)
            erros.push("Nome deve ter entre "+Cliente.TAM_MIN_NOME+" e "+Cliente.TAM_MAX_NOME+" caracteres.");

        if(this.cpf.length != Cliente.TAM_CPF)
            erros.push("CPF deve ter "+Cliente.TAM_CPF+" números.")


        return erros;
    }
}