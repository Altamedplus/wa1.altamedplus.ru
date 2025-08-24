<?php

namespace APP\Model;

use APP\Enum\ButtonType;
use APP\Enum\VariableReserveType as VRT;
use APP\Enum\VariableReserveType;
use Pet\Model\Model;

class SampleModel extends Model
{
    protected string $table = 'sample_messange_wa';

    public function replaseVariable($text): string
    {
        preg_match_all('/\{\{([A-Za-z0-9]{1,})\}\}/', $text, $macth);
        if (!empty($macth[1])) {
            foreach ($macth[1] as $nameUniq) {
                $content = new VariableModel(['name_uniq' => $nameUniq]);
                if ($content->exist()) {
                    $name = $content->name;
                    $content = "<span class='btn btn-green' data-messange='$nameUniq' title='$content->description'  data-content='$name'>$name</span>";
                    $text = str_replace('{{'.$nameUniq.'}}', $content, $text);
                } elseif (in_array($nameUniq, VRT::keys())) {
                    $content = "<span class='btn btn-green' data-consant='$nameUniq'  data-content='".VRT::get($nameUniq)."'>".VRT::get($nameUniq)."</span>";
                    $text = str_replace('{{'.$nameUniq.'}}', $content, $text);
                }
            }
        }

        $text = preg_replace('/\r\n/', '<br/>', $text);
        return $text;
    }


    public static function complectWhatsApp(int $id, $variables = [], $button = [], $clinicId = null): array
    {
        $result = (new self())->find(callback: function (Model $q) use ($id) {
            $q->select('sample_messange_wa.*',);
            $q->where("sample_messange_wa.id = $id");
        });

        if (empty($result[0])) return [];

        $s = (object)$result[0];
        foreach ($variables as $name => $values) {
            foreach ($values as $value) {
                $var = new VariableModel(['name_uniq' => $name]);

                if ($var->exist() && !empty($var->get('format'))) {
                    $value = date($var->format, strtotime($value));
                }

                $s->text = str_replace('{{' . $name . '}}', $value, $s->text);
            }
        }
        if (!empty($clinicId)) {
            $clinic = new ClinicModel(['id' => $clinicId]);
            foreach (VariableReserveType::data() as $key => $name) {
                if ($key == VariableReserveType::CLINIC) {
                    $s->text = str_replace('{{' . $key . '}}', $clinic->name, $s->text);
                }
                if ($key == VariableReserveType::ADDRESS_CLINIC) {
                    $s->text = str_replace('{{' . $key . '}}', $clinic->address, $s->text);
                }
            }
        }

        $data = [
           'contentType' =>  $s->content_type,
           'text' =>   $s->text,
        ];

        if (($header = (new HeaderSampleModel(['sample_id' => $s->id]))->headerComplect())) {
            $data['header'] = $header;
        }
        $sampleButtons =  (new ButtonsSampleModel())->findM(['sample_id' => $s->id], callback:function (Model $m) {
            $m->select('b.*');
            $m->join('buttons b')->on(['b.id', 'buttons_sample.buttons_id']);
        });

        $dataButton = [];
        foreach ($sampleButtons as $mBut) {
            foreach ($button as $id => $value) {
                if ((int)$id === (int)$mBut->id) {
                    $dataButton[] =  self::complectButtons($mBut, (int)$id, $value, $button);
                }
            }
        }

        if (!empty($dataButton)) {
            $data['keyboard'] = ['rows' => [[ 'buttons' => $dataButton ]]];
        }

        if (!empty($s->footer)) {
            $data['footer'] = ['text' => $s->footer ];
        }

        return $data;
    }

    public static function complectButtons(Model $button, $id, $value, $field): array
    {
        $result = [];
        $result['text'] = $button->text;
        if ($button->type == ButtonType::URL) {
            $result['url'] = $value;
            if ($button->is_url_postfix == 1) {
                $result['urlPostfix'] = $field['postfix'][$id];
            }
        }

        if ($button->type == ButtonType::PHONE) {
            $result['phone'] = $value;
        }
        if ($button->type == ButtonType::QUICK_REPLY) {
            $result['payload'] = $button->payload;
        }
        $result['type'] = $button->type;
        if (!empty($button->get('color'))) {
            $result['color'] = $button->get('color');
        }
        return $result;
    }

}

