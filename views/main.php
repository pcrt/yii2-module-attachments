<?php

$rand = uniqid();

?>

<div class="UppyForm<?=$rand?>">
  <form action="<?= $upload_url ?>">
    <h5>Uppy was not loaded — slow connection, unsupported browser, weird JS error on a page — but the upload still works, because HTML is cool like that</h5>
    <input type="file" name="files" multiple="false">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
    <button type="submit">Fallback Form Upload</button>
  </form>
</div>
<div class="new-upload-container UppyProgressBar<?=$rand?>"></div>

<div id="fileContainer" class="fileContainer"></div>

<div class="file-loader"><img height="30" width="30" src="data:image/gif;base64,<?= base64_encode(file_get_contents(__DIR__ . "/../assets/images/index.gif")) ?>" /></div>

<?php
$script = <<<EOD
  $("document").ready(
    function(){
      window.fUploader = new FileUploader({
        selector: 'UppyForm$rand',
        selector_progress: 'UppyProgressBar$rand',
        model_classname : '$model_classname',
        model_id : '$model_id',
        upload_url : '$upload_url',
        update_url : '$update_url',
        list_url : '$list_url',
        delete_url : '$delete_url',
        label:{
          chooseFiles: 'Carica File'
        }
      });
    }
  );
EOD;
$this->registerJs($script);
?>


<script type="text/javascript">

  function editTextFile(id){
    $('#labelTextFile'+id).hide();
    $('#editTextFile'+id).show();
  }

  function updateTextFile(id){
    var value = $('#textFile'+id).val();
    var field = 'title';
    window.fUploader.updateFile(id, value, field).done(function(){
      $('#labelFileTitle'+id).html(value);
      closeTextFile(id)
    });
  }

  function closeTextFile(id){
    $('#labelTextFile'+id).show();
    $('#editTextFile'+id).hide();
  }

  function editExpireFile(id){
    $('#labelFileExpire'+id).hide();
    $('#editFileExpire'+id).show();
  }

  function updateExpireFile(id){
    var value = $('#expireFile'+id).val();
    var field = 'expired_date';
    window.fUploader.updateFile(id, value, field).done(function(){
      if(value == ""){
        value = 'Nessuna Scadenza';
        $('#spanFileExpire'+id).html(value);
      }else{
        var d = new Date(value);
        $('#spanFileExpire'+id).html(d.getDate() + '/' + (d.getMonth()+1) + '/' + d.getFullYear());
      }
      $('#labelFileExpire'+id).show();
      $('#editFileExpire'+id).hide();
    });
  }

  function resetExpireFile(id){
    var value = '';
    var field = 'expired_date';
    window.fUploader.updateFile(id, value, field).done(function(){
      if(value == ""){
        value = 'Nessuna Scadenza';
        $('#spanFileExpire'+id).html(value);
      }else{
        var d = new Date(value);
        $('#spanFileExpire'+id).html(d.getDate() + '/' + (d.getMonth()+1) + '/' + d.getFullYear());
      }
      $('#labelFileExpire'+id).show();
      $('#editFileExpire'+id).hide();
    });
  }

  function toggleArchived(el, parent) {
    $(el).find('.caret-archived.opened').toggle()
    $(el).find('.caret-archived.closed').toggle()

    $(el).closest('.fileElement').find('.fileRow.archived').each(function (index, element) {
      $(element).toggle()
    })
  }

</script>