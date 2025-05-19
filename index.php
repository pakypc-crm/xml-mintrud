<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Данные об обученных лицах</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body { padding-top: 20px; }
        .table-responsive {
            margin-top: 20px;
        }
        .table-compact {
            font-size: 12px;
        }
        .table-compact th, .table-compact td {
            padding: 4px !important;
            white-space: normal;
            word-wrap: break-word;
            max-width: 150px;
        }
        .table-compact th {
            font-size: 12px;
            font-weight: bold;
        }
        /* Уменьшение отступов в контейнере для увеличения доступного пространства */
        .container-fluid {
            padding-left: 5px;
            padding-right: 5px;
        }
        .narrow-col {
            max-width: 50px;
        }
        .medium-col {
            max-width: 80px;
        }
        .wide-col {
            max-width: 120px;
        }
        pre.xml-view {
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 15px;
            margin-top: 20px;
            overflow: auto;
            max-height: 800px;
            white-space: pre-wrap;
        }
        .btn-group { margin-bottom: 20px; }
        .structured-xml-table th {
            background-color: #f8f8f8;
            font-weight: bold;
        }
        .structured-xml-table td {
            vertical-align: top;
            padding: 8px;
        }
        .toggle-xml {
            cursor: pointer;
            color: #337ab7;
            margin-right: 10px;
            font-weight: bold;
        }
        .xml-data {
            display: none;
            margin-top: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            max-height: 400px;
            overflow: auto;
        }
        .xml-content-collapsed {
            display: none;
        }
        .xml-content-expanded {
            display: block;
        }
        .sorted-indicator {
            margin-left: 5px;
            color: #337ab7;
            font-size: 10px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2>Данные об обученных лицах</h2>
            <p class="lead">Просмотр данных об обученных лицах из XML файла</p>
        </div>
    </div>


<?php

// Загружаем автозагрузчик Composer
require_once __DIR__ . '/vendor/autoload.php';

use Pakypc\XMLMintrud\XMLRenderer;

// Путь к XML файлу (по умолчанию используем sample-data.xml)
$xmlFile = __DIR__ . '/sample-data.xml';

// Параметр для передачи пользовательского XML файла через GET или POST запрос
$customXmlFile = $_REQUEST['xml_file'] ?? null;

if ($customXmlFile && file_exists($customXmlFile)) {
    $xmlFile = $customXmlFile;
}

    // Создаем рендерер
    $renderer = XMLRenderer::fromFile($xmlFile);
        echo $renderer->toHtml();
?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
    // Функционал для сворачивания/разворачивания XML в структурированном виде
    $(document).ready(function() {
        $(".toggle-xml").on("click", function() {
            var $this = $(this);
            var personId = $this.data("person-id");
            var $xmlContent = $("#xml-content-" + personId);

            if ($this.text() === "[+] Показать XML") {
                // Разворачиваем XML
                $this.text("[-] Скрыть XML");
                $xmlContent.slideDown();
            } else {
                // Сворачиваем XML
                $this.text("[+] Показать XML");
                $xmlContent.slideUp();
            }
        });
    });
</script>
</body>
</html>
