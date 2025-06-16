export class Avaria {
    constructor(
        public readonly id : number | null,
        public readonly descricao : string,
        public readonly item : number,
        public readonly dataHora : Date,
        public readonly funcionario : number,
        public readonly valor : number,
        public readonly imagem : File | null
    ){}

    validar() : string[]{
        const tiposImgPermitidos = ["image/jpg", "image/jpeg"];
        const problemas : string[] = [];

        if(this.imagem instanceof File && !tiposImgPermitidos.includes(this.imagem.type))
            problemas.push("Imagem deve ser dos tipos: "+tiposImgPermitidos.join(','));

        if(this.valor < 0)
            problemas.push("Valor da avaria deve ser maior do que zero.");

        return problemas;
    }
}