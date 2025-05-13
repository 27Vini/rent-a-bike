import { ErrorLocacao } from "../ErrorLocacao";

export function salvarEmLocalStorage(chave:string, dados:Object|number|string){
    if(chave == ''){
        throw ErrorLocacao.comProblemas(["Chave para LocalStorage deve estar preenchida."]);
    }

    const stringDados = JSON.stringify(dados);
    localStorage.setItem(chave, stringDados);
}

export function carregarDoLocalStorage(chave:string){
    if(chave == ''){
        throw ErrorLocacao.comProblemas(["Chave para LocalStorage deve estar preenchida."]);
    }

    const dados = localStorage.getItem(chave);
    return dados ? JSON.parse(dados) : [];
}