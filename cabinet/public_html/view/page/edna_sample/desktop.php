<div class="block-header">
    <p><?= $header ?></p>
</div>
<div class="flex-row-center">
    <div class="oferflow">
    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Header</th>
            <th>Body</th>
            <th>Footer</th>
            <th>Buttons</th>
            <th>Create</th>
        </tr>
        </thead>
        <tbody>
    <?foreach ($sample as $i => $s) :?>
        <?$content  = $s['content']?>
        <tr>
          <td><?=$s['name']?></td>
          <td><?=$s['type']?></td>
          <td>
           <div class="flex-column">
            <p data-header-type="<?=$content['header']['headerType']?>" class="m-0">Тип: <?=$content['header']['headerType']?></p>
            <p data-header-text="<?=$content['header']['text'] ?: $content['header']['headerExampleMediaUrl']?>" class="m-0">Содержимое: <?=($content['header']['text'] ?:
             "<img src='" . $content['header']['headerExampleMediaUrl'] . "'></img>") ?>
            </p>
          </div>
        </td>
        <td data-text="<?=$content['text']?>"><div class="text p-5"><?=$content['text']?></div></td>
        <td data-footer ><?=$content['footer']['text']?></td>
        <td>
            <div class="flex-colum">
                <?foreach($content['keyboard']['rows'][0]['buttons'] as $b):?>
                    <p class="m-0" data-text="<?=$b['text']?>">Text: <?=$b['text']?></p>
                    <p class="m-0" data-type="<?=$b['buttonType']?>">Type: <?=$b['buttonType']?></p>
                    <p class="m-0" data-url="<?=$b['url']?>">Url: <?=$b['url']?></p>
                    <p class="m-0" data-phone="<?=$b['phone']?>">Phone: <?=$b['phone']?></p>
                    <p class="m-0" data-payload="<?=$b['payload]']?>">Payload: <?=$b['payload]']?></p>
                    <button class="btn-round btn-content-edit m-15" evt="create-btn"></button>
                <?endforeach?>
            </div>
        </td>
        <td><button class="btn-round btn-content-edit" evt="create"></button></td>
        </tr>
    <?endforeach;?>
        </tbody>
    </table>
    </div>
</div>