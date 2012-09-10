// bind an event on multiple selectors
function bind_event(els, type, fn) {
  return Array.prototype.forEach.call(els, function(el) {
    el.addEventListener(type, fn)
  });
}

// go to clicked section
bind_event(document.querySelectorAll('section a[href]'), 'click', function(event) {
  event.preventDefault();

  load_panel(
    document.querySelector('section.current'),
    document.getElementById(event.target.href.split('#')[1])
  );
})

// toggle "current" class
function load_panel(previous, current) {
  previous && previous.classList.remove('current');
  current.classList.add('current');
}

// initialize with first section
load_panel(null, document.getElementById('section-01'));
