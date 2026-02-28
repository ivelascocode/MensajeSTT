# MensajesSTT
Vista de ultimo mensaje
# MensajeSTT - Live Message Viewer

Visualizador en vivo del último mensaje de la base de datos.

## 🎯 Demostración

**URL de acceso:**
```
http://3.145.204.1:8888/tt/MensajeSTT.php
```

## ✨ Características

- 📨 Muestra el último mensaje en tiempo real
- 🔄 Auto-actualiza cada 5 segundos
- 📋 Botón para copiar mensaje
- 🎨 Interfaz moderna con dark mode
- 📱 Responsive para móviles
- ⚡ Carga rápida

## 🛠️ Tecnologías

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML5 + CSS3
- **Fonts**: Google Fonts (Syne, JetBrains Mono)

## 📋 Requisitos

```php
<?php
// inc/config.php
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "root";
$DB_NAME = "Ataneresbd";

function conect() {
    global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME;
    return mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
}
?>
```

## 📊 Estructura de Base de Datos

```sql
CREATE TABLE tblMessages (
    idMessage INT PRIMARY KEY AUTO_INCREMENT,
    idUser INT,
    txtMessage TEXT,
    celPhonefrom VARCHAR(20),
    CellPhoneto VARCHAR(20),
    strUrlFileName VARCHAR(255),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🚀 Instalación Rápida

```bash
# 1. Copiar archivo
cp MensajeSTT.php /Applications/MAMP/htdocs/tt/

# 2. Acceder en navegador
http://localhost:8888/tt/MensajeSTT.php
```

## 📡 Flujo de Datos

```
Database (MySQL)
    ↓ SELECT txtMessage
PHP (MensajeSTT.php)
    ↓ Renderiza HTML
Navegador
    ↓ Auto-refresh 5s
Visualización en Vivo
```

## 🎨 Diseño

### Colores
- Fondo: `#0a0d14` (Oscuro profundo)
- Superficie: `#111520`
- Accent: `#4f9eff` (Azul)
- Error: `#f87171` (Rojo)

### Componentes
- Header con gradiente
- Card con mensaje centrado
- Indicador de estado
- Botones de acción

## 💻 Código PHP

```php
// Obtener último mensaje
$sql = "SELECT txtMessage 
        FROM tblMessages 
        ORDER BY idMessage DESC 
        LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$messageData = mysqli_fetch_assoc($result);
```

## 🔔 Estados

| Estado | Indicador | Descripción |
|--------|-----------|-------------|
| ✅ En vivo | 🟢 Verde | Hay mensajes |
| ⚠️ Sin mensajes | 🔴 Rojo | No hay datos |
| ❌ Error | 🔴 Rojo | Error de conexión |

## 📱 Responsive

- Desktop: 700px max-width
- Tablet: Ajustes automáticos
- Mobile: Stack vertical, botones full-width

## 🔐 Consideraciones de Seguridad

```php
// Sanitización
htmlspecialchars($txtMessage, ENT_QUOTES, 'UTF-8')

// Prepared statements
mysqli_prepare() + mysqli_stmt_bind_param()
```

## 🎬 Demo en Vivo

![Demo]

**Visualización:**
```
📨 Último Mensaje
═══════════════════════════════════
     Mensaje en Vivo
═══════════════════════════════════

┌─────────────────────────────────┐
│                                 │
│   ¡Hola! Este es el último      │
│   mensaje guardado en la base   │
│   de datos.                     │
│                                 │
│   🟢 ✅ En vivo                 │
│                                 │
└─────────────────────────────────┘

[🔄 Actualizar Ahora] [📋 Copiar Mensaje]
```

## 📝 Notas

- Auto-refresh limitado a 500 segundos (100 x 5s)
- Solo obtiene el ÚLTIMO mensaje
- Compatible con cualquier navegador moderno
- Funciona en AWS y localhost

## 🔗 Links Útiles

- [AWS Instance IP](http://3.145.204.1:8888/tt/MensajeSTT.php)
- [GitHub Repository](https://github.com/USERNAME/MensajeSTT)

## 📞 Contacto

- Email: tu@email.com
- AWS: `3.145.204.1:8888`

## 📄 Licencia

MIT License