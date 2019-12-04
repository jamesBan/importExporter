<?php

namespace ImportExporter\import;

use ImportExporter\Model;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use RuntimeException;
use Throwable;

/**
 * Class Excel
 * @property $reader IReadFilter
 */
class Excel extends Model
{
    /**
     * @var IReadFilter $readFilter 过滤器
     * @see https://phpspreadsheet.readthedocs.io/en/latest/topics/reading-and-writing-to-file/ Read specific cells only
     */
    public $readFilter;

    /**
     * @var string $filePath 文件路径
     */
    public $filePath;

    /**
     * @var string $fileExtension 文件扩展名
     */
    public $fileExtension;

    /**
     * @var callable $formatFunction 数据格式化回掉函数
     */
    public $formatFunction;

    /**
     * @var int $fistLine 数据第一行（如果需要所有值 传0）
     */
    public $fistLine = 0;

    public const EXCEL_2017 = 'xlsx';
    public const EXCEL_2003 = 'xls';
    public const EXCEL_CSV = 'csv';

    /**
     * @return array
     * @throws RuntimeException
     */
    public function readAll(): array
    {
        $this->checkFile();

        $reader = $this->getReader();

        if ($this->readFilter instanceof IReadFilter) {
            $reader->setReadFilter($this->readFilter);
        }

        try{
            $spreadsheet = $reader->load($this->filePath);
            $workSheet = $spreadsheet->setActiveSheetIndex(0);

            $data = $workSheet->toArray();
            if ($data && is_callable($this->formatFunction)) {
                $data = array_map($this->formatFunction, $data);
            }

            if((int)$this->fistLine > 0) {
                $data = array_slice($data, $this->fistLine);
            }

            return $data;
        } catch (Throwable $e) {
            throw new RuntimeException('读取文件内容错误:'.$e->getMessage(), 400);
        }

    }

    /**
     * @return Csv|Xls|Xlsx
     */
    public function getReader()
    {
        $reader = new Xlsx();
        switch ($this->fileExtension) {
            case self::EXCEL_2017:
                $reader = new Xlsx();
                break;
            case self::EXCEL_2003:
                $reader = new Xls();
                break;
            case self::EXCEL_CSV:
                $reader = new Csv();
                break;
        }

        $reader->setReadDataOnly(true);
        return $reader;
    }

    /**
     * @throws RuntimeException
     */
    protected function checkFile(): void
    {
        if (!file_exists($this->filePath)) {
            throw new RuntimeException('文件路径不存在', 400);
        }

        if (!is_readable($this->filePath)) {
            throw new RuntimeException('文件路径读取失败', 400);
        }

        if (!in_array($this->fileExtension, [
            self::EXCEL_CSV,
            self::EXCEL_2003,
            self::EXCEL_2017
        ], true)) {
            throw new RuntimeException('文件类型不支持', 400);
        }
    }
}