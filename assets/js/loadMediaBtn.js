/*** to display medias feature on trick's page ***/

const $ = require("jquery");
$(document).ready(function () {
    const box = document.getElementById('box-media');
    const btn = document.getElementById('btn-media');
    if (window.screen.width > 992) {
        box.style.display = 'flex';
        btn.style.display = 'none';
    }
    if (window.screen.width <= 992) {

        btn.addEventListener('click', function handleClick() {
            if (box.style.display === 'none') {
                box.style.display = 'flex';

                btn.textContent = 'collapse medias';
            } else {
                box.style.display = 'none';

                btn.textContent = 'Show medias';
            }
        })
    }
});