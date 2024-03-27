jQuery(document).ready(function () {

  if (jQuery('#acf-group_6306d0c92202e').hasClass('acf-postbox')) {
     // when click the add row
     jQuery('.acf-button').on('click', function () {
      displayImage();
      addRow();
    });
    addRow();
    function addRow(){
      setTimeout(() => {
        displayImage();
        // get the length of increment sidebanner class
        var total = jQuery('[class*="sidebanner-"]').length;
        // put each function to target the sidebanner classname increment
        jQuery('[class*="sidebanner-"]').each(function (index) {
          // get the last increment of side banner
          if (index === total - 1 - 1) {
            selectBanner();
          }
        });
      }, 500);
    }

    function selectBanner(){
      jQuery('#sideBanner select').on('change', function () {
        var getDefault = jQuery(this.options[this.selectedIndex]).prop('value');
        var getID = jQuery(this);
        // get the image url
        // get the parent of select tag
        var getSelectTag = jQuery(this).parents()[2];
        // target the message classname
        var getAcf_Class = getSelectTag.getElementsByClassName('acf-field-6323d6f659103');
        // get the child of message
        var getMessageClass = getAcf_Class[0].children;
        // get the increament sidebanner classname
        var getClassBanner = getMessageClass[0].className;
        var getImageUrl = jQuery(this.options[this.selectedIndex]).prop('innerHTML');
        setTimeout(() => {
          // display image selected
          if (getID[0].selectedOptions[0].selected == true) {
            // display image selected
            if(getDefault == ""){
              // if the value is empty
               jQuery("." + getClassBanner + "").html("<p></p>");
            }else{
                  // display image selected
               jQuery("." + getClassBanner + "").html("<img src="+getImageUrl+" width='300px' />");
            }
          }
        }, 1000);
      });
    }
    displayImage();
    function displayImage() {
      // click the select option and get the image url
      selectBanner();
      // make dynamic class side banner
      setTimeout(() => {
        var getClassName = document.getElementsByClassName('acf-field-6323d6f659103');
        //make increment class sidebanner
        for (var m = 0; m <= getClassName.length; m++) {
          jQuery(getClassName[m]).html('<div class= "sidebanner-' + m + '"></div>');
        }
      }, 500);

        // Display selected option when refresh or click the update
        setTimeout(() => {
          // make each function to get the selected side banner and to display the image
          jQuery('#sideBanner select').each(function (index) {
            var getDefault = jQuery(this.options[this.selectedIndex]).prop('value');
            // get the image url
            var getImageUrl = jQuery(this.options[this.selectedIndex]).prop('innerHTML');
            // target sidebanner id and select tag
            var getID = jQuery(this);
            // put a condition to selected is equal to tru then display the image
            if(getID[0].selectedOptions[0].selected == true){
               if(getDefault == ""){
                // if the value is empty
                 jQuery(".sidebanner-"+index).html("<p></p>");
              }else{
                    // display image selected
                 jQuery(".sidebanner-"+index).html("<img src="+getImageUrl+" width='300px' />");
              }
            }
          });
        }, 500);

      // Manipulate the acf repeater to target the change label text
      setTimeout(() => {
        jQuery("#sideBanner select").each(function (e) {
          var getSelect = jQuery(this);
          var getOption = getSelect[0].children;
          jQuery(getOption).each(function (index) {
            // here the change label text without changing the innerHTml
            var getValue = getOption[index].value;
            getOption[index].label = 'banner-' + getValue;
            getOption[0].label = '- Select Banner -';
           
          });
        });
       
      }, 1000);
    }
  };


  // condition to only work on dashboard
  if (jQuery('.table').hasClass('countdown-table')) {
    //Implemented databales to dashboard
    jQuery('#countdown_ongoing').DataTable();
    jQuery('#countdown_reminder').DataTable();
    jQuery('#countdown_expired').DataTable();

    //Remove the dataTables sorting function on action column
    jQuery(".countdown-action").removeClass("sorting");
  }

});
