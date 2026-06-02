<style>
    .btn {
        display: inline-block;
        background-color: #007bff;
        color: #ffffff;
        font-size: 16px;
        font-weight: 500;
        font-family: inherit;
        text-align: center;
        text-decoration: none;
        padding: 12px 24px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        line-height: 1.5
    }

    .btn:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn:focus-visible {
        outline: 2px solid #007bff;
        outline-offset: 2px;
    }
</style>

<div class="header_div">
    <img width="50" src="https://www.altamedplus.ru/upload/main/f04/f04d7dab6f33560da79ec27c78d63fe1.png" height="36" bxmailattachcid="1241"> <span class="header">Заказ справки для<br>налогового вычета</span>
</div>

<p>
    Уважаемый пациент!
</p>

<p>
    Вы успешно подали заявление на оформление справки для налогового вычета. Вашему заявлению присвоен порядковый номер <strong>№ <?= $id ?></strong>.<br>
    Cправка будет изготовлена в течение 10 дней после подачи Заявления. После подготовки справки мы сообщим Вам о ее готовности.<br>
    <strong>Важно! Начиная с 2024 года справки формируются в электронном формате и отправляются в ФНС автоматически.
        За 2023 справку вы можете запросить через чат мобильного приложения Альтамед+ или забрать в клинике.</strong>
</p>
<p style="color:#0086c1;"><strong>Обращаем ваше внимание, что услуги подолога не являются медицинскими (Письмо ФНС №ГД-4-3.6145@ от 04.04.2014) и расходы на них не включаются в справку о налоговом вычете.</strong></p>
<p>
    <span class="form_data">
        Дата подачи заявления: <?= date('d.m.Y') ?><br>
        Контактный телефон: <?= $phone ?><br>
    </span>
</p>
<br>
<p>Проверить статус заявления: <a class="btn" href="<?= $statusUrl ?>" target="_blank"> Проверить статус заявления</p>

<p>Не отвечайте на это письмо, оно сгенерировано автоматически. По всем вопросам вы можете обратиться по адресу <a href="mailto:nalog@altamed-plus.ru">nalog@altamed-plus.ru</a> или по телефону: <a href="tel:+74952129003">+7 (495) 212-90-03</a>.</p>
<p>С уважением, сеть медицинских центров &laquo;Альтамед+&raquo;</p>