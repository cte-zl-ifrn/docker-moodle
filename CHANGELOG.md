# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

## [Unreleased]

### Added
- GitHub Actions workflow para CI/CD automatizado
- Detecção inteligente de mudanças na pasta `/base`
- Build condicional da imagem base (`ctezlifrn/avamoodlebase`)
- Deploy automatizado em produção via SSH
- Cache de layers Docker para acelerar builds
- Documentação completa em README.md e CONTRIBUTING.md

### Changed
- **Quebra de compatibilidade:** o Apache passa a escutar na porta `8080` (não privilegiada), em vez da `80`.
  O `www-data` não conseguia abrir a `80` em runtimes que não liberam portas baixas, como o Podman (#41).
  Ajuste os consumidores: mapeamentos `X:80` viram `X:8080` e URLs internas entre contêineres passam a usar `:8080`.
  No Helm, o `service.port` continua `80` e o `targetPort` passa a `8080`.
- Migração de GitLab CI para GitHub Actions
- Estrutura do projeto agora é um monorepo com duas imagens Docker
- Processo de release simplificado (apenas criar e push de tag)

### Removed
- Script `release.sh` (substituído por GitHub Actions)
- Dependência do GitLab CI/CD
- Login manual no registry (agora automatizado)

### Fixed
- Processo de build mais consistente e reproduzível
- Melhor controle de versões das imagens

---

## Versões Anteriores

Versões anteriores usavam GitLab CI e não possuem registro formal de mudanças.
