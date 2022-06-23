<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);
Loader::includeModule($module_id);

$aTabs = array(
    array(
        "DIV" => "edit",
        "TAB" => Loc::getMessage("OPTIONS_TAB_NAME"),
        "TITLE" => Loc::getMessage("OPTIONS_TAB_NAME"),
        "OPTIONS" => array(
            array(
                "hide_news",
                Loc::getMessage("OPTIONS_TAB_HIDE_NEWS"),
                "N",
                array("checkbox")
            ),
            array(
                "phone",
                Loc::getMessage("OPTIONS_TAB_PHONE"),
                "",
                array("text", 50)
            ),
            array(
                "email",
                Loc::getMessage("OPTIONS_TAB_EMAIL"),
                "",
                array("text", 50)
            ),
            array(
                "SOCIAL_VK",
                Loc::getMessage("OPTIONS_TAB_SOCIAL_VK"),
                "",
                array("text", 50)
            ),
            array(
                "SOCIAL_OK",
                Loc::getMessage("OPTIONS_TAB_SOCIAL_OK"),
                "",
                array("text", 50)
            ),
        )
    )
);

if ($request->isPost() && check_bitrix_sessid()) {
    foreach ($aTabs as $aTab) {
        foreach ($aTab["OPTIONS"] as $arOption) {
            if (!is_array($arOption)) {
                continue;
            }
            if ($arOption["note"]) {
                continue;
            }
            if ($request["apply"]) {
                $optionValue = $request->getPost($arOption[0]);
                if ($arOption[0] == "hide_news") {
                    if ($optionValue == "") {
                        $optionValue = "N";
                    }
                }
                Option::set($module_id, $arOption[0],
                    is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
            } elseif ($request["default"]) {
                Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
    }
    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . $module_id . "&lang=" . LANG);
}

$tabControl = new CAdminTabControl(
    "tabControl",
    $aTabs
);

$tabControl->Begin();
?>

<form action="<? echo($APPLICATION->GetCurPage()); ?>?mid=<? echo($module_id); ?>&lang=<? echo(LANG); ?>" method="post">
    <?
    foreach ($aTabs as $aTab) {
        if ($aTab["OPTIONS"]) {
            $tabControl->BeginNextTab();
            __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
        }
    }
    $tabControl->Buttons();
    ?>
    <input type="submit" name="apply" value="<? echo(Loc::GetMessage("OPTIONS_INPUT_APPLY")); ?>" class="adm-btn-save"/>
    <input type="submit" name="default" value="<? echo(Loc::GetMessage("OPTIONS_INPUT_DEFAULT")); ?>"/>
    <? echo(bitrix_sessid_post()); ?>
</form>

<?php
$tabControl->End();