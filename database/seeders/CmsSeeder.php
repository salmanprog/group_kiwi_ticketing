<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addCmsRoles();
        $this->addCmsUser();
        $this->addCmsModules();
        $this->addApplicationSettings();
        $this->cms_widget();
        $this->superAdminPermission();
        $this->companyPermission();
        $this->managerPermission();
        $this->salesmanPermission();
        $this->clientPermission();
        // $this->organizationType();
        $this->organizationEventType();
    }

    public function organizationType()
    {
        $organizationTypes = [
            'Church Cristian',
            'Church Other',
            'Company',
            'Individual',
            'Non Profit',
            'Other',
            'Elementary School',
            'High School',
            'Middle School',
            'School Other',
            'School Private',
            'Vendor',
            'Youth Girl Scout',
            'Youth Other',
            'Youth Recreation',
            'Youth Sports',
        ];

        foreach ($organizationTypes as $type) {
            \DB::table('organization_type')->insert([
                'name' => $type,
                'company_id' => 1,
                'slug' => Str::slug($type),
            ]);
        }
    }

    public function organizationEventType()
    {
        $organizationTypes = [
            'Birhtday',
            'Cash Group',
            'Cash Group + Box Lunch',
            'Consigment',
            'Special Event',
            'Picnic',
            'Other',
            'Sponsor',
        ];

        foreach ($organizationTypes as $type) {
            \DB::table('organization_event_type')->insert([
                'name' => $type,
                'company_id' => 1,
                'slug' => Str::slug($type),
            ]);
        }
    }

    public function addCmsRoles()
    {
        \DB::table('user_groups')->insert([
            [
                'title' => 'Super Admin',
                'slug' => Str::slug('Super Admin'),
                'type' => 'admin',
                'is_super_admin' => '0',
                'created_at' => Carbon::now()
            ],
            [
                'title' => 'Company',
                'slug' => 'company',
                'type' => 'user',
                'is_super_admin' => '0',
                'created_at' => Carbon::now()
            ],
            [
                'title' => 'Manager',
                'slug' => 'manager',
                'type' => 'user',
                'is_super_admin' => '0',
                'created_at' => Carbon::now()
            ],
            [
                'title' => 'salesman',
                'slug' => 'salesman',
                'type' => 'user',
                'is_super_admin' => '0',
                'created_at' => Carbon::now()
            ],
            [
                'title' => 'Client',
                'slug' => 'client',
                'type' => 'user',
                'is_super_admin' => '0',
                'created_at' => Carbon::now()
            ]
        ]);
    }

    public function addCmsUser()
    {
        $role = \DB::table('user_groups')->where('title', 'Super Admin')->first();
        \DB::table('users')->insert([
            'user_group_id' => $role->id,
            'user_type' => 'admin',
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'slug' => 'superadmin',
            'email' => 'admin@kiwi.com',
            'mobile_no' => '1-8882051816',
            'password' => Hash::make('Admin@123$'),
            'platform_type' => 'custom',
            'is_email_verify' => '1',
            'email_verify_at' => Carbon::now(),
            'is_mobile_verify' => '1',
            'mobile_verify_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);
    }

    public function addCmsModules()
    {
        $data = [
            [
                'parent_id' => 0,
                'name' => 'Company',
                'slug' => 'company-management',
                'route_name' => 'company-management.index',
                'icon' => 'fa fa-users',
                'status' => '1',
                'sort_order' => 2,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 0,
                'name' => 'Application Setting',
                'slug' => 'application-setting',
                'route_name' => 'admin.application-setting',
                'icon' => 'fa fa-cog',
                'status' => '1',
                'sort_order' => 2,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 0,
                'name' => 'Manager',
                'slug' => 'manager-management',
                'route_name' => 'manager-management.index',
                'icon' => 'fa fa-users',
                'status' => '1',
                'sort_order' => 3,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 0,
                'name' => 'Salesman',
                'slug' => 'salesman-management',
                'route_name' => 'salesman-management.index',
                'icon' => 'fa fa-users',
                'status' => '1',
                'sort_order' => 4,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 6,
                'name' => 'Client',
                'route_name' => 'client-management.index',
                'slug' => 'client-management',
                'icon' => 'fa fa-users',
                'status' => '1',
                'sort_order' => 5,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 6,
                'name' => 'Account',
                'route_name' => 'organization.index',
                'slug' => 'organization',
                'icon' => 'fa fa-sitemap',
                'status' => '1',
                'sort_order' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 6,
                'name' => 'Account Type',
                'route_name' => 'organization-type.index',
                'slug' => 'organization-type',
                'icon' => 'fa fa-sitemap',
                'status' => '1',
                'sort_order' => 6,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 6,
                'name' => 'Event Type',
                'route_name' => 'event-type.index',
                'slug' => 'event-type',
                'icon' => 'fa fa-sitemap',
                'status' => '1',
                'sort_order' => 6,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 10,
                'name' => 'Product Category',
                'route_name' => 'product-category.index',
                'slug' => 'product-category',
                'icon' => 'fa fa-question-circle-o',
                'status' => '1',
                'sort_order' => 7,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 10,
                'name' => 'Product',
                'route_name' => 'product.index',
                'slug' => 'product',
                'icon' => 'fab fa-product-hunt',
                'status' => '1',
                'sort_order' => 6,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 0,
                'name' => 'Estimate',
                'route_name' => 'estimate.index',
                'slug' => 'estimate',
                'icon' => 'fa fa-file-text',
                'status' => '1',
                'sort_order' => 6,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 0,
                'name' => 'Invoice',
                'route_name' => 'invoice.index',
                'slug' => 'invoice',
                'icon' => 'fa fa-dollar',
                'status' => '1',
                'sort_order' => 6,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 0,
                'name' => 'Contract',
                'route_name' => 'contract.index',
                'slug' => 'contract',
                'icon' => 'fa fa-file-text-o',
                'status' => '1',
                'sort_order' => 6,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 0,
                'name' => 'Crm ',
                'route_name' => 'crm.index',
                'slug' => 'crm',
                'icon' => 'fa fa-cog',
                'status' => '1',
                'sort_order' => 6,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 0,
                'name' => 'Crm Settings',
                'route_name' => 'crm-settings.index',
                'slug' => 'crm-settings',
                'icon' => 'fa fa-cog',
                'status' => '1',
                'sort_order' => 6,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 0,
                'name' => 'Sales Team',
                'route_name' => 'sales-team.index',
                'slug' => 'sales-team',
                'icon' => 'fa fa-cog',
                'status' => '1',
                'sort_order' => 6,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 0,
                'name' => 'Opportunites',
                'route_name' => 'opportunites.index',
                'slug' => 'opportunites',
                'icon' => 'fa fa-cog',
                'status' => '1',
                'sort_order' => 6,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 0,
                'name' => 'Opportunites Setting',
                'route_name' => 'opportunites-setting.index',
                'slug' => 'opportunites-setting',
                'icon' => 'fa fa-cog',
                'status' => '1',
                'sort_order' => 6,
                'created_at' => Carbon::now()
            ],
            [
                'parent_id' => 0,
                'name' => 'Reporting',
                'route_name' => 'reporting.index',
                'slug' => 'reporting',
                'icon' => 'fa fa-cog',
                'status' => '1',
                'sort_order' => 6,
                'created_at' => Carbon::now()
            ],
        ];



        \DB::table('cms_modules')->insert($data);
    }


    public function addApplicationSettings()
    {
        $data = [
            [
                'identifier' => 'application_setting',
                'meta_key' => "favicon",
                'value' => uploadMediaByPath('app_setting', public_path('images/favicon.png')),
                'is_file' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'identifier' => 'application_setting',
                'meta_key' => "logo",
                'value' => uploadMediaByPath('app_setting', public_path('images/logo.jpg')),
                'is_file' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'identifier' => 'application_setting',
                'meta_key' => "application_name",
                'value' => 'Kiwi CRM',
                'is_file' => 0,
                'created_at' => Carbon::now()
            ]
        ];
        \DB::table('application_setting')->insert($data);
    }

    public function cms_widget()
    {
        \DB::table('cms_widgets')
            ->insert([
                [
                    'title' => 'Total Manager',
                    'slug' => time() . uniqid(),
                    'description' => NULL,
                    'icon' => 'icon-user',
                    'color' => '#4b71fa',
                    'div_column_class' => 'col-md-3',
                    'link' => '/admin/manager-management',
                    'widget_type' => 'small_box',
                    'sql' => 'Select count(*) from users limit 1',
                    'config' => NULL,
                ],
                [
                    'title' => 'Total Company',
                    'slug' => time() . uniqid(),
                    'description' => NULL,
                    'icon' => 'icon-people',
                    'color' => '#7bcb4d',
                    'div_column_class' => 'col-md-3',
                    'link' => '/admin/app-users',
                    'widget_type' => 'small_box',
                    'sql' => 'Select count(*) from users limit 1',
                    'config' => NULL,
                ],
                [
                    'title' => 'Total User',
                    'slug' => time() . uniqid(),
                    'description' => NULL,
                    'icon' => 'icon-user',
                    'color' => '#4b71fa',
                    'div_column_class' => 'col-md-3',
                    'link' => '/admin/app-users',
                    'widget_type' => 'small_box',
                    'sql' => 'Select count(*) from users limit 1',
                    'config' => NULL,
                ],
                [
                    'title' => 'Total Roles',
                    'slug' => time() . uniqid(),
                    'description' => NULL,
                    'icon' => 'icon-people',
                    'color' => '#7bcb4d ',
                    'div_column_class' => 'col-md-3',
                    'link' => '/admin/app-users',
                    'widget_type' => 'small_box',
                    'sql' => 'Select count(*) from users limit 1',
                    'config' => NULL,
                ],
                [
                    'title' => 'Users',
                    'slug' => time() . uniqid(),
                    'description' => NULL,
                    'icon' => 'icon-user',
                    'color' => '#fff',
                    'div_column_class' => 'col-md-12',
                    'link' => '/admin/app-users',
                    'widget_type' => 'line_chart',
                    'sql' => 'SELECT count(id) AS value, MONTHNAME(created_at) as label FROM users where YEAR(created_at) = YEAR(now()) group by MONTH(created_at) order by MONTH(created_at) asc',
                    'config' => NULL,
                ]
            ]);

    }


    public function superAdminPermission()
    {
        $superAdminRoleId = \DB::table('user_groups')->where('slug', 'super-admin')->value('id');

        $modules = [
            'company-management' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '0',
            ],
            'application-setting' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '0',
            ],
            'manager-management' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'salesman-management' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'client-management' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'organization' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'organization-type' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'event-type' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'product' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'product-category' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'estimate' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'invoice' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'contract' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'crm' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'crm-settings' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'sales-team' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'opportunites' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'opportunites-setting' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'reporting' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ]
        ];

        foreach ($modules as $slug => $permission) {
            $moduleId = \DB::table('cms_modules')->where('slug', $slug)->value('id');

            $modulePermission = [
                'user_id' => 1,
                'user_group_id' => $superAdminRoleId,
                'cms_module_id' => $moduleId,
                'is_add' => $permission['is_add'],
                'is_view' => $permission['is_view'],
                'is_update' => $permission['is_update'],
                'is_delete' => $permission['is_delete'],
                'created_at' => Carbon::now(),
            ];

            \DB::table('cms_module_permissions')->insert($modulePermission);
        }
    }



    public function companyPermission()
    {
        $companyRoleId = \DB::table('user_groups')->where('slug', 'company')->value('id');

        $modules = [
            'company-management' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'application-setting' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'manager-management' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'salesman-management' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'client-management' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '0',
            ],
            'organization' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'organization-type' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'event-type' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'product' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'product-category' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'estimate' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'invoice' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'contract' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'crm' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'crm-settings' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'sales-team' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'opportunites' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'opportunites-setting' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'reporting' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ]
        ];

        foreach ($modules as $slug => $permission) {
            $moduleId = \DB::table('cms_modules')->where('slug', $slug)->value('id');

            $modulePermission = [
                'user_id' => 1,
                'user_group_id' => $companyRoleId,
                'cms_module_id' => $moduleId,
                'is_add' => $permission['is_add'],
                'is_view' => $permission['is_view'],
                'is_update' => $permission['is_update'],
                'is_delete' => $permission['is_delete'],
                'created_at' => Carbon::now(),
            ];

            \DB::table('cms_module_permissions')->insert($modulePermission);
        }

    }

    public function managerPermission()
    {
        $managerRoleId = \DB::table('user_groups')->where('slug', 'manager')->value('id');

        $modules = [
            'company-management' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'application-setting' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'manager-management' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'salesman-management' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '0',
            ],
            'client-management' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '0',
            ],
            'organization' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'organization-type' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'event-type' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'product' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'product-category' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'estimate' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'invoice' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'contract' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'crm' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'crm-settings' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'sales-team' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'opportunites' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'opportunites-setting' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'reporting' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ]

        ];

        foreach ($modules as $slug => $permission) {
            $moduleId = \DB::table('cms_modules')->where('slug', $slug)->value('id');

            $modulePermission = [
                'user_id' => 1,
                'user_group_id' => $managerRoleId,
                'cms_module_id' => $moduleId,
                'is_add' => $permission['is_add'],
                'is_view' => $permission['is_view'],
                'is_update' => $permission['is_update'],
                'is_delete' => $permission['is_delete'],
                'created_at' => Carbon::now(),
            ];

            \DB::table('cms_module_permissions')->insert($modulePermission);
        }

    }

    public function salesmanPermission()
    {
        $modules = [
            'company-management' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'application-setting' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'manager-management' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'salesman-management' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'client-management' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '0',
            ],
            'organization' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'organization-type' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'event-type' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'product' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'product-category' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'estimate' => [
                'is_add' => '1',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '1',
            ],
            'invoice' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'contract' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'crm' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'crm-settings' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'sales-team' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'opportunites' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'opportunites-setting' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'reporting' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ]
        ];

        foreach ($modules as $slug => $permission) {
            $moduleId = \DB::table('cms_modules')->where('slug', $slug)->value('id');

            $modulePermission = [
                'user_id' => 1,
                'user_group_id' => \DB::table('user_groups')->where('slug', 'salesman')->value('id'),
                'cms_module_id' => $moduleId,
                'is_add' => $permission['is_add'],
                'is_view' => $permission['is_view'],
                'is_update' => $permission['is_update'],
                'is_delete' => $permission['is_delete'],
                'created_at' => Carbon::now(),
            ];

            \DB::table('cms_module_permissions')->insert($modulePermission);
        }

    }

    public function clientPermission()
    {
        $clientRoleId = \DB::table('user_groups')->where('slug', 'client')->value('id');

        $modules = [
            'company-management' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'application-setting' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'manager-management' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'salesman-management' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'client-management' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'organization' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'organization-type' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'event-type' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'product' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'product-category' => [
                'is_add' => '0',
                'is_view' => '0',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'estimate' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '1',
                'is_delete' => '0',
            ],
            'invoice' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'contract' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'crm' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'crm-settings' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'sales-team' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'opportunites' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'opportunites-setting' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ],
            'reporting' => [
                'is_add' => '0',
                'is_view' => '1',
                'is_update' => '0',
                'is_delete' => '0',
            ]
        ];

        foreach ($modules as $slug => $permission) {
            $moduleId = \DB::table('cms_modules')->where('slug', $slug)->value('id');

            $modulePermission = [
                'user_id' => 1,
                'user_group_id' => $clientRoleId,
                'cms_module_id' => $moduleId,
                'is_add' => $permission['is_add'],
                'is_view' => $permission['is_view'],
                'is_update' => $permission['is_update'],
                'is_delete' => $permission['is_delete'],
                'created_at' => Carbon::now(),
            ];

            \DB::table('cms_module_permissions')->insert($modulePermission);
        }

    }
}
