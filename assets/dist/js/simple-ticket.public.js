(function($) {

  $(document).ready(function(){

    $('.simple-ticket-link-form-toggle a').click(function(event){
      event.preventDefault();
      var target = $(this).attr('href');
      $(target).fadeIn(300);
    });

    $('#simple-ticket-form').submit(function(event) {
      var nameVal = $('input[name=simple-ticket-user-name]').val();
      var emailVal = $('input[name=simple-ticket-user-email]').val();
      var urlVal = $('input[name=simple-ticket-current-url]').val();
      var descVal = $('textarea[name=simple-ticket-description]').val();
      var imgVal = $('input[name=simple-ticket-screenshot]').val();

      var formData = {
        'name'  : nameVal,
        'email' : emailVal,
        'url'   : urlVal,
        'desc'  : descVal,
        'img'   : imgVal
      };


      console.log(formData);

      // stop the form from submitting the normal way and refreshing the page
      event.preventDefault();
    });
  });

})(jQuery);