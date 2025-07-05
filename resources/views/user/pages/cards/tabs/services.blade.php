<div class="card">
    <div class="card-header">
        <h3 class="card-title">🎯 Services</h3>
        <div class="card-actions">
            <button type="button" class="btn btn-primary" onclick="addService()">
                Add Service
            </button>
        </div>
    </div>
    <div class="card-body">
        <div id="services-container">
            <div class="row service-item mb-4 p-3 border rounded">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Service Name</label>
                        <input type="text" class="form-control" name="service_name[]" placeholder="1-on-1 Consultation">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service Description</label>
                        <textarea class="form-control" name="service_description[]" rows="3" placeholder="Detailed description of your service"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="price[]" placeholder="99.00">
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
                        <label class="form-label">Service Image</label>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary" onclick="chooseServiceImage(this)">
                                Choose from Gallery
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="openServiceUnsplash(this)">
                                Browse Unsplash
                            </button>
                        </div>
                        <input type="hidden" name="service_image[]" class="service-image-input">
                        <div class="mt-2 image-preview" style="display: none;">
                            <img src="" class="img-thumbnail" style="max-width: 150px;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service Type</label>
                        <select class="form-control" name="service_type[]">
                            <option value="Consultation">Consultation</option>
                            <option value="Coaching">Coaching</option>
                            <option value="Design">Design</option>
                            <option value="Development">Development</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enable Inquiry Button</label>
                        <select class="form-control" name="service_inquiry[]">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-danger" onclick="removeService(this)">
                            Remove Service
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Unsplash Modal for Services -->
<div class="modal fade" id="serviceUnsplashModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose Service Image from Unsplash</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" id="serviceUnsplashSearch" class="form-control" placeholder="Search for service images...">
                    <button type="button" class="btn btn-primary mt-2" onclick="searchServiceUnsplash()">Search</button>
                </div>
                <div id="serviceUnsplashResults" class="row"></div>
            </div>
        </div>
    </div>
</div>

<script>
let currentServiceImageInput = null;

function addService() {
    const container = document.getElementById('services-container');
    const newItem = container.querySelector('.service-item').cloneNode(true);
    
    // Clear all inputs
    newItem.querySelectorAll('input, textarea, select').forEach(input => {
        if (input.type === 'hidden') input.value = '';
        else if (input.tagName === 'SELECT') input.selectedIndex = 0;
        else input.value = '';
    });
    
    // Hide image preview
    newItem.querySelector('.image-preview').style.display = 'none';
    
    container.appendChild(newItem);
}

function removeService(button) {
    const container = document.getElementById('services-container');
    if (container.children.length > 1) {
        button.closest('.service-item').remove();
    }
}

function chooseServiceImage(button) {
    // Aquí puedes integrar con el sistema de media library existente
    alert('Media Library integration - TODO');
}

function openServiceUnsplash(button) {
    currentServiceImageInput = button.closest('.service-item').querySelector('.service-image-input');
    $('#serviceUnsplashModal').modal('show');
}

function searchServiceUnsplash() {
    const query = document.getElementById('serviceUnsplashSearch').value;
    const resultsDiv = document.getElementById('serviceUnsplashResults');
    
    if (!query) return;
    
    // Mostrar loading
    resultsDiv.innerHTML = '<div class="col-12 text-center"><div class="spinner-border"></div></div>';
    
    // Simular búsqueda Unsplash - aquí integrarías con tu API
    setTimeout(() => {
        resultsDiv.innerHTML = `
            <div class="col-md-4 mb-3">
                <div class="card" onclick="selectServiceUnsplashImage('https://via.placeholder.com/400x300?text=Service+1')">
                    <img src="https://via.placeholder.com/400x300?text=Service+1" class="card-img-top">
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card" onclick="selectServiceUnsplashImage('https://via.placeholder.com/400x300?text=Service+2')">
                    <img src="https://via.placeholder.com/400x300?text=Service+2" class="card-img-top">
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card" onclick="selectServiceUnsplashImage('https://via.placeholder.com/400x300?text=Service+3')">
                    <img src="https://via.placeholder.com/400x300?text=Service+3" class="card-img-top">
                </div>
            </div>
        `;
    }, 1000);
}

function selectServiceUnsplashImage(imageUrl) {
    if (currentServiceImageInput) {
        currentServiceImageInput.value = imageUrl;
        
        // Show preview
        const serviceItem = currentServiceImageInput.closest('.service-item');
        const preview = serviceItem.querySelector('.image-preview');
        const img = preview.querySelector('img');
        
        img.src = imageUrl;
        preview.style.display = 'block';
        
        $('#serviceUnsplashModal').modal('hide');
    }
}
</script>