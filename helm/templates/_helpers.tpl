{{/*
Expand the name of the chart.
*/}}
{{- define "moodle-proximo.name" -}}
{{- default .Chart.Name .Values.nameOverride | trunc 63 | trimSuffix "-" }}
{{- end }}
{{- define "moodle.name" -}}{{- include "moodle-proximo.name" . -}}{{- end }}

{{/*
Create a default fully qualified app name.
We truncate at 63 chars because some Kubernetes name fields are limited to this (by the DNS naming spec).
If release name contains chart name it will be used as a full name.
*/}}
{{- define "moodle-proximo.fullname" -}}
{{- if .Values.fullnameOverride }}
{{- .Values.fullnameOverride | trunc 63 | trimSuffix "-" }}
{{- else }}
{{- $name := default .Chart.Name .Values.nameOverride }}
{{- if contains $name .Release.Name }}
{{- .Release.Name | trunc 63 | trimSuffix "-" }}
{{- else }}
{{- printf "%s-%s" .Release.Name $name | trunc 63 | trimSuffix "-" }}
{{- end }}
{{- end }}
{{- end }}
{{- define "moodle.fullname" -}}{{- include "moodle-proximo.fullname" . -}}{{- end }}

{{/*
Create chart name and version as used by the chart label.
*/}}
{{- define "moodle-proximo.chart" -}}
{{- printf "%s-%s" .Chart.Name .Chart.Version | replace "+" "_" | trunc 63 | trimSuffix "-" }}
{{- end }}
{{- define "moodle.chart" -}}{{- include "moodle-proximo.chart" . -}}{{- end }}

{{/*
Common labels
*/}}
{{- define "moodle-proximo.labels" -}}
helm.sh/chart: {{ include "moodle-proximo.chart" . }}
{{ include "moodle-proximo.selectorLabels" . }}
{{- if .Chart.AppVersion }}
app.kubernetes.io/version: {{ .Chart.AppVersion | quote }}
{{- end }}
app.kubernetes.io/managed-by: {{ .Release.Service }}
{{- end }}
{{- define "moodle.labels" -}}{{- include "moodle-proximo.labels" . -}}{{- end }}

{{/*
Selector labels
*/}}
{{- define "moodle-proximo.selectorLabels" -}}
app.kubernetes.io/name: {{ include "moodle-proximo.name" . }}
app.kubernetes.io/instance: {{ .Release.Name }}
{{- end }}
{{- define "moodle.selectorLabels" -}}{{- include "moodle-proximo.selectorLabels" . -}}{{- end }}

{{/*
Create the name of the service account to use
*/}}
{{- define "moodle-proximo.serviceAccountName" -}}
{{- if .Values.serviceAccount.create }}
{{- default (include "moodle-proximo.fullname" .) .Values.serviceAccount.name }}
{{- else }}
{{- default "default" .Values.serviceAccount.name }}
{{- end }}
{{- end }}
{{- define "moodle.serviceAccountName" -}}{{- include "moodle-proximo.serviceAccountName" . -}}{{- end }}
