function checkViewport(elem) {
    var viewportTop = $(window).scrollTop();
    var viewportBottom = viewportTop + $(window).height();
    var elementTop = elem.offset().top;
    var elementBottom = elementTop + elem.outerHeight();
    return elementBottom > viewportTop && elementTop < viewportBottom;
  }

const nav = document.querySelector('#navbar');

let prevScrollpos= window.pageYOffset;
nav.classList.add('hide')

window.addEventListener('scroll', ()=> {

    let currentScrollPos= window.pageYOffset;
    if(prevScrollpos > currentScrollPos){
        nav.classList.remove('hide');
    }else{
        nav.classList.remove('hide');
    }
    prevScrollpos= currentScrollPos;
});