#!/usr/bin/env pwsh
# FOSSestate startup script
# Starts Docker services (MySQL + PHP/Symfony) then the React dev server.

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

$Root = $PSScriptRoot

function Write-Step([string]$msg) {
    Write-Host ""
    Write-Host "==> $msg" -ForegroundColor Cyan
}

function Write-Ok([string]$msg) {
    Write-Host "    $msg" -ForegroundColor Green
}

function Write-Warn([string]$msg) {
    Write-Host "    [warn] $msg" -ForegroundColor Yellow
}

# ── 1. Verify Docker is running ─────────────────────────────────────────────
Write-Step "Checking Docker..."
try {
    docker info 2>&1 | Out-Null
    Write-Ok "Docker is running."
} catch {
    Write-Host "ERROR: Docker does not appear to be running. Start Docker Desktop and try again." -ForegroundColor Red
    exit 1
}

# ── 2. Start / rebuild Docker services ──────────────────────────────────────
Write-Step "Starting Docker services (mysql + php)..."
Set-Location $Root
docker compose up -d --build
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: docker compose up failed." -ForegroundColor Red
    exit 1
}
Write-Ok "Containers started."

# ── 3. Wait for the PHP container to become healthy ──────────────────────────
Write-Step "Waiting for PHP/Symfony to be ready..."
$timeout  = 120   # seconds
$elapsed  = 0
$interval = 5

do {
    Start-Sleep -Seconds $interval
    $elapsed += $interval
    $ready = $false
    try {
        $resp = Invoke-WebRequest -Uri "http://localhost:8080/api/health" -UseBasicParsing -TimeoutSec 3 -ErrorAction SilentlyContinue
        if ($resp.StatusCode -eq 200) { $ready = $true }
    } catch {
        # not ready yet - keep waiting
    }
    if ($ready) { break }
    Write-Host "    ...still waiting ($elapsed/$timeout s)" -ForegroundColor DarkGray
} while ($elapsed -lt $timeout)

if ($elapsed -ge $timeout) {
    Write-Warn "PHP container did not respond within ${timeout}s. Check logs: docker compose logs php"
} else {
    Write-Ok "PHP/Symfony is up at http://localhost:8080"
}

# ── 4. Install npm dependencies if missing ───────────────────────────────────
Write-Step "Checking frontend dependencies..."
$wwwDir = Join-Path $Root "www"
$nmDir  = Join-Path $wwwDir "node_modules"

if (-not (Test-Path $nmDir)) {
    Write-Host "    node_modules not found - running npm install..." -ForegroundColor DarkGray
    Set-Location $wwwDir
    npm install
    if ($LASTEXITCODE -ne 0) {
        Write-Host "ERROR: npm install failed." -ForegroundColor Red
        exit 1
    }
    Write-Ok "Dependencies installed."
} else {
    Write-Ok "node_modules found, skipping install."
}

# ── 5. Launch React dev server ───────────────────────────────────────────────
Write-Step "Starting React dev server..."
Set-Location $wwwDir
Write-Ok "Frontend will be available at http://localhost:3000"
Write-Ok "Backend API is at http://localhost:8080"
Write-Host ""
Write-Host "Press Ctrl+C to stop the dev server (Docker services keep running)." -ForegroundColor DarkGray
Write-Host "To stop everything: docker compose down" -ForegroundColor DarkGray
Write-Host ""

npm start
