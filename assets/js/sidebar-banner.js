function digitalcdt($expdate, $display, $numColor, $bgColor, $numLayout, $position ) {
    var dateTimer = setInterval(function() {
        // End date
        var enddate = new Date($expdate).getTime();
        // Get today's date and time
        var now = new Date().getTime();
        // Find the distance between now and the count down date
        var milliseconds = enddate - now;
        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(milliseconds / (1000 * 60 * 60 * 24));
        var hours = Math.floor((milliseconds % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((milliseconds % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((milliseconds % (1000 * 60)) / 1000);

        // Css for identifier ($display)
        // Add width 100% if layout is equal to 4
        if ($numLayout == 4) {
            document.getElementById($display).style.cssText = `text-align: center; display: flex; width:100%`;
        } else {
            document.getElementById($display).style.cssText = `text-align: center; display: flex;`;
        }

        // For displaying days, hours, minutes and seconds
        function displayIdentifier(days, hours, minutes, seconds) {

            function addZeroToLeft() {
                // Convert int to string to count characters if how many numbers
                var dayNumber = days.toString().length;
                var hourNumber = hours.toString().length;
                var minNumber = minutes.toString().length;
                var secNumber = seconds.toString().length;
                // Add zero to left if value is single digit else dont add
                ( dayNumber == 1 ? days = '0' + days : '' );
                ( hourNumber == 1 ? hours = '0' + hours : '' );
                ( minNumber == 1 ? minutes = '0' + minutes : '' );
                ( secNumber == 1 ?  seconds = '0' + seconds : '' );
            }
            addZeroToLeft();

            // If cdt is inside the image display the following layout
            var labelPositionMin = '';
            if ($position == 'inside_numbers') {
                labelPositionMin = '<li class="borders"><h1 class="display-number">'+ minutes +'<p class="label">MIN</p></h1></li>'
            } else {
                labelPositionMin = '<li class="borders"><p class="display-number">'+ minutes +'</p><p class="label">MIN</p></li>'
            }

            // Round off if mins have 30 mins above add +1 on hours,
            // Adding list layout if layout is equal to 3 or 4.
            var addLayout = '';
            document.getElementById($display);
            if ( $numLayout == '2' ) {
                ( minutes >= 30 ? hours = Math.floor((milliseconds % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)) + 1 : '' );
                if (hours == 24) {
                    days = Math.floor(milliseconds / (1000 * 60 * 60 * 24)) + 1;
                    hours = "00";
                }
            } else if ( $numLayout == '3' ) {
                addLayout = labelPositionMin;
            } else if ( $numLayout == '4' ) {
                addLayout = '<li class="borders"><p class="display-number">'+ minutes +'</p><p class="label">MIN</p></li><li class="borders"><p class="display-number">'+ seconds +'</p><p class="label">SEC</p></li>';
            }
            
            addZeroToLeft();
            
            // If cdt is inside the image display the following layout
            var labelPositionMinSec = labelPositionDaysHrs = '';
            if ($position == 'inside_numbers') {
                labelPositionDaysHrs = '<li class="borders"><h1 class="display-number">'+ days +'<p class="label">DAY</p></h1></li><li class="borders"><h1 class="display-number">'+ hours +'<p class="label">HRS</p></h1></li>';
                labelPositionMinSec = ' <li class="borders"><h1 class="display-number">'+ minutes +'</p><p class="label">MIN</h1></li><li class="borders"><h1 class="display-number">'+ seconds +'</p><p class="label">SEC</h1></li>'
            } else {
                labelPositionDaysHrs = '<li class="borders"><p class="display-number">'+ hours +'</p><p class="label">DAY</p></li><li class="borders"><p class="display-number">'+ hours +'</p><p class="label">HRS</p></li>'
                labelPositionMinSec = '<li class="borders"><p class="display-number">'+ minutes +'</p><p class="label">MIN</p></li><li class="borders"><p class="display-number">'+ seconds +'</p><p class="label">SEC</p></li>'
            }

            // For layout 2, if days and hours are 0 switch to minutes and seconds display
            if ( $numLayout == 2 && minutes <= 30 && days == 0 && hours == 0 ) {
                document.getElementById($display).innerHTML = `
                <ul id="ul_`+ $display +`" class="content-wrapper">
                    `+ labelPositionMinSec +`
                </ul>
                `;
            } else {
                document.getElementById($display).innerHTML = `
                <ul style="width: 100%" id="ul_`+ $display +`" class="content-wrapper">
                <li class="borders cdt-timer-icon"><img draggable="false" role="img" class="emoji" alt="hourglass" src="https://s.w.org/images/core/emoji/14.0.0/svg/231b.svg"></li>
                    `+ labelPositionDaysHrs +`
                    `+ addLayout +`
                </ul>
                `;
            }
        }

        displayIdentifier(days, hours, minutes, seconds);

        // If date expired display 00
        if ( milliseconds < 0 ) {
          clearInterval(dateTimer);
          document.getElementById($display).innerHTML=` `;
        }

        // List background color css
        var bgColor = document.getElementById($display).getElementsByClassName("borders");
        for ( var x = 0; x < bgColor.length; x++ ) {
            bgColor[x].style.backgroundColor = $bgColor;
        }

        // add 25% on class name borders if layout is equal to 4
        var listWith = document.getElementById($display).getElementsByClassName("borders");
        if ($numLayout == 4) {
            for( var i = 0; i < listWith.length; i++ ) {
                listWith[i].style.width = '25%';
            }
        } else {
            for( var i = 0; i < listWith.length; i++ ) {
                listWith[i].style.width = '';
            }
        }

        // List color css
        var list = document.getElementById($display).getElementsByClassName("display-number");
        for( var i = 0; i < list.length; i++ ) {
            list[i].style.color = $numColor;
        }

        // Adding margin-top -4px if cdt is inside the image
        var cssForLabel = document.getElementById($display).getElementsByClassName("label");
        if ($position == 'inside_numbers') {
            for( var i = 0; i < cssForLabel.length; i++ ) {
                cssForLabel[i].style.marginTop = '-4px';
            }
        }
        
        // Change css class if timer is inside the image
        if ($position == 'inside_numbers') {
            var cssAfter = document.getElementById($display).getElementsByTagName("h1");
            for( var i = 0; i <= cssAfter.length; i++ ) {
                jQuery(cssAfter[i]).attr('class','display-number-inside-label');
            }
        }
    }, 1000);
}
