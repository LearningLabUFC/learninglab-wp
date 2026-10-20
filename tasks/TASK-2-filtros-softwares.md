# TASK 2 — Filtros por Categoria na Listagem de Softwares

## 📌 Contexto
O CPT `software` possui a taxonomia personalizada `tipo_software` já registrada e populada com termos como *Web App*, *Ferramenta*, *Biblioteca*, *Mobile*, *API*, etc.

Atualmente, a página de listagem ([`page-softwares.php`](file:///Users/douglas/Local%20Sites/learninglab/app/public/wp-content/themes/learninglab-site/page-softwares.php)) renderiza todos os softwares em um único grid estático. Para melhorar a usabilidade e a descoberta de ferramentas, precisamos de uma barra de filtros por categoria no topo da listagem, permitindo que o usuário visualize rapidamente apenas as ferramentas do seu interesse sem recarregar a página.

---

## 🎯 Escopo e Requisitos

### 1. Barra de Filtros Dinâmica
- Localizada acima do grid de cards de softwares;
- Deve renderizar dinamicamente os termos da taxonomia `tipo_software` que possuem posts associados;
- Deve conter uma opção inicial **"Todos"**, que inicia selecionada por padrão;
- Os botões/pílulas devem ter estilo interativo (hover e estado ativo bem destacados).

### 2. Comportamento de Filtragem (Client-side)
- A filtragem deve acontecer no frontend (via JavaScript), de forma instantânea e sem recarregamento de página;
- Ao clicar em uma categoria específica (ex: *Web App*), apenas os cards daquele tipo devem ficar visíveis;
- Ao clicar em "Todos", todos os cards voltam a aparecer;
- Acessibilidade: garanta atributos como `aria-selected` ou classes de estado ativo nos botões.

### 3. Estado Vazio (*Empty State*)
- Se porventura uma categoria selecionada não possuir cards visíveis, exibir uma mensagem amigável (ex: *"Nenhum software encontrado nesta categoria."*).

---

## 💡 Dicas e Orientações Técnicas

- **Consulta dos termos:**  
  Utilize `get_terms()` para buscar os termos de `tipo_software` (use `'hide_empty' => true` para não exibir categorias vazias).

- **Marcação nos cards:**  
  Uma abordagem simples e eficiente para o JavaScript é adicionar um atributo customizado (como `data-tipo="..."`) no container de cada card dentro do loop de `page-softwares.php`, preenchendo com o slug do termo.

- **Filtragem em JavaScript:**  
  Pode ser criada uma função Vanilla JS simples adicionando um listener de clique nos botões de filtro, alternando a visibilidade (`display: none` / `display: block` ou classes CSS) com base no atributo `data-tipo`. Se optar por um arquivo JS separado, lembre-se de enfileirá-lo em `inc/styles-scripts.php` condicionado a `is_page('softwares')`.

- **Estilização:**  
  Estilize as pílulas de filtro em `assets/css/software.css`, utilizando as variáveis de cores do tema (`--ll-accent`, `--ll-surface`, `--ll-border`, etc.) para manter coerência visual.

---

## 📁 Arquivos Envolvidos
- `themes/learninglab-site/page-softwares.php` *(markup dos filtros e atributos nos cards)*
- `themes/learninglab-site/assets/css/software.css` *(estilos das pílulas e estados)*
- `themes/learninglab-site/assets/js/` *(script de filtragem interativa)*
- `themes/learninglab-site/inc/styles-scripts.php` *(enfileiramento do script, se criado separadamente)*

---

## ✅ Critérios de Aceite (Definition of Done)
- [ ] A barra de filtros busca automaticamente os termos existentes de `tipo_software`.
- [ ] O botão "Todos" inicia ativo por padrão.
- [ ] Ao clicar em qualquer filtro, o grid é atualizado instantaneamente sem reload da página.
- [ ] O botão selecionado recebe indicação visual de ativo.
- [ ] O estado vazio é exibido caso nenhum item corresponda ao filtro selecionado.
- [ ] A barra de filtros quebra de linha de forma elegante em telas mobile.

---

## 🧪 Cenários de Teste
1. **Filtro básico:** Acesse `/softwares/`. Clique em um termo específico e verifique se apenas os softwares desse tipo são exibidos.
2. **Retorno para Todos:** Clique novamente no botão "Todos" e certifique-se de que a listagem completa reaparece.
3. **Novo termo no WP:** Cadastre um novo software no WordPress com um novo tipo de software. Verifique se o novo botão de filtro aparece automaticamente na página.
4. **Mobile:** Reduza a tela para largura de smartphone e confira se os botões se adaptam confortavelmente sem estourar o layout.

---

## 🚀 Instruções de Entrega
1. Crie uma nova branch a partir de `develop`:
   ```bash
   git checkout develop && git pull
   git checkout -b feat/softwares-category-filters
   ```
2. Desenvolva e teste localmente no LocalWP.
3. Abra um Pull Request para a branch `develop` com um GIF ou vídeo curto demonstrando a filtragem em funcionamento.
