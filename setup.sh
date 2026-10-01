#!/usr/bin/env bash
#
# Thin wrapper: run the cross-platform setup script with PHP.
# Usage: ./setup.sh [--skip-build]

set -euo pipefail

here="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

exec php "$here/setup.php" "$@"
