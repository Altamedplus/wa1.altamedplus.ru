<?php

namespace APP\Controller\Ajax\Sample;

use APP\Controller\AjaxController;
use APP\Enum\ButtonType;
use APP\Enum\HeaderType;
use APP\Enum\VariableType;
use APP\Model\ButtonsSampleModel;
use APP\Model\HeaderSampleModel;
use APP\Model\SampleModel;
use APP\Model\VariableModel;
use APP\Module\UI\UI;
use Pet\Model\Model;
use Pet\Router\Header;

class Get extends AjaxController
{

    public function helper()
    {
        //  нужно из шаблона получить все переменные
        $sample = new SampleModel(['id'=> attr('id')]);
        $text = $sample->text;
        $aliases = $this->getAlias($text);
        $result = [];
        $i = 1;
        foreach ($aliases as $alias) {
            $variable = new VariableModel(['name_uniq' => $alias]);
            if (!$variable->exist()) continue;
            $format = [];
            if (!empty($variable->get('format'))) {
                $format = ['data-format' => $variable->format];
            }
            $result[] = VariableType::getHtml((int)$variable->type, [
                'name' => 'var_'.$alias."[]",
                'placeholder' => $variable->name,
                'title' => $variable->description,
                'data-variable' => $alias,
                'data-count' => $i,
                'data-reload' => '',
                'tabindex' => $i
            ] + $format);
            $i++;
        }

        $buttons = (new ButtonsSampleModel())->find(['sample_id' => $sample->id], function (Model $q) {
            $q->select('b.*');
            $q->join('buttons b')->on('buttons_sample.buttons_id = b.id');
        });

        $dataButtons = $this->getFieldsButton($buttons);
        $header = (new HeaderSampleModel(['sample_id' => $sample->id]));
        $message = '';

        if ($header->type == HeaderType::TEXT) {
            $message = "<b class='flex-row-center w-100'>{$header->text}</b>";
        }

        if ($header->type == HeaderType::IMAGE) {
            $message = "<div class='messange-header-img'><img src='{$header->img_url}'></img></div>";
        }

        $text = $sample->replaseVariable($text);
        $text = preg_replace('/\*(.*?)\*/', '<b>$1</b>', $text);
        $message .= $text;
        $message .= "<br/><br/><i class='flex-row-center fs-11 w-100'>{$sample->footer}</i>";
        $message .= implode('<br/>', $this->getMessageButton($buttons));
        return [
            'header' => '',
            'html' => $result,
            'message' => $message,
            'button' => $dataButtons,
        ];
    }

    /**
     * getAlias
     *
     * @param  mixed $text
     * @return array
     */
    private function getAlias(string $text): array
    {
        preg_match_all('/\{\{([A-Za-z0-9]{1,})\}\}/', $text, $macth);
        if (!empty($macth[1])) {
            return $macth[1];
        }
        return [];
    }

    private function getMessageButton(array $buttons): array
    {
        $result = [];
        foreach ($buttons as $button) {
            $button = (object)$button;
            if ($button->type == ButtonType::PHONE) {
                $result[] = UI::showStr([
                    'tag' => 'button',
                    'class' => 'message-btn-phone',
                    'type' => 'button',
                    'style' => !empty($button->color) ? "background:{$button->color}": "",
                    'name' => 'button[' . $button->id . ']',
                    'placeholder' => $button->text,
                    'title' => $button->text,
                    'textContent' => $button->phone
                ]);
            }

            if ($button->type == ButtonType::URL) {
                if (!empty($button->url)) {
                    $result[] = UI::showStr([
                        'tag' => 'a',
                        'type' => 'button',
                        'class' => 'message-btn-url',
                        'name' => 'button[' . $button->id . ']',
                        'style' => !empty($button->color) ? "background:{$button->color}": "",
                        'placeholder' => $button->text,
                        'title' => $button->url . ($button->is_url_postfix == 1 ? $button->url_postfix : ''),
                        'href' => $button->url,
                        'data-btn-postfix' => $button->is_url_postfix == 1 ? $button->url_postfix : '',
                        'textContent' => $button->text
                    ]);
                }
            }
            if ($button->type == ButtonType::QUICK_REPLY) {
                    $result[] = UI::showStr([
                        'tag' => 'button',
                        'type' => 'button',
                        'class' => 'message-btn-quick',
                        'style' => !empty($button->color) ? "background:{$button->color}": "",
                        'name' => 'button[' . $button->id . ']',
                        'placeholder' => $button->text,
                        'title' => $button->text,
                        'textContent' => $button->text,
                        'data-btn-payload' => $button->payload
                    ]);
            }
        }

        return $result;
    }

    private function getFieldsButton(array $buttons): array
    {
        $result = [];
        foreach ($buttons as $button) {
            $button = (object)$button;
            if ($button->type == ButtonType::PHONE) {
                $result[] = UI::showStr([
                    'tag' => 'input',
                    'type' => 'hidden',
                    'name' => 'button[' . $button->id . ']',
                    'placeholder' => $button->text,
                    'title' => $button->text,
                    'value' => $button->phone
                ]);
            }

            if ($button->type == ButtonType::URL) {
                if (!empty($button->url)) {
                    $result[] = UI::showStr([
                        'tag' => 'input',
                        'type' => 'hidden',
                        'name' => 'button[' . $button->id . ']',
                        'placeholder' => $button->text,
                        'title' => $button->text,
                        'value' => $button->url
                    ]);
                }
                if ($button->is_url_postfix == 1) {
                    $result[] = UI::showStr([
                        'tag' => 'input',
                        'type' => 'text',
                        'name' => 'button[postfix][' . $button->id . ']',
                        'placeholder' => $button->text,
                        'title' =>$button->text,
                        'value' => $button->url_postfix,
                        'data-reload' => ''
                    ]);
                }
            }
            if ($button->type == ButtonType::QUICK_REPLY) {
                    $result[] = UI::showStr([
                        'tag' => 'input',
                        'type' => 'hidden',
                        'name' => 'button[' . $button->id . ']',
                        'placeholder' => $button->text,
                        'title' => $button->text,
                        'value' => $button->payload
                    ]);
            }
        }

        return $result;
    }
}
