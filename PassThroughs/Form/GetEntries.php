<?php

namespace Modules\Form\PassThroughs\Form;

use function foo\func;
use Modules\Form\Models\Form;
use Modules\Form\PassThroughs\PassThrough;

class GetEntries extends PassThrough
{

    /**
     * @var Form
     */
    private $form;

    /**
     * Storage constructor.
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    /**
     * @param null $limit
     * @return array
     */
    public function all($limit = null)
    {
        $array = [];
        foreach ($this->getEntries() as $i => $entry) {
            foreach ($this->getColumns() as $key => $field) {
                $array[$i][$key] = $this->getValue($key, $entry, $limit);
            }
        }

        return $array;
    }

    /**
     * @param      $entry
     * @param null $limit
     * @return array
     */
    public function get($entry, $limit = null)
    {
        $array = [];

        $entry = $this->getEntries()->get($entry);
        if (!$entry) {
            return [];
        }

        foreach ($this->getColumns() as $key => $field) {
            $array[$key] = $this->getValue($key, $entry, $limit);
        }

        return $array;
    }

    /**
     * @return mixed
     */
    public function count()
    {
        return $this->getEntries()->count();
    }

    /**
     * @return mixed
     */
    public function getColumns()
    {
        $columns = $this->form->fields->pluck('label', 'key');
        $columns->prepend('Entry ID', 'id');
        $columns->put('created_at', 'Submitted At');

        return $columns->toArray();
    }

    /**
     * @return collection
     */
    private function getEntries()
    {
        return $this->form->form_entries->groupBy('batch')->map(function ($entry) {
            return $entry->keyBy('form_field.key');
        });
    }

    /**
     * @param $key
     * @param $entry
     * @return string
     */
    private function getValue($key, $entry, $limit = null)
    {
        if ($key == 'id') {
            return $this->getId($entry);
        }

        if ($key == 'created_at') {
            return $this->getCreatedAt($entry);
        }

        return isset($entry[$key]) ? ($limit ? str_limit($entry[$key]['value'], $limit) : $entry[$key]['value']) : '';
    }

    /**
     * @param $entries
     * @return mixed
     */
    private function getId($entries)
    {
        return $entries->pluck('batch')->first();
    }

    /**
     * @param $entries
     * @return mixed
     */
    private function getCreatedAt($entries)
    {
        return ($entry = $entries->pluck('created_at')->first()) ? $entry->format('d.m.Y H:i') : '';
    }
}
