import { Currencies, Money } from "ts-money";
import { ItemLocacao } from "../item/item-locacao";
import { Avaria } from "../item/avaria";

export class ServicoDevolucao{
    static calcularValores(subtotais : number [], horasCorridas : number) : {valorTotal, desconto, valorFinal}{
        const valorTotal = this.calcularValorTotal(subtotais);
        const desconto = this.calcularDesconto(valorTotal, horasCorridas);
        const valorFinal = this.calcularValorFinal(valorTotal, desconto);
        return {valorTotal, desconto, valorFinal};
    }

    private static calcularValorTotal(subtotais : number []) : Money{
        let valorTotal = new Money(0, Currencies.BRL)
        for(const subtotal of subtotais){
            valorTotal = valorTotal.add(new Money(subtotal * 100, Currencies.BRL));
        }
        return valorTotal;
    }

    private static calcularDesconto(valorTotal : Money, horasCorridas : number) : Money{
        if(horasCorridas > 2){
            const desconto = Math.round(valorTotal.amount * 0.1);
            return new Money(desconto, Currencies.BRL);
        }
        return new Money(0, Currencies.BRL);
    }

    private static calcularValorFinal(valorTotal : Money , desconto : Money) :Money{
        return valorTotal.subtract(desconto);
    }

    public static calcularMulta(itensLocacao : ItemLocacao[], avariasDevolucao : Avaria[]) : Money{
        let multa = new Money(0, Currencies.BRL)

        for(const il of itensLocacao){
            for(const a of avariasDevolucao){
                if(a.item == il.item.id){              
                    const precoLocacao = Money.fromDecimal(il.precoLocacao, Currencies.BRL);
                    const valorAvaria = Money.fromDecimal(a.valor, Currencies.BRL);
                    const precoLocacaoPorcentagem = precoLocacao.multiply(0.10);

                    multa = multa.add(precoLocacaoPorcentagem).add(valorAvaria);
                }
            }
        }

        return multa;
    }
}