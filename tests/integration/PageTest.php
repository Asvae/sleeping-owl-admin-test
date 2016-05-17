<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Role;
use App\User;
use SleepingOwl\Admin\Model\ModelConfiguration;

class User2 extends User {

}

class PageTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var User
     */
    private $admin;

    public function setUp()
    {
        parent::setUp();

        $this->admin = User::create([
            'name'     => 'admin_test',
            'email'    => 'admin_test@site.com',
            'password' => 'password',
        ]);

        $roles = Role::whereIn('name', ['admin', 'manager'])->pluck('id')->toArray();
        $this->admin->roles()->attach($roles);
    }

    public function test_displays_title()
    {
        AdminSection::registerModel(User2::class,
            function (ModelConfiguration $model) {
                $model->setTitle('Test page')->setAlias('test-page');
            })->addMenuPage(User2::class);

        $this->actingAs($this->admin)->visit('admin/test-page')->see('Test page');
    }
}