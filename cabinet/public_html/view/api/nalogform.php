<!DOCTYPE html>
<html lang="ru">
<?

use APP\Model\TaxpayerListModel; ?>
<? include __DIR__ . '/head.php' ?>

<body>
    <form class="form">
        <div class="row">
            <h4>ДАННЫЕ ПАЦИЕНТА</h4>
        </div>
         <div class="row">
            <span class="quote"> Пациент, даже несовершеннолетний должен именть ИНН, иначе ФНС не примет справку в работу</span>
         </div>
        <div class="row">
            <div class="form-block left">
                <label>Ф.И.О. пациента полностью <span>*</span></label>
                <input type="text" name="name" />
            </div>
            <div class="form-block">
                <label>Дата рождения пациента <span>*</span></label>
                <input type="date" name="date_birth">
            </div>
        </div>

        <div class="row">
            <div class="form-block left">
                <label>Укажите контактный телефон <span>*</span></label>
                <input type="text" name="phone" />
                <br/>
                <label>Ваш E-mail <span>*</span></label>
                <input type="text" name="email" />
            </div>
            <div class="form-block">
                <label>За какой год получить справку <span>*</span></label>
                <div class="radio-box">
                    <? foreach (
                        [
                            ['2022' => 'Бумажный формат'],
                            ['2023' => 'Бумажный формат'],
                            ['2024' => 'Отправить в ФНС']
                        ] as $data
                    ) : ?>
                        <label class="radio-year">
                            <p><?= $year = array_keys($data)[0] ?></p>
                            <input type="checkbox" name="<?= $year ?>"></input>
                            <p><?= $data[$year] ?></p>
                        </label>
                    <? endforeach ?>
                </div>
            </div>
        </div>
        <div class="row">
            <h4>ДАННЫЕ НАЛОГОПЛАТЕЛЬЩИКА</h4>
        </div>
        <div class="row">
            <div class="form-block left">
                <label>Степень родства <span>*</span></label>
                <select name="taxpayer">
                    <? foreach ((new TaxpayerListModel())->findM() as $list) : ?>
                        <option value="<?= $list->type_id ?>"><?= $list->name ?></option>
                    <? endforeach; ?>
                </select>
            </div>
            <div class="form-block ">
                    <label>Дата рождения плательщика <span>*</span></label>
                    <input type="date" name="date_birth">
            </div>
        </div>
        <div class="row">
            <div class="form-block left">
                <label>ФИО налогоплательщика <span>*</span></label>
                <input type="text" name="fio_nalog" />
            </div>
            <div class="form-block">
                <label>ИНН налогоплательщика <span>*</span></label>
                <input type="text" name="inn" />
            </div>
        </div>
    </form>
</body>

</html>