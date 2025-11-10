<?php
/**
 * Manejo de mensajes en la sesion.
 */

class Flash
{
    /**
     * Agrega un mensaje de éxito
     * 
     * @param string $mensaje Mensaje a mostrar
     */
    public static function success($mensaje)
    {
        self::set('success', $mensaje);
    }

    /**
     * Agrega un mensaje de error
     * 
     * @param string $mensaje Mensaje a mostrar
     */
    public static function error($mensaje)
    {
        self::set('error', $mensaje);
    }

    /**
     * Agrega un mensaje de advertencia
     * 
     * @param string $mensaje Mensaje a mostrar
     */
    public static function warning($mensaje)
    {
        self::set('warning', $mensaje);
    }

    /**
     * Agrega un mensaje informativo
     * 
     * @param string $mensaje Mensaje a mostrar
     */
    public static function info($mensaje)
    {
        self::set('info', $mensaje);
    }

    /**
     * Almacena un mensaje en la sesión
     * 
     */
    private static function set($tipo, $mensaje)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }

        if (!isset($_SESSION['flash'][$tipo])) {
            $_SESSION['flash'][$tipo] = [];
        }

        $_SESSION['flash'][$tipo][] = $mensaje;
    }

    /**
     * Obtiene todos los mensajes flash
     *
     */
    private static function getAll()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $mensajes = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);

        return $mensajes;
    }

    /**
     * Devuelve los mensajes flash como HTML
     * 
     */
    public static function render()
    {
        $html = '';
        $mensajes = self::getAll();

        $tipos = [
            'success' => ['clase' => 'alert-success', 'icono' => 'check-circle-fill'],
            'error' => ['clase' => 'alert-danger', 'icono' => 'exclamation-triangle-fill'],
            'warning' => ['clase' => 'alert-warning', 'icono' => 'exclamation-circle-fill'],
            'info' => ['clase' => 'alert-info', 'icono' => 'info-circle-fill']
        ];

        foreach ($tipos as $tipo => $config) {
            if (isset($mensajes[$tipo]) && !empty($mensajes[$tipo])) {
                foreach ($mensajes[$tipo] as $mensaje) {
                    $html .= '<div class="alert ' . $config['clase'] . ' alert-dismissible fade show" role="alert">';
                    $html .= '<i class="bi bi-' . $config['icono'] . ' me-2"></i>';
                    $html .= htmlspecialchars($mensaje);
                    $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    $html .= '</div>';
                }
            }
        }

        return $html;
    }
}


