/*** to display medias feature on trick's page ***/

$(document).ready(function () {
        if (document.getElementById('box-media')) {

            const box = document.getElementById('box-media');
            const btn = document.getElementById('btn-media');
            btn.addEventListener('click', function handleClick() {


                console.log(box.classList);
                if (box.classList.contains("d-none")) {
                    box.classList.remove("d-none");

                    btn.textContent = 'collapse medias';
                } else {
                    box.classList.add("d-none");

                    btn.textContent = 'Show medias';
                }
            })
        }
    }
);