<?php


namespace ImportExporter\export;


use League\Csv\Writer;

class Csv extends Download
{
    public function format(): void
    {
        $csv = Writer::createFromString('');

        //load the CSV document from a string
        $csv->insertOne($this->header);
        $csv->insertAll($this->data);

        $csv->output($this->getFilename());
    }

    public function getExtension(): string
    {
        return 'csv';
    }

}