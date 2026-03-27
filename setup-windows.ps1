$ErrorActionPreference = 'Stop'

function Refresh-Path {
    $machine = [System.Environment]::GetEnvironmentVariable('Path', 'Machine')
    $user = [System.Environment]::GetEnvironmentVariable('Path', 'User')
    $env:Path = "$machine;$user"
}

function Add-PathIfExists {
    param(
        [string]$DirPath
    )

    if (-not (Test-Path $DirPath)) {
        return
    }

    $segments = $env:Path -split ';'
    if (-not ($segments -contains $DirPath)) {
        $env:Path = "$DirPath;$env:Path"
    }
}

function Resolve-Composer {
    $composer = Get-Command composer -ErrorAction SilentlyContinue
    if ($composer) {
        return $composer.Source
    }

    $candidateFiles = @(
        'C:\ProgramData\ComposerSetup\bin\composer.bat',
        'C:\Program Files\Composer\bin\composer.bat',
        "$env:LOCALAPPDATA\Programs\Composer\composer.bat"
    )

    foreach ($file in $candidateFiles) {
        if (Test-Path $file) {
            Add-PathIfExists -DirPath (Split-Path -Parent $file)
            $composer = Get-Command composer -ErrorAction SilentlyContinue
            if ($composer) {
                return $composer.Source
            }

            return $file
        }
    }

    return $null
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

$composerExe = Resolve-Composer
if (-not $composerExe) {
    throw "Composer was installed but is still not available. Open a new terminal and run: winget install --id Composer.Composer -e"
}

if (-not (Test-Path '.env') -and (Test-Path '.env.example')) {
    Copy-Item '.env.example' '.env'
}

& $composerExe install
php artisan key:generate
php artisan migrate --force
npm install
npm run build

Write-Host "Setup complete. Run 'composer run dev' to start the app." -ForegroundColor Green
