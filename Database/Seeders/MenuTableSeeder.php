<?php

namespace Modules\Form\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\Menu;
use Netcore\Translator\Helpers\TransHelper;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $menus = [
            'leftAdminMenu' => [
                [
                    'name'            => 'Forms',
                    'icon'            => 'ion-ios-list-outline',
                    'type'            => 'route',
                    'is_active'       => 1,
                    'value'           => 'admin::form.index',
                    'active_resolver' => 'admin::form.*',
                    'module'          => 'Form',
                    'parameters'      => json_encode([])
                ]
            ]
        ];

        foreach ($menus as $key => $items) {
            $menu = Menu::firstOrCreate([
                'key' => $key
            ]);

            $translations = [];
            foreach (TransHelper::getAllLanguages() as $language) {
                $translations[$language->iso_code] = [
                    'name' => ucwords(preg_replace(array('/(?<=[^A-Z])([A-Z])/', '/(?<=[^0-9])([0-9])/'), ' $0', $key))
                ];
            }
            $menu->updateTranslations($translations);

            foreach ($items as $item) {
                $row = $menu->items()->firstOrCreate(array_except($item, ['name', 'value', 'parameters']));

                $translations = [];
                foreach (TransHelper::getAllLanguages() as $language) {
                    $translations[$language->iso_code] = [
                        'name'       => $item['name'],
                        'value'      => $item['value'],
                        'parameters' => $item['parameters']
                    ];
                }
                $row->updateTranslations($translations);
            }
        }
    }
}
