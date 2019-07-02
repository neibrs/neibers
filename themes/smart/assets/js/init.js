(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.init= {
    attach: function attach(context, settings) {
      /*
       * =======================================================================
       * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
       * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
       * MERCHANTABILITY, IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
       * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
       * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
       * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
       * =======================================================================
       * original filename: app.config.js
       * filesize: 12kb
       * =======================================================================
       *
       * GLOBAL ROOT (DO NOT CHANGE)
       */
      $.root_ = $('body');
      /*
       * APP CONFIGURATION (HTML/AJAX/PHP Versions ONLY)
       * Description: Enable / disable certain theme features here
       * GLOBAL: Your left nav in your app will no longer fire ajax calls, set
       * it to false for HTML version
       */
      $.navAsAjax = false;
      /*
       * GLOBAL: Sound Config (define sound path, enable or disable all sounds)
       */
      $.sound_path = "sound/";
      $.sound_on = true;
      /*
       * SAVE INSTANCE REFERENCE (DO NOT CHANGE)
       * Save a reference to the global object (window in the browser)
       */
      var root = this,
        /*
         * DEBUGGING MODE
         * debugState = true; will spit all debuging message inside browser console.
         * The colors are best displayed in chrome browser.
         */
        debugState = false,
        debugStyle = 'font-weight: bold; color: #00f;',
        debugStyle_green = 'font-weight: bold; font-style:italic; color: #46C246;',
        debugStyle_red = 'font-weight: bold; color: #ed1c24;',
        debugStyle_warning = 'background-color:yellow',
        debugStyle_success = 'background-color:green; font-weight:bold; color:#fff;',
        debugStyle_error = 'background-color:#ed1c24; font-weight:bold; color:#fff;',
        /*
         * Impacts the responce rate of some of the responsive elements (lower
         * value affects CPU but improves speed)
         */
        throttle_delay = 350,
        /*
         * The rate at which the menu expands revealing child elements on click
         */
        menu_speed = 235,
        /*
         * Collapse current menu item as other menu items are expanded
         * Careful when using this option, if you have a long menu it will
         * keep expanding and may distrupt the user experience This is best
         * used with fixed-menu class
         */
        menu_accordion = true,
        /*
         * Turn on JarvisWidget functionality
         * Global JarvisWidget Settings
         * For a greater control of the widgets, please check app.js file
         * found within COMMON_ASSETS/UNMINIFIED_JS folder and see from line 1355
         * dependency: js/jarviswidget/jarvis.widget.min.js
         */
        enableJarvisWidgets = true,
        /*
         * Use localstorage to save widget settings
         * turn this off if you prefer to use the onSave hook to save
         * these settings to your datatabse instead
         */
        localStorageJarvisWidgets = true,
        /*
         * Turn off sortable feature for JarvisWidgets
         */
        sortableJarvisWidgets = true,
        /*
         * Warning: Enabling mobile widgets could potentially crash your webApp
         * if you have too many widgets running at once
         * (must have enableJarvisWidgets = true)
         */
        enableMobileWidgets = false,
        /*
         * Turn on fast click for mobile devices
         * Enable this to activate fastclick plugin
         * dependency: js/plugin/fastclick/fastclick.js
         */
        fastClick = false,
        /*
         * SMARTCHAT PLUGIN ARRAYS & CONFIG
         * Dependency: js/plugin/moment/moment.min.js
         *             js/plugin/cssemotions/jquery.cssemoticons.min.js
         *             js/smart-chat-ui/smart.chat.ui.js
         * (DO NOT CHANGE BELOW)
         */
        boxList = [],
        showList = [],
        nameList = [],
        idList = [],
        /*
         * Width of the chat boxes, and the gap inbetween in pixel (minus padding)
         */
        chatbox_config = {
          width: 200,
          gap: 35
        },
        /*
         * These elements are ignored during DOM object deletion for ajax version
         * It will delete all objects during page load with these exceptions:
         */
        ignore_key_elms = ["#header, #left-panel, #right-panel, #main, div.page-footer, #shortcut, #divSmallBoxes, #divMiniIcons, #divbigBoxes, #voiceModal, script, .ui-chatbox"],
        /*
         * VOICE COMMAND CONFIG
         * dependency: js/speech/voicecommand.js
         */
        voice_command = true,
        /*
         * Turns on speech as soon as the page is loaded
         */
        voice_command_auto = false,
        /*
         * 	Sets the language to the default 'en-US'. (supports over 50 languages
         * 	by google)
         *
         *  Afrikaans         ['af-ZA']
         *  Bahasa Indonesia  ['id-ID']
         *  Bahasa Melayu     ['ms-MY']
         *  Català            ['ca-ES']
         *  Čeština           ['cs-CZ']
         *  Deutsch           ['de-DE']
         *  English           ['en-AU', 'Australia']
         *                    ['en-CA', 'Canada']
         *                    ['en-IN', 'India']
         *                    ['en-NZ', 'New Zealand']
         *                    ['en-ZA', 'South Africa']
         *                    ['en-GB', 'United Kingdom']
         *                    ['en-US', 'United States']
         *  Español           ['es-AR', 'Argentina']
         *                    ['es-BO', 'Bolivia']
         *                    ['es-CL', 'Chile']
         *                    ['es-CO', 'Colombia']
         *                    ['es-CR', 'Costa Rica']
         *                    ['es-EC', 'Ecuador']
         *                    ['es-SV', 'El Salvador']
         *                    ['es-ES', 'España']
         *                    ['es-US', 'Estados Unidos']
         *                    ['es-GT', 'Guatemala']
         *                    ['es-HN', 'Honduras']
         *                    ['es-MX', 'México']
         *                    ['es-NI', 'Nicaragua']
         *                    ['es-PA', 'Panamá']
         *                    ['es-PY', 'Paraguay']
         *                    ['es-PE', 'Perú']
         *                    ['es-PR', 'Puerto Rico']
         *                    ['es-DO', 'República Dominicana']
         *                    ['es-UY', 'Uruguay']
         *                    ['es-VE', 'Venezuela']
         *  Euskara           ['eu-ES']
         *  Français          ['fr-FR']
         *  Galego            ['gl-ES']
         *  Hrvatski          ['hr_HR']
         *  IsiZulu           ['zu-ZA']
         *  Íslenska          ['is-IS']
         *  Italiano          ['it-IT', 'Italia']
         *                    ['it-CH', 'Svizzera']
         *  Magyar            ['hu-HU']
         *  Nederlands        ['nl-NL']
         *  Norsk bokmål      ['nb-NO']
         *  Polski            ['pl-PL']
         *  Português         ['pt-BR', 'Brasil']
         *                    ['pt-PT', 'Portugal']
         *  Română            ['ro-RO']
         *  Slovenčina        ['sk-SK']
         *  Suomi             ['fi-FI']
         *  Svenska           ['sv-SE']
         *  Türkçe            ['tr-TR']
         *  български         ['bg-BG']
         *  Pусский           ['ru-RU']
         *  Српски            ['sr-RS']
         *  한국어          ['ko-KR']
         *  中文                            ['cmn-Hans-CN', '普通话 (中国大陆)']
         *                    ['cmn-Hans-HK', '普通话 (香港)']
         *                    ['cmn-Hant-TW', '中文 (台灣)']
         *                    ['yue-Hant-HK', '粵語 (香港)']
         *  日本語                         ['ja-JP']
         *  Lingua latīna     ['la']
         */
        voice_command_lang = 'en-US',
        /*
         * 	Use localstorage to remember on/off (best used with HTML Version
         * 	when going from one page to the next)
         */
        voice_localStorage = true;
      /*
       * Voice Commands
       * Defines voice command variables and functions
       */
      if (voice_command) {

        var commands = {

          'show dashboard' : function() { $('nav a[href="dashboard.html"]').trigger("click"); },
          'show inbox' : function() { $('nav a[href="inbox.html"]').trigger("click"); },
          'show graphs' : function() { $('nav a[href="flot.html"]').trigger("click"); },
          'show flotchart' : function() { $('nav a[href="flot.html"]').trigger("click"); },
          'show morris chart' : function() { $('nav a[href="morris.html"]').trigger("click"); },
          'show inline chart' : function() { $('nav a[href="inline-charts.html"]').trigger("click"); },
          'show dygraphs' : function() { $('nav a[href="dygraphs.html"]').trigger("click"); },
          'show tables' : function() { $('nav a[href="table.html"]').trigger("click"); },
          'show data table' : function() { $('nav a[href="datatables.html"]').trigger("click"); },
          'show jquery grid' : function() { $('nav a[href="jqgrid.html"]').trigger("click"); },
          'show form' : function() { $('nav a[href="form-elements.html"]').trigger("click"); },
          'show form layouts' : function() { $('nav a[href="form-templates.html"]').trigger("click"); },
          'show form validation' : function() { $('nav a[href="validation.html"]').trigger("click"); },
          'show form elements' : function() { $('nav a[href="bootstrap-forms.html"]').trigger("click"); },
          'show form plugins' : function() { $('nav a[href="plugins.html"]').trigger("click"); },
          'show form wizards' : function() { $('nav a[href="wizards.html"]').trigger("click"); },
          'show bootstrap editor' : function() { $('nav a[href="other-editors.html"]').trigger("click"); },
          'show dropzone' : function() { $('nav a[href="dropzone.html"]').trigger("click"); },
          'show image cropping' : function() { $('nav a[href="image-editor.html"]').trigger("click"); },
          'show general elements' : function() { $('nav a[href="general-elements.html"]').trigger("click"); },
          'show buttons' : function() { $('nav a[href="buttons.html"]').trigger("click"); },
          'show fontawesome' : function() { $('nav a[href="fa.html"]').trigger("click"); },
          'show glyph icons' : function() { $('nav a[href="glyph.html"]').trigger("click"); },
          'show flags' : function() { $('nav a[href="flags.html"]').trigger("click"); },
          'show grid' : function() { $('nav a[href="grid.html"]').trigger("click"); },
          'show tree view' : function() { $('nav a[href="treeview.html"]').trigger("click"); },
          'show nestable lists' : function() { $('nav a[href="nestable-list.html"]').trigger("click"); },
          'show jquery U I' : function() { $('nav a[href="jqui.html"]').trigger("click"); },
          'show typography' : function() { $('nav a[href="typography.html"]').trigger("click"); },
          'show calendar' : function() { $('nav a[href="calendar.html"]').trigger("click"); },
          'show widgets' : function() { $('nav a[href="widgets.html"]').trigger("click"); },
          'show gallery' : function() { $('nav a[href="gallery.html"]').trigger("click"); },
          'show maps' : function() { $('nav a[href="gmap-xml.html"]').trigger("click"); },
          'show pricing tables' : function() { $('nav a[href="pricing-table.html"]').trigger("click"); },
          'show invoice' : function() { $('nav a[href="invoice.html"]').trigger("click"); },
          'show search' : function() { $('nav a[href="search.html"]').trigger("click"); },
          'go back' :  function() { history.back(1); },
          'scroll up' : function () { $('html, body').animate({ scrollTop: 0 }, 100); },
          'scroll down' : function () { $('html, body').animate({ scrollTop: $(document).height() }, 100);},
          'hide navigation' : function() {
            if ($.root_.hasClass("container") && !$.root_.hasClass("menu-on-top")){
              $('span.minifyme').trigger("click");
            } else {
              $('#hide-menu > span > a').trigger("click");
            }
          },
          'show navigation' : function() {
            if ($.root_.hasClass("container") && !$.root_.hasClass("menu-on-top")){
              $('span.minifyme').trigger("click");
            } else {
              $('#hide-menu > span > a').trigger("click");
            }
          },
          'mute' : function() {
            $.sound_on = false;
            $.smallBox({
              title : "MUTE",
              content : "All sounds have been muted!",
              color : "#a90329",
              timeout: 4000,
              icon : "fa fa-volume-off"
            });
          },
          'sound on' : function() {
            $.sound_on = true;
            $.speechApp.playConfirmation();
            $.smallBox({
              title : "UNMUTE",
              content : "All sounds have been turned on!",
              color : "#40ac2b",
              sound_file: 'voice_alert',
              timeout: 5000,
              icon : "fa fa-volume-up"
            });
          },
          'stop' : function() {
            smartSpeechRecognition.abort();
            $.root_.removeClass("voice-command-active");
            $.smallBox({
              title : "VOICE COMMAND OFF",
              content : "Your voice commands has been successfully turned off. Click on the <i class='fa fa-microphone fa-lg fa-fw'></i> icon to turn it back on.",
              color : "#40ac2b",
              sound_file: 'voice_off',
              timeout: 8000,
              icon : "fa fa-microphone-slash"
            });
            if ($('#speech-btn .popover').is(':visible')) {
              $('#speech-btn .popover').fadeOut(250);
            }
          },
          'help' : function() {
            $('#voiceModal').removeData('modal').modal( { remote: "ajax/modal-content/modal-voicecommand.html", show: true } );
            if ($('#speech-btn .popover').is(':visible')) {
              $('#speech-btn .popover').fadeOut(250);
            }
          },
          'got it' : function() {
            $('#voiceModal').modal('hide');
          },
          'logout' : function() {
            $.speechApp.stop();
            window.location = $('#logout > span > a').attr("href");
          }
        };

      };
      /*
       * END APP.CONFIG
       */

      /*
       * GLOBAL: interval array (to be used with jarviswidget in ajax and
       * angular mode) to clear auto fetch interval
       */
      $.intervalArr = [];

      /*
       * Calculate nav height
       */
      var calc_navbar_height = function() {
          var height = null;

          if ($('#header').length)
            height = $('#header').height();

          if (height === null)
            height = $('<div id="header"></div>').height();

          if (height === null)
            return 49;
          // default
          return height;
        },

        navbar_height = calc_navbar_height,
        /*
         * APP DOM REFERENCES
         * Description: Obj DOM reference, please try to avoid changing these
         */
        shortcut_dropdown = $('#shortcut'),

        bread_crumb = $('#ribbon ol.breadcrumb'),
        /*
         * Top menu on/off
         */
        topmenu = false,
        /*
         * desktop or mobile
         */
        thisDevice = null,
        /*
         * DETECT MOBILE DEVICES
         * Description: Detects mobile device - if any of the listed device is
         * detected a class is inserted to $.root_ and the variable thisDevice
         * is decleard. (so far this is covering most hand held devices)
         */
        ismobile = (/iphone|ipad|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase())),
        /*
         * JS ARRAY SCRIPT STORAGE
         * Description: used with loadScript to store script path and file name
         * so it will not load twice
         */
        jsArray = {},
        /*
         * App Initialize
         * Description: Initializes the app with intApp();
         */
        initApp = (function(app) {

          /*
           * ADD DEVICE TYPE
           * Detect if mobile or desktop
           */
          app.addDeviceType = function() {

            if (!ismobile) {
              // Desktop
              $.root_.addClass("desktop-detected");
              thisDevice = "desktop";
              return false;
            } else {
              // Mobile
              $.root_.addClass("mobile-detected");
              thisDevice = "mobile";

              if (fastClick) {
                // Removes the tap delay in idevices
                // dependency: js/plugin/fastclick/fastclick.js
                $.root_.addClass("needsclick");
                FastClick.attach(document.body);
                return false;
              }

            }

          };
          /* ~ END: ADD DEVICE TYPE */

          /*
           * CHECK FOR MENU POSITION
           * Scans localstroage for menu position (vertical or horizontal)
           */
          app.menuPos = function() {

            if ($.root_.hasClass("menu-on-top") || localStorage.getItem('sm-setmenu')=='top' ) {
              topmenu = true;
              $.root_.addClass("menu-on-top");
            }
          };
          /* ~ END: CHECK MOBILE DEVICE */

          /*
           * SMART ACTIONS
           */
          app.SmartActions = function(){

            var smartActions = {

              // LOGOUT MSG
              userLogout: function($this){

                // ask verification
                $.SmartMessageBox({
                  title : "<i class='fa fa-sign-out txt-color-orangeDark'></i> Logout <span class='txt-color-orangeDark'><strong>" + $('#show-shortcut').text() + "</strong></span> ?",
                  content : $this.data('logout-msg') || "You can improve your security further after logging out by closing this opened browser",
                  buttons : '[No][Yes]'

                }, function(ButtonPressed) {
                  if (ButtonPressed == "Yes") {
                    $.root_.addClass('animated fadeOutUp');
                    setTimeout(logout, 1000);
                  }
                });
                function logout() {
                  window.location = $this.attr('href');
                }

              },

              // RESET WIDGETS
              resetWidgets: function($this){

                $.SmartMessageBox({
                  title : "<i class='fa fa-refresh' style='color:green'></i> Clear Local Storage",
                  content : $this.data('reset-msg') || "Would you like to RESET all your saved widgets and clear LocalStorage?1",
                  buttons : '[No][Yes]'
                }, function(ButtonPressed) {
                  if (ButtonPressed == "Yes" && localStorage) {
                    localStorage.clear();
                    location.reload();
                  }

                });
              },

              // LAUNCH FULLSCREEN
              launchFullscreen: function(element){

                if (!$.root_.hasClass("full-screen")) {

                  $.root_.addClass("full-screen");

                  if (element.requestFullscreen) {
                    element.requestFullscreen();
                  } else if (element.mozRequestFullScreen) {
                    element.mozRequestFullScreen();
                  } else if (element.webkitRequestFullscreen) {
                    element.webkitRequestFullscreen();
                  } else if (element.msRequestFullscreen) {
                    element.msRequestFullscreen();
                  }

                } else {

                  $.root_.removeClass("full-screen");

                  if (document.exitFullscreen) {
                    document.exitFullscreen();
                  } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                  } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                  }

                }

              },

              // MINIFY MENU
              minifyMenu: function($this){
                if (!$.root_.hasClass("menu-on-top")){
                  $.root_.toggleClass("minified");
                  $.root_.removeClass("hidden-menu");
                  $('html').removeClass("hidden-menu-mobile-lock");
                  $this.effect("highlight", {}, 500);
                }
              },

              // TOGGLE MENU
              toggleMenu: function(){
                if (!$.root_.hasClass("menu-on-top")){
                  $('html').toggleClass("hidden-menu-mobile-lock");
                  $.root_.toggleClass("hidden-menu");
                  $.root_.removeClass("minified");
                  //} else if ( $.root_.hasClass("menu-on-top") && $.root_.hasClass("mobile-view-activated") ) {
                  // suggested fix from Christian Jäger
                } else if ( $.root_.hasClass("menu-on-top") && $(window).width() < 979 ) {
                  $('html').toggleClass("hidden-menu-mobile-lock");
                  $.root_.toggleClass("hidden-menu");
                  $.root_.removeClass("minified");
                }
              },

              // TOGGLE SHORTCUT
              toggleShortcut: function(){

                if (shortcut_dropdown.is(":visible")) {
                  shortcut_buttons_hide();
                } else {
                  shortcut_buttons_show();
                }

                // SHORT CUT (buttons that appear when clicked on user name)
                shortcut_dropdown.find('a').click(function(e) {
                  e.preventDefault();
                  window.location = $(this).attr('href');
                  setTimeout(shortcut_buttons_hide, 300);

                });

                // SHORTCUT buttons goes away if mouse is clicked outside of the area
                $(document).mouseup(function(e) {
                  if (!shortcut_dropdown.is(e.target) && shortcut_dropdown.has(e.target).length === 0) {
                    shortcut_buttons_hide();
                  }
                });

                // SHORTCUT ANIMATE HIDE
                function shortcut_buttons_hide() {
                  shortcut_dropdown.animate({
                    height : "hide"
                  }, 300, "easeOutCirc");
                  $.root_.removeClass('shortcut-on');

                }

                // SHORTCUT ANIMATE SHOW
                function shortcut_buttons_show() {
                  shortcut_dropdown.animate({
                    height : "show"
                  }, 200, "easeOutCirc");
                  $.root_.addClass('shortcut-on');
                }

              }

            };

            $.root_.on('click', '[data-action="userLogout"]', function(e) {
              var $this = $(this);
              smartActions.userLogout($this);
              e.preventDefault();

              //clear memory reference
              $this = null;

            });

            /*
             * BUTTON ACTIONS
             */
            $.root_.on('click', '[data-action="resetWidgets"]', function(e) {
              var $this = $(this);
              smartActions.resetWidgets($this);
              e.preventDefault();

              //clear memory reference
              $this = null;
            });

            $.root_.on('click', '[data-action="launchFullscreen"]', function(e) {
              smartActions.launchFullscreen(document.documentElement);
              e.preventDefault();
            });

            $.root_.on('click', '[data-action="minifyMenu"]', function(e) {
              var $this = $(this);
              smartActions.minifyMenu($this);
              e.preventDefault();

              //clear memory reference
              $this = null;
            });

            $.root_.on('click', '[data-action="toggleMenu"]', function(e) {
              smartActions.toggleMenu();
              e.preventDefault();
            });

            $.root_.on('click', '[data-action="toggleShortcut"]', function(e) {
              smartActions.toggleShortcut();
              e.preventDefault();
            });

          };
          /* ~ END: SMART ACTIONS */

          /*
           * ACTIVATE NAVIGATION
           * Description: Activation will fail if top navigation is on
           */
          app.leftNav = function(){

            // INITIALIZE LEFT NAV
            if (!topmenu) {
              if (!null) {
                $('nav ul').jarvismenu({
                  accordion : menu_accordion || true,
                  speed : menu_speed || true,
                  closedSign : '<em class="fa fa-plus-square-o"></em>',
                  openedSign : '<em class="fa fa-minus-square-o"></em>'
                });
              } else {
                alert("Error - menu anchor does not exist");
              }
            }

          };
          /* ~ END: ACTIVATE NAVIGATION */

          /*
           * MISCELANEOUS DOM READY FUNCTIONS
           * Description: fire with jQuery(document).ready...
           */
          app.domReadyMisc = function() {

            /*
             * FIRE TOOLTIPS

            if ($("[rel=tooltip]").length) {
              $("[rel=tooltip]").tooltip();
            }*/

            // SHOW & HIDE MOBILE SEARCH FIELD
            $('#search-mobile').click(function() {
              $.root_.addClass('search-mobile');
            });

            $('#cancel-search-js').click(function() {
              $.root_.removeClass('search-mobile');
            });

            // ACTIVITY
            // ajax drop
            $('#activity').click(function(e) {
              var $this = $(this);

              if ($this.find('.badge').hasClass('bg-color-red')) {
                $this.find('.badge').removeClassPrefix('bg-color-');
                $this.find('.badge').text("0");
              }

              if (!$this.next('.ajax-dropdown').is(':visible')) {
                $this.next('.ajax-dropdown').fadeIn(150);
                $this.addClass('active');
              } else {
                $this.next('.ajax-dropdown').fadeOut(150);
                $this.removeClass('active');
              }

              var theUrlVal = $this.next('.ajax-dropdown').find('.btn-group > .active > input').attr('id');

              //clear memory reference
              $this = null;
              theUrlVal = null;

              e.preventDefault();
            });

            $('input[name="activity"]').change(function() {
              var $this = $(this);

              url = $this.attr('id');
              container = $('.ajax-notifications');

              loadURL(url, container);

              //clear memory reference
              $this = null;
            });

            // close dropdown if mouse is not inside the area of .ajax-dropdown
            $(document).mouseup(function(e) {
              if (!$('.ajax-dropdown').is(e.target) && $('.ajax-dropdown').has(e.target).length === 0) {
                $('.ajax-dropdown').fadeOut(150);
                $('.ajax-dropdown').prev().removeClass("active");
              }
            });

            // loading animation (demo purpose only)
            $('button[data-btn-loading]').on('click', function() {
              var btn = $(this);
              btn.button('loading');
              setTimeout(function() {
                btn.button('reset');
              }, 3000);
            });

            // NOTIFICATION IS PRESENT
            // Change color of lable once notification button is clicked

            $this = $('#activity > .badge');

            if (parseInt($this.text()) > 0) {
              $this.addClass("bg-color-red bounceIn animated");

              //clear memory reference
              $this = null;
            }


          };
          /* ~ END: MISCELANEOUS DOM */

          /*
           * MISCELANEOUS DOM READY FUNCTIONS
           * Description: fire with jQuery(document).ready...
           */
          app.mobileCheckActivation = function(){

            if ($(window).width() < 979) {
              $.root_.addClass('mobile-view-activated');
              $.root_.removeClass('minified');
            } else if ($.root_.hasClass('mobile-view-activated')) {
              $.root_.removeClass('mobile-view-activated');
            }

            if (debugState){
              console.log("mobileCheckActivation");
            }

          }
          /* ~ END: MISCELANEOUS DOM */

          return app;

        })({});

      initApp.addDeviceType();
      initApp.menuPos();

      $(context).once('context').on('load', function () {
        initApp.SmartActions();
        initApp.leftNav();
        initApp.domReadyMisc();
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
