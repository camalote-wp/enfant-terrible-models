#!/usr/bin/env bash
set -euo pipefail

PLUGIN="enfant-terrible-models"
PLUGIN_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
LANG_DIR="$PLUGIN_ROOT/languages"
POT_FILE="$LANG_DIR/${PLUGIN}.pot"
TMP_POT="$(mktemp)"

IDENTIFIER="I18N"

log() {
  local msg="$1"
  local status="${2:-INFO}"

  echo
  echo "[$(date +'%Y-%m-%d %H:%M:%S')] [$IDENTIFIER] [$status] $msg"
  echo "----------------------------------------------------------------------------"
}

fail() {
  log "$1" "ERROR"
  exit 1
}

check_dependencies() {
  local deps=(
    wp
    msginit
    msgmerge
    msgfmt
  )

  for dep in "${deps[@]}"; do
    command -v "$dep" >/dev/null 2>&1 || fail "$dep not found"
  done
}

prepare() {
  mkdir -p "$LANG_DIR"
}

generate_pot() {
  log "Generating POT" "1/4"

  wp i18n make-pot "$PLUGIN_ROOT" "$TMP_POT" \
    --domain="$PLUGIN" \
    --headers='{"Report-Msgid-Bugs-To":"https://github.com/camalote-wp/enfant-terrible-models/issues"}' \
    --include="dist"
  mv -f "$TMP_POT" "$POT_FILE"
}

update_po_files() {
  log "Updating PO files" "2/4"

  shopt -s nullglob

  for po in "$LANG_DIR"/*.po; do
    msgmerge \
      --update \
      --backup=none \
      "$po" \
      "$POT_FILE"
  done
}

compile_mo_files() {
  log "Compiling MO files" "3/4"

  shopt -s nullglob

  for po in "$LANG_DIR"/*.po; do
    msgfmt "$po" -o "${po%.po}.mo"
  done
}

generate_json() {
    log "Generating JSON files" "4/4"
    wp i18n make-json "$LANG_DIR" "$LANG_DIR" \
        --domain="$PLUGIN" \
        --no-purge
}

add_locale() {
  local locale="${1:-}"

  [[ -n "$locale" ]] || fail "Locale required"

  local po_file="$LANG_DIR/${PLUGIN}-${locale}.po"

  [[ ! -f "$po_file" ]] || fail "Locale already exists: $locale"

  generate_pot

  log "Creating locale: $locale" "ADD"

  msginit \
    --no-translator \
    --input="$POT_FILE" \
    --locale="$locale" \
    --output-file="$po_file"

  msgfmt "$po_file" -o "${po_file%.po}.mo"

  generate_json

  log "Locale created: $locale" "DONE"
}

sync_translations() {
  generate_pot
  update_po_files
  compile_mo_files
  generate_json

  log "Translations synchronized" "DONE"
}

usage() {
  cat <<EOF
Usage:

  ./translations.sh sync
  ./translations.sh add-locale es_ES
  ./translations.sh add-locale ca

EOF
}

main() {
  check_dependencies
  prepare

  case "${1:-sync}" in
    sync)
      sync_translations
      ;;
    add-locale)
      add_locale "${2:-}"
      ;;
    *)
      usage
      exit 1
      ;;
  esac
}

main "$@"