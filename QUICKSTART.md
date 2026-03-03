# Guia Rápido - AVA Moodle Docker

## 🚀 Release em 3 Passos

```bash
# 1. Commit e push suas mudanças
git add .
git commit -m "feat: sua mudança"
git push

# 2. Crie uma tag
git tag 4.5.10.030

# 3. Push da tag (aciona o CI/CD)
git push origin 4.5.10.030
```

✅ GitHub Actions faz o resto automaticamente!

## 📦 O que Acontece Automaticamente

1. **Verifica mudanças** em `/base`
2. **Build da base** (se `/base` mudou) → `ctezlifrn/avamoodlebase:TAG`
3. **Build principal** (sempre) → `ctezlifrn/avamoodle:TAG`
4. **Push** das imagens para o registry
5. **Deploy** no servidor de produção

## 🔧 Desenvolvimento Local

```bash
# Subir ambiente local
docker compose up -d

# Ver logs
docker compose logs -f ava

# Rebuild local
docker compose build
docker compose up -d
```

## 📋 Secrets Necessários (GitHub)

### Organization level (já configurados)

**Variables:**
- `DOCKERHUB_HOST` - Host do Docker Hub (docker.io)

**Secrets:**
- `DOCKERHUB_USERNAME` - Usuário
- `DOCKERHUB_TOKEN` - Token de acesso

### Repository level (configurar)

Configure em: **Settings → Secrets and variables → Actions**

- `SSH_HOST` - IP do servidor

## 🏗️ Estrutura de Imagens

```
ctezlifrn/avamoodlebase (base/)
         ↓
         └── usa como FROM
         
ctezlifrn/avamoodle (main/)
```

## 🐛 Troubleshooting

### Workflow não rodou?
```bash
# Verificar se a tag foi enviada
git ls-remote --tags origin
```

### Build falhou?
- Ver logs em: **Actions → Build and Deploy**

### Deploy falhou?
```bash
# Testar SSH
ssh root@$SSH_HOST
```

## 📁 Estrutura Importante

```
moodle_docker/
├── .github/workflows/build-and-deploy.yml  ← CI/CD config
├── base/                                   ← Imagem base
│   └── Dockerfile
├── main/                                   ← Imagem principal
│   ├── Dockerfile
│   └── build/plugins/                      ← Plugins (.zip)
└── docker-compose.yml                      ← Dev local
```

## 🔄 Formato da Tag

`<MOODLE_VERSION>.<BUILD>`

Exemplos:
- `4.5.10.028` → Moodle 4.5.10, build 28
- `4.5.11.001` → Moodle 4.5.11, build 1

## ⏱️ Tempo de Build

- **Sem mudança em `/base`**: ~5-10 min
- **Com mudança em `/base`**: ~15-25 min

## 📖 Mais Informações

- [README.md](README.md) - Documentação completa
- [CONTRIBUTING.md](CONTRIBUTING.md) - Guia de contribuição
- [CHANGELOG](CHANGELOG) - Histórico de mudanças
