# Guia de Git/GitHub — LearningLab

Guia de fluxo de trabalho Git para a equipe de desenvolvimento do site LearningLab. O repositório é um **monorepo** que contém o tema WordPress e o plugin principal.

## Sumário

1. [Estrutura do Repositório](#estrutura-do-repositório)
2. [Configuração Inicial](#configuração-inicial)
3. [Estrutura de Branches](#estrutura-de-branches)
4. [Fluxo de Trabalho](#fluxo-de-trabalho)
5. [Convenções de Nomenclatura](#convenções-de-nomenclatura)
6. [Comandos Úteis](#comandos-úteis)
7. [Pull Requests](#pull-requests)
8. [Deploy (CI/CD)](#deploy-cicd)
9. [Referência Rápida](#referência-rápida)

---

## Estrutura do Repositório

Este repositório é um **monorepo** hospedado em `wp-content/`. Ele contém dois subprojetos rastreados pelo Git:

```
wp-content/
├── themes/
│   └── learninglab-site/      ← Tema WordPress
└── plugins/
    └── learninglab-core/      ← Plugin principal (CPTs, taxonomias, meta boxes)
```

> Apenas `themes/learninglab-site/` e `plugins/learninglab-core/` são rastreados pelo Git.
> Outros plugins, temas e uploads são ignorados via `.gitignore`.

---

## Configuração Inicial

### 1. Instalar o Git

**Mac:**
```bash
brew install git
```

**Windows:** Baixe em https://git-scm.com/download/win

**Linux (Ubuntu/Debian):**
```bash
sudo apt-get install git
```

### 2. Configurar sua identidade

```bash
git config --global user.name "Seu Nome"
git config --global user.email "seuemail@exemplo.com"
```

### 3. Clonar o repositório

> ⚠️ O repositório deve ser clonado **dentro** da pasta `wp-content` de uma instalação WordPress.
> Recomendamos usar o **LocalWP** para desenvolvimento local. Veja o [README.md](./README.md) para instruções completas.

```bash
# Navegue até wp-content da sua instalação local
cd /caminho/para/wordpress/wp-content

# Clone o repositório (isso vai preencher themes/ e plugins/)
git clone https://github.com/LearningLabUFC/learninglab-wp.git .
```

---

## Estrutura de Branches

| Branch | Propósito |
|--------|-----------|
| `main` | Produção — código estável, deploy automático via GitHub Actions |
| `develop` | Integração — recebe features prontas antes de ir para main |
| `feature/*` | Novas funcionalidades no **tema** |
| `plugin/*` | Novas funcionalidades no **plugin** |
| `fix/*` | Correções de bugs |
| `hotfix/*` | Correções urgentes diretamente de `main` |
| `refactor/*` | Refatorações sem mudança de comportamento |
| `docs/*` | Apenas documentação |

### Visualizar branches

```bash
# Branches locais
git branch

# Todas (locais + remotas)
git branch -a
```

---

## Fluxo de Trabalho

### 1. Sempre parta da `develop` atualizada

```bash
git checkout develop
git pull origin develop
```

### 2. Crie sua branch de trabalho

```bash
# Para mudanças no tema
git checkout -b feature/nome-da-funcionalidade

# Para mudanças no plugin
git checkout -b plugin/nome-da-funcionalidade
```

### 3. Desenvolva e faça commits frequentes

```bash
# Adicione arquivos modificados
git add .

# Faça commit com mensagem descritiva (veja convenções abaixo)
git commit -m "feat: adiciona grid de subprojetos na página inicial"
```

### 4. Envie sua branch para o remoto

```bash
git push origin feature/nome-da-funcionalidade
```

### 5. Abra um Pull Request

- Base: `develop`
- Compare: sua branch
- Preencha título, descrição e adicione reviewers

---

## Convenções de Nomenclatura

### Branches

```
feature/nome-da-funcionalidade     → nova feature no tema
plugin/nome-da-funcionalidade      → nova feature no plugin
fix/descricao-do-bug               → correção de bug
hotfix/descricao-urgente           → correção urgente em produção
refactor/componente-refatorado     → refatoração
docs/nome-do-documento             → documentação
```

### Commits (Conventional Commits)

| Prefixo | Quando usar | Exemplo |
|---------|-------------|---------|
| `feat:` | Nova funcionalidade | `feat: adiciona filtro de artigos por ano` |
| `fix:` | Correção de bug | `fix: corrige layout da grid de membros no mobile` |
| `refactor:` | Refatoração | `refactor: divide meta-boxes em arquivos separados` |
| `style:` | Formatação, CSS (sem lógica) | `style: ajusta espaçamento da seção hero` |
| `docs:` | Documentação | `docs: atualiza README com passos de instalação` |
| `chore:` | Tarefas de config/build | `chore: atualiza dependências do Composer` |
| `ci:` | Pipeline CI/CD | `ci: adiciona job de deploy do plugin` |

**Regras:**
- Use o **imperativo presente**: "adiciona", não "adicionado"
- Sem ponto final no título
- Máximo 72 caracteres no título
- Use o corpo do commit para explicar o *porquê*, não o *o quê*

---

## Comandos Úteis

```bash
# Ver status dos arquivos
git status

# Ver histórico resumido
git log --oneline

# Ver histórico com gráfico de branches
git log --graph --oneline --all

# Desfazer alterações não commitadas em um arquivo
git restore nome-do-arquivo

# Desfazer git add (tirar do stage)
git restore --staged nome-do-arquivo

# Desfazer último commit (mantendo as alterações)
git reset --soft HEAD~1

# Atualizar branch com develop (sem merge commit)
git rebase develop

# Buscar atualizações remotas sem aplicar
git fetch --all
```

### Resolver conflitos

```bash
git status          # identifica arquivos com conflito
# Edite os arquivos conflitantes
git add .           # marca como resolvidos
git commit          # finaliza o merge/rebase
```

---

## Pull Requests

### Criando um PR

1. Acesse o repositório em https://github.com/LearningLabUFC/learninglab-wp
2. Clique em **Pull Requests → New Pull Request**
3. Configure:
   - **base:** `develop`
   - **compare:** sua branch
4. Preencha:
   - **Título:** mensagem clara e curta
   - **Descrição:** o que foi feito, como testar, screenshots se aplicável
5. Adicione **reviewers** (colegas da equipe)
6. Clique em **Create Pull Request**

### Revisando um PR

1. Acesse o PR no GitHub
2. Vá em **Files changed** para ver as alterações
3. Clique no `+` ao lado de uma linha para comentar
4. Em **Review changes**, escolha: Approve, Comment ou Request changes

---

## Deploy (CI/CD)

O deploy é feito automaticamente via **GitHub Actions** ao fazer push na branch `main`.

O pipeline `.github/workflows/deploy.yml` possui dois jobs independentes:

| Job | O que faz |
|-----|-----------|
| `deploy-tema` | Envia `themes/learninglab-site/` para o servidor via FTPS |
| `deploy-plugin` | Envia `plugins/learninglab-core/` para o servidor via FTPS |

### Secrets necessários no repositório GitHub

| Secret | Descrição |
|--------|-----------|
| `FTP_SERVER` | Endereço do servidor FTP de produção |
| `FTP_USERNAME` | Usuário FTP |
| `FTP_PASSWORD` | Senha FTP |

> ⚠️ **Nunca** faça push direto em `main`. Sempre passe por `develop` e abra um PR.

---

## Referência Rápida

### Ciclo de trabalho diário

```bash
git checkout develop && git pull          # 1. Atualiza develop
git checkout -b feature/minha-feature     # 2. Cria branch
# ... desenvolve ...
git add . && git commit -m "feat: ..."    # 3. Commita
git push origin feature/minha-feature    # 4. Envia para o remoto
# 5. Abre Pull Request no GitHub para develop
```

### Tabela de branches

| Tipo | Formato | Exemplo |
|------|---------|---------|
| Feature (tema) | `feature/nome` | `feature/pagina-contato` |
| Feature (plugin) | `plugin/nome` | `plugin/cpt-eventos` |
| Correção | `fix/nome` | `fix/menu-mobile` |
| Urgente | `hotfix/nome` | `hotfix/falha-login` |
| Refatoração | `refactor/nome` | `refactor/css-variaveis` |

---

> **Dúvidas?** Fale com o coordenador do projeto ou abra uma [issue](https://github.com/LearningLabUFC/learninglab-wp/issues).
