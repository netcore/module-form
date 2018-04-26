<?php

namespace Modules\Form\Traits;

use Illuminate\Support\Collection;
use Netcore\Translator\Helpers\TransHelper;

trait FieldTrait
{
    /**
     * @param null $data
     * @return Collection
     */
    private function getFields($data): Collection
    {
        $fields = $data->fields()->with('translations')->orderBy('order', 'ASC')->get()->transform(function (
            $f
        ) {
            $field = $f->toArray();
            $field['translations'] = $f->translations->keyBy('locale');

            return $field;
        })->toArray();

        return collect($fields)->map(function ($field, $id) {
            return $this->mapField($id, $field);
        });
    }

    /**
     * @param $id
     * @param $field
     * @return array
     */
    private function mapField($id, $field): array
    {
        $translations = [];
        $languages = TransHelper::getAllLanguages();

        foreach ($languages as $key => $language) {
            $label = isset($field['translations'][$language->iso_code]) && isset($field['translations'][$language->iso_code]['label']) ? $field['translations'][$language->iso_code]['label'] : (isset($field['translations'][$key]['label']) ? $field['translations'][$key]['label'] : '(Not specified)');
            $placeholder = isset($field['translations'][$language->iso_code]) && isset($field['translations'][$language->iso_code]['placeholder']) ? $field['translations'][$language->iso_code]['placeholder'] : (isset($field['translations'][$key]['placeholder']) ? $field['translations'][$key]['placeholder'] : '');

            $translations[$language->iso_code] = [
                'label'       => $label,
                'placeholder' => $placeholder
            ];
        }

        return [
            'id'           => isset($field['id']) ? (int)$field['id'] : (int)$id,
            'key'          => isset($field['key']) ? $field['key'] : '',
            'type'         => $field['type'],
            'translations' => $translations,
            'order'        => isset($field['order']) ? (int)$field['order'] : (int)$id + 1,
            'meta'         => $this->parseData($field, 'meta'),
            'show_label'   => $field['show_label'],
            'optionsType'  => ''
        ];
    }

    /**
     * @param $formField
     * @param $type
     * @return mixed
     */
    private function parseData($formField, $type)
    {
        $data = isset($formField[$type]) ? $formField[$type] : [];

        if (!in_array($type, ['options'])) {
            return $data;
        }

        if (!$data) {
            return [];
        }

        return $formField['options_type'] === 'data' ? array_combine($data['key'], $data['value']) : $data;
    }

    /**
     * @param        $formField
     * @param null   $order
     * @param string $action
     * @return array
     */
    private function field($formField, $order = null, $action = 'update'): array
    {
        $data = [
            'key'        => isset($formField['key']) ? $formField['key'] : '',
            'meta'       => [
                'attributes'   => $this->parseData($formField, 'attributes'),
                'options'      => $this->parseData($formField, 'options'),
                'options_type' => $this->parseData($formField, 'option_type'),
                'validation'   => $this->parseData($formField, 'validation')
            ],
            'order'      => $order ?: $formField['order'],
            'show_label' => isset($formField['show_label']) ? 1 : 0
        ];

        if ($action === 'create') {
            $data['type'] = $formField['type'];
        }

        return $data;
    }
}
