import { Page } from "@playwright/test";
import { APP } from "../infra/app";
import { sel } from "../src/login/seletores-login";

export async function logar(page: Page) {
    await page.goto(APP);

    await page.fill(sel.campoUsuario, '12345678901');
    await page.fill(sel.campoSenha, 'senha123');

    await page.click(sel.botaoLogar);
    await page.waitForURL('**/listagem-locacoes.html');
}
