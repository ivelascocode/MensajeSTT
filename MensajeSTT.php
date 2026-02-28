<?php
// filepath: /Applications/MAMP/htdocs/tt/MensajeSTT.php
header('Content-Type: text/html; charset=utf-8');
require_once 'inc/config.php';

try {
    $conn = conect();
    
    if (!$conn) {
        throw new Exception("Error de conexión a la base de datos");
    }
    
    // Obtener el ÚLTIMO mensaje de tblMessages
    $sql = "SELECT txtMessage 
            FROM tblMessages 
            ORDER BY idMessage DESC 
            LIMIT 1";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        throw new Exception("Error preparando la consulta");
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $messageData = mysqli_fetch_assoc($result);
    
    // Si no hay mensaje, mostrar placeholder
    if (!$messageData) {
        $txtMessage = 'No hay mensajes disponibles';
        $hasMessage = false;
    } else {
        $txtMessage = $messageData['txtMessage'];
        $hasMessage = true;
    }
    
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    $txtMessage = '❌ Error: ' . $e->getMessage();
    $hasMessage = false;
} finally {
    if (isset($stmt)) mysqli_stmt_close($stmt);
    if (isset($conn)) mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Último Mensaje</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;600;700&family=Syne:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0a0d14;
            --surface: #111520;
            --border: #1e2740;
            --accent: #4f9eff;
            --accent2: #a78bfa;
            --text: #e2e8f0;
            --muted: #64748b;
            --error: #f87171;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Syne', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
        }

        /* Grid background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(79,158,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(79,158,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 700px;
            width: 100%;
        }

        /* HEADER */
        header {
            text-align: center;
            margin-bottom: 48px;
        }

        .tag {
            display: inline-block;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--accent);
            border: 1px solid rgba(79,158,255,0.3);
            padding: 6px 16px;
            border-radius: 20px;
            margin-bottom: 20px;
            background: rgba(79,158,255,0.05);
        }

        h1 {
            font-size: 48px;
            font-weight: 800;
            line-height: 1.1;
            background: linear-gradient(135deg, #fff 0%, var(--accent) 60%, var(--accent2) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
        }

        .subtitle {
            color: var(--muted);
            font-family: 'JetBrains Mono', monospace;
            font-size: 14px;
        }

        /* MESSAGE CARD */
        .message-card {
            background: var(--surface);
            border: 2px solid var(--border);
            border-radius: 16px;
            padding: 48px;
            margin-bottom: 32px;
            transition: border-color 0.3s, box-shadow 0.3s, transform 0.2s;
            animation: slideUp 0.5s ease both;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .message-card:hover {
            border-color: var(--accent);
            box-shadow: 0 12px 40px rgba(79,158,255,0.15);
            transform: translateY(-4px);
        }

        .message-card.error {
            border-color: var(--error);
        }

        .message-card.error:hover {
            box-shadow: 0 12px 40px rgba(248,113,113,0.15);
        }

        .message-card.loading {
            border-color: var(--accent);
        }

        /* MESSAGE CONTENT */
        .message-text {
            font-size: 26px;
            line-height: 1.8;
            color: var(--text);
            word-break: break-word;
            text-align: center;
            font-weight: 500;
        }

        .message-text.empty {
            color: var(--muted);
            font-style: italic;
            font-size: 20px;
        }

        .message-text.error {
            color: var(--error);
            font-size: 18px;
        }

        /* STATUS INDICATOR */
        .status {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--muted);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent3);
            animation: pulse 2s infinite;
        }

        .status-dot.error {
            background: var(--error);
        }

        /* ACTIONS */
        .actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 32px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text);
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn:hover {
            border-color: var(--accent);
            color: var(--accent);
            box-shadow: 0 4px 12px rgba(79,158,255,0.2);
        }

        .btn-primary {
            background: var(--accent);
            border-color: var(--accent);
            color: var(--bg);
        }

        .btn-primary:hover {
            background: rgba(79,158,255,0.9);
            box-shadow: 0 8px 24px rgba(79,158,255,0.3);
        }

        /* ANIMATIONS */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.5;
                transform: scale(1.2);
            }
        }

        /* RESPONSIVE */
        @media (max-width: 640px) {
            h1 {
                font-size: 36px;
            }

            .message-card {
                padding: 32px;
                min-height: 250px;
            }

            .message-text {
                font-size: 20px;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- HEADER -->
        <header>
            <div class="tag">📨 Último Mensaje</div>
            <h1>Mensaje en Vivo</h1>
            <p class="subtitle">Actualiza cada 5 segundos</p>
        </header>

        <!-- MESSAGE CARD -->
        <div class="message-card <?php echo $hasMessage ? '' : ($txtMessage === 'No hay mensajes disponibles' ? '' : 'error'); ?>">
            <div class="message-text <?php echo !$hasMessage ? 'empty' : (strpos($txtMessage, '❌') === 0 ? 'error' : ''); ?>">
                <?php echo htmlspecialchars($txtMessage); ?>
            </div>

            <!-- Status Indicator -->
            <div class="status">
                <div class="status-dot <?php echo $hasMessage ? '' : 'error'; ?>"></div>
                <span><?php echo $hasMessage ? '✅ En vivo' : '⚠️ Sin mensajes'; ?></span>
            </div>
        </div>

        <!-- ACTIONS -->
        <div class="actions">
            <button class="btn btn-primary" onclick="location.reload();">
                🔄 Actualizar Ahora
            </button>
            <button class="btn" onclick="copyMessage();">
                📋 Copiar Mensaje
            </button>
        </div>
    </div>

    <script>
        function copyMessage() {
            const message = `<?php echo addslashes(htmlspecialchars($txtMessage)); ?>`;
            navigator.clipboard.writeText(message).then(() => {
                alert('✅ Mensaje copiado al portapapeles');
            }).catch(() => {
                alert('❌ Error al copiar');
            });
        }

        // Auto-refresh cada 5 segundos
        
    </script>
</body>
</html>