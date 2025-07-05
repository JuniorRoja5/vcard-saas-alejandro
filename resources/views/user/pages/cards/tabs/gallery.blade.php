<div class="card">
    <div class="card-header">
        <h3 class="card-title">📸 Gallery</h3>
        <div class="card-actions">
            <button type="button" class="btn btn-primary" onclick="addGalleryImage()">
                Add Image
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row" id="gallery-container">
            <div class="col-md-4 mb-3 gallery-item">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="image-placeholder" style="height: 200px; background: #f8f9fa; border: 2px dashed #dee2e6; display: flex; align-items: center; justify-content: center;">
                                <span class="text-muted">No image selected</span>
                            </div>
                            <img src="" class="img-fluid rounded image-preview" style="max-height: 200px; display: none;">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control mb-2" name="gallery_title[]" placeholder="Image title">
                            <textarea class="form-control" name="gallery_description[]" rows="2" placeholder="Image description"></textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="chooseGalleryImage(this)">
                                Choose from Gallery
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="openGalleryUnsplash(this)">
                                Browse Unsplash
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeGalleryImage(this)">
                                Remove
                            </button>
                        </div>
                        <input type="hidden" name="gallery_image[]" class="gallery-image-input">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Unsplash Modal for Gallery -->
<div class="modal fade" id="galleryUnsplashModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose Gallery Image from Unsplash</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" id="galleryUnsplashSearch" class="form-control" placeholder="Search for gallery images...">
                    <button type="button" class="btn btn-primary mt-2" onclick="searchGalleryUnsplash()">Search</button>
                </div>
                <div id="galleryUnsplashResults" class="row"></div>
            </div>
        </div>
    </div>
</div>

<script>
let currentGalleryImageInput = null;

function addGalleryImage() {
    const container = document.getElementById('gallery-container');
    const newItem = container.querySelector('.gallery-item').cloneNode(true);
    
    // Clear all inputs
    newItem.querySelectorAll('input, textarea').forEach(input => {
        input.value = '';
    });
    
    // Reset image display
    newItem.querySelector('.image-placeholder').style.display = 'flex';
    newItem.querySelector('.image-preview').style.display = 'none';
    
    container.appendChild(newItem);
}

function removeGalleryImage(button) {
    const container = document.getElementById('gallery-container');
    if (container.children.length > 1) {
        button.closest('.gallery-item').remove();
    }
}

function chooseGalleryImage(button) {
    // Aquí puedes integrar con el sistema de media library existente
    alert('Media Library integration - TODO');
}

function openGalleryUnsplash(button) {
    currentGalleryImageInput = button.closest('.gallery-item').querySelector('.gallery-image-input');
    $('#galleryUnsplashModal').modal('show');
}

function searchGalleryUnsplash() {
    const query = document.getElementById('galleryUnsplashSearch').value;
    const resultsDiv = document.getElementById('galleryUnsplashResults');
    
    if (!query) return;
    
    // Mostrar loading
    resultsDiv.innerHTML = '<div class="col-12 text-center"><div class="spinner-border"></div></div>';
    
    // Simular búsqueda Unsplash
    setTimeout(() => {
        resultsDiv.innerHTML = `
            <div class="col-md-3 mb-3">
                <div class="card" onclick="selectGalleryUnsplashImage('https://via.placeholder.com/300x200?text=Gallery+1')">
                    <img src="https://via.placeholder.com/300x200?text=Gallery+1" class="card-img-top">
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card" onclick="selectGalleryUnsplashImage('https://via.placeholder.com/300x200?text=Gallery+2')">
                    <img src="https://via.placeholder.com/300x200?text=Gallery+2" class="card-img-top">
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card" onclick="selectGalleryUnsplashImage('https://via.placeholder.com/300x200?text=Gallery+3')">
                    <img src="https://via.placeholder.com/300x200?text=Gallery+3" class="card-img-top">
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card" onclick="selectGalleryUnsplashImage('https://via.placeholder.com/300x200?text=Gallery+4')">
                    <img src="https://via.placeholder.com/300x200?text=Gallery+4" class="card-img-top">
                </div>
            </div>
        `;
    }, 1000);
}

function selectGalleryUnsplashImage(imageUrl) {
    if (currentGalleryImageInput) {
        currentGalleryImageInput.value = imageUrl;
        
        // Show preview
        const galleryItem = currentGalleryImageInput.closest('.gallery-item');
        const placeholder = galleryItem.querySelector('.image-placeholder');
        const preview = galleryItem.querySelector('.image-preview');
        
        preview.src = imageUrl;
        placeholder.style.display = 'none';
        preview.style.display = 'block';
        
        $('#galleryUnsplashModal').modal('hide');
    }
}
</script>