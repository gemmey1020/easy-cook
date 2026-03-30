#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

if ! command -v node >/dev/null 2>&1; then
  echo "Node.js is required to run the checkout storage smoke script."
  exit 1
fi

if [[ -z "${PLAYWRIGHT_BROWSER_PATH:-}" ]]; then
  if command -v google-chrome >/dev/null 2>&1; then
    export PLAYWRIGHT_BROWSER_PATH="$(command -v google-chrome)"
  elif command -v google-chrome-stable >/dev/null 2>&1; then
    export PLAYWRIGHT_BROWSER_PATH="$(command -v google-chrome-stable)"
  elif command -v chromium-browser >/dev/null 2>&1; then
    export PLAYWRIGHT_BROWSER_PATH="$(command -v chromium-browser)"
  elif command -v chromium >/dev/null 2>&1; then
    export PLAYWRIGHT_BROWSER_PATH="$(command -v chromium)"
  fi
fi

if [[ ! -f "$ROOT_DIR/node_modules/playwright-core/package.json" ]]; then
  echo "playwright-core is missing. Run: npm install"
  exit 1
fi

node "$ROOT_DIR/scripts/checkout_storage_smoke.mjs"
