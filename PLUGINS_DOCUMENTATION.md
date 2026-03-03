# Documentação de Plugins Instalados

> **Data de Atualização**: Março de 2026  
> **Fonte**: Repositório em `/main/build/plugins`

Esta documentação lista todos os plugins que serão instalados no ambiente Moodle, organizados por tipo.

---

## 📋 Índice

- [Plugins de Autenticação](#plugins-de-autenticação)
- [Plugins de Inscrição](#plugins-de-inscrição)
- [Plugins de Atividade (mod_)](#plugins-de-atividade)
- [Plugins de Bloco](#plugins-de-bloco)
- [Plugins de Formato de Curso](#plugins-de-formato-de-curso)
- [Plugins de Filtro](#plugins-de-filtro)
- [Plugins de Editor Atto](#plugins-de-editor-atto)
- [Plugins de Editor Tiny](#plugins-de-editor-tiny)
- [Plugins de Disponibilidade](#plugins-de-disponibilidade)
- [Plugins de Campo de Perfil](#plugins-de-campo-de-perfil)
- [Plugins de Fases de Relatório](#plugins-de-fases-de-relatório)
- [Plugins Locais](#plugins-locais)
- [Plugins de Tema](#plugins-de-tema)
- [Plugins de Ferramenta Admin](#plugins-de-ferramenta-admin)
- [Plugins Diversos](#plugins-diversos)

---

## Plugins de Autenticação

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `auth_suap` | 2026_02_13_053 | Autenticação integrada com SUAP |

**Total**: 1 plugin

---

## Plugins de Inscrição

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `enrol_attributes` | moodle50_2025070700 | Inscrição baseada em atributos |
| `enrol_badgeenrol` | moodle36_2018072700 | Inscrição por badges |
| `enrol_coursecompleted` | moodle50_2025090300 | Inscrição automática ao completar curso |
| `enrol_programs` | moodle45_2024103100 | Inscrição em programas |
| `enrol_suap` | 20250703003 | Inscrição integrada com SUAP |
| `enrol_xp` | moodle51_2025100500 | Inscrição por pontos XP |

**Total**: 6 plugins

---

## Plugins de Atividade

### Módulos de Atividade (mod_)

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `mod_activitymap` | moodle40_2022060400 | Mapa de atividades interativo |
| `mod_attendance` | moodle45_2024082400 | Controle de presença |
| `mod_certificatebeautiful` | moodle51_2025102902 | Certificados customizados |
| `mod_checklist` | moodle51_2025101800 | Listas de verificação |
| `mod_codexproitec` | 2025070314 | Modulo Codex PROITEC |
| `mod_coursecertificate` | moodle45_2025031804 | Certificados de conclusão do curso |
| `mod_customcert` | moodle45_2024042216 | Certificados personalizados |
| `mod_game` | moodle50_2025070501 | Atividades em forma de jogos |
| `mod_googlemeet` | moodle42_2023050101 | Integração com Google Meet |
| `mod_hvp` | moodle45_2026012200 | Conteúdo H5P interativo |
| `mod_imagemap` | 2026022300 | Mapas de imagem interativos com áreas clicáveis |
| `mod_interactivevideo` | moodle51_2026021100 | Vídeos interativos |
| `mod_journal` | moodle51_2026012100 | Diário de reflexão |
| `mod_learningmap` | moodle51_2025092600 | Mapa de aprendizado (versão 2025092600) |
| `mod_learningmap` | moodle51_2026021900 | Mapa de aprendizado (versão 2026021900) |
| `mod_mapaproitec` | 2025070310 | Mapa PROITEC |
| `mod_medalhasproitec` | 2025070316 | Sistema de medalhas PROITEC |
| `mod_mediagallery` | moodle45_2024080801 | Galeria de mídia |
| `mod_multiprogress` | 2025070321 | Progresso em múltiplos módulos |
| `mod_offlinequiz` | moodle45_2024071203 | Questionários offline |
| `mod_onlyofficeeditor` | moodle50_2025080402 | Editor Only Office integrado |
| `mod_poster` | moodle45_2025040100 | Criação de pôsteres |
| `mod_quizgame` | moodle42_2022112200 | Questionários em formato de jogo |

**Total**: 23 plugins

---

## Plugins de Bloco

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `block_availdep` | moodle40_2022090100 | Mostra dependências de atividades |
| `block_checklist` | moodle51_2025101800 | Bloco de lista de verificação |
| `block_completion_levels` | moodle50_2026012701 | Mostra níveis de conclusão |
| `block_completion_progress` | moodle51_2025101300 | Progresso de conclusão de atividades |
| `block_configurable_reports` | moodle45_2024051300 | Relatórios configuráveis |
| `block_course_gallery` | 2025072305 | Galeria de cursos |
| `block_course_rating` | 202507031004 | Avaliação de cursos |
| `block_custom_css` | 2025070302 | CSS customizado por bloco |
| `block_dedication` | moodle44_2024072200 | Rastreamento de dedicação do aluno |
| `block_enrolmenttimer` | moodle37_2019091800 | Contador para inscrição |
| `block_game` | moodle51_2025102202 | Bloco de gamificação |
| `block_grade_me` | moodle45_2024100700 | Mostra minhas avaliações |
| `block_lpprogress` | moodle50_2025121000 | Progresso de learning paths |
| `block_multiblock` | moodle45_2025030700 | Múltiplos blocos em um |
| `block_multiprogress` | 2025070309 | Múltiplos indicadores de progresso |
| `block_myprograms` | moodle45_2024091900 | Meus programas |
| `block_qrcode` | moodle45_2025022400 | Gerador de código QR |
| `block_ranking` | moodle44_2024092000 | Ranking de alunos |
| `block_site_info` | 2025_05_05_01 | Informações do site |
| `block_stash` | moodle45_2024091200 | Sistema de recompensas (Stash) |
| `block_timetable` | moodle45_2025032100 | Grade horária |
| `block_uicustomcss` | moodle41_2012062899 | CSS customizado da interface |
| `block_xp` | moodle51_2025100501 | Sistema de pontos XP |

**Total**: 23 plugins

---

## Plugins de Formato de Curso

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `format_flexsections` | moodle45_2024120801 | Seções flexíveis de curso |
| `format_learningmap` | moodle50_2025041700 | Formato de mapa de aprendizado |
| `format_remuiformat` | moodle51_2025110500 | Formato Remui (tema visual) |
| `format_tiles` | moodle45_2025070355 | Formato em blocos/azulejos |
| `format_timeline` | moodle43_2023100902 | Formato em linha do tempo |

**Total**: 5 plugins

---

## Plugins de Filtro

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `filter_cssinject` | moodle51_2025101104 | Injeção de CSS |
| `filter_filtercodes` | moodle51_2025102700 | Codificação de filtros |
| `filter_generico` | moodle51_2025100500 | Filtro genérico extensível |
| `filter_multilang2` | moodle50_2025041701 | Suporte a múltiplos idiomas |
| `filter_shortcodes` | moodle51_2025100100 | Shortcodes customizados |
| `filter_syntaxhighlighter` | moodle45_2025010900 | Destaque de sintaxe de código |
| `filter_ws` | moodle45_2024100803 | Filtro de webservice |

**Total**: 7 plugins

---

## Plugins de Editor Atto

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `atto_chemistry` | moodle31_2015092900 | Fórmulas químicas |
| `atto_computing` | moodle30_2015092900 | Notação computacional |
| `atto_count` | moodle35_2018062700 | Contador de palavras |
| `atto_justify` | moodle45_2018041600 | Justificação de texto |
| `atto_morebackcolors` | moodle45_2021062100 | Mais cores de fundo |
| `atto_morefontcolors` | moodle45_2021062100 | Mais cores de fonte |
| `atto_wordimport` | moodle45_2025042800 | Importação de documentos Word |

**Total**: 7 plugins

---

## Plugins de Editor Tiny

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `tiny_c4l` | moodle51_2026021500 | Content for Learning (C4L) |
| `tiny_codepro` | moodle51_2025121802 | Destaque de código avançado |
| `tiny_filterws` | moodle41_2022122101 | Filtro de webservice |
| `tiny_fontcolor` | moodle51_2025101000 | Cor de fonte |
| `tiny_fontfamily` | moodle50_2025062900 | Família de fontes |
| `tiny_fontsize` | moodle50_2025062900 | Tamanho de fonte |
| `tiny_generico` | moodle51_2025100500 | Tiny genérico extensível |
| `tiny_molstructure` | moodle45_2025022700 | Estruturas moleculares |
| `tiny_multilang2` | moodle51_2025100900 | Suporte a múltiplos idiomas (v1) |
| `tiny_multilang2` | moodle51_2025102200 | Suporte a múltiplos idiomas (v2) |
| `tiny_orphanedfiles` | moodle45_2025052003 | Detecção de arquivos órfãos |
| `tiny_preview` | moodle51_2023042406 | Pré-visualização |
| `tiny_qrcode` | moodle45_2024110301 | Código QR |
| `tiny_snippet` | moodle50_2025071000 | Trechos de código |
| `tiny_stash` | moodle45_2024022900 | Integração com Stash |
| `tiny_teamsmeeting` | moodle50_2025100203 | Integração com Microsoft Teams |
| `tiny_widgethub` | moodle51_2025102101 | Hub de widgets |
| `tiny_wordimport` | moodle50_2025043000 | Importação de Word |
| `tiny_wordlimit` | moodle50_2025043000 | Limite de palavras |

**Total**: 19 plugins

---

## Plugins de Disponibilidade

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `availability_badge` | moodle31_2016020200 | Condição: Possuir badge |
| `availability_cohort` | moodle45_2024100701 | Condição: Pertencer a coorte |
| `availability_coursecompleted` | moodle45_2024100700 | Condição: Curso completado |
| `availability_courseprogress` | moodle41_2020070100 | Condição: Progresso do curso |
| `availability_enrol` | moodle34_2018030900 | Condição: Método de inscrição |
| `availability_game` | moodle40_2022042610 | Condição: Completou jogo |
| `availability_language` | moodle45_2024100700 | Condição: Idioma |
| `availability_maxviews` | moodle42_2023062700 | Condição: Visualizações máximas |
| `availability_mobileapp` | moodle45_2024121600 | Condição: Acesso via aplicativo móvel |
| `availability_othercompleted` | moodle42_2023050310 | Condição: Outro usuário completou |
| `availability_password` | moodle45_2024100701 | Condição: Proteção por senha |
| `availability_quizquestion` | moodle311_2021031700 | Condição: Resposta a questão de quiz |
| `availability_relativedate` | moodle45_2024100700 | Condição: Data relativa |
| `availability_role` | moodle45_2024100703 | Condição: Papel/Função |
| `availability_sectioncompleted` | moodle45_2025032401 | Condição: Seção completada |
| `availability_stash` | moodle51_2025112800 | Condição: Itens no Stash |
| `availability_user` | moodle45_2024082101 | Condição: Usuário específico |
| `availability_xp` | moodle51_2025100500 | Condição: Nível de XP |

**Total**: 18 plugins

---

## Plugins de Campo de Perfil

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `profilefield_associated` | moodle310_2021021600 | Campo associado |
| `profilefield_autocomplete` | moodle311_2022071900 | Campo autocompletar |
| `profilefield_brasilufmunicipio` | moodle50_2025090400 | Campo: UF e município brasileiro |
| `profilefield_conditional` | moodle50_2025062100 | Campo condicional |
| `profilefield_cpf` | moodle41_2014041700 | Campo: CPF |
| `profilefield_dynamicmenu` | moodle32_2017021201 | Campo de menu dinâmico |
| `profilefield_dynamicmultiselect` | moodle32_2017021201 | Campo multi-seleção dinâmica |
| `profilefield_masked` | moodle41_2020080800 | Campo com máscara |
| `profilefield_multiselect` | moodle39_2020061800 | Campo multi-seleção |
| `profilefield_orcid` | moodle40_2022070900 | Campo: ORCID |
| `profilefield_statictext` | moodle40_2022041200 | Campo de texto estático |
| `profilefield_timestamp` | moodle45_2022021700 | Campo: Timestamp |

**Total**: 12 plugins

---

## Plugins de Fases de Relatório

### Grade Reports

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `gradereport_gradedist` | moodle45_2024100400 | Distribuição de notas |
| `gradereport_quizanalytics` | moodle50_2025051900 | Análise de quizzes |

**Total**: 2 plugins

### Grade Export

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `gradeexport_checklist` | moodle51_2025101800 | Exportação de checklist para notas |

**Total**: 1 plugin

### Site Reports

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `report_coursesize` | moodle45_2024112602 | Tamanho do curso (v1) |
| `report_coursesize` | moodle45_2025121001 | Tamanho do curso (v2) |
| `report_extendedlog` | moodle45_2024010800 | Log estendido |
| `report_offlinequizcron` | moodle45_2024071200 | Cron para offline quiz |
| `report_overviewstats` | moodle50_2025052900 | Estatísticas gerais |
| `report_securityaudit` | moodle45_2024101600 | Auditoria de segurança |

**Total**: 6 plugins

**Total Geral de Relatórios**: 9 plugins

---

## Plugins Locais

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `local_cohortrole` | moodle51_2025100700 | Gerenciamento de papéis em coortes |
| `local_debugtoolbar` | moodle50_2025042200 | Barra de ferramentas de debug |
| `local_glossary_wordimport` | moodle50_2025042300 | Importação de glossário do Word |
| `local_ivannotation` | - | IvNote - Anotações |
| `local_ivdecision` | - | IvNote - Decisões |
| `local_ivh5pupload` | - | IvNote - Upload H5P |
| `local_ivhtmlviewer` | - | IvNote - Visualizador HTML |
| `local_ivinlineannotation` | - | IvNote - Anotações Inline |
| `local_ivofficeviewer` | - | IvNote - Visualizador Office |
| `local_ivpdfviewer` | - | IvNote - Visualizador PDF |
| `local_ivxpreward` | - | IvNote - XP Reward |
| `local_lesson_wordimport` | moodle50_2025042500 | Importação de lição do Word |
| `local_mailtest` | moodle50_2025042700 | Teste de envio de email |
| `local_maintenance_livecheck` | moodle45_2024100702 | Verificação de manutenção ao vivo |
| `local_openlms` | moodle45_2024103100 | Integração OpenLMS |
| `local_pnp` | 2025_08_22_06 | PnP customizações |
| `local_suap` | 20260206084 | Integração com SUAP |
| `local_wordimport` | moodle45_2025042500 | Importação genérica do Word |

**Total**: 18 plugins

---

## Plugins de Tema

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `theme_moove` | moodle45_2024100802 | Tema Moove (limpo e moderno) |
| `theme_suap` | 2025_11_02_001 | Tema SUAP customizado |

**Total**: 2 plugins

---

## Plugins de Ferramenta Admin

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `tool_bulkchangeprofilefields` | moodle44_2024061800 | Alteração em massa de campos de perfil |
| `tool_certificate` | moodle45_2025031804 | Gerenciador de certificados |
| `tool_clearbackupfiles` | moodle45_2025012900 | Limpeza de arquivos de backup |
| `tool_coursearchiver` | moodle45_2025060200 | Arquivamento de cursos |
| `tool_coursefields` | moodle45_2024100701 | Campos customizados de curso |
| `tool_datewatch` | moodle45_2025031801 | Monitoramento de datas críticas |
| `tool_hidecourses` | moodle45_2018112800 | Ocultamento em massa de cursos |
| `tool_migratehvp2h5p` | moodle45_2025012000 | Migração HVP para H5P |
| `tool_objectfs` | moodle45_2025040900 | Object Storage (v1) |
| `tool_objectfs` | moodle45_2025082700 | Object Storage (v2) |
| `tool_opcache` | moodle45_2024100700 | OPcache Manager (v1) |
| `tool_opcache` | moodle45_2024100703 | OPcache Manager (v2) |
| `tool_redirects` | moodle45_2025080603 | Gerenciador de redirecionamentos |
| `tool_ribbons` | moodle44_2021020402 | Fitas de badge |
| `tool_sentry` | moodle51_2025102400 | Integração Sentry para erros |
| `tool_uploadenrolmentmethods` | moodle50_2025051600 | Upload em massa de métodos de inscrição |
| `tool_userdebug` | moodle45_2024102001 | Debug de usuário |

**Total**: 17 plugins

---

## Plugins Diversos

| Plugin | Versão | Descrição |
|--------|--------|-----------|
| `booktool_wordimport` | moodle50_2025042300 | Importação de Word em livros |
| `customfield_training` | 2024091900 | Campo customizado: Treinamento |
| `lang_pt_br` | - | Pacote de idioma: Português (Brasil) |
| `qformat_h5p` | moodle51_2020071513 | Formato de questão: H5P |
| `qformat_wordtable` | moodle50_2025042300 | Formato de questão: Word Table |

**Total**: 5 plugins

---

## 📊 Resumo Estatístico

| Tipo | Quantidade |
|------|-----------|
| Plugins de Atividade (mod_) | 23 |
| Plugins de Bloco | 23 |
| Plugins do Editor Tiny | 19 |
| Plugins de Disponibilidade | 18 |
| Plugins Locais | 18 |
| Plugins de Ferramenta Admin | 17 |
| Plugins de Campo de Perfil | 12 |
| Plugins de Inscrição | 6 |
| Plugins de Filtro | 7 |
| Plugins de Editor Atto | 7 |
| Plugins de Formato de Curso | 5 |
| Plugins de Relatório | 9 |
| Plugins de Tema | 2 |
| Plugins de Autenticação | 1 |
| Plugins Diversos | 5 |
| **TOTAL** | **172** |

---

## ⚙️ Notas Importantes

1. **Versionamento**: Alguns plugins possuem múltiplas versões instaladas (ex: `mod_learningmap` tem 2 versões, `tool_objectfs` tem 2 versões). Isso pode ser para compatibilidade ou migração gradual.

2. **Plugins SUAP**: Vários plugins possuem integração SUAP (`auth_suap`, `enrol_suap`, `local_suap`, `theme_suap`), indicando integração com o sistema SUAP.

3. **Plugins IvNote**: Vários plugins com prefixo `local_iv*` são parte do sistema IvNote para anotações e visualização.

4. **Plugins Atto vs Tiny**: Há suporte para ambos os editores, indicando transição de Atto para Tiny.

5. **Plugins PROITEC**: Vários plugins com sufixo `proitec` indicam customizações específicas para o projeto PROITEC.

6. **Language Pack**: Apenas o pacote `lang_pt_br` está incluído, indicando suporte principal ao português brasileiro.

---

**Documentação criada**: Março de 2026

