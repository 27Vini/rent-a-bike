export class ErrorLocacao extends Error {
    private problemas: string[] = [];

    public constructor( message?: string ) {
        super( message );
    }

    static comProblemas( problemas: string[] ): ErrorLocacao {
        const e = new ErrorLocacao();
        e.setProblemas( problemas );
        return e;
    }

    public setProblemas( problemas: string[] ) {
        this.problemas = problemas;
    }

    public getProblemas(): string[] {
        return this.problemas;
    }
}