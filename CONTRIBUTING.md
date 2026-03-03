# Guia de Contribuição

Obrigado por contribuir com o AVA Moodle Docker! Este documento descreve o fluxo de trabalho para contribuir com o projeto.

## Fluxo de Trabalho

### 1. Clone e Configure o Repositório

```bash
git clone <repository-url>
cd moodle_docker
```

### 2. Crie uma Branch para sua Feature/Fix

```bash
git checkout -b feat/minha-nova-funcionalidade
# ou
git checkout -b fix/correcao-de-bug
```

### 3. Faça suas Alterações

#### Alterações na Imagem Base (`/base`)

Se você modificar arquivos dentro da pasta `/base`, a imagem `ctezlifrn/avamoodlebase` será automaticamente reconstruída no próximo release.

```bash
# Exemplo: atualizar versão do PHP, adicionar extensões, etc.
vim base/Dockerfile
```

#### Alterações na Imagem Principal (`/main`)

Modificações na pasta `/main` afetam apenas a imagem principal `ctezlifrn/avamoodle`.

```bash
# Exemplo: adicionar plugins, atualizar configuração
cp novo-plugin.zip main/build/plugins/
vim main/Dockerfile
```

### 4. Teste Localmente

```bash
# Build local
docker build -t test-image .

# Ou use docker-compose
docker compose build
docker compose up -d
```

### 5. Commit com Mensagens Convencionais

Use [Conventional Commits](https://www.conventionalcommits.org/):

- `feat:` nova funcionalidade
- `fix:` correção de bug
- `docs:` documentação
- `style:` formatação de código
- `refactor:` refatoração
- `test:` testes
- `chore:` tarefas de manutenção
- `build:` mudanças no build ou dependências
- `ci:` mudanças no CI/CD

```bash
git add .
git commit -m "feat: adicionar suporte ao plugin XYZ"
```

### 6. Push e Crie um Pull Request

```bash
git push origin feat/minha-nova-funcionalidade
```

Crie um Pull Request no GitHub para revisão.

## Criando um Release

**Apenas maintainers** podem criar releases. O processo é:

### 1. Defina a Versão

O formato da tag é: `<MOODLE_VERSION>.<BUILD_NUMBER>`

Exemplo: `4.5.10.029` significa:
- Moodle 4.5.10
- Build 029

### 2. Crie e Envie a Tag

```bash
# Certifique-se de estar na branch principal
git checkout main
git pull origin main

# Crie a tag
git tag 4.5.10.029

# Envie a tag (isso aciona o CI/CD)
git push origin 4.5.10.029
```

### 3. Monitore o Workflow

1. Acesse a aba **Actions** no GitHub
2. Acompanhe o progresso do workflow "Build and Deploy"
3. Verifique os logs de cada job:
   - `detect-base-changes` - Detecta mudanças em `/base`
   - `build-base` - Build da imagem base (condicional)
   - `build-main` - Build da imagem principal
   - `deploy` - Deploy no servidor

### 4. Verifique o Deploy

Após o deploy, acesse o ambiente para confirmar que está funcionando:

```bash
# Verificar logs remotamente
ssh root@$SSH_HOST "cd /var/dockers && docker compose logs -n 100 ava"
```

## Estrutura de Branches

- `main` - Branch principal, sempre estável
- `feat/*` - Novas funcionalidades
- `fix/*` - Correções de bugs
- `docs/*` - Atualizações de documentação

## Checklist para Pull Requests

- [ ] Código segue os padrões do projeto
- [ ] Documentação atualizada (se aplicável)
- [ ] Testado localmente com `docker compose`
- [ ] Mensagens de commit seguem Conventional Commits
- [ ] Pull Request tem descrição clara do que foi alterado

## Dúvidas?

Abra uma issue no GitHub ou entre em contato com os maintainers do projeto.
