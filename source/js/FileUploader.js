import Core from '@uppy/core'
import FileInput from '@uppy/file-input'
import ProgressBar from '@uppy/progress-bar'
import XHRUpload from '@uppy/xhr-upload'

class FileUploader {

  vars = {
    selector: '',
    selector_progress: '',
    upload_url: '',
    update_url: '',
    delete_url: '',
    list_url: '',
    model_classname: '',
    model_id: '',
    label: {
      chooseFiles: 'Scegli File'
    }
  }

  constructor(options){
    const self = this

    $.extend(self.vars , options)
    let uppy = new Core({ debug: false, autoProceed: true, allowMultipleUploadBatches: true })

    uppy.use(FileInput, {
      target: '.'+self.vars.selector,
      replaceTargetContent:true,
      locale: {
        strings: {
          chooseFiles: self.vars.label.chooseFiles
        }
      }
    })

    uppy.use(ProgressBar, {
      id: self.vars.selector_progress,
      target: '.'+self.vars.selector_progress,
      hideAfterFinish: true,
    })

    uppy.use(XHRUpload, {
      endpoint: self.vars.upload_url,
      formData: true,
      fieldName: 'files',
      limit: 1,
    })

    uppy.setMeta({
      _csrf: yii.getCsrfToken(),
      model_classname: self.vars.model_classname,
      model_id: self.vars.model_id,
      method: 'put'
    })

    uppy.on('upload-progress', (file, response) => {
      $('.new-upload-container .uppy-ProgressBar-inner').show()
    })

    uppy.on('upload-success', (file, response) => {
      self.loadList()
      $('.uppy-ProgressBar-inner').css('width',0)
      //$('.uppy-ProgressBar-percentage').html(0)
    })

    $('.deleteFile').on('click',function(){
      var id = $(this).data.id
      var data = {
        _csrf: yii.getCsrfToken(),
        model_classname: self.vars.model_classname,
        model_id: self.vars.model_id,
        file_id: id,
        method: 'rm'
      }
      $.post(self.vars.delete_url, data).done(function(data) {
        self.loadList()
      })
    })

    self.loadList()
  }

  loadList = function(){
    const self = this

    $('#fileContainer').html('')
    $('.file-loader').show()

    var data = {
      _csrf: yii.getCsrfToken(),
      model_classname: self.vars.model_classname,
      model_id: self.vars.model_id,
      method: 'list'
    }

    $.post(self.vars.list_url, data).done(function(data) {
      $('.file-loader').hide()

      $('#fileContainer').html(data.html)
      $('#fileContainer > div.fileElement').each(function () {
        self.initRow($(this))
      })
    })

  }

  initRow = function(row){
    const self = this

    var id = $(row)[0].dataset.id

    let _uppy = new Core({ debug: false, autoProceed: true, allowMultipleUploadBatches: true })

    const fileInput = document.querySelector('#reuploader'+id)

    $('.reuploader'+id).on('click',function(){
      fileInput.click()
    })

    fileInput.addEventListener('change', (event) => {
      const files = Array.from(event.target.files)

      $('#fileProgressBar'+id).find('.uppy-ProgressBar-inner').show()

      files.forEach((file) => {
        try {
          _uppy.addFile({
            source: 'file input',
            name: file.name,
            type: file.type,
            data: file,
          })
          _uppy.on('upload-success', (file, response) => {
            self.loadList()
          })
        } catch (err) {
          if (err.isRestriction) {
            // handle restrictions
            console.log('Restriction error:', err)
          } else {
            // handle other errors
            console.error(err)
          }
        }
      })
    })

    // it’s probably a good idea to clear the `<input>`
    // after the upload or when the file was removed
    // (see https://github.com/transloadit/uppy/issues/2640#issuecomment-731034781)
    _uppy.on('file-removed', () => {
      fileInput.value = null
    })

    _uppy.on('complete', () => {
      fileInput.value = null
    })

    _uppy.use(ProgressBar, {
      target: '#fileProgressBar'+id,
      hideAfterFinish: true,
      replaceTargetContent: true,
    })

    _uppy.use(XHRUpload, {
      endpoint: self.vars.upload_url,
      formData: true,
      fieldName: 'files',
      limit: 1,
    })

    _uppy.setMeta({
      _csrf: yii.getCsrfToken(),
      model_classname: self.vars.model_classname,
      model_id: self.vars.model_id,
      file_id: id,
      method: 'replace'
    })
  }

  deleteFile = function(id){
    const self = this

    var data = {
      _csrf: yii.getCsrfToken(),
      model_classname: self.vars.model_classname,
      model_id: self.vars.model_id,
      file_id: id,
      method: 'rm'
    }
    $.post(self.vars.delete_url, data).done(function(data) {
      self.loadList()
    })
  }

  updateFile = function(id, value, field){
    const self = this

    var data = {
      _csrf: yii.getCsrfToken(),
      file_id: id,
      field: field,
      value: value,
      method: 'update'
    }
    $.post(self.vars.update_url, data).done(function(data) {
      self.loadList()
    })
  }

}

global.FileUploader = FileUploader