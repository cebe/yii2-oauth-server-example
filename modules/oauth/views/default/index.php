<?php

/** @var \yii\web\View $this */
/** @var \app\modules\oauth\models\AccessToken $accessToken */

use yii\helpers\Html;

?>
<h1>OAuth Module</h1>

<?php if ($accessToken) { ?>
    <?= $accessToken->clientowner->name . ' - ' ?>
    <?= Html::a('Revoke', ['/oauth/default/revoke', 'clientId' => $accessToken->oauth_client_id]) ?>
<?php } ?>

