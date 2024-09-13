<div class="wrap">
    <h1>Configuraciones de HPS Hub</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('hps-hub-settings-group');
        do_settings_sections('hps-hub-settings');
        submit_button();
        ?>
    </form>
</div>
