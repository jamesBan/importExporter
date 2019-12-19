# excel 导入 导出

## 安装

```shell
composer require "james-ban/import-export"
```



## 使用



###  导入

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

//过滤器
$filter = new class implements PhpOffice\PhpSpreadsheet\Reader\IReadFilter {
   public function readCell($column, $row, $worksheetName = ''):bool
   {
        return $column === 'A';
   }
};

$reader = new ImportExporter\import\Excel([
	'filePath' => '/Users/burn/Desktop/维权.xlsx',
	'fileExtension' => 'xlsx',
	'formatFunction' => function($item) {//只读取第一个单元格
		return current($item);
	},
	'fistLine' => 0,
	'readFilter' => $filter//读取A列
]);


print_r($reader->readAll());

```





###  导出

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

//导出
$downloader = new ImportExporter\export\ExcelXml([
	'data' => [[1, '222'], [2, 'xxx']],
	'header' => ['id', 'name'],
	'filename' => 'test.xls',
]);
$downloader->download();


```
