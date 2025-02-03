<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin.php";

use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use My\Currency\CurrencyRateTable;
use My\Currency\CurrencyImport;

Loader::includeModule("my.currency");

$APPLICATION->SetTitle("Список курсов валют");

// Выводим сообщение об обновлении
if (!empty($_REQUEST["msg"])) {
    CAdminMessage::ShowMessage(["MESSAGE" => $_REQUEST["msg"], "TYPE" => "OK"]);
}

// "Обновить курс"
if (isset($_REQUEST['update_currency']) && $_REQUEST['update_currency'] == 'Y') {
    \My\Currency\Agent::loadCurrencyData();
    LocalRedirect($APPLICATION->GetCurPage() . "?msg=Курс валют обновлен");
}

$sort = new CAdminSorting("SORT", "DATE", "desc");
$list = new CAdminList("currency_list", $sort);

// Фильтр
$filterFields = [
    ["id" => "CODE", "name" => "Код валюты", "type" => "string"],
    ["id" => "DATE", "name" => "Дата", "type" => "date"],
    ["id" => "COURSE", "name" => "Курс", "type" => "number"],
];

// Получаем значения фильтров из GET-запроса
$filterValues = [
    "CODE" => $_GET["CODE"] ?? "",
    "DATE" => $_GET["DATE"] ?? "",
    "COURSE" => $_GET["COURSE"] ?? "",
];

$filterCriteria = [];
if (!empty($filterValues["CODE"])) {
    $filterCriteria["=CODE"] = $filterValues["CODE"];
}
if (!empty($filterValues["DATE"])) {
    $filterCriteria[">=DATE"] = new DateTime($filterValues["DATE"] . " 00:00:00");
    $filterCriteria["<=DATE"] = new DateTime($filterValues["DATE"] . " 23:59:59");
}

if (!empty($filterValues["COURSE"])) {
    $filterCriteria[">=COURSE"] = $filterValues["COURSE"];
}

$result = CurrencyRateTable::getList([
    "order" => ["DATE" => "DESC"],
    "filter" => $filterCriteria,
]);

$list->AddHeaders([
    ["id" => "ID", "content" => "ID", "sort" => "ID", "default" => true],
    ["id" => "CODE", "content" => "Код валюты", "sort" => "CODE", "default" => true],
    ["id" => "COURSE", "content" => "Курс", "sort" => "COURSE", "default" => true, "align" => "right"],
    ["id" => "DATE", "content" => "Дата", "sort" => "DATE", "default" => true],
]);

while ($row = $result->fetch()) {
    $rowData = $list->AddRow($row["ID"], $row);
    $rowData->AddViewField("COURSE", number_format($row["COURSE"], 2, ".", " ") . " ₽");
}

$filter = new CAdminFilter("currency_filter", array_column($filterFields, "name"));

?>
<form name="filter_form" method="GET" action="<?= $APPLICATION->GetCurPage() ?>">
    <?php $filter->Begin(); ?>
    <tr>
        <td>Код валюты:</td>
        <td><input type="text" name="CODE" value="<?= htmlspecialcharsbx($filterValues["CODE"]) ?>"></td>
    </tr>
    <tr>
        <td>Дата:</td>
        <td><?= Calendar("DATE", $filterValues["DATE"]) ?></td>
    </tr>
    <tr>
        <td>Курс (больше или равен):</td>
        <td><input type="number" step="any" name="COURSE" value="<?= htmlspecialcharsbx($filterValues["COURSE"]) ?>"></td>
    </tr>
    <?php $filter->Buttons(["table_id" => "currency_list", "url" => $APPLICATION->GetCurPage(), "form" => "filter_form"]); ?>
    <?php $filter->End(); ?>
</form>

<!-- Кнопка для обновления курса валют -->
<form method="GET" action="<?= $APPLICATION->GetCurPage() ?>">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="update_currency" value="Y">
    <input type="submit" value="Обновить курс" class="adm-btn-save" />
</form>

<?php
$list->DisplayList();
require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php";
?>
