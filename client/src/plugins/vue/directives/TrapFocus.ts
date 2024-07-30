
/**
 * Src: https://hidde.blog/using-javascript-to-trap-focus-in-an-element/
 */
const trapFocus = (el: any) => {
  var focusableEls = el.querySelectorAll('a[href]:not([disabled]), button:not([disabled]), textarea:not([disabled]), input[type="text"]:not([disabled]), input[type="radio"]:not([disabled]), input[type="checkbox"]:not([disabled]), select:not([disabled])');
  var firstFocusableEl = focusableEls[0];  
  var lastFocusableEl = focusableEls[focusableEls.length - 1];
  var KEYCODE_TAB = 9;
    
  el.addEventListener('keydown', function(e: KeyboardEvent) {
    var isTabPressed = (e.key === 'Tab' || e.keyCode === KEYCODE_TAB);

    if (!isTabPressed) { 
      return; 
    }

    if ( e.shiftKey ) /* shift + tab */ {
      if (document.activeElement === firstFocusableEl) {
        lastFocusableEl.focus();
          e.preventDefault();
        }
      } else /* tab */ {
      if (document.activeElement === lastFocusableEl) {
        firstFocusableEl.focus();
          e.preventDefault();
        }
      }
  });
}

export default {
    mounted(el: Element, bindings: Object){
        trapFocus(el);
    },
    beforeUnmount(el: Element, bindings: Object){
    },
}