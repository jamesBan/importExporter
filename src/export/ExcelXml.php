<?php

namespace ImportExporter\export;

/**
 * Class Xml
 * @package app\utils\export
 */
class ExcelXml extends Download
{
    public function format(): void
    {
        $this->sendXml();
        $this->sendHead();
        $this->sendBody();
        $this->sendFoot();
    }

    protected function sendXml()
    {
        echo <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook
	xmlns="urn:schemas-microsoft-com:office:spreadsheet"
	xmlns:x="urn:schemas-microsoft-com:office:excel"
	xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
	xmlns:html="https://www.w3.org/TR/html401/">
	<Worksheet ss:Name="CognaLearn+Intedashboard">
		<Table>
			<Column ss:Index="1" ss:AutoFitWidth="0" ss:Width="110"/>
EOF;
    }

    protected function sendFoot()
    {
        echo <<<EOF
		</Table>
	</Worksheet>
</Workbook>
EOF;
    }

    protected function sendBody()
    {
        foreach ($this->data as $items) {
            echo '<Row>';
            foreach ($items as $item) {
                echo '<Cell><Data ss:Type="String">' . $item . '</Data></Cell>';
            }
            echo '</Row>';
        }
    }

    protected function sendHead()
    {
        $result = "<Row>";
        foreach ($this->header as $item) {
            $result .= '<Cell><Data ss:Type="String">' . $item . '</Data></Cell>';
        }

        echo $result . "</Row>";
    }

    public function getExtension(): string
    {
        return 'xlsx';
    }

}