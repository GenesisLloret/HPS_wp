<?php

namespace HPS_Hub\Models;

if (!defined('ABSPATH')) {
    exit;
}

class UploadModel {
    public static function handle_extension_upload() {
        if (!isset($_FILES['extension_zip']) || $_FILES['extension_zip']['error'] != UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Hubo un problema con la subida del archivo.'];
        }

        $uploaded_file = $_FILES['extension_zip'];

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

        $upload_dir = HPS_HUB_PLUGIN_DIR . 'exts/';
        $zip_path = $upload_dir . basename($uploaded_file['name']);

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

                // Verificar tipos de archivos permitidos
                $allowed_extensions = ['php', 'css', 'js', 'json', 'txt', 'md'];
                $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
                if (!in_array($file_extension, $allowed_extensions)) {
                    unlink($zip_path);
                    return ['success' => false, 'message' => 'El archivo ZIP contiene archivos no permitidos.'];
                }
            }

            $zip->extractTo($upload_dir);
            $zip->close();
            unlink($zip_path);

            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'No se pudo descomprimir el archivo ZIP.'];
        }
    }
}
