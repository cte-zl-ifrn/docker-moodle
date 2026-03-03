# AVA Moodle Docker

Este é um monorepo que gerencia duas imagens Docker relacionadas:

1. **`ctezlifrn/avamoodlebase`** - Imagem base do Moodle (pasta `/base`), esta tem basicamente o Moodle
2. **`ctezlifrn/avamoodle`** - Imagem principal do AVA (pasta `/main`), esta estende da anterior e adiciona os plugins que estiverem na pasta `/main/build/plugins/`

O sistema de versionamento segue o padrão `M.m.r.s`, onde:
1. `M` = Major version do Moodle
2. `m` = Minor version do Moodle
3. `r` = Release version do Moodle
4. `s` = Sequential version da CTE/DEAD/ZL/IFRN

## CI/CD com GitHub Actions

O projeto usa GitHub Actions para build e deploy automatizado. O workflow é acionado automaticamente ao criar uma tag Git.

### Fluxo de Build Inteligente

1. **Detecção de Mudanças**: Verifica se houve alterações na pasta `/base` desde a última tag
2. **Build Condicional da Base**: Constrói `ctezlifrn/avamoodlebase` **apenas** se `/base` foi modificado
3. **Build Principal**: Sempre constrói `ctezlifrn/avamoodle` em toda tag
4. **Deploy Automático**: Atualiza o servidor de produção após builds bem-sucedidos

### Como Fazer um Release

```bash
# 1. Defina a versão
export BASE_IMAGE_VERSION=4.5.10.34

# 2. Atualize a versão da image base
cd ~/projetos/IFRN/ava/lms/moodle_docker
sed -i "s/MOODLE_IMAGE_VERSION=.*$/MOODLE_IMAGE_VERSION=${BASE_IMAGE_VERSION}/g" ./main/Dockerfile

# 3. Commit suas alterações
git add .
git commit -m "ci: [fix] As 5 ocorrências de secrets.SSH_HOST foram atualizadas para vars.SSH_HOST"
git push origin main

# 4. Crie uma tag (formato: versão Moodle.build)
git tag $BASE_IMAGE_VERSION

# 5. Envie a tag para o GitHub (isso aciona o CI/CD)
git push origin $BASE_IMAGE_VERSION
```

### Configuração dos Secrets

Configure os seguintes secrets no GitHub (Settings → Secrets and variables → Actions):

#### Secrets (Organization level - já configurados)

| Secret               | Descrição                     |
|----------------------|-------------------------------|
| `DOCKERHUB_USERNAME` | Usuário do Docker Hub         |
| `DOCKERHUB_TOKEN`    | Token de acesso do Docker Hub |

#### Variables (Organization level - já configuradas)

| Variable         | Descrição          | Exemplo      |
|------------------|--------------------|--------------|
| `DOCKERHUB_HOST` | Host do Docker Hub | `docker.io`  |

#### Secrets (Repository level - necessários)

| Secret     | Descrição               | Exemplo       |
|------------|-------------------------|---------------|
| `SSH_HOST` | IP/hostname do servidor | `10.4.5.10`   |

## Desenvolvimento Local

### Build Manual (sem CI/CD)

```bash
# Login no registry
docker login docker.io -u <username> -p <token>

# Build da imagem base (se necessário)
cd base
docker build -t ctezlifrn/avamoodlebase:4.5.10.032 .
cd ..

# Build da imagem principal
cd main
docker build --build-arg AVA_IMAGE_VERSION=4.5.10.032 \
  -t ctezlifrn/avamoodle:4.5.10.032 .

# Push para o registry
docker push ctezlifrn/avamoodlebase:4.5.10.032
docker push ctezlifrn/avamoodle:4.5.10.032
```

### Ambiente de Desenvolvimento Local

```bash
# Limpar volumes existentes (cuidado: apaga dados!)
sudo rm -rf volumes
mkdir -p volumes/ava/moodledata && touch volumes/ava/moodledata/.empty
chmod -R 777 volumes/ava/moodledata

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
<script src='http://ava/lib/javascript.php/1692023308/lib/jquery/jquery-3.6.1.min.js'></script>
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