/**
 * Gets the DOM element of the given id.
 * 
 * @param {string} el The id of the element to get 
 * @returns {HTMLElement|null}
 */
function __(el){
    return document.getElementById(el);
}

/**
 * Gets the DOM element of the given class name.
 * 
 * @param {string} el The class name of the element(s) to get 
 * @returns {HTMLCollectionOf<Element>} The collection of the elements
 */
function ___(el){
    return document.getElementsByClassName(el);
}

/**
 * Generates a unique id.
 * 
 * @returns {int} The unique generated id
 */
function uniqueID() {
    return Math.floor(Math.random() * Date.now()) 
        + Math.floor(Math.random() * Date.now())
}

/**
 * Set the table so that when clicked it goes to the product selected.
 * 
 * @param {HTMLTableRowElement} itemsClassName The table row containing the product's data
 * @param {string} itemsDataItemName The attribute name to get the that stores the link to the product
 */
function setTableItemToItemLocation(itemsClassName, itemsDataItemName){
    var products = ___(itemsClassName);
    for(var ctr = 0; ctr < products.length; ctr++){
        products[ctr].onclick = function(){
            APIAction = this.getAttribute(itemsDataItemName);
            window.location = APIEndpoint + APIAction;
        };
    }
}

/**
 * Hide the given element using the display style.
 * 
 * @param {HTMLElement} el The html element to hide
 */
function hideElement(el){
    el.style.display = "none";
}

/**
 * Display the given element using the display style.
 * 
 * @param {HTMLElement} el The html element to display
 */
function showElement(el){
    el.style.display = "block";
}

/**
 * Disable the given button element.
 * 
 * @param {HTMLButtonElement} btnEl The btn to disable
 */
function disableBtn(btnEl){
    btnEl.disabled = true;
    btnEl.classList.add('btn-disabled');
}

/**
 * Enable the given button element.
 * 
 * @param {HTMLButtonElement} btnEl 
 */
function enableBtn(btnEl){
    btnEl.disabled = false;
    btnEl.classList.remove('btn-disabled');
}

/**
 * Hides/Shows the element containing the products images 
 * if no images exists.
 */
function toggleFormImgsCont(){
    var imgContEls = ___('form-image');
    var productImgsContEl = __('product-images');
    (imgContEls.length == 0)? 
    hideElement(productImgsContEl): 
    showElement(productImgsContEl);
}

/**
 * Display the product's image.
 * 
 * @param {File} img The image to display
 */
function displaySelectedImg(img){
    // Get product images container.
    var imgsContEl = __('product-images');

    // Image and upload data container.
    var imgContEl = document.createElement('div');
    imgContEl.classList.add('form-image');

    // Image Element.
    var imgEl = document.createElement('img');
    imgEl.src = URL.createObjectURL(img);

    // Image uploading data container.
    var imgUploadDataContEl = document.createElement('div');
    imgUploadDataContEl.classList.add('image-upload-data-cont');
    // Upload data progress bar.
    var imgUploadDataProgressBar = document.createElement('div');
    imgUploadDataProgressBar.classList.add('img-upload-progress-bar');
    // Upload data progress text.
    var imgUploadDataEl = document.createElement('div');
    imgUploadDataEl.classList.add('image-upload-data');
    var imgUploadDataPEl = document.createElement('p');
    imgUploadDataPEl.classList.add('image-upload-data-p');
    imgUploadDataPEl.innerHTML = 'uploading...';
    imgUploadDataEl.appendChild(imgUploadDataPEl);

    // Add the image to the DOM.
    imgContEl.appendChild(imgEl);
    imgUploadDataContEl.appendChild(imgUploadDataProgressBar);
    imgUploadDataContEl.appendChild(imgUploadDataEl);
    imgContEl.appendChild(imgUploadDataContEl);
    imgsContEl.appendChild(imgContEl);
}

/**
 * Upload the selected image to the server.
 * 
 * @param {File} img The image to upload
 * @param {string} url The url to upload the image to
 */
function uploadImage(img, imageName, url){
    var formData = new FormData();
    formData.set('product-image', img, imageName)
    uploadWithAjax(formData, url);
}

/**
 * Upload the image using AJAX.
 * 
 * @param {FormData} formData The form to upload
 * @param {string} url The URL to upload to
 */
function uploadWithAjax(formData, url){
    var request = new XMLHttpRequest();

    // Update the upload data about the uploading process.
    request.onprogress = function(progressEvent){
        if(!progressEvent.lengthComputable) return;
        progress = Math.round(progressEvent.loaded / progressEvent.total * 100) + "%";
        
        // Update DOM with uploading data.
        var imgUploadedEls = ___('image-upload-data-p');
        imgUploadedEls[imgUploadedEls.length - 1].innerHTML = progress;
    };

    // Remove the upload information element when done uploading.
    request.onload = function(){
        var imgsUploadDataEl = ___('image-upload-data-cont');
        hideElement(imgsUploadDataEl[imgsUploadDataEl.length - 1]);
        enableBtn(__('product-add-image-btn'));
        setRemoveImageEventsOnAllImages();
    };

    request.open('post', url);
    request.send(formData);
}

/**
 * Add the remove image element to the last product's image.
 * 
 * @param {int} productFormId The product's form id
 */
function addRemoveImageElToLastImage(imageName){
    var image = getLastImage();
    image.appendChild(createRemoveImageEl(imageName));
}

function getLastImage(){
    var imagesEls = ___('form-image');
    return imagesEls[imagesEls.length - 1];
}

/**
 * Create the remove image element for product's images.
 * 
 * @returns {HTMLElement} The remove image element
 */
function createRemoveImageEl(imageName){
    // <div class="remove-image-btn-cont">
    //     <div class="remove-image-btn-cont-inner">
    //         <div class="remove-image-btn" data-image-name="">
    //             <span class="remove-image-text">Remove</span>
    //         </div>
    //     </div>
    // </div>
    var cont = document.createElement('div');
    cont.classList.add('remove-image-btn-cont');

    var contInner = document.createElement('div');
    contInner.classList.add('remove-image-btn-cont-inner');

    var removeBtn = document.createElement('div');
    removeBtn.classList.add('remove-image-btn');
    removeBtn.setAttribute('data-image-name', imageName);

    var removeBtnText = document.createElement('span');
    removeBtnText.classList.add('remove-image-text');
    removeBtnText.innerHTML = 'Remove';

    removeBtn.appendChild(removeBtnText);
    contInner.appendChild(removeBtn);
    cont.appendChild(contInner);

    return cont;
}

function getProductFormId(){
    return __('product-form').getAttribute('data-form-id');
}

function getProductId(){
    return __('product-form').getAttribute('data-product-id');
}

function setRemoveImageEventsOnAllImages(){
    var imageRemoveBtns = ___('remove-image-btn');
    for(var ctr = 0; ctr < imageRemoveBtns.length; ctr++){
        imageRemoveBtns[ctr].onclick = function(){
            var productFormId = getProductFormId();
            var imageName = this.getAttribute('data-image-name');
            var productId = getProductId();
            var imageDOMEl = this.parentNode.parentNode.parentNode;
            deleteImage(imageDOMEl, '/media/image/delete', imageName, productFormId, productId);
        };
    }
}

function deleteImage(imageDOMEl, url, imageName, productFormId, productId){
    var formData = new FormData();
    formData.set('image-name', imageName);
    formData.set('product-form-id', productFormId);
    formData.set('product-id', productId);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function(){
        if(this.status == 200 && this.readyState == 4){
            if(request.responseText == 1)
                hideElement(imageDOMEl);
            else
                alert('ERROR: can not remove image.');
        }
    };
    request.open('post', url);
    request.send(formData);
}