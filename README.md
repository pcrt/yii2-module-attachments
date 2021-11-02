# Modulo gestione allegati

### Configurazione

Dopo l'installazione, è necessario collegare la migration, inserendo il seguente codice in `config/console.php`

```php
'aliases' => [
    '@pcrt/file' => '@vendor/pcrt/yii2-attachments'
],

'controllerMap' => [
    'migrate' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationNamespaces' => [
            'pcrt\file\migrations'
        ]   
    ]
]
```

ed eseguire le migration con `php yii migrate`.

Poi, impostare la configurazione in `config/web.php`

```php
'modules' => [
    'attachments' => [
        'class' => 'pcrt\file\Module',
        'filePluginSavePath' => '/files' //cartella del filesystem in cui salvare i file (all'interno della directory pubblica di yii)
    ]
]
```

------

### Esempio widget

```php
FileUploader::widget([
    'model_classname' => 'Attachment', //nome dell'entità o tabella
    'model_id' => 1, //id dell'elemento
])
```