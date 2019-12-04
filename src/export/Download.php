<?php

namespace ImportExporter\export;

use ImportExporter\Model;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Download
 * @package ImportExporter\export
 */
abstract class Download extends Model
{
    public $data;
    public $header;
    public $filename;

    public function download():void
    {
        $response = new Response();
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Accept-Ranges', 'bytes');
        $response->headers->set('Expires', 0);
        $response->headers->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $this->getFilename()
        );
        $response->headers->set('Content-Disposition', $disposition);
        $response->sendHeaders();

        $this->format();
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        $filePathInfo = pathinfo($this->filename);
        return $filePathInfo['filename'] . '-' . date('Y-m-d') . '.' . $this->getExtension();
    }

    abstract public function format(): void;

    abstract public function getExtension(): string;
}