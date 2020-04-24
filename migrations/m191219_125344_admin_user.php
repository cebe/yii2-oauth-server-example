<?php

use yii\db\Migration;

/**
 * Class m191219_125344_admin_user
 */
class m191219_125344_admin_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // create a role named "administrator"
        $administratorRole = $auth->createRole('administrator');
        $administratorRole->description = 'Administrator';
        $auth->add($administratorRole);

        // create permission for certain tasks
        $permission = $auth->createPermission('user-management');
        $permission->description = 'User Management';
        $auth->add($permission);

        // let administrators do user management
        $auth->addChild($administratorRole, $auth->getPermission('user-management'));

        // create user "admin" with password "verysecret"
        $user = new \Da\User\Model\User([
            'scenario' => 'create',
            'email' => "email@example.com",
            'username' => "admin",
            'password' => "verysecret"  // >6 characters!
        ]);
        $user->confirmed_at = time();
        $user->save();

        // assign role to our admin-user
        $auth->assign($administratorRole, $user->id);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

         // delete permission
         $auth->remove($auth->getPermission('user-management'));

         // delete admin-user and administrator role
         $administratorRole = $auth->getRole("administrator");
         $user = \Da\User\Model\User::findOne(['name'=>"admin"]);
         $auth->revoke($administratorRole, $user->id);
         $user->delete();
    }
}
