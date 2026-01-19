<!DOCTYPE html>
<html lang="ru">
<?use APP\Form\Form;?>
<?include __DIR__ . '/head.php' ?>
<body>
    <? view('header');?>
    <div class="content">
        <div class="wrapper" >
             <div class="block_logo">
                <img src="<?=img('wa')?>">
            </div>
            <div class="block_form">
                <form name="forgot" csrf-token="<?=Form::csrf()?>">
                    <h3>Восcтановление пароля</h3>
                    <input ui="text-form" label="Логин" type="text" placeholder="Телефон"  name="login" data-error=""/>
                     <div class="flex-column">
                        <p>Если у вас нет аккаунта. Обратитесь в Тех. Поддержку</p>
                        <p>Новый сгенерированый пароль поступит в Sms<p>
                    </div>
                    <div class="flex-row">
                        <button type="submit" class="btn btn-submit-blue">Запросить пароль</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?view('footer');?>
</body>
</html>