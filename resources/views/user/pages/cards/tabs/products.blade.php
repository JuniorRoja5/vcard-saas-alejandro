<div class="card">
    <div class="card-header">
        <h3 class="card-title">💼 Products</h3>
        <div class="card-actions">
            <button type="button" class="btn btn-primary" onclick="addProduct()">
                Add Product
            </button>
        </div>
    </div>
    <div class="card-body">
        <div id="products-container">
            <div class="row product-item mb-4 p-3 border rounded">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="product_name[]" placeholder="My Digital Product">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Description</label>
                        <textarea class="form-control" name="product_subtitle[]" rows="2" placeholder="Brief description of your product"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="regular_price[]" placeholder="19.99">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Currency</label>
                            <select class="form-control" name="currency[]">
                                <option value="USD">USD ($)</option>
                                <option value="EUR">EUR (€)</option>
                                <option value="GBP">GBP (£)</option>
                                <option value="CAD">CAD (C$)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Product Image</label>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary" onclick="chooseImage(this)">
                                Choose from Gallery
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="openUnsplash(this)">
                                Browse Unsplash
                            </button>
                        </div>
                        <input type="hidden" name="product_image[]" class="product-image-input">
                        <div class="mt-2 image-preview" style="display: none;">
                            <img src="" class="img-thumbnail" style="max-width: 150px;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Type</label>
                        <select class="form-control" name="badge[]">
                            <option value="Digital">Digital Product</option>
                            <option value="Service">Service</option>
                            <option value="Course">Course</option>
                            <option value="Ebook">Ebook</option>
                            <option value="Software">Software</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-danger" onclick="removeProduct(this)">
                            Remove Product
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Unsplash Modal -->
<div class="modal fade" id="unsplashModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose from Unsplash</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" id="unsplashSearch" class="form-control" placeholder="Search for images...">
                    <button type="button" class="btn btn-primary mt-2" onclick="searchUnsplash()">Search</button>
                </div>
                <div id="unsplashResults" class="row"></div>
            </div>
        </div>
    </div>
</div>

<script>
let currentImageInput = null;

function addProduct() {
    const container = document.getElementById('products-container');
    const newItem = container.querySelector('.product-item').cloneNode(true);
    
    // Clear all inputs
    newItem.querySelectorAll('input, textarea, select').forEach(input => {
        if (input.type === 'hidden') input.value = '';
        else input.value = '';
    });
    
    // Hide image preview
    newItem.querySelector('.image-preview').style.display = 'none';
    
    container.appendChild(newItem);
}

function removeProduct(button) {
    const container = document.getElementById('products-container');
    if (container.children.length > 1) {
        button.closest('.product-item').remove();
    }
}

function chooseImage(button) {
    // Aquí puedes integrar con el sistema de media library existente
    alert('Media Library integration - TODO');
}

function openUnsplash(button) {
    currentImageInput = button.closest('.product-item').querySelector('.product-image-input');
    $('#unsplashModal').modal('show');
}

function searchUnsplash() {
    const query = document.getElementById('unsplashSearch').value;
    const resultsDiv = document.getElementById('unsplashResults');
    
    if (!query) return;
    
    // Mostrar loading
    resultsDiv.innerHTML = '<div class="col-12 text-center"><div class="spinner-border"></div></div>';
    
    // Simular búsqueda Unsplash - aquí integrarías con tu API
    setTimeout(() => {
        resultsDiv.innerHTML = `
            <div class="col-md-4 mb-3">
                <div class="card" onclick="selectUnsplashImage('https://via.placeholder.com/400x300?text=Sample+1')">
                    <img src="https://via.placeholder.com/400x300?text=Sample+1" class="card-img-top">
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card" onclick="selectUnsplashImage('https://via.placeholder.com/400x300?text=Sample+2')">
                    <img src="https://via.placeholder.com/400x300?text=Sample+2" class="card-img-top">
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card" onclick="selectUnsplashImage('https://via.placeholder.com/400x300?text=Sample+3')">
                    <img src="https://via.placeholder.com/400x300?text=Sample+3" class="card-img-top">
                </div>
            </div>
        `;
    }, 1000);
}

function selectUnsplashImage(imageUrl) {
    if (currentImageInput) {
        currentImageInput.value = imageUrl;
        
        // Show preview
        const productItem = currentImageInput.closest('.product-item');
        const preview = productItem.querySelector('.image-preview');
        const img = preview.querySelector('img');
        
        img.src = imageUrl;
        preview.style.display = 'block';
        
        $('#unsplashModal').modal('hide');
    }
}
</script>