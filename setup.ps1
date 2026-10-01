# Thin wrapper: run the cross-platform setup script with PHP.
# Usage: .\setup.ps1 [--skip-build]
# If Windows blocks the script, run:
#   powershell -ExecutionPolicy Bypass -File .\setup.ps1

$ErrorActionPreference = 'Stop'

$root = Split-Path -Parent $MyInvocation.MyCommand.Path

& php (Join-Path $root 'setup.php') @args

exit $LASTEXITCODE
