// Countdown Timer
function digitalcdt($expdate, $display, $numColor, $bgColor, $numLayout) {
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
        document.getElementById($display).style.cssText = `
            text-align: center;
            margin: 0 0 24px 0;
            display: flex;
            justify-content: center;
        `;

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

            // Round off if 30 above add +1 on hours,
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
                addLayout = '<li class="borders"><p class="display-number">'+ minutes +'</p><br><span>MINUTE/S</span></li>';
            } else if ( $numLayout == '4' ) {
                addLayout = '<li class="borders"><p class="display-number">'+ minutes +'</p><br><span>MINUTE/S</span></li><li class="borders"><p class="display-number">'+ seconds +'</p><br><span>SECOND/S</span></li>';
            }

            addZeroToLeft();

            // For 2 layout, if days and hours are 0 switch to minutes and seconds display
            if ( $numLayout == 2 && minutes <= 30 && days == 0 && hours == 0 ) {
                document.getElementById($display).innerHTML = `
                <ul class="content-wrapper" style="display: flex; flex-direction: row;">
                    <li class="borders"><p class="display-number">`+ minutes +`</p><br><span>MINUTE/S</span></li>
                    <li class="borders"><p class="display-number">`+ seconds +`</p><br><span>SECOND/S</span></li>
                </ul>
                `;
            } else {
                document.getElementById($display).innerHTML = `
                <ul class="content-wrapper" style="display: flex; flex-direction: row;">
                    <li class="borders"><p class="display-number">`+ days +`</p><br><span>DAY/S</span></li>
                    <li class="borders"><p class="display-number">`+ hours +`</p><br><span>HOUR/S</span></li>
                    `+ addLayout +`
                </ul>
                `;
            }

            // List background color css
            var bgColor = document.getElementById($display).getElementsByClassName("borders");
            for ( var x = 0; x < bgColor.length; x++ ) {
                bgColor[x].style.backgroundColor = $bgColor;
            }
        }
        displayIdentifier(days, hours, minutes, seconds);

        // If date expired display 00
        if ( milliseconds < 0 ) {
          clearInterval(dateTimer);
          displayIdentifier('00', '00', '00', '00');
        }

        // List color css
        var list = document.getElementById($display).getElementsByClassName("display-number");
        for( var i = 0; i < list.length; i++ ) {
            list[i].style.color = $numColor;
        }
    }, 1000);
}

// Icon Timer
function iconCdt($dateExpiry, $svgId, $displayIcon, $textDisplay) {
  var interval = setInterval(function() {
    // End date
    var enddate = new Date($dateExpiry).getTime();
    // Get today's date and time
    var now = new Date().getTime();
    // Find the distance between now and the count down date
    var milliseconds = enddate - now;
    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(milliseconds / (1000 * 60 * 60 * 24));
    var hours = Math.floor((milliseconds % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((milliseconds % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((milliseconds % (1000 * 60)) / 1000);

    if (days < 10) { days = "0" + days; }
    if (hours < 10) { hours = "0" + hours; }
    if (minutes < 10) { minutes = "0" + minutes; }
    if (seconds < 10) { seconds = "0" + seconds; }

    // Countdown timer display
    document.getElementById($textDisplay).innerHTML = days + ':' + hours + ':' + minutes + ':' + seconds;

    // Timer color changes
    switch (true) {
      case (days >= 2):
        document.querySelector(".timer-display#"+$svgId).classList.add('greentime');
        document.querySelector("#"+ $svgId +" circle#c1").classList.add('show');
        document.querySelector("svg#"+$svgId).setAttribute("class", "greenpie");
        break;
      case (days == 1):
        document.querySelector(".timer-display#"+$svgId).classList.add('greentime');
        document.querySelector("#"+ $svgId +" path#c2").classList.add('show');
        document.querySelector("#"+ $svgId +" circle#c1").classList.remove('show');
        document.querySelector("svg#"+$svgId).setAttribute("class", "greenpie");
        break;
      case (days == 0):
        switch (true) {
          case (hours <= 24 && hours >= 12):
            document.querySelector(".timer-display#"+$svgId).classList.add('yellowtime');
            document.querySelector("#"+ $svgId +" path#c3").classList.add('show');
            document.querySelector("#"+ $svgId +" path#c2").classList.remove('show');
            document.querySelector("svg#"+$svgId).setAttribute("class", "yellowpie");
            break;
          case (hours <= 11 && hours >= 8):
            document.querySelector(".timer-display#"+$svgId).classList.add('yellowtime');
            document.querySelector("#"+ $svgId +" path#c4").classList.add('show');
            document.querySelector("#"+ $svgId +" path#c3").classList.remove('show');
            document.querySelector("svg#"+$svgId).setAttribute("class", "yellowpie");
            break;
          case (hours <= 7 && hours >= 4):
            document.querySelector(".timer-display#"+$svgId).classList.add('orangetime');
            document.querySelector("#"+ $svgId +" path#c5").classList.add('show');
            document.querySelector("#"+ $svgId +" path#c4").classList.remove('show');
            document.querySelector("svg#"+$svgId).setAttribute("class", "orangepie");
            break;
          case (hours < 4 && hours >= 2):
            document.querySelector(".timer-display#"+$svgId).classList.add('orangetime');
            document.querySelector("#"+ $svgId +" path#c6").classList.add('show');
            document.querySelector("#"+ $svgId +" path#c5").classList.remove('show');
            document.querySelector("svg#"+$svgId).setAttribute("class", "orangepie");
            break;
          case (hours == 1):
            document.querySelector(".timer-display#"+$svgId).classList.add('redtime');
            document.querySelector("#"+ $svgId +" path#c7").classList.add('show');
            document.querySelector("#"+ $svgId +" path#c6").classList.remove('show');
            document.querySelector("svg#"+$svgId).setAttribute("class", "redpie");
            break;
          default:
            document.querySelector(".timer-display#"+$svgId).classList.add('redtime');
            document.querySelector("#"+ $svgId +" path#c8").classList.add('show');
            document.querySelector("#"+ $svgId +" path#c7").classList.remove('show');
            document.querySelector("svg#"+$svgId).setAttribute("class", "redpie");
            break;
        }
        break;
    }
    // If the count down is over
    if (milliseconds < 0) {
      clearInterval(interval);
      document.getElementById($textDisplay).remove();
      document.getElementById($svgId).remove();
      document.getElementById($displayIcon).remove();
    }
  }, 1000);

  // Display Icon
  document.getElementById($displayIcon).innerHTML = `
    <svg version="1.1" id="`+ $svgId +`" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
      viewBox="0 0 1007.1 820.1" style="enable-background:new 0 0 1007.1 820.1;" xml:space="preserve" width="500px">
      <path class="st0" d="M448.3,149v42.5c0,3.9,3.2,7.1,7.1,7.1l0,0c3.9,0,7.1,3.2,7.1,7.1v15h68.6v-15c0-3.9,3.2-7.1,7.1-7.1l0,0	c3.9,0,7.1-3.2,7.1-7.1V149c0-3.9-3.2-7.1-7.1-7.1c-18,0-64.9,0-82.9,0C451.5,141.9,448.3,145.1,448.3,149z"/>
      <path class="st0" d="M338,234.4l10.3,16.3c1,1.5,2.9,2,4.4,1l0,0c1.5-1,3.5-0.5,4.4,1l3.6,5.7l26.2-16.6l-3.6-5.7	c-1-1.5-0.5-3.5,1-4.4l0,0c1.5-1,2-2.9,1-4.4L375,211c-1-1.5-2.9-2-4.4-1c-6.9,4.3-24.8,15.7-31.7,20.1	C337.5,230.9,337.1,232.9,338,234.4z"/>
      <path class="st0" d="M656.2,234.4l-10.3,16.3c-1,1.5-2.9,2-4.4,1l0,0c-1.5-1-3.5-0.5-4.4,1l-3.6,5.7l-26.2-16.6l3.6-5.7	c1-1.5,0.5-3.5-1-4.4l0,0c-1.5-1-2-2.9-1-4.4l10.3-16.3c1-1.5,2.9-2,4.4-1c6.9,4.3,24.8,15.7,31.7,20.1	C656.7,230.9,657.1,232.9,656.2,234.4z"/>
      <circle class="st0" cx="496.8" cy="469.5" r="255.4"/>
      <circle class="st1" cx="496.8" cy="469.5" r="206.6"/>
      <path class="st2" id="c8" d="M497.2,289.6v182.2c-43.4-42.2-86.7-84.5-130-126.7C400,310.9,446.1,289.6,497.2,289.6z"/>
      <path class="st2" id="c7" d="M316.8,469.5c0,0.8,0,1.6,0,2.4l180-0.2V289.5C397.4,289.5,316.8,370.1,316.8,469.5z"/>
      <path class="st3" id="c6" d="M496.8,289.5v182.2c-42.1,42.1-84.1,84.1-126.2,126.2c-33.2-32.7-53.8-78.1-53.8-128.4	C316.8,370.1,397.4,289.5,496.8,289.5z"/>
      <path class="st3" id="c5" d="M496.8,289.5v360c-99.4,0-180-80.6-180-180C316.8,370.1,397.4,289.5,496.8,289.5z"/>
      <path class="st4" id="c4" d="M626.5,594.8c-0.2,0.2-0.4,0.4-0.5,0.5c-42.9-42.2-85.9-84.4-128.8-126.7l-0.5,0.5v-1l0-0.3l0.7-178.4	c-99.8,0-180.6,80.6-180.6,180s80.9,180,180.6,180c50.7,0,96.6-20.8,129.4-54.4C626.7,595,626.6,594.9,626.5,594.8z"/>
      <path class="st4" id="c3" d="M676.8,469.5c0,99.4-80.6,180-180,180s-180-80.6-180-180s80.6-180,180-180l-0.7,179.6l0.8-0.8h179.9	C676.8,468.7,676.8,469.1,676.8,469.5z"/>
      <path class="st5" id="c2" d="M623.4,341.6c-0.1,0.1-0.2,0.2-0.3,0.2c-0.1,0.2-0.3,0.5-0.5,0.7c0,0,0,0,0,0l-0.1,0.1
        c-0.1,0.1-0.1,0.1-0.2,0.2L496.1,469.1l0.7-179.6c-99.4,0-180,80.6-180,180s80.6,180,180,180s180-80.6,180-180  C676.8,419.5,656.4,374.2,623.4,341.6z"/>
      <circle class="st5" id="c1" cx="496.8" cy="469.5" r="180"/>
    </svg>
  `;
  return interval;
}
