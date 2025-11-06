<?php

namespace APP\Model;

use Exception;
use Pet\Model\Model;

class MessageModel extends Model
{
    protected string $table = 'message';

    public function getHtml(): string
    {
        try {
            $data = json_decode($this->data_request);
        } catch (Exception $e) {
            return '';
        }
        $messange = '';
        if ($data?->header?->headerType == "TEXT") {
            $messange .= "<h4>{$data->header->text}</h4>";
        }
        if ($data?->contentType == "TEXT") {
            $text = str_replace("\n\r", '<br/>', $data->text);
            $text = preg_replace('/\*(.*?)\*/', '<b>$1</b>', $text);
            $messange .= "<div class=\"body\">{$text}</div>";
        }
        if (!empty($data?->footer)) {
            $messange .= "<div class=\"footer\"><b>{$data->footer->text}</b></div>";
        }
        return $messange;
    }
}
