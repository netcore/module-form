<?php

namespace Modules\Form\Traits;

use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Writers\CellWriter;
use Modules\Form\Models\Form;

trait ExportTrait
{
    /**
     * @param string $form
     * @param string $type
     * @return mixed
     */
    public function export($form = 'all', $type = 'xlsx')
    {
        if ($form == 'all') {
            $forms = Form::all();
        } else {
            $forms = Form::find($form);
        }

        if (! $forms) {
            return back()->withErrors('Form not found!');
        }

        if (! in_array($type, ['xlsx', 'csv'])) {
            $type = 'xlsx';
        }

        $name = $form == 'all' ? 'All forms' : 'Form - ' . $forms->name;
        $name .= '-' . Carbon::now()->format('d.m.Y-H:i');

        return Excel::create($name, function ($excel) use ($form, $forms) {
            $excel->sheet('Forms', function ($sheet) use ($form, $forms) {

                if ($form == 'all') {
                    $row = 2;
                    foreach ($forms as $key => $form) {
                        $entries = $form->entries()->count();
                        $row = $key == 0 ? 1 : $row + $entries;

                        if ($key == 0 || $row == ($row + $entries) - 1) {
                            $sheet->row($row, $form->entries()->getColumns());
                            $sheet->row($row, function (CellWriter $row) {
                                $row->setFontWeight(true);
                                $row->setBackground('#cccccc');
                            });
                        }

                        foreach ($form->entries()->all() as $key => $entry) {
                            $row++;
                            $sheet->row($row, $entry);
                        }
                    }
                } else {
                    $row = 2;

                    $sheet->row(1, $forms->entries()->getColumns());
                    $sheet->row(1, function (CellWriter $row) {
                        $row->setFontWeight(true);
                        $row->setBackground('#cccccc');
                    });

                    foreach ($forms->entries()->all() as $key => $entry) {
                        $sheet->row($row, $entry);
                        $row++;
                    }
                }
            });

        })->download($type);
    }
}
