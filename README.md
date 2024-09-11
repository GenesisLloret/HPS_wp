# PLUGIN
```
/hps-hub/
│
├── /admin/
│   ├── menu.php                // Clase para crear el menú en el panel de administración
│   ├── upload.php              // Clase para gestionar la subida de extensiones Zip
│   ├── extensions.php          // Clase para gestionar las extensiones subidas
│   ├── settings.php            // Clase para manejar la zona de configuración
│   ├── config.json             // Archivo que almacena el estado de las extensiones activas
│
├── /exts/                      // Carpeta de extensiones
│   └── /nombre-extension/      // Carpeta donde se extraen las extensiones subidas
│       ├── data.json           // Configuración de la extensión
│       └── index.php           // Funcionalidades de la extensión
│
├── /assets/
│   ├── /css/
│   │   └── admin.css           // Estilos personalizados para el panel de administración
│   └── /js/
│       └── admin.js            // Scripts personalizados para el panel de administración
│
├── hps-hub.php                 // Archivo principal del plugin
├── uninstall.php               // Código para la desinstalación del plugin
└── readme.txt                  // Descripción del plugin
```
