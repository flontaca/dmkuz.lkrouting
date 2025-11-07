<?
/**
 * @global CMain $APPLICATION
 */
global $APPLICATION;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

use Bitrix\Main\HttpApplication;
use Bitrix\Main\Config\Option;

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = $request->get('mid');

$MODULE_RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($MODULE_RIGHT < 'R') {
    $APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));
}
Loader::includeModule($module_id);

$aTabs = [
    [
        'DIV' => 'settings_tab',
        'TAB' => Loc::getMessage('SETTINGS_TAB'),
        'TITLE' => Loc::getMessage('SETTINGS_TITLE'),
        'OPTIONS' => [
            'test',
            [
                'option_name',
                'поясняющий текст',
                '',
                ['text', 50]
            ],
        ],
    ],
    [
        'DIV' => 'rights_tab',
        'TAB' => Loc::getMessage('RIGHTS_TAB'),
        'TITLE' => Loc::getMessage('RIGHTS_TITLE'),
    ],
];

// Визуальный вывод
$tabControl = new CAdminTabControl('tabControl', $aTabs);
$tabControl->Begin();
?>
    <form method='post' action='' name='dmkuz_lkrouting_module_settings'>
        <?= bitrix_sessid_post(); ?>
        <? foreach ($aTabs as $aTab) {
            if ($aTab["OPTIONS"]) {
                // завершает предыдущую закладку, если она есть, начинает следующую
                $tabControl->BeginNextTab();
                // отрисовываем форму из массива
                __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
            }
        }

        $tabControl->BeginNextTab();
        require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/admin/group_rights.php');
        $tabControl->EndTab();

        $tabControl->Buttons(); ?>
        <input type="submit" name="save" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>">
        <? $tabControl->End(); ?>
    </form>
<?

// Сохранение
if ($request->isPost() && $request['save'] && check_bitrix_sessid() && $MODULE_RIGHT == 'W') {
    foreach ($aTabs as $aTab) {
        foreach ($aTab['OPTIONS'] as $arOption) {
            __AdmSettingsSaveOptions($module_id, $aTab['OPTIONS']);
        }
    }

    LocalRedirect($request->getRequestUri());
}
?>