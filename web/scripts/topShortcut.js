const topShortcut = document.getElementById('top-shortcut');

visibility()

window.onscroll = () => {
  visibility()
}

topShortcut.onclick = () => {
  window.scrollTo(0, 0);
}

function visibility() {
  if(window.scrollY > 500) {
    topShortcut.style.visibility = 'visible';
    topShortcut.style.opacity = 1;
  } else {
    topShortcut.style.visibility = 'hidden';   
    topShortcut.style.opacity = 0;   
  }
}