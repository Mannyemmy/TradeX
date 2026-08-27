#!/bin/bash
# Manual deploy - identical to what .cpanel.yml runs, but in your terminal
# where you can see errors. cPanel's runner reports failures as successes.
#
#   cd ~/repositories/TradeX && bash server-config/manual-deploy.sh
#
# Safe to re-run. Never touches .env, .htaccess, web.config, storage/,
# vendor/ or bootstrap/cache/.

set -uo pipefail

# Resolve the repo root explicitly. cd "$(git rev-parse ...)" is NOT safe:
# on failure it expands to cd "" which silently succeeds, leaving the script
# running from the wrong directory with the sync aimed at the wrong tree.
TOP="$(git rev-parse --show-toplevel 2>/dev/null || true)"
[ -n "$TOP" ] || { echo "ABORT - not inside a git repository"; exit 1; }
cd "$TOP" || exit 1

bash "$TOP/server-config/deploy-sync.sh"
