# LearningLab — Site WordPress

Site oficial do projeto **LearningLab UFC**, desenvolvido em WordPress com tema e plugin customizados.

🔗 Repositório: [github.com/LearningLabUFC/learninglab-wp](https://github.com/LearningLabUFC/learninglab-wp)

---

## Estrutura do Repositório

Este repositório é um **monorepo** que representa o conteúdo da pasta `wp-content` de uma instalação WordPress. Apenas os projetos desenvolvidos pela equipe são rastreados:

```
wp-content/                          ← raiz do repositório
├── .github/
│   └── workflows/
│       └── deploy.yml               ← CI/CD: deploy via FTP para produção
├── plugins/
│   └── learninglab-core/            ← Plugin principal (CPTs, taxonomias, meta boxes)
│       ├── includes/
│       │   ├── post-types/          ← Um arquivo PHP por CPT
│       │   ├── taxonomies/          ← Um arquivo PHP por taxonomia
│       │   └── meta-boxes/          ← Um arquivo PHP por grupo de meta boxes
│       └── learninglab-core.php
├── themes/
│   └── learninglab-site/            ← Tema WordPress
│       ├── assets/                  ← CSS, JS, fontes, imagens
│       ├── inc/                     ← Funções do tema (shortcodes, menus, etc.)
│       ├── template-parts/          ← Partes de template reutilizáveis
│       └── functions.php
├── composer.json                    ← Dependências de desenvolvimento (PHP stubs)
├── GIT_WORKFLOW.md                  ← Guia de uso do Git para a equipe
└── README.md                        ← Este arquivo
```

---

## Pré-requisitos

| Ferramenta | Versão recomendada | Link |
|------------|--------------------|------|
| PHP | 8.1+ | https://www.php.net |
| Composer | 2.x | https://getcomposer.org |
| Git | qualquer recente | https://git-scm.com |
| LocalWP | qualquer recente | https://localwp.com |

> **LocalWP** é a ferramenta recomendada para rodar o WordPress localmente. Ele já inclui PHP, MySQL e servidor web pré-configurados.

---

## Instalação Local (passo a passo)

### 1. Criar um site no LocalWP

1. Abra o **LocalWP** e clique em **+ Create a new site**
2. Defina o nome do site (ex: `learninglab`)
3. Escolha o ambiente (PHP 8.1+, MySQL 8.0+)
4. Conclua a criação — o LocalWP vai montar a instalação WordPress completa

### 2. Clonar o repositório dentro do site

> O repositório deve ser clonado na pasta `wp-content` do site criado pelo LocalWP.

```bash
# Navegue até a pasta wp-content do seu site LocalWP
# Exemplo (Mac):
cd ~/Local\ Sites/learninglab/app/public/wp-content

# Verifique que a pasta está vazia (exceto pelo index.php padrão)
ls

# Clone o repositório nesta pasta
git clone https://github.com/LearningLabUFC/learninglab-wp.git .
```

> O `.` no final faz o clone diretamente na pasta atual, sem criar uma subpasta.

### 3. Instalar dependências de desenvolvimento (Composer)

As dependências do Composer são apenas para **desenvolvimento** (WordPress PHP stubs para autocomplete no editor). Não são necessárias em produção.

```bash
# Dentro da pasta wp-content
composer install
```

Isso criará a pasta `vendor/` com os stubs do WordPress. O `vendor/` é ignorado pelo `.gitignore` e **não vai para o repositório**.

### 4. Ativar o tema e o plugin no WordPress

1. Acesse o painel WordPress em `http://learninglab.local/wp-admin`
2. Vá em **Plugins → Plugins instalados** e ative o **LearningLab Core**
3. Vá em **Aparência → Temas** e ative o **LearningLab Site**

### 5. Verificar a instalação

Após ativar o tema e o plugin, os seguintes itens devem aparecer no menu lateral do painel WP:

- **Membros** (CPT)
- **Subprojetos** (CPT)
- **Cursos** (CPT)
- **Artigos** (CPT)
- **Avaliações** (CPT)

---

## Desenvolvimento

### Fluxo de trabalho Git

Leia o [GIT_WORKFLOW.md](./GIT_WORKFLOW.md) para entender como a equipe usa Git, cria branches e abre Pull Requests.

### Branches principais

| Branch | Propósito |
|--------|-----------|
| `main` | Produção (deploy automático) |
| `develop` | Integração de features |

**Nunca** faça push diretamente em `main` ou `develop`. Sempre trabalhe em uma branch separada e abra um PR.

### Onde mexer

| O que você quer fazer | Onde está |
|-----------------------|-----------|
| Criar/editar um CPT | `plugins/learninglab-core/includes/post-types/` |
| Criar/editar uma taxonomia | `plugins/learninglab-core/includes/taxonomies/` |
| Criar/editar uma meta box | `plugins/learninglab-core/includes/meta-boxes/` |
| Editar layout / templates | `themes/learninglab-site/` |
| Criar/editar um shortcode | `themes/learninglab-site/inc/shortcodes.php` |
| Adicionar CSS | `themes/learninglab-site/assets/css/` |
| Adicionar JS | `themes/learninglab-site/assets/js/` |

---

## Deploy

O deploy é feito **automaticamente** via GitHub Actions ao fazer merge em `main`.

- **Tema** → enviado via FTPS para `/wp-content/themes/learninglab-site/` no servidor
- **Plugin** → enviado via FTPS para `/wp-content/plugins/learninglab-core/` no servidor

Os secrets de FTP (`FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD`) são configurados pelo administrador no painel **Settings → Secrets** do repositório no GitHub.

---

## Tecnologias

- **WordPress** — CMS base
- **PHP 8.1+** — linguagem do back-end
- **Vanilla CSS / JS** — sem frameworks front-end
- **Composer** — gerenciamento de dependências de desenvolvimento
- **GitHub Actions** — CI/CD para deploy automatizado

---

## Contribuindo

1. Leia o [GIT_WORKFLOW.md](./GIT_WORKFLOW.md)
2. Faça fork ou crie uma branch a partir de `develop`
3. Abra um Pull Request com descrição clara do que foi feito
4. Aguarde a revisão de um colega antes do merge

---

> Dúvidas? Abra uma [issue](https://github.com/LearningLabUFC/learninglab-wp/issues) ou fale com o coordenador do projeto.
