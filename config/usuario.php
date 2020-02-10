<?php
/**
 *
 */

return [

    // https://yii2-usuario.readthedocs.io/en/latest/
    'class' => \Da\User\Module::class,
    // 'class' => app\modules\usuario\Module::class,
    'administrators' => ['admin'],
    'controllerNamespace' => 'app\modules\usuariomod\controllers'
    // ...other configs from here: https://yii2-usuario.readthedocs.io/en/latest/installation/configuration-options/
    // e.g.
    // 'generatePasswords' => true,
    // 'switchIdentitySessionKey' => 'myown_usuario_admin_user_key',
];
