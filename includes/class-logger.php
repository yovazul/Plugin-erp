<?php
/**
 * Clase para registro de logs
 */

if (!defined('ABSPATH')) {
    exit;
}

class DCF_Logger {
    
    /**
     * Escribir un mensaje en el log
     * 
     * @param string $message Mensaje a registrar
     * @param string $level Nivel de log (info, warning, error)
     */
    public static function log($message, $level = 'info') {
        $log_file = DCF_LOG_FILE;
        
        // Crear el directorio si no existe
        $log_dir = dirname($log_file);
        if (!file_exists($log_dir)) {
            wp_mkdir_p($log_dir);
        }
        
        // Formatear el mensaje
        $timestamp = current_time('Y-m-d H:i:s');
        $log_entry = sprintf(
            "[%s] [%s] %s\n",
            $timestamp,
            strtoupper($level),
            $message
        );
        
        // Escribir en el archivo
        // Usar FILE_APPEND para añadir al final del archivo
        // LOCK_EX para evitar problemas de concurrencia
        @file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
        
        // Rotar el log si es demasiado grande (más de 5MB)
        self::rotate_log_if_needed($log_file);
    }
    
    /**
     * Rotar el archivo de log si supera un tamaño determinado
     * 
     * @param string $log_file Ruta al archivo de log
     */
    private static function rotate_log_if_needed($log_file) {
        if (!file_exists($log_file)) {
            return;
        }
        
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (filesize($log_file) > $max_size) {
            // Crear backup del log actual
            $backup_file = $log_file . '.' . date('Y-m-d-His') . '.bak';
            @rename($log_file, $backup_file);
            
            // Limpiar backups antiguos (mantener solo los últimos 5)
            self::clean_old_backups(dirname($log_file));
        }
    }
    
    /**
     * Limpiar backups antiguos
     * 
     * @param string $log_dir Directorio de logs
     */
    private static function clean_old_backups($log_dir) {
        $backup_files = glob($log_dir . '/*.bak');
        
        if (count($backup_files) > 5) {
            // Ordenar por fecha de modificación
            usort($backup_files, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            
            // Eliminar los más antiguos
            $files_to_delete = array_slice($backup_files, 0, count($backup_files) - 5);
            foreach ($files_to_delete as $file) {
                @unlink($file);
            }
        }
    }
    
    /**
     * Obtener el contenido del log
     * 
     * @param int $lines Número de líneas a obtener (0 = todas)
     * @return string Contenido del log
     */
    public static function get_log($lines = 100) {
        $log_file = DCF_LOG_FILE;
        
        if (!file_exists($log_file)) {
            return __('No hay registros disponibles.', 'dolibarr-contact-form');
        }
        
        if ($lines === 0) {
            return file_get_contents($log_file);
        }
        
        // Obtener las últimas N líneas
        $file = new SplFileObject($log_file);
        $file->seek(PHP_INT_MAX);
        $total_lines = $file->key() + 1;
        
        $start_line = max(0, $total_lines - $lines);
        
        $file->seek($start_line);
        $content = '';
        
        while (!$file->eof()) {
            $content .= $file->current();
            $file->next();
        }
        
        return $content;
    }
}
