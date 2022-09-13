const $ = require("jquery");

/*** Load More Posts feature on trick's page ***/

$(document).ready(function () {
console.log(document.getElementById("load-more-posts"));
if(document.getElementById("load-more-posts")){
    const loadPostBtn = document.getElementById("load-more-posts");
    let offsetPost = parseInt(loadPostBtn.dataset.offsetpost);
    let slug = loadPostBtn.dataset.slug;

    loadPostBtn.addEventListener("click", function (e) {
        e.preventDefault();
        const container = $("#post-container");
        offsetPost = parseInt(loadPostBtn.dataset.offsetpost);
        let request = new XMLHttpRequest();
        request.open('GET', "/trick/" + slug + "/nextposts/" + offsetPost);
        request.responseType = 'text';
        request.send();
        loadPostBtn.innerHTML = "Loading ..."
        request.onload = function() {
            loadPostBtn.innerHTML = "Older comments..."
            let $html = request.response;

            if ($html.length == 0){
                loadPostBtn.remove();
            }else{
                container.append($html);
                loadPostBtn.dataset.offsetpost = (offsetPost + 5).toString();
            }
        }

    });
}});