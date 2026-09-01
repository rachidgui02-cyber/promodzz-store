# DZCommerce Environment Switcher
# Usage: .\switch-env.ps1 [staging|production]

param(
    [Parameter(Mandatory=$true)]
    [ValidateSet("staging", "production")]
    [string]$Environment
)

$projectPath = "C:\Users\rachid pc\Documents\New OpenCode Project\backend"
Set-Location $projectPath

switch ($Environment) {
    "staging" {
        Write-Host "🔄 Switching to STAGING environment..." -ForegroundColor Yellow
        Copy-Item ".env.staging" ".env" -Force
        php artisan config:clear
        php artisan cache:clear
        Write-Host "✅ Now running STAGING (database_staging.sqlite)" -ForegroundColor Green
        Write-Host "🚀 Run: php artisan serve" -ForegroundColor Cyan
    }
    "production" {
        Write-Host "🔄 Switching to PRODUCTION environment..." -ForegroundColor Yellow
        if (Test-Path ".env.production") {
            Copy-Item ".env.production" ".env" -Force
        } else {
            Write-Host "⚠️  .env.production not found. Creating from current .env..." -ForegroundColor Red
            Copy-Item ".env" ".env.production" -Force
        }
        php artisan config:clear
        php artisan cache:clear
        Write-Host "✅ Now running PRODUCTION (database.sqlite)" -ForegroundColor Green
        Write-Host "🚀 Run: php artisan serve" -ForegroundColor Cyan
    }
}
