# AVA Moodle Docker

Este é um monorepo que gerencia duas imagens Docker relacionadas:

1. **`ctezlifrn/avamoodlebase`** - Imagem base do Moodle (pasta `/base`), esta tem basicamente o Moodle
2. **`ctezlifrn/avamoodle`** - Imagem principal do AVA (pasta `/main`), esta estende da anterior e adiciona os plugins que estiverem na pasta `/main/build/plugins/`

O sistema de versionamento segue o padrão `M.m.r.s`, onde:
1. `M` = Major version do Moodle
2. `m` = Minor version do Moodle
3. `r` = Release version do Moodle
4. `s` = Sequential version da CTE/DEAD/ZL/IFRN

As imagens rodam como `www-data` e o Apache escuta na porta **8080** (não privilegiada), para funcionar em qualquer
runtime (Docker, Podman, Kubernetes). Mapeie a porta do host para `8080`, por exemplo `-p 7080:8080`, e use
`http://<servico>:8080` nas chamadas entre contêineres.

## CI/CD com GitHub Actions

O projeto usa GitHub Actions para build e deploy automatizado. O workflow `.github/workflows/build-and-deploy.yml` é acionado automaticamente ao criar qualquer tag Git.

### Fluxo de Build Inteligente

O workflow possui uma trava de segurança por formato de tag e um mecanismo de build e deploy condicional:

1. **Validação do Formato da Tag (Trava de Segurança)**:
   - **Produção**: Apenas tags puramente numéricas no padrão `M.m.r.s` (ex: `4.5.12.072`).
   - **Teste / Homologação**: Tags numéricas terminadas obrigatoriamente em `-test` (ex: `4.5.12.072-test`).
   - Qualquer outro formato de tag (ex: `v1`, `wip`, `bugfix`) é bloqueado e não executa o build.

2. **Detecção de Mudanças na Imagem Base**:
   - Compara a pasta `/base` entre a tag atual e a tag anterior (`git diff`).
   - Se `/base` foi modificado (ou na primeira release), a imagem base `ctezlifrn/avamoodlebase:<versao>` é construída e publicada. Caso contrário, a etapa `build-base` é ignorada (*skipped*).

3. **Build da Imagem**:
   - **Produção** (`is_prod`): Constrói e envia a imagem principal `ctezlifrn/avamoodle:<tag>`.
   - **Teste** (`is_test`): Constrói e envia a imagem de desenvolvimento `ctezlifrn/avamoodledev:<tag>` (usando o estágio `dev` do Dockerfile).

4. **Deploy Automático**:
   - **Produção**: Atualiza o servidor via Docker Compose (job `deploy-main`) de qualquer tag que não termine e `-test`.
   - **Teste**: Atualiza o ambiente de homologação no cluster Kubernetes via Helm Chart (job `deploy-test`).

### Como Fazer um Release

```bash
# 1. Defina a versão desejada
export IMAGE_VERSION=4.5.12.072

# 2. Atualize a versão da imagem base no main/Dockerfile (se aplicável)
sed -i "s/MOODLE_IMAGE_VERSION=.*$/MOODLE_IMAGE_VERSION=${IMAGE_VERSION}/g" ./main/Dockerfile

# 3. Commit suas alterações
git add .
git commit -m "build: atualizar versao da imagem para ${IMAGE_VERSION}"
git push origin main

# 4a. Para Release de TESTE (deploy em Kubernetes via Helm):
git tag ${IMAGE_VERSION}-test
git push origin ${IMAGE_VERSION}-test

# 4b. Para Release de PRODUÇÃO (deploy via Docker Compose):
git tag ${IMAGE_VERSION}
git push origin ${IMAGE_VERSION}
```

### Configuração de Secrets e Variáveis

Configure as seguintes credenciais e variáveis no GitHub (Settings → Secrets and variables → Actions):

#### Organization / Repository Secrets & Variables

| Nome | Tipo | Descrição | Exemplo |
|------|------|-----------|---------|
| `DOCKERHUB_USERNAME` | Secret | Usuário do Docker Hub | `ctezlifrn` |
| `DOCKERHUB_TOKEN` | Secret | Token de Acesso ao Docker Hub | `dckr_pat_...` |
| `DOCKERHUB_HOST` | Variable | Registry Host | `docker.io` |
| `SSH_HOST` | Variable / Secret | Servidor de Produção (Docker Compose) | `10.4.5.10` |
| `K8S_HOST` | Variable / Secret | Servidor de Homologação (Helm/K8s) | `10.4.5.20` |
| `NVM_INSTALL_SHA256` | Variable | Checksum para instalador do NVM | `...` |
| `PHPUNIT_11_PHAR_SHA256` | Variable | Checksum do PHAR do PHPUnit 11 | `...` |

## Desenvolvimento Local

### Configuração do Pre-commit

Este repositório possui validações automáticas com `pre-commit` para verificação de sintaxe YAML, linting de Dockerfiles (`hadolint`), validação de scripts Shell (`shellcheck`), remoção de espaços em branco e verificação de chaves privadas.

Para instalar e ativar os ganchos locais:

```bash
# 1. Instalar a ferramenta (se ainda não instalada)
pip install pre-commit

# 2. Ativar os ganchos git no repositório
pre-commit install

# 3. Executar manualmente em todos os arquivos
pre-commit run --all-files
```

### Build Manual (sem CI/CD)

```bash
# Login no registry
docker login docker.io -u <username> -p <token>

# Build da imagem base (se necessário)
cd base
docker build -t ctezlifrn/avamoodlebase:4.5.11.044 .
cd ..

# Build da imagem principal
cd main
docker build --build-arg AVA_IMAGE_VERSION=4.5.11.048 \
  -t ctezlifrn/avamoodle:4.5.11.048 .

# Push para o registry
docker push ctezlifrn/avamoodlebase:4.5.11.044
docker push ctezlifrn/avamoodle:4.5.11.048
```

### Ambiente de Desenvolvimento Local

```bash
# Limpar volumes existentes (cuidado: apaga dados!)
sudo rm -rf volumes
mkdir -p volumes/moodle/data && touch volumes/moodle/data/.empty
chmod -R 777 volumes/moodle/data

# Iniciar contêineres
docker compose up -d

# Testar sincronização SUAP (exemplo)
curl -X POST \
  -H "Authentication: Token 1" \
  -d @./moodle__local_suap/sync_up_enrolments_sample.json \
  http://localhost:7080/local/suap/sync_up_enrolments.php
```

## Estrutura do Projeto

```
moodle_docker/
├── .github/
│   └── workflows/
│       └── build-and-deploy.yml   # Workflow CI/CD
├── base/                          # Imagem base (avamoodlebase)
│   ├── Dockerfile
│   ├── build/
│   └── runtime/
├── main/                          # Imagem principal (avamoodle)
│   ├── Dockerfile
│   └── build/
│       └── plugins/               # Plugins para instalação
│           ├── *.zip
└── docker-compose.yml
```

## Plugins que serão instalados

Você pode conferir a lista de [plugins aqui](PLUGINS_DOCUMENTATION).

## Configuração Adicional do Moodle

### Como adicionar máscara CPF no perfil

**additionalhtmlhead**
```html
<script src='http://moodle/lib/javascript.php/1692023308/lib/jquery/jquery-3.6.1.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js'></script>
```

**additionalhtmlfooter**
```html
<script>jQuery("#profilefield_cpf").mask("999.999.999-99");</script>
```

## Troubleshooting

### Workflow não está executando

- Verifique se a tag foi enviada: `git push origin --tags`
- Confirme que os secrets estão configurados corretamente no GitHub
- Veja os logs em: **Actions** tab no repositório GitHub

### Build falhou

- **Build Base**: Verifique erros no Dockerfile da pasta `/base`
- **Build Main**: Verifique erros no Dockerfile raiz
- Acesse os logs detalhados na aba Actions → selecione o workflow que falhou

### Deploy falhou

- Verifique conectividade SSH: `ssh root@$SSH_HOST`
- Verifique se o caminho `/var/dockers/docker-compose.yml` existe no servidor

### Forçar rebuild da imagem base

Se precisar forçar o rebuild da imagem base sem mudanças em `/base`:

```bash
# Faça uma mudança trivial em /base
echo "# $(date)" >> base/example.env
git add base/example.env
git commit -m "build: force base image rebuild"
git tag 4.5.10.030
git push origin 4.5.10.030
```

## Monitoramento

Após o deploy, o workflow aguarda 120 segundos e exibe os últimos 1000 logs do container. Monitore a saída para verificar se o Moodle iniciou corretamente.

Você também pode verificar manualmente:

```bash
ssh root@$SSH_HOST "cd /var/dockers && docker compose logs -f ava"
```

## Documentação relevante

- [Quick start](QUICKSTART)
- [License](LICENSE)
- [Security](SECURITY)
- [Change log](CHANGELOG)
- [Contributing](CONTRIBUTING)
