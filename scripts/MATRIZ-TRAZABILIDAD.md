# SIGA — Matriz de Trazabilidad de Requisitos (ISO/IEC 29148:2018)

| ID Requisito | Origen / Necesidad | Descripción | Criterio de Aceptación | Componente Backend (Laravel / SQL) | Componente Frontend (Angular) | Pruebas / Evidencia | Estado |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **REQ-CORE-001** | FASE 2 / Identidad | Roles técnicos PostgreSQL y Esquema Core | BD responde a roles App, Owner y Migrator | `01_init_databases_and_roles.sql` | N/A | Script SQL ejecutado | **CERRADO** |
| **REQ-AUTH-001** | FASE 4 / Seguridad | Autenticación de Usuarios via Sanctum | Login emite token Bearer válido con JSON HTTP 200 | `AuthController.php`, `User.php` | N/A (Prueba HTTP) | Test `Invoke-RestMethod` exitoso (Token emitido) | **CERRADO** |
| **REQ-FRONT-001**| FASE 5 / Frontend | Estructura Base Angular Clean Arch + Signals | Carga de App con Interceptor HTTP y Manejo de Contexto | N/A | `Frontend/src/app/core` | Verificación de build y routing | **EN PROCESO** |
