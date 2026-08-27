#!/bin/bash
# Shared deploy logic, called by both .cpanel.yml and manual-deploy.sh.
#
# Uses tar rather than rsync: on this host rsync is present but not executable
# by the account ("timeout: failed to run command 'rsync': Permission denied"),
# and cPanel's task runner reported the failed deploy as successful.
#
# Additive only - tar overwrites files that exist and adds ones that don't,
# but never deletes. Server-only files are excluded, not removed.

set -uo pipefail

DEPLOYPATH=/home/wealthw5/tradexpromax
SRC="$PWD"

[ -d "$DEPLOYPATH" ] || { echo "ABORT - deploy path missing"; exit 1; }
[ -f "$SRC/index.php" ] && [ -f "$SRC/.cpanel.yml" ] || { echo "ABORT - not the repo root"; exit 1; }
[ "$SRC" != "$DEPLOYPATH" ] || { echo "ABORT - source and target are the same directory"; exit 1; }

BEFORE=$(stat -c %s "$DEPLOYPATH/test-homepage.html" 2>/dev/null || echo 0)

# Secrets and runtime state that exist only on the server, plus local-only dirs.
# -m on extract stamps files with the current time, so File Manager timestamps
# actually change (tar otherwise restores the source mtime).
tar -cf - -C "$SRC" \
  --exclude='./.git' \
  --exclude='./.gitignore' \
  --exclude='./.gitattributes' \
  --exclude='./.cpanel.yml' \
  --exclude='./.env' \
  --exclude='./.env.*' \
  --exclude='./.htaccess' \
  --exclude='./web.config' \
  --exclude='./storage' \
  --exclude='./bootstrap/cache' \
  --exclude='./vendor' \
  --exclude='./node_modules' \
  --exclude='./.claude' \
  --exclude='./.agents' \
  --exclude='./tests' \
  --exclude='./server-config' \
  --exclude='./deploy.sh' \
  --exclude='.DS_Store' \
  . | tar -xmf - -C "$DEPLOYPATH" || { echo "ABORT - tar sync failed"; exit 1; }

# Only clears. config:cache / route:cache are deliberately NOT run: this app's
# config/app.php uses env('TIMEZONE','UTC'), and a blank TIMEZONE in the server
# .env bakes an invalid timezone into bootstrap/cache/config.php, which then
# fatals on every web request. Re-add caching once TIMEZONE is set.
cd "$DEPLOYPATH" || exit 1
timeout 30 php artisan config:clear || true
timeout 30 php artisan cache:clear  || true
timeout 30 php artisan view:clear   || true
timeout 30 php artisan route:clear  || true

AFTER=$(stat -c %s "$DEPLOYPATH/test-homepage.html" 2>/dev/null || echo 0)
echo
echo "homepage before : $BEFORE bytes"
echo "homepage after  : $AFTER bytes"
echo "assets in docroot: $(ls "$DEPLOYPATH/assets" 2>/dev/null | wc -l) files"
if [ "$AFTER" -gt 0 ] && [ "$AFTER" -lt 2000000 ]; then
  echo "RESULT - optimized homepage is live"
else
  echo "RESULT - homepage still looks unoptimized, check the output above"
fi
