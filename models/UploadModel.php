<?php

namespace HPSHUB\Models;

use HPSHUB\Includes\Core\Helper;

if (!defined('ABSPATH')) {
    exit;
}

class UploadModel {
    public static function handle_module_upload() {
        // Comprobar si hay un archivo ZIP subido
        if (!isset($_FILES['module_zip']) || $_FILES['module_zip']['error'] != UPLOAD_ERR_OK) {
            error_log('Error en la subida del archivo ZIP.');
            return ['success' => false, 'message' => 'Hubo un problema con la subida del archivo.'];
        }

        $uploaded_file = $_FILES['module_zip'];

        // Tipos MIME permitidos para archivos ZIP
        $allowed_mime_types = [
            'application/zip',
            'application/x-zip-compressed',
            'multipart/x-zip',
            'application/x-compressed',
        ];

        if (!in_array($uploaded_file['type'], $allowed_mime_types)) {
            error_log('Tipo de archivo no permitido: ' . $uploaded_file['type']);
            return ['success' => false, 'message' => 'Solo se permiten archivos ZIP.'];
        }

        // Definir el directorio donde se va a extraer el módulo
        $upload_dir = HPSHUB_DIR . 'Modules/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
            error_log('Directorio de módulos creado: ' . $upload_dir);
        } else {
            error_log('Directorio de módulos ya existente: ' . $upload_dir);
        }

        // Ruta del archivo ZIP subido
        $zip_path = $upload_dir . basename($uploaded_file['name']);
        error_log('Ruta temporal del ZIP: ' . $zip_path);

        if (!move_uploaded_file($uploaded_file['tmp_name'], $zip_path)) {
            error_log('Error moviendo el archivo subido al directorio de módulos.');
            return ['success' => false, 'message' => 'No se pudo mover el archivo subido.'];
        }

        // Descomprimir el archivo ZIP
        $zip = new \ZipArchive;
        if ($zip->open($zip_path) === TRUE) {
            // Validar y extraer el archivo
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);

                // Verificar que no haya rutas ascendentes
                if (strpos($filename, '../') !== false) {
                    unlink($zip_path);
                    error_log('Ruta ascendente detectada en el archivo ZIP.');
                    return ['success' => false, 'message' => 'El archivo ZIP contiene rutas no permitidas.'];
                }
            }

            // Nombre de la carpeta del módulo
            $module_name = basename($uploaded_file['name'], '.zip');
            $extract_path = $upload_dir . $module_name;

            if (!file_exists($extract_path)) {
                mkdir($extract_path, 0755, true);
                error_log('Directorio de módulo creado: ' . $extract_path);
            } else {
                error_log('Directorio de módulo ya existente: ' . $extract_path);
            }

            // Extraer el archivo en el directorio correcto
            $zip->extractTo($extract_path);
            $zip->close();
            unlink($zip_path);  // Eliminar el archivo ZIP subido

            error_log('Archivo ZIP descomprimido correctamente en: ' . $extract_path);
            return ['success' => true];
        } else {
            error_log('Error al descomprimir el archivo ZIP.');
            return ['success' => false, 'message' => 'No se pudo descomprimir el archivo ZIP.'];
        }
    }
}
