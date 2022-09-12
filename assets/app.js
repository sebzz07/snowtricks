/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';


// start the Stimulus application
import './bootstrap';


const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

/*** Generator of new under form to add a trick's picture ***/

/*
$(document).ready(function() {
    if( $('[data-toggle="popover"]') != null){
        $('[data-toggle="popover"]').popover();
    }

});
 */
const addItem = (e) => {
    const collectionHolder = document
        .querySelector(e.currentTarget.dataset.collection);

    const item = document.createElement('div');
    item.classList.add('col-4');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(/__name__/g, collectionHolder.dataset.index);

    item.querySelector('.btn-remove').addEventListener('click', () => item.remove());

    collectionHolder.appendChild(item);
    collectionHolder.dataset.index++;

}

document.querySelectorAll('.btn-add').forEach(btn => btn.addEventListener('click', addItem));

document
    .querySelectorAll('.btn-remove')
    .forEach(
        btn => btn.addEventListener(
            'click',
            (e) => console.log(e.currentTarget
                .closest('.existing-item')
                .remove())
        )
    );

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

            request.onload = function() {
                let $html = request.response;
                console.log($html);

                while($html.charAt(0) !== '<' && $html.charAt(0) !== "" )
                {
                    console.log($html.charAt(0));
                    $html = $html.substr(1);
                }

                if ($html == ""){
                    loadBtn.remove();
                }else{
                    container.append($html);
                    loadBtn.dataset.offset = (offset + 5).toString();
                }
            }

        });
};


/*** Load More Posts feature on trick's page ***/
console.log(document.getElementById("load-more-posts"));
    if(document.getElementById("load-more-posts")){
        const loadPostBtn = document.getElementById("load-more-posts");
        let offsetPost = parseInt(loadPostBtn.dataset.offsetpost);
        let slug = loadPostBtn.dataset.slug;

        loadPostBtn.addEventListener("click", function (e) {
            e.preventDefault();
            const container = $("#post-container");
            offsetPost = parseInt(loadPostBtn.dataset.offsetpost);
            console.log(offsetPost);
            let request = new XMLHttpRequest();
            request.open('GET', "/trick/" + slug + "/nextposts/" + offsetPost);
            request.responseType = 'text';
            request.send();

            request.onload = function() {
                let $html = request.response;
                console.log($html);

                while($html.charAt(0) !== '<' && $html.charAt(0) !== "" )
                {
                    console.log($html.charAt(0));
                    $html = $html.substr(1);
                }

                if ($html == ""){
                    loadPostBtn.remove();
                }else{
                    container.append($html);
                    loadPostBtn.dataset.offsetpost = (offsetPost + 5).toString();
                }
            }

        });
}});