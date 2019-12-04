<?php

namespace ImportExporter\export;

/**
 * Class Excel
 * @package app\utils\export
 */
class Excel extends Download
{
    public function format(): void
    {
        $this->sendHeader();
        $this->sendBody();
        $this->sendFoot();
    }

    public function getExtension(): string
    {
        return 'xls';
    }


    protected function sendFoot()
    {
        echo <<<EOF
                </tbody>
            </table>
        </div>
    </body>

</html>
EOF;
    }


    protected function sendBody()
    {
        foreach ($this->data as $item) {
            echo '<tr><td>';
            echo ltrim(implode('</td><td>', $item), '<td>');
            echo '<tr>';
        }
    }

    protected function sendHeader()
    {
        $header = '<tr><th>' . rtrim(implode('</th><th>', $this->header), '<th>') . '</th></tr>';
        $content = <<<EOF
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
    <meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
    
    <head>
        <!--[if gte mso 9]>
            <xml>
                <x:ExcelWorkbook>
                    <x:ExcelWorksheets>
                        <x:ExcelWorksheet>
                            <x:Name>Table</x:Name>
                            <x:WorksheetOptions>
                                <x:DisplayGridlines/></x:WorksheetOptions>
                        </x:ExcelWorksheet>
                    </x:ExcelWorksheets>
                </x:ExcelWorkbook>
            </xml>
        <![endif]-->
        <style>
</style>
    </head>
    
    <body>
        <div class="Section1">
            <table>
                <thead>
                    <tr>
                        $header
                    </tr>
                </thead>
                <tbody>
EOF;

        echo $content;
    }

}