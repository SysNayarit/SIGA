# =============================================================================
# PRUEBA DE API: GET api/v1/personas
# RUTA: C:\MM\Proyectos\SIGA\scripts\Test-GetPersonasApi.ps1
# =============================================================================

$uri = "http://localhost:8000/api/v1/personas"

try {
    $response = Invoke-RestMethod -Uri $uri -Method Get -Headers @{ "Accept" = "application/json" }
    Write-Host "STATUS: 200 OK" -ForegroundColor Green
    Write-Host "RESPUESTA DE LA API:" -ForegroundColor Yellow
    $response | ConvertTo-Json -Depth 5
} catch {
    Write-Host "ERROR EN LA PETICION:" -ForegroundColor Red
    $_.Exception.Message
}