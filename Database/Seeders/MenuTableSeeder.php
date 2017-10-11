<?php

namespace Modules\Form\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\Menu;

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
                    'value'           => 'admin::form.index',
                    'active_resolver' => 'admin::form.*',
                    'module'          => 'Form',
                    'parameters'      => json_encode([])
                ]
            ]
        ];

        foreach ($menus as $name => $items) {
            $menu = Menu::firstOrCreate([
                'name' => $name
            ]);

            foreach ($items as $item) {
                $i = $menu->items()->firstOrCreate($item);
                $i->is_active = 1;
                $i->save();
            }
        }
    }
}
