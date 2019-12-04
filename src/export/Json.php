<?php

namespace ImportExporter\export;

/**
 * Class Json
 * @package ImportExporter\export
 */
class Json extends Download
{
    public function format(): void
    {
        echo '{';
        echo sprintf('"header": [%s],', $this->prettyJson($this->header));
        echo '"data": [';
        foreach ($this->data as $value) {
            echo $this->prettyJson($value), ',';
        }
        echo ']}';
    }

    public function getExtension(): string
    {
        return 'json';
    }


    protected function prettyJson(array $data)
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

}