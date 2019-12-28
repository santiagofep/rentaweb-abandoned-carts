jQuery(document).ready(function($) {
  create_cart();
  $("#billing_email").focusout(function(event) {
    create_cart();
  });
  function create_cart() {
    var data = {
      email: $("#billing_email").val(),
      phone: $("#billing_phone").val(),
      action: "rentaweb_update_cart"
    };

    jQuery.post(rw_abandoned_obj.ajax_url, data, function(response) {
      console.log(response);
    });
  }
});
