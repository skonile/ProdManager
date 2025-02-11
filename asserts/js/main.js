var APIEndpoint = "http://prodmanager.local";
var domainName  = "http://prodmanager.local";

var routes = {
    '': homePage,
    'home': homePage,
    'product': productPage,
    'products': productsPage,
    'category': categoryPage,
    'categories': categoriesPage,
    'tag': tagPage,
    'tags': tagsPage,
    'apis': APIsPage,
    'settings': settingsPage,
    'about': aboutPage
};

resolveUrl(window.location.href);

function resolveUrl(currentUrl){
    var url = currentUrl.replace(domainName, '');
    var urlPart = url.split('/')[1];
    for(var key in routes){
        if(urlPart == key){
            routes[key]();
            break;
        }
    }
}

function homePage(){}

function productPage(){
    var addImgBtn = __('product-add-image-btn');
    var productForm   = __('product-form');

    // Generate unique id for the form and add to it.
    var productFormId = '';
    if(window.location.href.endsWith('product/create')){
        productFormId = uniqueID();
    } else {
        productFormId = 'product-' + productForm.getAttribute('data-product-id');
        productFormId = productFormId + '-' + uniqueID();
    }

    productForm.setAttribute('data-form-id', productFormId);
    __('product-client-id').value = productFormId;

    // Hide the images cont if none are available for the product.
    toggleFormImgsCont();

    // Click the hidden file input element when the add image button is clicked.
    addImgBtn.onclick = function(){
        __('product-img-input').click();
    };

    // upload the the selected image if any.
    __('product-img-input').onchange = function(){
        var productFormId = getProductFormId();
        // disable the add image btn.
        disableBtn(addImgBtn);
        
        if(this.files.length > 0){
            var img = this.files[0];
            var imageName = productFormId + '-' + img.name;
            showElement(__('product-images'));
            displaySelectedImg(img);
            addRemoveImageElToLastImage(imageName);
            uploadImage(img, imageName, '/media/image/upload');
        }

        setRemoveImageEventsOnAllImages();
    };

    // Set the remove btn events.
    setRemoveImageEventsOnAllImages();

    productForm.onsubmit = function(){
        var validator = new Validator();
        var priceEl = __('product-price');

        alert(validator.isValidPrice(priceEl.value));
        return true;
    };
}

function productsPage(){
    setTableItemToItemLocation("product-item", "data-product-item");
}

function categoryPage(){}

function categoriesPage(){
    setTableItemToItemLocation("category-item", "data-category-item");
}

function tagPage(){}

function tagsPage(){
    setTableItemToItemLocation("tag-item", "data-tag-item");
}

function APIsPage(){}

function settingsPage(){}

function aboutPage(){}