# TASK 3 — Carrossel de Subprojetos na Home Page (Swiper.js)

## 📌 Contexto
O LearningLab desenvolve diversos subprojetos de pesquisa, extensão e tecnologia (CPT `subprojetos`). Atualmente, esses subprojetos só têm visibilidade na página interna `/subprojetos/`, sem qualquer presença na página inicial ([`front-page.php`](file:///Users/douglas/Local%20Sites/learninglab/app/public/wp-content/themes/learninglab-site/front-page.php)).

Como a Home já utiliza a biblioteca **Swiper.js** em outras seções ("Para leitura" e "O que dizem sobre nós"), devemos criar uma **nova seção de Subprojetos na Home** em formato de carrossel, reutilizando a biblioteca existente e o template part padronizado de cards.

---

## 🎯 Escopo e Requisitos

### 1. Seção de Subprojetos na Home (`front-page.php`)
- Posicionar a nova seção na home (sugestão: após Cursos e antes de Leitura);
- **Cabeçalho da seção:**
  - Título chamativo (ex: *"Nossos Subprojetos"*);
  - Botão de ação (CTA) direcionando para a página completa `/subprojetos/`.
- **Estrutura do Carrossel:**
  - Container estruturado para Swiper (`.swiper`, `.swiper-wrapper`, `.swiper-slide`);
  - Query para buscar os posts publicados do CPT `subprojetos`;
  - Dentro de cada slide, renderizar o card padrão utilizando o template part unificado `template-parts/content-card.php`;
  - Controles de navegação: setas (próximo/anterior) e paginação (bullets).

### 2. Comportamento do Carrossel (Swiper.js)
- Reutilizar a biblioteca Swiper já carregada no tema;
- **Responsividade (Breakpoints):**
  - Mobile: 1 card visível por vez;
  - Tablet: 2 cards visíveis por vez;
  - Desktop: 3 cards visíveis por vez;
- Suporte a rolagem por toque (*touch/swipe* em mobile e drag em desktop);
- Loop contínuo e autoplay com pausa ao passar o mouse (*hover*);
- **Isolamento de seletores:** As classes das setas e paginação devem ser específicas deste carrossel (ex: `.subprojetos-next`, `.subprojetos-prev`, `.subprojetos-pagination`) para não interferir nos demais carrosséis da página.

### 3. Estilização e Enfileiramento de Scripts
- Garantir que o CSS unificado de cards (`cards.css`) seja carregado na página inicial;
- Estilizar a seção em `assets/css/front-page.css` ou arquivo CSS dedicado, alinhando com o design das demais seções da home;
- Criar o script de inicialização do carrossel e enfileirá-lo adequadamente em `inc/styles-scripts.php`.

---

## 💡 Dicas e Orientações Técnicas

- **Consulta WordPress:**  
  Utilize `WP_Query` consultando `'post_type' => 'subprojetos'`, `'post_status' => 'publish'` e ordenando pelos mais recentes. Não se esqueça do `wp_reset_postdata()` ao final do loop.

- **Reaproveitamento de Componente:**  
  Dentro de cada `.swiper-slide`, chame `get_template_part('template-parts/content', 'card')`. Isso garante que qualquer melhoria futura no visual dos cards seja refletida automaticamente na Home.

- **Enfileiramento no tema:**  
  Em `inc/styles-scripts.php`:
  - Adicione `is_front_page()` na condição que carrega `cards.css`;
  - Crie e enfileire o script `swiper-init-subprojetos.js` com dependência de `'swiper-js'`.

- **Inspiração no código existente:**  
  Veja como os carrosséis de notícias e avaliações foram inicializados em `assets/js/swiper-init-noticias.js` para manter a mesma convenção de código.

---

## 📁 Arquivos Envolvidos
- `themes/learninglab-site/front-page.php` *(markup da nova seção)*
- `themes/learninglab-site/inc/styles-scripts.php` *(enfileiramento de scripts e estilos)*
- `themes/learninglab-site/assets/js/swiper-init-subprojetos.js` *(inicialização do Swiper)*
- `themes/learninglab-site/assets/css/front-page.css` *(estilos da seção e controles)*

---

## ✅ Critérios de Aceite (Definition of Done)
- [ ] A seção de subprojetos é exibida na Home Page com título e botão para ver todos.
- [ ] Os subprojetos são carregados dinamicamente via `WP_Query`.
- [ ] Os cards utilizam o template unificado `content-card.php` e herdam o estilo correto de `cards.css`.
- [ ] As setas de navegação e as bolinhas de paginação funcionam corretamente.
- [ ] O carrossel responde aos breakpoints (1 slide em mobile, 2 em tablet, 3 em desktop).
- [ ] A navegação deste carrossel não interfere nos outros carrosséis da home (notícias e avaliações).
- [ ] O botão "ver todos os projetos" redireciona para `/subprojetos/`.

---

## 🧪 Cenários de Teste
1. **Responsividade:** Redimensione o navegador ou utilize o DevTools para testar a transição entre 1, 2 e 3 cards por slide.
2. **Navegação:** Clique nas setas, nas bolinhas de paginação e arraste com o mouse/dedo.
3. **Conflito entre carrosséis:** Navegue pelo carrossel de subprojetos e confirme que os carrosséis de notícias e avaliações continuam funcionando de forma independente.
4. **Links dos cards:** Clique em um dos cards e certifique-se de que a página interna do subprojeto é aberta corretamente.

---

## 🚀 Instruções de Entrega
1. Crie uma nova branch a partir de `develop`:
   ```bash
   git checkout develop && git pull
   git checkout -b feat/home-subprojetos-carousel
   ```
2. Desenvolva e teste localmente no LocalWP.
3. Abra um Pull Request para a branch `develop` incluindo uma imagem ou vídeo demonstrando o carrossel em funcionamento na home.
