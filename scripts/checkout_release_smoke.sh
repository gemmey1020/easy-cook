#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

"$ROOT_DIR/scripts/checkout_storage_smoke.sh"
"$ROOT_DIR/scripts/checkout_auth_smoke.sh"
