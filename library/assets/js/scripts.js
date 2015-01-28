(function($) {

  $(document).ready(function() {

    window.mobilecheck = function() {
      var check = false;
      (function(a,b){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
      return check;
    }

    window.tabletcheck = function() {
      var check = false;
      if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        check = true;
      }
      return check;
    }

    /*
        content filtering for current issue
    */
    var poetryItems = $('.fc_poetry').parent(),
        artItems = $('.fc_art').parent(),
        reviewItems = $('.fc_review').parent(),
        fictionItems = $('.fc_fiction').parent(),
        musicItems = $('.fc_music').parent(),
        interviewItems = $('.fc_interview').parent(),
        nonfictionItems = $('.fc_nonfiction').parent(),
        allItems = [poetryItems, artItems, reviewItems, fictionItems, musicItems, interviewItems, nonfictionItems],
        filterGroup = $('.filter-group'),
        filterBtn = $('.filter_btn'),
        arrowIcon = filterBtn.find('i');

    $('input', $('.filter-group')).each(function () {
      $(this).on('click touchstart', function(e) {

        if( mobilecheck() ) {
          toggleFilterMenu(e);
        }

        switch($(this).attr('value')) {
          case 'All':
            // show all
            $.each(allItems, function() {
              $(this).removeClass('hidden');
            });
            break;
          case 'Poetry':
            $.each(allItems, function() {
              $(this).not( poetryItems ).addClass('hidden');
            });
            poetryItems.removeClass('hidden');
            break;
          case 'Art':
            $.each(allItems, function() {
              $(this).not( artItems ).addClass('hidden');
            });
            artItems.removeClass('hidden');
            break;
          case 'Fiction':
            $.each(allItems, function() {
              $(this).not( fictionItems ).addClass('hidden');
            });
            fictionItems.removeClass('hidden');
            break;
          case 'Reviews':
            $.each(allItems, function() {
              $(this).not( reviewItems ).addClass('hidden');
            });
            reviewItems.removeClass('hidden');
            break;
          case 'Music':
            $.each(allItems, function() {
              $(this).not( musicItems ).addClass('hidden');
            });
            musicItems.removeClass('hidden');
            break;
          case 'Interviews':
            $.each(allItems, function() {
              $(this).not( interviewItems ).addClass('hidden');
            });
            interviewItems.removeClass('hidden');
            break;
          case 'Nonfiction':
            $.each(allItems, function() {
              $(this).not( nonfictionItems ).addClass('hidden');
            });
            nonfictionItems.removeClass('hidden');
            break;
        }
      });
    });

    $(filterBtn).on('click touchstart', function(e) {
      toggleFilterMenu(e);
      e.preventDefault();
    });

    function toggleFilterMenu(e) {
      filterGroup.slideToggle(350);
      arrowIcon.toggleClass('fa-rotate-180');
    }



    /*
        full screen nav overlay and svg icon animation
    */
    var menuTrigger = $('.js-menu-trigger'),
        menu = $('.js-menu'),
        menuScreen = $('.js-menu-screen'),
        top = $('path.top'),
        mid = $('path.mid'),
        bot = $('path.bot'),
        tg = $('.top-group'),
        bg = $('.bot-group'),
        htm = $('html'),
        lst = $('.menu'),
        navSwitch = true;

    menuTrigger.on('click touchstart', function(e) {
      toggleMainMenu(e);
    });

    menuScreen.on('click touchstart', function(e) {
      toggleMainMenu(e);
    });

    function toggleMainMenu(e) {
      if (navSwitch) {
        tg.attr('class', 'top-group anim');
        bg.attr('class', 'bot-group anim');
        top.attr('class', 'top anim');
        mid.attr('class', 'mid anim');
        bot.attr('class', 'bot anim');
        menuTrigger.attr('title', 'Close Menu');
        navSwitch = false;
      } else {
        tg.attr('class', 'top-group');
        bg.attr('class', 'bot-group');
        top.attr('class', 'top');
        mid.attr('class', 'mid');
        bot.attr('class', 'bot');
        menuTrigger.attr('title', 'Main Menu');
        navSwitch = true;
      }
      htm.toggleClass('menu-open');
      menu.toggleClass('is-visible');
      lst.toggleClass('is-visible');
      menuScreen.toggleClass('is-visible');
      e.preventDefault();
    }



    /*
        top nav bar
    */
    // var topMenuContainer = $('.top-bar');



    /*
        scrolling
    */
    var win = $(window),
        mainHeroHeight = $('.hero-main').height(),
        carouselHero = $('.hero-carousel'),
        content = $('#content'),
        marg;

    function checkScroll( scroll ) {
      var scrollTop = win.scrollTop();
      var offset;
      if(carouselHero.length > 0) {
        offset = carouselHero.offset().top;
      } else {
        offset = content.offset().top;
      }
      var distance = mainHeroHeight - (offset - scrollTop);
      var percDown = (mainHeroHeight - distance) / mainHeroHeight;

      marg = -(win.scrollTop() / scroll.data('speed'));
      scroll.css({ marginBottom: marg, opacity: percDown });

      // if (win.scrollTop() < mainHeroHeight + marg) {
      //   if(!topMenuContainer.hasClass('nav-hide')) {
      //     topMenuContainer.removeClass('nav-show').addClass('nav-hide');
      //   }
      // } else {
      //   if(!topMenuContainer.hasClass('nav-show')) {
      //     topMenuContainer.addClass('nav-show').removeClass('nav-hide');
      //   }
      // }
    }
    if( !mobilecheck() && !tabletcheck() ) {
      $('div[data-type="scroll"]').each(function(){
        // check on load
        var scroll = $(this);
        checkScroll(scroll);

        $(window).scroll(function() {
          // animate on scroll
          checkScroll(scroll);
        });
      });
    }



    /*
        responsive legacy videos and object embeds
    */
    $(function() {

      var $allVideos = $("iframe[src^='http://player.vimeo.com'], [src^='//player.vimeo.com'], iframe[src^='http://www.youtube.com'], object[type='application/x-shockwave-flash'], object, embed"),
          $fluidEl = $(".entry-content");

      $allVideos.each(function() {
        console.log($(this));
        $(this)
          // jQuery .data does not work on object/embed elements
          .attr('data-aspectRatio', this.height / this.width)
          .removeAttr('height')
          .removeAttr('width');

      });

      $(window).resize(function() {

        var newWidth = $fluidEl.width();
        $allVideos.each(function() {

          var $el = $(this);
          $el
              .width(newWidth)
              .height(newWidth * $el.attr('data-aspectRatio'));

        });

      }).resize();
    });

  });

})(jQuery);
