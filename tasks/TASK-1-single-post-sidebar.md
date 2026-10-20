# TASK 1 — Redesign da Página de Post com Sidebar de Metadados e Compartilhamento

## 📌 Contexto
Atualmente, a página de post individual (`single.php` / `template-parts/content-article.php`) exibe o nome do autor, data e hora aglomerados dentro da faixa roxa superior. Além disso, no rodapé do artigo há um bloco extenso com a biografia do autor (`author-section.php`), gerando repetição visual (a biografia completa já possui sua página dedicada em `author.php`).

Seguindo o padrão de design já adotado com sucesso nas páginas individuais de **Cursos** e **Softwares**, os posts do blog devem adotar um layout moderno de duas colunas, com o cabeçalho limpo e uma sidebar lateral elegante para metadados e ações.

---

## 🎯 Escopo e Requisitos

### 1. Faixa Roxa Superior (`.container-header`)
- Deve conter **apenas** o título do post centralizado e limpo;
- Remover os dados de autor, data e hora de dentro do cabeçalho roxo.

### 2. Layout em Duas Colunas
- **Coluna Principal (esquerda no desktop):** Imagem destacada e o corpo do texto do artigo;
- **Sidebar de Metadados (direita no desktop / acima do texto no mobile):**
  - **Card do Autor:** Avatar do autor e nome com link para o seu arquivo (`author.php`);
  - **Categoria:** Pílula/badge clicável com o nome da categoria principal;
  - **Data e Hora:** Data de publicação formatada de forma amigável;
  - **Tempo de Leitura Estimado:** Indicação calculada automaticamente com base no tamanho do texto (ex: *"X min de leitura"*);
  - **Compartilhamento Social:** Botões para compartilhar no WhatsApp, LinkedIn, X (Twitter) e um botão de "Copiar Link" com feedback visual temporário (ex: *"Link copiado!"*).
- A sidebar deve ser **sticky** no desktop enquanto o usuário rola o artigo, e se posicionar de forma fluida acima do conteúdo em telas mobile.

### 3. Limpeza do Rodapé
- Remover o bloco repetido de biografia do autor ao final do post (`template-parts/author-section.php`), mantendo apenas as seções de comentários e navegação entre posts.

---

## 💡 Dicas e Orientações Técnicas

- **Cálculo de tempo de leitura:**  
  Crie uma função helper (ex: em `inc/helpers.php` ou `inc/template-tags.php`). Uma boa média de leitura para estimativa é de aproximadamente **200 palavras por minuto** (dica: utilize funções nativas como `strip_tags()` e `str_word_count()` sobre o conteúdo do post).

- **Links de compartilhamento:**  
  Utilize URLs de compartilhamento padrão das plataformas:
  - *WhatsApp:* `https://api.whatsapp.com/send?text=...`
  - *LinkedIn:* `https://www.linkedin.com/sharing/share-offsite/?url=...`
  - *X (Twitter):* `https://twitter.com/intent/tweet?url=...&text=...`
  - *Copiar link:* Utilize a API `navigator.clipboard.writeText()` com tratamento para fallback e uma classe para exibir o feedback temporário.

- **Estilização e Design System:**  
  Aproveite as variáveis CSS já existentes no tema (`--ll-accent`, `--ll-surface`, `--ll-border`, `--ll-text-muted`, etc.) para garantir harmonia com o restante do site. Para o comportamento sticky, configure `position: sticky; top: ...` na coluna lateral para telas desktop (`@media (min-width: 992px)`).

---

## 📁 Arquivos Envolvidos
- `themes/learninglab-site/template-parts/content-article.php` *(estrutura HTML do post)*
- `themes/learninglab-site/assets/css/single.css` *(estilos da sidebar, grid e botões)*
- `themes/learninglab-site/inc/helpers.php` *(helper para tempo de leitura)*
- `themes/learninglab-site/assets/js/main.js` *(ou script pontual para a ação de copiar link)*

---

## ✅ Critérios de Aceite (Definition of Done)
- [ ] A faixa roxa superior exibe exclusivamente o título do post centralizado.
- [ ] A sidebar exibe avatar e nome do autor com link funcional para a página de autor.
- [ ] A categoria do post é exibida em formato de badge clicável.
- [ ] O tempo estimado de leitura é calculado e exibido dinamicamente.
- [ ] Os botões de WhatsApp, LinkedIn e X abrem a tela de compartilhamento com a URL e título do post.
- [ ] O botão de copiar link copia a URL atual para a área de transferência e exibe um feedback visual claro.
- [ ] No desktop, a sidebar acompanha a rolagem (sticky) sem sobrepor outros elementos.
- [ ] No mobile, a sidebar é exibida acima do corpo do texto, sem quebrar o layout.
- [ ] O box grande de biografia foi removido do rodapé do post.

---

## 🧪 Cenários de Teste
1. **Desktop:** Acesse um post longo no blog. Role a página e certifique-se de que a sidebar acompanha a leitura suavemente.
2. **Mobile:** Acesse o post em modo responsivo (< 768px). Confirme que a sidebar fica posicionada antes do texto e que todos os botões permanecem confortáveis para o toque.
3. **Compartilhamento:** Clique no botão de copiar link e cole em outra aba para confirmar se a URL copiada está correta.
4. **Posts com pouco ou muito texto:** Verifique se o cálculo de minutos de leitura faz sentido (mínimo de 1 min).

---

## 🚀 Instruções de Entrega
1. Crie uma nova branch a partir de `develop`:
   ```bash
   git checkout develop && git pull
   git checkout -b feat/single-post-sidebar
   ```
2. Desenvolva e teste localmente no LocalWP.
3. Abra um Pull Request para a branch `develop` com prints demonstrando a visualização em Desktop e Mobile.
