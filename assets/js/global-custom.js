function alert_output(){
  var refresh = 1000; // Refresh rate in milli seconds
  time = setTimeout('alert_data()',refresh)
}

(function (define) {
  define(["jquery"], function ($) {
    return (function () {
      var $container;
      var listener;
      var alertId = 0;
      var alertType = {
        warning: "warning",
        classic: "classic"
      };

      var alert_notif = {
        clear: clear,
        remove: remove,
        getContainer: getContainer,
        options: {},
        subscribe: subscribe,
        version: "2.1.2",
        warning: warning,
        classic: classic
      };

      var previousAlert;

      return alert_notif;

      function getContainer(options, create) {
        if (!options) {
          options = getOptions();
        }
        $container = $("#" + options.containerId);
        if ($container.length) {
          return $container;
        }
        if (create) {
          $container = createContainer(options);
        }
        return $container;
      }

      function classic(message, title, optionsOverride) {
        return notify({
          type: alertType.classic,
          iconClass: getOptions().iconClasses.classic,
          message: message,
          optionsOverride: optionsOverride,
          title: title
        });
      }

      function subscribe(callback) {
        listener = callback;
      }

      function warning(message, title, optionsOverride) {
        return notify({
          type: alertType.warning,
          iconClass: getOptions().iconClasses.warning,
          message: message,
          optionsOverride: optionsOverride,
          title: title
        });
      }

      function clear(alertElement, clearOptions) {
        var options = getOptions();
        if (!$container) {
          getContainer(options);
        }
        if (!clearAlert(alertElement, options, clearOptions)) {
          clearContainer(options);
        }
      }

      function remove(alertElement) {
        var options = getOptions();
        if (!$container) {
          getContainer(options);
        }
        if (alertElement && $(":focus", alertElement).length === 0) {
          removeAlert(alertElement);
          return;
        }
        if ($container.children().length) {
          $container.remove();
        }
      }

      // internal functions

      function clearContainer(options) {
        var alertsToClear = $container.children();
        for (var i = alertsToClear.length - 1; i >= 0; i--) {
          clearAlert($(alertsToClear[i]), options);
        }
      }

      function clearAlert(alertElement, options, clearOptions) {
        var force =
          clearOptions && clearOptions.force ? clearOptions.force : false;
        if (
          alertElement &&
          (force || $(":focus", alertElement).length === 0)
        ) {
          alertElement[options.hideMethod]({
            duration: options.hideDuration,
            easing: options.hideEasing,
            complete: function () {
              removeAlert(alertElement);
            }
          });
          return true;
        }
        return false;
      }

      function createContainer(options) {
        $container = $("<div/>")
          .attr("id", options.containerId)
          .addClass(options.positionClass)
          .attr("aria-live", "polite")
          .attr("role", "alert");

        $container.appendTo($(options.target));
        return $container;
      }

      function getDefaults() {
        return {
          tapToDismiss: true,
          alertClass: "alert",
          containerId: "alert-container",
          debug: false,

          showMethod: "fadeIn", //fadeIn, slideDown, and show are built into jQuery
          showDuration: 300,
          showEasing: "swing", //swing and linear are built into jQuery
          onShown: undefined,
          hideMethod: "fadeOut",
          hideDuration: 1000,
          hideEasing: "swing",
          onHidden: undefined,
          closeMethod: false,
          closeDuration: false,
          closeEasing: false,

          extendedTimeOut: 1000,
          iconClasses: {
            warning: "alert-warning",
            classic: "alert-classic"
          },
          positionClass: "alert-top-right",
          timeOut: 5000, // Set timeOut and extendedTimeOut to 0 to make it sticky
          titleClass: "alert-title",
          messageClass: "alert-message",
          escapeHtml: false,
          target: "body",
          closeHtml: '<button type="button">&times;</button>',
          newestOnTop: true,
          preventDuplicates: false,
          progressBar: false
        };
      }

      function publish(args) {
        if (!listener) {
          return;
        }
        listener(args);
      }

      function notify(map) {
        var options = getOptions();
        var iconClass = map.iconClass || options.iconClass;

        if (typeof map.optionsOverride !== "undefined") {
          options = $.extend(options, map.optionsOverride);
          iconClass = map.optionsOverride.iconClass || iconClass;
        }

        if (shouldExit(options, map)) {
          return;
        }

        alertId++;

        $container = getContainer(options, true);

        var intervalId = null;
        var alertElement = $("<div/>");
        var $titleElement = $("<div/>");
        var $messageElement = $("<div/>");
        var $progressElement = $("<div/>");
        var $closeElement = $(options.closeHtml);
        var progressBar = {
          intervalId: null,
          hideEta: null,
          maxHideTime: null
        };
        var response = {
          alertId: alertId,
          state: "visible",
          startTime: new Date(),
          options: options,
          map: map
        };

        personalizeAlert();

        displayAlert();

        handleEvents();

        publish(response);

        if (options.debug && console) {
          console.log(response);
        }

        return alertElement;

        function escapeHtml(source) {
          if (source == null) source = "";

          return new String(source)
            .replace(/&/g, "&amp;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#39;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
        }

        function personalizeAlert() {
          setIcon();
          setTitle();
          setMessage();
          setCloseButton();
          setProgressBar();
          setSequence();
        }

        function handleEvents() {
          alertElement.hover(stickAround, delayedHideAlert);
          if (!options.onclick && options.tapToDismiss) {
            alertElement.click(hideAlert);
          }

          if (options.closeButton && $closeElement) {
            $closeElement.click(function (event) {
              if (event.stopPropagation) {
                event.stopPropagation();
              } else if (
                event.cancelBubble !== undefined &&
                event.cancelBubble !== true
              ) {
                event.cancelBubble = true;
              }
              hideAlert(true);
            });
          }

          if (options.onclick) {
            alertElement.click(function (event) {
              options.onclick(event);
              hideAlert();
            });
          }
        }

        function displayAlert() {
          alertElement.hide();

          alertElement[options.showMethod]({
            duration: options.showDuration,
            easing: options.showEasing,
            complete: options.onShown
          });

          if (options.timeOut > 0) {
            intervalId = setTimeout(hideAlert, options.timeOut);
            progressBar.maxHideTime = parseFloat(options.timeOut);
            progressBar.hideEta =
              new Date().getTime() + progressBar.maxHideTime;
            if (options.progressBar) {
              progressBar.intervalId = setInterval(updateProgress, 10);
            }
          }
        }

        function setIcon() {
          if (map.iconClass) {
            alertElement.addClass(options.alertClass).addClass(iconClass);
          }
        }

        function setSequence() {
          if (options.newestOnTop) {
            $container.prepend(alertElement);
          } else {
            $container.append(alertElement);
          }
        }

        function setTitle() {
          if (map.title) {
            $titleElement
              .append(!options.escapeHtml ? map.title : escapeHtml(map.title))
              .addClass(options.titleClass);
            alertElement.append($titleElement);
          }
        }

        function setMessage() {
          if (map.message) {
            $messageElement
              .append(
                !options.escapeHtml ? map.message : escapeHtml(map.message)
              )
              .addClass(options.messageClass);
            alertElement.append($messageElement);
          }
        }

        function setCloseButton() {
          if (options.closeButton) {
            $closeElement.addClass("alert-close-button").attr("role", "button");
            alertElement.prepend($closeElement);
          }
        }

        function setProgressBar() {
          if (options.progressBar) {
            $progressElement.addClass("alert-progress");
            alertElement.prepend($progressElement);
          }
        }

        function shouldExit(options, map) {
          if (options.preventDuplicates) {
            if (map.message === previousAlert) {
              return true;
            } else {
              previousAlert = map.message;
            }
          }
          return false;
        }

        function hideAlert(override) {
          var method =
            override && options.closeMethod !== false
              ? options.closeMethod
              : options.hideMethod;
          var duration =
            override && options.closeDuration !== false
              ? options.closeDuration
              : options.hideDuration;
          var easing =
            override && options.closeEasing !== false
              ? options.closeEasing
              : options.hideEasing;
          if ($(":focus", alertElement).length && !override) {
            return;
          }
          clearTimeout(progressBar.intervalId);
          return alertElement[method]({
            duration: duration,
            easing: easing,
            complete: function () {
              removeAlert(alertElement);
              if (options.onHidden && response.state !== "hidden") {
                options.onHidden();
              }
              response.state = "hidden";
              response.endTime = new Date();
              publish(response);
            }
          });
        }

        function delayedHideAlert() {
          if (options.timeOut > 0 || options.extendedTimeOut > 0) {
            intervalId = setTimeout(hideAlert, options.extendedTimeOut);
            progressBar.maxHideTime = parseFloat(options.extendedTimeOut);
            progressBar.hideEta =
              new Date().getTime() + progressBar.maxHideTime;
          }
        }

        function stickAround() {
          clearTimeout(intervalId);
          progressBar.hideEta = 0;
          alertElement
            .stop(true, true)
            [options.showMethod]({
              duration: options.showDuration,
              easing: options.showEasing
            });
        }

        function updateProgress() {
          var percentage =
            ((progressBar.hideEta - new Date().getTime()) /
              progressBar.maxHideTime) *
            100;
          $progressElement.width(percentage + "%");
        }
      }

      function getOptions() {
        return $.extend({}, getDefaults(), alert_notif.options);
      }

      function removeAlert(alertElement) {
        if (!$container) {
          $container = getContainer();
        }
        if (alertElement.is(":visible")) {
          return;
        }
        alertElement.remove();
        alertElement = null;
        if ($container.children().length === 0) {
          $container.remove();
          previousAlert = undefined;
        }
      }
    })();
  });
})(
  typeof define === "function" && define.amd
    ? define
    : function (deps, factory) {
        if (typeof module !== "undefined" && module.exports) {
          //Node
          module.exports = factory(require("jquery"));
        } else {
          window.alert_notif = factory(window.jQuery);
        }
      }
);

function countdown_notification(alert_message) {
  alert_notif.options = {
    closeButton: true,
    debug: false,
    newestOnTop: true,
    progressBar: true,
    positionClass: "alert-bottom-right",
    preventDuplicates: false,
    onclick: null,
    showDuration: "300",
    hideDuration: "10000",
    timeOut: "10000",
    extendedTimeOut: "10000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut"
  };
  alert_notif["warning"](alert_message);
}
