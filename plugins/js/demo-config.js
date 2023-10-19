$(function(){
  var baseUrl = $('.navbar-brand').attr('href');
  $('#drag-and-drop-zone').dmUploader({
    url: baseUrl+'/back-end/api/media/create.php',
    maxFileSize: 2000000, // 2 Megs 
    onDragEnter: function(){
      this.addClass('active');
    },
    onDragLeave: function(){
      this.removeClass('active');
    },
    onNewFile: function(id, file){
      ui_multi_add_file(id, file);
    },
    onBeforeUpload: function(id){
      ui_multi_update_file_status(id, 'uploading', 'Uploading...');
      ui_multi_update_file_progress(id, 0, '', true);
    },
    onUploadCanceled: function(id) {
      ui_multi_update_file_status(id, 'warning', 'Canceled by User');
      ui_multi_update_file_progress(id, 0, 'warning', false);
    },
    onUploadProgress: function(id, percent){
      ui_multi_update_file_progress(id, percent);
    },
    onUploadSuccess: function(id, data){
      //ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
      //ui_add_log('Upload of file #' + id + ' COMPLETED', 'success');
      ui_multi_update_file_status(id, 'success', 'Upload Complete');
      ui_multi_update_file_progress(id, 100, 'success', false);
      setTimeout(function(){
         $('#files li').fadeOut("slow");
       }, 5000);
    },
    onUploadError: function(id, xhr, status, message){
      ui_multi_update_file_status(id, 'danger', message);
      ui_multi_update_file_progress(id, 0, 'danger', false);  
    },
    onFallbackMode: function(){
      // When the browser doesn't support this plugin :(
      ui_add_log('Plugin cant be used here, running Fallback callback', 'danger');
    },
    onFileSizeError: function(file){
      ui_add_log('File \'' + file.name + '\' cannot be added: size excess limit', 'danger');
      setTimeout(function(){
         $('#debug li').fadeOut("slow");
       }, 5000);
    }
  });
});