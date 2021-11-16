<?php
use yii\widgets\ListView;
?>

<?php foreach($files as $file) {
  $archiveds = $file->getArchived();  
?>

<div class="fileElement" data-id="<?=$file->id?>">
  <div class="fileRow" data-id="<?=$file->id?>">

    <div class="fileTextwrapper">
      <div class="fileIconWrapper">
        <div class="fileIcon"><?= file_get_contents(Yii::getAlias('@file') . '/assets/images/file-alt-regular.svg') ?></div>
      </div>
      <div class="fileText">
        <div class="fileTitle">
          <div id="labelTextFile<?=$file->id?>" class="labelFileTitle" onclick="editTextFile(<?=$file->id?>)">
            <span id="labelFileTitle<?=$file->id?>"><?= $file->title ?></span>
            <div class="fileIcon"><?= file_get_contents(Yii::getAlias('@file') . '/assets/images/edit-regular.svg') ?></div>
          </div>
          <div id="editTextFile<?=$file->id?>" class="editFileTitle" style="display:none;">
            <input id="textFile<?=$file->id?>" class="inputTitle" type="text" value="<?=$file->title?>" data-id="<?=$file->id?>"/>
            <div class="editFileTitleIcon" onclick="closeTextFile(<?=$file->id?>)"><?= file_get_contents(Yii::getAlias('@file') . '/assets/images/times-solid.svg') ?></div>
            <div class="editFileTitleIcon" onclick="updateTextFile(<?=$file->id?>)"><?= file_get_contents(Yii::getAlias('@file') . '/assets/images/check-solid.svg') ?></div>
          </div>
        </div>
        <div class="fileOriginalName"><?= $file->original_filename ?></div>
        <div id="fileProgressBar<?=$file->id?>" class="fpbar fileProgressBar<?=$file->id?>"><?= $file->original_filename ?></div>
      </div>
    </div>
    <div class="fileExpireWrapper">
      <div id="labelFileExpire<?=$file->id?>" class="labelFileExpire" onclick="editExpireFile(<?=$file->id?>)">
          <span id="spanFileExpire<?=$file->id?>">
            <?php
              if($file->expired_date !== null &&  $file->expired_date !== ''){
                echo \Yii::$app->formatter->asDate($file->expired_date, 'dd/MM/yyyy');
              }else{
                echo "Nessuna Scadenza";
              }
            ?>
          </span><!--<div class="labelFileTitleIcon edit"></div>-->
      </div>
      <div id="editFileExpire<?=$file->id?>" class="editFileExpire" style="display:none;">
        <input id="expireFile<?=$file->id?>" class="inputExpire" type="date" value="<?=$file->expired_date?>" data-id="<?=$file->id?>"/>
        <div class="resetFileTitleIcon" onclick="resetExpireFile(<?=$file->id?>)"><?= file_get_contents(Yii::getAlias('@file') . '/assets/images/times-solid.svg') ?></div>
        <div class="editFileTitleIcon" onclick="updateExpireFile(<?=$file->id?>)"><?= file_get_contents(Yii::getAlias('@file') . '/assets/images/check-solid.svg') ?></div>
      </div>
    </div>
    <div class="fileMenuWrapper">
      <div class="fileVersionWrapper">
        <div id="fileVersion<?=$file->id?>" class="fileVersion" title="Numero di versione">
          <?=$file->version?>
        </div>
      </div>
      <div class="fileMenuWrapper">
        <a title="Visualizza" class="actionButton" href="<?=$file->url?>" target="_blank"><?= file_get_contents(Yii::getAlias('@file') . '/assets/images/cloud-download.svg') ?></a>
        <div title="Sostituisci" class="actionButton reuploader<?=$file->id?>"><?= file_get_contents(Yii::getAlias('@file') . '/assets/images/cloud-replace.svg') ?><input type="file" id="reuploader<?=$file->id?>" style="display:none;"></div>
        <div title="Elimina" class="actionButton" onclick="window.fUploader.deleteFile(<?= $file->id ?>)"><?= file_get_contents(Yii::getAlias('@file') . '/assets/images/trash-alt-regular.svg') ?></div>
      </div>

      <div class="container-file-expand pointer <?= empty($archiveds) ? 'disabled' : '' ?>" onclick="toggleArchived(this)">
        <div class="caret-archived opened" title="Visualizza archiviati"><?= file_get_contents(Yii::getAlias('@file') . '/assets/images/caret-down-solid.svg') ?></div>
        <div class="caret-archived closed" title="Visualizza archiviati" style="display: none;"><?= file_get_contents(Yii::getAlias('@file') . '/assets/images/caret-up-solid.svg') ?></div>
      </div>
    </div>

  </div>

  <div clas="container-archived">
  <?php foreach($archiveds as $archived) { ?>
    <div class="fileRow archived" style="display: none;">
      <div class="fileTextwrapper">
        <div class="fileIconWrapper">
          <div class="fileIcon"><?= file_get_contents(Yii::getAlias('@file') . '/assets/images/file-alt-regular.svg') ?></div>
        </div>
        <div class="fileText">
          <div class="fileTitle">
            <div id="labelTextFile<?=$archived->id?>" class="labelFileTitle">
              <span id="labelFileTitle<?=$archived->id?>"><?= $archived->title ?></span>
            </div>
          </div>
          <div class="fileOriginalName"><?= $archived->original_filename ?></div>
        </div>
      </div>
      <div class="fileExpireWrapper">
        <div id="labelFileExpire<?=$archived->id?>" class="labelFileExpire">
            <span id="spanFileExpire<?=$archived->id?>">
              <?php
                if($archived->expired_date !== null &&  $archived->expired_date !== ''){
                  echo \Yii::$app->formatter->asDate($archived->expired_date, 'dd/MM/yyyy');
                }else{
                  echo "Nessuna Scadenza";
                }
              ?>
            </span>
        </div>
      </div>
      <div class="fileVersionWrapper">
        <div id="fileVersion<?=$archived->id?>" class="fileVersion" title="Numero di versione">
          <?=$archived->version?>
        </div>
      </div>
      <div class="fileMenuWrapper">
        <a title="Visualizza" class="actionButton" href="<?=$archived->url?>" target="_blank"><?= file_get_contents(Yii::getAlias('@file') . '/assets/images/cloud-download.svg') ?></a>
        <div title="Elimina" class="actionButton" onclick="window.fUploader.deleteFile(<?= $archived->id ?>)"><?= file_get_contents(Yii::getAlias('@file') . '/assets/images/trash-alt-regular.svg') ?></div>
      </div>

    </div>
  <?php } ?>
  </div>

</div>
<?php } ?>