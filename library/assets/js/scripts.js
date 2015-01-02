(function($) {

  $(document).ready(function() {

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
    });

    function toggleFilterMenu(e) {
      filterGroup.slideToggle(350);
      arrowIcon.toggleClass('fa-rotate-180');
      e.preventDefault();
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
    var topMenuContainer = $('.top-bar');



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

      if (win.scrollTop() < mainHeroHeight + marg) {
        if(!topMenuContainer.hasClass('nav-hide')) {
          topMenuContainer.removeClass('nav-show').addClass('nav-hide');
        }
      } else {
        if(!topMenuContainer.hasClass('nav-show')) {
          topMenuContainer.addClass('nav-show').removeClass('nav-hide');
        }
      } 
    }

    $('div[data-type="scroll"]').each(function(){
      // check on load
      var scroll = $(this);
      checkScroll(scroll);

      $(window).scroll(function() {
        // animate on scroll
        checkScroll(scroll);
      });
    });



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