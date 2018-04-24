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
    private function getFields($data = null): Collection
    {
        $currentFields = [];
        $oldFields = old('fields', []);

        if ($data) {
            $currentFields = $data->fields()->with('translations')->orderBy('order', 'ASC')->get()->transform(function (
                $f
            ) {
                $field = $f->toArray();
                $field['translations'] = $f->translations->keyBy('locale');

                return $field;
            })->toArray();
        }

        $fields = array_merge($oldFields, $currentFields);

        // Unique
        $fields = array_filter($fields, function ($value, $key) use ($fields) {
            return $key === array_search($value['id'], array_column($fields, 'id'));
        }, ARRAY_FILTER_USE_BOTH);

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
            $name = isset($field['translations'][$language->iso_code]) && isset($field['translations'][$language->iso_code]['label']) ? $field['translations'][$language->iso_code]['label'] : (isset($field['translations'][$key]['label']) ? $field['translations'][$key]['label'] : '(Not specified)');
            $placeholder = isset($field['translations'][$language->iso_code]) && isset($field['translations'][$language->iso_code]['placeholder']) ? $field['translations'][$language->iso_code]['placeholder'] : (isset($field['translations'][$key]['placeholder']) ? $field['translations'][$key]['placeholder'] : '');

            $translations[$language->iso_code] = [
                'name'        => $name,
                'label'       => $name,
                'placeholder' => $placeholder
            ];
        }

        return [
            'id'           => isset($field['id']) ? (int)$field['id'] : (int)$id,
            'key'          => isset($field['key']) ? $field['key'] : '',
            'type'         => $field['type'],
            'type_name'    => ucfirst($field['type']),
            'translations' => $translations,
            'order'        => isset($field['order']) ? (int)$field['order'] : (int)$id + 1,
            'meta'         => $this->parseData($field, 'meta')
        ];
    }

    /**
     * @param $formField
     * @param $type
     * @return array
     */
    private function parseData($formField, $type): array
    {
        $data = isset($formField[$type]) ? $formField[$type] : [];

        if (!in_array($type, ['options'])) {
            return $data;
        }

        if (!$data) {
            return [];
        }

        return array_combine($data['key'], $data['value']);
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
            'meta'       => [
                'attributes' => $this->parseData($formField, 'attributes'),
                'options'    => $this->parseData($formField, 'options'),
                'validation' => $this->parseData($formField, 'validation')
            ],
            'order'      => $order ?: $formField['order'],
            'show_label' => isset($formField['show_label']) ? 1 : 0
        ];

        if ($action === 'create') {
            $data['key'] = isset($formField['key']) ? $formField['key'] : '';
            $data['type'] = $formField['type'];
        }

        return $data;
    }
}
