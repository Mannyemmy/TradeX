#!/bin/bash
# Manual deploy - the same steps as .cpanel.yml, run directly, bypassing
# cPanel's task queue (for when "Deploy HEAD Commit" sticks in "queued").
#
#   cd ~/repositories/TradeX && bash server-config/manual-deploy.sh
#
# Safe to re-run. Never touches .env, .htaccess, web.config, storage/,
# vendor/ or bootstrap/cache/ - see the rsync excludes below.

set -uo pipefail

# Resolve the repo root explicitly. cd "$(git rev-parse ...)" is NOT safe:
# on failure it expands to cd "" which silently succeeds, leaving the script
# running from the wrong directory with rsync aimed at the wrong tree.
TOP="$(git rev-parse --show-toplevel 2>/dev/null || true)"
[ -n "$TOP" ] || { echo "ABORT - not inside a git repository"; exit 1; }
cd "$TOP"

export DEPLOYPATH=/home/wealthw5/tradexpromax
export SRC="$PWD"
[ -d "$DEPLOYPATH" ] || { echo "ABORT - deploy path missing"; exit 1; }
[ -f "$SRC/index.php" ] && [ -f "$SRC/.cpanel.yml" ] || { echo "ABORT - not the repo root"; exit 1; }
timeout 600 rsync -rlptD --timeout=30 --no-perms --chmod=D755,F644 --exclude='.git/' --exclude='.gitignore' --exclude='.cpanel.yml' --exclude='.env' --exclude='.env.*' --exclude='.htaccess' --exclude='web.config' --exclude='storage/' --exclude='bootstrap/cache/' --exclude='vendor/' --exclude='node_modules/' --exclude='.claude/' --exclude='.agents/' --exclude='tests/' --exclude='.DS_Store' --exclude='deploy.sh' --exclude='server-config/' "$SRC/" "$DEPLOYPATH/"
cd "$DEPLOYPATH" && timeout 30 php artisan config:clear || true
cd "$DEPLOYPATH" && timeout 30 php artisan cache:clear || true
cd "$DEPLOYPATH" && timeout 30 php artisan view:clear || true
cd "$DEPLOYPATH" && timeout 30 php artisan route:clear || true
cd "$DEPLOYPATH" && timeout 60 php artisan config:cache || true
cd "$DEPLOYPATH" && timeout 60 php artisan route:cache || true
echo "Deploy finished"

echo
echo "Docroot size:"
du -sh "$DEPLOYPATH" 2>/dev/null || true
echo "Quota:"
quota -s 2>/dev/null || echo "(quota unavailable)"
