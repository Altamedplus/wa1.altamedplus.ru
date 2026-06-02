<style>
    .st-line {
        display: flex;
        flex-direction: row;
        padding-bottom: 50px;
    }

    .line-left-s {
        padding-left: 5px !important;
    }

    .st-line span {
        position: relative;
        margin: 0px;
        padding: 0px;
        padding: 15px 10px 19px 40px;
        width: 125px;
        text-align: center;
        font-weight: 600;
    }

    .st-line span::before {
        content: ' ';
        position: absolute;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        background: #757b92;
        left: calc(50% - 3px);
        bottom: -6px;
        z-index: 9999;
    }

    .line-s::after {
        content: ' ';
        position: absolute;
        width: 100%;
        height: 3px;
        background: #757b92;
        left: 58%;
        bottom: 0px;
    }

    .green-st-1,
    .green-st-2 {
        color: #1ebd71 !important;
    }

    .green-st-1::after {
        background: #757b92 !important;
        color: #757b92 !important;
    }

    .green-st-1::before {
        background: #1ebd71 !important;
        color: #1ebd71 !important;
    }

    .green-st-2::after,
    .green-st-2::before {
        background: #1ebd71 !important;
        color: #1ebd71 !important;
    }
</style>
<div class="content-text">
    <ul>
        <li>Заявление № <?= $nalog->id; ?> </li>
        <li>ФИО заявителя: <?= $name; ?></li>
    </ul>
    <h3>Готовность </h3>
    <div class="st-line">
        <span class="line-s line-left-s <?= $new ?>">Принято</span>
        <span class="line-s <?= $working; ?>">В работе</span>
        <span class=" <?= $ready; ?>">Готово к выдаче</span>
    </div>
    <? if (!$s_1 && $s_2) : ?>
        <p>Справки за 2024 и 2025 отправляются в Налоговую в электронном формате, приходить за ними не требуеться. Справки за 2023 год можно запросить в чате мобильного приложения "Альтамед+" Либо получить при визите в клинику.</p>
        <a href="https://www.altamedplus.ru/about/nalogovyy-vychet/kak-oformit-vychet-v-fns/">Инструкция как оформить декларацию в ФНС</a>
    <? else : ?>
        <p>Расчетная дата подготовки комплекта документов: <?= $endDate ?>. Ожидайте уведомления о готовности.</p>
    <? endif ?>
</div>