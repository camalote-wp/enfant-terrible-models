#!/usr/bin/env bash
set -euo pipefail

# =============================================================================
# Script: generate-pot.sh
# Purpose: Generate POT file for WordPress plugin (container-friendly)
# Notes: Step-based logging for clarity in CI/CD
# =============================================================================

PLUGIN="cmlt-editorial-control"
PLUGIN_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
TMP_POT="$(mktemp)"
IDENTIFIER="POTGEN"

# -----------------------------------------------------------------------------
# Generic logging function
# Arguments:
#   $1 = message
#   $2 = step number or special keyword "DONE"
# -----------------------------------------------------------------------------
log() {
  local msg="$1"
  local status="${2:-}"

  if [[ "$status" == "DONE" ]]; then
    echo
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] [$IDENTIFIER] [$status] ✓ $msg"
    echo "----------------------------------------------------------------------------"
  else
    echo
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] [$IDENTIFIER] [$status] $msg"
    echo "----------------------------------------------------------------------------"
  fi
}

# -----------------------------------------------------------------------------
# Dependencies check
# -----------------------------------------------------------------------------
check_dependencies() {
  if ! command -v wp &>/dev/null; then
    echo
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] [$IDENTIFIER] [ERROR] wp-cli not found. Please run this inside the container where wp-cli is available."
    echo "----------------------------------------------------------------------------"
    exit 1
  fi
}

# -----------------------------------------------------------------------------
# Steps
# -----------------------------------------------------------------------------
prepare_dirs() {
  log "Preparing languages directory" "1/3"
  mkdir -p "$PLUGIN_ROOT/languages"
}

generate_pot() {
  log "Generating POT file" "2/3"
  wp i18n make-pot "$PLUGIN_ROOT" "$TMP_POT" \
    --domain="$PLUGIN" \
    --package-name="CamaloteWP - Editorial Control" \
    --headers='{"Report-Msgid-Bugs-To":"https://github.com/camalote-wp/cmlt-editorial-control/issues"}'
}

finalize_pot() {
  log "Moving POT file to languages directory" "3/3"
  mv -f "$TMP_POT" "$PLUGIN_ROOT/languages/${PLUGIN}.pot"
}

# -----------------------------------------------------------------------------
# Main workflow
# -----------------------------------------------------------------------------
main() {
  log "Starting POT generation process" "START"

  check_dependencies
  prepare_dirs
  generate_pot
  finalize_pot

  log "POT generated: $PLUGIN_ROOT/languages/${PLUGIN}.pot" "DONE"
}

main "$@"
