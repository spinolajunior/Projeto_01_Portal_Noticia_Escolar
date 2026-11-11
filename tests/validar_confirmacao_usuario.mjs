// Testa os eventos reais da confirmação com DOM simulado; não acessa dados reais.
import {readFile} from 'node:fs/promises';
import {Script, createContext} from 'node:vm';

const fonte = await readFile(new URL('../application/view/include/pages/dinamico/paineis/painel_usuarios.php', import.meta.url), 'utf8');
const inicio = fonte.indexOf("        const avisoExclusao =");
const fim = fonte.indexOf('        window.addEventListener("pageshow"', inicio);
if (inicio < 0 || fim < 0) throw new Error('Bloco de confirmação não encontrado.');

let foco = null;
function elemento(id) {
  return {id, dataset: {}, disabled: false, textContent: '', eventos: {},
    addEventListener(nome, fn) { this.eventos[nome] = fn; },
    focus() { foco = this.id; },
    disparar(nome) { const evento = {bloqueado: false, preventDefault() { this.bloqueado = true; }}; this.eventos[nome]?.(evento); return evento; },
  };
}
const ids = Object.fromEntries(['excluirUsuarioModal','confirmarExcluirUsuario','cancelarExcluirUsuario','nomeExcluirUsuario'].map(id => [id, elemento(id)]));
function formulario(nome) {
  const form = elemento(nome);
  form.dataset.nome = nome;
  form.botao = elemento('botao-'+nome);
  form.querySelector = () => form.botao;
  form.envios = 0;
  form.submit = () => form.envios++;
  return form;
}
const desktop = formulario('Pessoa Desktop');
const mobile = formulario('<img src=x onerror=alert(1)>');
const modal = {
  aberto: false,
  show() { this.aberto = true; ids.excluirUsuarioModal.disparar('shown.bs.modal'); },
  hide() { this.aberto = false; ids.excluirUsuarioModal.disparar('hidden.bs.modal'); },
};
const contexto = createContext({
  document: {getElementById: id => ids[id]},
  painel: {querySelectorAll: () => [desktop, mobile]},
  bootstrap: {Modal: {getOrCreateInstance: () => modal}},
});
new Script(fonte.slice(inicio, fim)).runInContext(contexto);
const resultados = [];
function verificar(teste, passou) { resultados.push({teste, passou}); }

const primeiro = desktop.disparar('submit');
verificar('Abrir aviso bloqueia envio imediato', primeiro.bloqueado && modal.aberto && desktop.envios === 0);
verificar('Cancelar recebe foco inicial', foco === 'cancelarExcluirUsuario');
modal.hide();
verificar('Cancelar mantém conta e retorna foco', desktop.envios === 0 && foco === desktop.botao.id);
ids.confirmarExcluirUsuario.disparar('click');
verificar('Confirmar sem formulário não envia', desktop.envios === 0 && mobile.envios === 0);
mobile.disparar('submit');
verificar('Nome potencialmente malicioso é atribuído como texto', ids.nomeExcluirUsuario.textContent === mobile.dataset.nome);
ids.confirmarExcluirUsuario.disparar('click');
verificar('Confirmar envia somente o formulário escolhido', mobile.envios === 1 && desktop.envios === 0);
verificar('Confirmação desabilita ações durante envio', mobile.botao.disabled && ids.confirmarExcluirUsuario.disabled);
ids.confirmarExcluirUsuario.disparar('click');
mobile.disparar('submit');
verificar('Cliques repetidos não repetem exclusão', mobile.envios === 1);
console.log(JSON.stringify({total: resultados.length, falhas: resultados.filter(r => !r.passou)}));
if (resultados.some(r => !r.passou)) throw new Error('Falha no fluxo de confirmação.');
