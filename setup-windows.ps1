$ErrorActionPreference = 'Stop'

function Refresh-Path {
    $machine = [System.Environment]::GetEnvironmentVariable('Path', 'Machine')
    $user = [System.Environment]::GetEnvironmentVariable('Path', 'User')
    $env:Path = "$machine;$user"
}

function Ensure-Command {
    param(
        [string]$CommandName,
        [scriptblock]$InstallBlock
    )

    if (-not (Get-Command $CommandName -ErrorAction SilentlyContinue)) {
        & $InstallBlock
        Refresh-Path
    }

    if (-not (Get-Command $CommandName -ErrorAction SilentlyContinue)) {
        throw "'$CommandName' is still unavailable after install. Open a new terminal and re-run this script."
    }
}

if (-not (Get-Command winget -ErrorAction SilentlyContinue)) {
    throw "winget is required but not found. Install App Installer from Microsoft Store, then re-run."
}

Ensure-Command -CommandName php -InstallBlock {
    winget install --id PHP.PHP -e --accept-package-agreements --accept-source-agreements
}

Ensure-Command -CommandName composer -InstallBlock {
    winget install --id Composer.Composer -e --accept-package-agreements --accept-source-agreements
}

Ensure-Command -CommandName npm -InstallBlock {
    winget install --id OpenJS.NodeJS.LTS -e --accept-package-agreements --accept-source-agreements
}

Set-Location $PSScriptRoot

if (-not (Test-Path '.env') -and (Test-Path '.env.example')) {
    Copy-Item '.env.example' '.env'
}

composer install
php artisan key:generate
php artisan migrate --force
npm install
npm run build

Write-Host "Setup complete. Run 'composer run dev' to start the app." -ForegroundColor Green
