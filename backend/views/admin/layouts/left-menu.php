<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$controller = $this->context;
$menus = $controller->module->menus;

$route = $controller->route;

// Filter the menus to include only "user" and "assignment"
$allowedMenus = ['user', 'assignment'];

$filteredMenus = array_filter($menus, function ($key) use ($allowedMenus) {
    return in_array($key, $allowedMenus);
}, ARRAY_FILTER_USE_KEY);

// Set the active state based on the current route
foreach ($filteredMenus as $i => $menu) {
    $filteredMenus[$i]['active'] = strpos($route, trim($menu['url'][0], '/')) === 0;
}

$this->params['nav-items'] = $filteredMenus;
?>

<?php $this->beginContent($controller->module->mainLayout) ?>
<div class="row">
    <div class="col-sm-3">
        <div class="container-md my-3 p-4 rounded-3 bg-white shadow">
            <div id="manager-menu" class="list-group">
                <?php
                foreach ($filteredMenus as $menu) {
                    $label = Html::tag('i', '', ['class' => 'glyphicon glyphicon-chevron-right pull-right']) .
                        Html::tag('span', Html::encode($menu['label']), []);
                    $active = $menu['active'] ? ' active' : '';
                    echo Html::a($label, $menu['url'], [
                        'class' => 'list-group-item' . $active,
                    ]);
                }
                ?>
            </div>
        </div>
    </div>

    <div class="col-sm-9">
        <div class="container-md my-3 p-4 rounded-3 bg-white shadow">
        <?= $content ?>
        </div>
    </div>
</div>
<?php
list(, $url) = Yii::$app->assetManager->publish('@mdm/admin/assets');
$this->registerCssFile($url . '/list-item.css');
?>

<?php $this->endContent(); ?>
