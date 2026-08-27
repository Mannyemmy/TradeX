#!/bin/bash
# Read-only deploy diagnostic. Changes nothing. Run from inside the cloned repo:
#   cd <repo> && bash server-config/diagnose.sh
DEPLOYPATH=/home/wealthw5/tradexpromax
REPO="$(git rev-parse --show-toplevel 2>/dev/null || echo '?')"

echo "repo root  : $REPO"
echo "deploy path: $DEPLOYPATH"
if [ "$REPO" = "$DEPLOYPATH" ]; then
  echo ">> SAME DIRECTORY - rsync is a no-op. The git pull IS the deploy."
else
  echo ">> separate directories - rsync is required"
fi

echo
echo "--- homepage (the real test - size, not timestamp) ---"
for d in "$REPO" "$DEPLOYPATH"; do
  f="$d/test-homepage.html"
  if [ -f "$f" ]; then
    printf '  %-42s %10s bytes  %s\n' "$f" "$(stat -c %s "$f")" "$(stat -c %y "$f" | cut -d. -f1)"
  else
    printf '  %-42s MISSING\n' "$f"
  fi
done
echo "  expect ~1,177,212 = optimized     ~9,900,000 = still the old file"

echo
echo "--- assets/ ---"
printf '  repo   : %s files\n' "$(ls "$REPO/assets" 2>/dev/null | wc -l)"
printf '  docroot: %s files   (expect 333)\n' "$(ls "$DEPLOYPATH/assets" 2>/dev/null | wc -l)"

echo
echo "--- live .htaccess performance rules ---"
printf '  gzip/cache rules present: %s   (0 = not applied yet)\n' \
  "$(grep -c 'mod_deflate\|immutable' "$DEPLOYPATH/.htaccess" 2>/dev/null || echo 0)"

echo
echo "--- disk ---"
du -sh "$DEPLOYPATH" 2>/dev/null
quota -s 2>/dev/null || echo "  (quota unavailable)"
