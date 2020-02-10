<?php

/** @var \yii\web\View $this */

use yii\helpers\Html;

?>
<h1>OAuth Module</h1>

Allow or deny access

<p></p>
<?=$scopeDesc?>

<p></p>
<p></p>
<?=Html::a('Allow', ['/oauth/default/authorize', 'result' => 'yes']);?>
<p></p>
<?=Html::a('Deny', ['/oauth/default/authorize', 'result' => 'no']);?>
<p></p>
