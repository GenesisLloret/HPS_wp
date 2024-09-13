<?php

namespace HPSHUB\Models;

use HPSHUB\Includes\Core\Helper;

if (!defined('ABSPATH')) {
    exit;
}

class UploadModel {
    public static function handle_module_upload() {
        if (!isset($_FILES['module_zip']) || $_FILES['module_zip']['error'] != UPLOAD_ERR_OK) {
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
            return ['success' => false, 'message' => 'Solo se permiten archivos ZIP.'];
        }

        $upload_dir = wp_upload_dir();
        $upload_path = $upload_dir['basedir'] . '/module-loader/';
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $zip_path = $upload_path . basename($uploaded_file['name']);

        if (!move_uploaded_file($uploaded_file['tmp_name'], $zip_path)) {
            return ['success' => false, 'message' => 'No se pudo mover el archivo subido.'];
        }

        $zip = new \ZipArchive;
        if ($zip->open($zip_path) === TRUE) {
            // Validar contenido del ZIP
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);

                // Verificar que no hay rutas ascendentes
                if (strpos($filename, '../') !== false) {
                    unlink($zip_path);
                    return ['success' => false, 'message' => 'El archivo ZIP contiene rutas no permitidas.'];
                }
            }

            $module_name = basename($uploaded_file['name'], '.zip');
            $extract_path = MODULE_LOADER_DIR . 'modules/' . $module_name;

            if (!file_exists($extract_path)) {
                mkdir($extract_path, 0755, true);
            }

            $zip->extractTo($extract_path);
            $zip->close();
            unlink($zip_path);

            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'No se pudo descomprimir el archivo ZIP.'];
        }
    }
}
