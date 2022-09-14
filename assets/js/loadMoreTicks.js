const $ = require("jquery");

/*** Load More Tricks feature on the main page ***/
$(document).ready(function () {
    if(document.getElementById("load-more")){

        const loadBtn = document.getElementById("load-more");
        let offset = parseInt(loadBtn.dataset.offset);


        loadBtn.addEventListener("click", function (e) {
            e.preventDefault();
            const container = $("#trick-container");
            offset = parseInt(loadBtn.dataset.offset);

            let request = new XMLHttpRequest();
            request.open('GET', "/nextTricks/" + offset);
            request.responseType = 'text';
            request.send();
            loadBtn.innerHTML = "Loading ..."
            request.onload = function() {
                loadBtn.innerHTML = "Load more"
                let $html = request.response;

                if ($html.length == 0){
                    loadBtn.remove();
                }else{
                    container.append($html);
                    loadBtn.dataset.offset = (offset + 8).toString();
                }
            }

        });
    };});

