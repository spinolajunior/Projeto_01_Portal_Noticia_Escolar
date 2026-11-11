// Somente compilação: não abre nem controla um navegador e não executa o site.
import {readdir, readFile} from 'node:fs/promises';
import {join} from 'node:path';
import {Script} from 'node:vm';
import {fileURLToPath} from 'node:url';
let total = 0;
const erros = [];
async function verificar(pasta) {
  for (const arquivo of await readdir(pasta, {withFileTypes: true})) {
    const caminho = join(pasta, arquivo.name);
    if (arquivo.isDirectory()) {
      if (!['assets', 'img'].includes(arquivo.name)) await verificar(caminho);
    } else if (arquivo.name.endsWith('.php')) {
      const fonte = (await readFile(caminho, 'utf8')).replace(/<\?[\s\S]*?\?>/g, '');
      for (const script of fonte.matchAll(/<script\b[^>]*>([\s\S]*?)<\/script>/g)) {
        if (!script[1].trim()) continue;
        total++;
        try { new Script(script[1], {filename: caminho}); }
        catch (erro) { erros.push({arquivo: caminho, erro: erro.message}); }
      }
    }
  }
}
await verificar(fileURLToPath(new URL('../application/view', import.meta.url)));
console.log(JSON.stringify({total, erros}));
if (erros.length) throw new Error('JavaScript inválido.');
