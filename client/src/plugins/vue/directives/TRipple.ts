const ripple = function (el: Element, options: Object): EventListener {
  let _options = Object.assign(
    {
      color: "bg-slate-50",
      animation: "animate-ripple",
    },
    options
  );
  var handler = function (e: MouseEvent) {
    const x = e.offsetX;
    const y = e.offsetY;
    const ripples = document.createElement("span");
    ripples.setAttribute("style", `top: ${y}px; left: ${x}px`);
    ripples.className =
      `${_options.color}` +
      " absolute -translate-x-1/2 -translate-y-1/2 rounded-full animate-ripple pointer-events-none";
    el.appendChild(ripples);
    setTimeout(() => ripples.remove(), 500);
  };
  return handler;
};

const tripple = {
  mounted(el: Element, binding: any) {
    el.addEventListener("click", ripple(el, binding.value));
  },
  beforeUnmount(el: Element, binding: any) {
    el.removeEventListener("click", ripple(el, binding.value));
  },
};

export default tripple;
