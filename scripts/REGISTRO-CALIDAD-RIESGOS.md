# SIGA — Registro de Métricas de Calidad y Riesgos (ISO/IEC 25010 / ISO/IEC 27001)

## 1. Métricas de Calidad (ISO/IEC 25010:2023)
* **Tiempo de Respuesta API Login:** Umbral < 300 ms.
* **Cobertura de Pruebas API:** 100% de endpoints principales verificados via PowerShell / Postman.
* **Seguridad de Datos:** Tokens revocables, passwords hasheados con Bcrypt.

## 2. Registro de Riesgos y Controles (ISO/IEC 27001:2022)
* **Riesgo 01:** Fuga o inyección de SQL por consultas directas.
  * *Control:* Uso obligatorio de Eloquent ORM, Prepared Statements y validación estricta via Form Requests.
* **Riesgo 02:** Inconsistencia de identidades entre bases de datos separadas.
  * *Control:* Identificador único institucional (`persona_id` / UUID) transversal en `core.personas`.
