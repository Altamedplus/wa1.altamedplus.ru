<?php

namespace APP\Model;

use APP\Enum\HeaderType;
use Pet\Model\Model;
use Pet\Router\Header;

class HeaderSampleModel extends Model
{
    protected string $table = 'header';

    public function default()
    {
        $this->show(' COLUMNS ');
        $filds = $this->fetch();
        $fildsDefault = [];
        foreach ($filds as $fild) {
            if (in_array($fild['Field'], ['id', 'cdate', 'update', 'sample_id'])) {
                continue;
            }
            $fildsDefault[$fild['Field']] = $fild['Default'];
        }
        return $fildsDefault;
    }

    public function setDefault()
    {
        $this->set($this->default());
    }

    public function headerComplect(): array| false
    {
        if (!$this->exist()) {
            return false;
        }
        $result = [];

        switch ($this->type) {
            case HeaderType::TEXT:
                $result = [
                    'headerType' =>  HeaderType::TEXT,
                    'text' => $this->text
                ];
                if (empty($this->get('text')))
                    $result = false;
                break;
            case HeaderType::DOCUMENT:
                $result = [
                    'headerType' =>  HeaderType::DOCUMENT,
                    'documentUrl' => $this->document_url,
                    'documentName' => $this->document_name
                ];
                break;
            case HeaderType::IMAGE:
                $result = [
                    'headerType' => HeaderType::IMAGE,
                    'imageUrl' => $this->img_url
                ];
                break;
            case HeaderType::VIDEO:
                $result = [
                    'headerType' => HeaderType::VIDEO,
                    'videoName' => $this->video_name,
                    'videoUrl' => $this->video_url,
                ];
                break;
        }

        return $result;
    }
}
